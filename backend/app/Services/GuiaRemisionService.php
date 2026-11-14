<?php

namespace App\Services;

use DateTime;
use DOMDocument;
use Illuminate\Support\Facades\DB;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Driver;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Despatch\Vehicle;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class GuiaRemisionService
{
    private const RUC_EMPRESA = '20611081651';
    private const RAZON_SOCIAL_EMPRESA = 'OMCAR SOLUTIONS E.I.R.L.';
    // Consulta del ticket: cada 3 s hasta 20 s, para que la petición termine bien antes del timeout del hosting
    private const POLL_INTERVAL = 3;
    private const POLL_MAX_SECONDS = 20;

    public function createSee(): \Greenter\Api
    {
        $api = new \Greenter\Api([
            'auth' => 'https://api-seguridad.sunat.gob.pe/v1',
            'cpe' => 'https://api-cpe.sunat.gob.pe/v1',
        ]);

        $certificate = file_get_contents(storage_path('app/certs/certificado_om_2026.pem'));

        $api->setBuilderOptions([
            'strict_variables' => true,
            'optimizations' => 0,
            'debug' => true,
            'cache' => false,
        ])
            ->setApiCredentials(env('SUNAT_CLIENT_ID', '1be3fd93-fa8b-4329-bb4e-260c67b7f7c8'), env('SUNAT_CLIENT_SECRET', '35gHoUKEWOl5Al6oH7SK8w=='))
            ->setClaveSOL(env('SUNAT_RUC', '20611081651'), env('SUNAT_USUARIO', 'OMCAR225'), env('SUNAT_CLAVE', 'Omcar225'))
            ->setCertificate($certificate);

        return $api;
    }

    /**
     * Envía la guía y consulta su ticket por un tiempo corto (POLL_MAX_SECONDS).
     * Antes se esperaba un sleep fijo de 60 s y el proxy de Hostinger cortaba la petición con 504
     * aunque el envío terminaba bien. Si SUNAT sigue procesando se devuelve Result = PENDIENTE y el
     * ticket queda guardado: al volver a llamar se consulta ese ticket en lugar de reenviar la guía.
     */
    public function procesarGuia(int $idGuia): array
    {
        try {
            $guia = $this->obtenerGuia($idGuia);
            if (!$guia) {
                return ['Result' => 'ERROR', 'Message' => 'Guía no encontrada'];
            }

            if ((int) $guia['estado'] === 1) {
                return ['Result' => 'OK', 'Message' => 'La guía ya fue aceptada por SUNAT'];
            }

            $despatch = $this->construirDespatch($guia, $idGuia);
            $api = $this->createSee();
            $ticket = $guia['ticket'] ?? null;

            if (empty($ticket)) {
                $res = $api->send($despatch);
                $this->saveXml($despatch, $api->getLastXml());

                if (!$res->isSuccess()) {
                    $errorMsg = $res->getError()->getMessage();
                    $this->guardarRespuestaSunat($idGuia, $errorMsg);
                    return ['Result' => 'ERROR', 'Message' => $errorMsg];
                }

                $ticket = $res->getTicket();
                // Se guarda de inmediato para no reenviar la guía si esta petición se corta
                $this->guardarTicket($idGuia, $ticket);
            }

            return $this->consultarTicket($idGuia, $ticket, $despatch, $api);
        } catch (\Exception $e) {
            $errorMsg = "Excepción: " . $e->getMessage() . " | Línea: " . $e->getLine();

            try {
                $this->guardarRespuestaSunat($idGuia, $errorMsg);
            } catch (\Exception $saveException) {
                \Illuminate\Support\Facades\Log::error("No se pudo guardar error en BD: " . $saveException->getMessage());
            }

            return ['Result' => 'ERROR', 'Message' => $errorMsg];
        }
    }

    private function obtenerGuia(int $idGuia): ?array
    {
        $guia = DB::table('guia_cabecera')->where('id', $idGuia)->first();
        return $guia ? (array) $guia : null;
    }

    private function construirDespatch(array $guia, int $idGuia): Despatch
    {
        $company = (new Company())
            ->setRuc(self::RUC_EMPRESA)
            ->setRazonSocial(self::RAZON_SOCIAL_EMPRESA);

        $envio = $this->construirShipment($guia);

        [$serie, $correlativo] = explode('-', $guia['num_guia']);
        $destinatario = $this->obtenerDestinatario($guia['ruc_destinatario']);

        $despatch = (new Despatch())
            ->setVersion('2022')
            ->setTipoDoc('09')
            ->setSerie($serie)
            ->setCorrelativo($correlativo)
            ->setFechaEmision(new DateTime($guia['fecha_emision']))
            ->setCompany($company)
            ->setDestinatario((new Client())
                ->setTipoDoc('6')
                ->setNumDoc($guia['ruc_destinatario'])
                ->setRznSocial($destinatario['name'] ?? '-'))
            ->setEnvio($envio);

        $items = $this->obtenerDetallesGuia($idGuia);
        $despatch->setDetails($items);

        return $despatch;
    }

    private function construirShipment(array $guia): Shipment
    {
        $totalPeso = $guia['total_neto'] > 0 ? $guia['total_neto'] : $guia['total_bruto'];

        $envio = (new Shipment())
            ->setCodTraslado($guia['motivo_traslado'])
            ->setModTraslado($guia['modalidad_trasnporte'])
            ->setFecTraslado(new DateTime($guia['fecha_traslado']))
            ->setPesoTotal(floatval($totalPeso))
            ->setUndPesoTotal('KGM');

        if ($guia['motivo_traslado'] == 13) {
            $envio->setDesTraslado($guia['descripcion_motivo'] ?? '');
        }

        $envio->setLlegada(new Direction($guia['ubigeo_destino'], $guia['destino']))
            ->setPartida(new Direction($guia['ubigeo'], $guia['origen']));

        if (!empty($guia['ruc_transportista'])) {
            $transportista = $this->obtenerTransportista($guia['ruc_transportista']);
            if ($transportista) {
                $transp = (new Transportist())
                    ->setTipoDoc($transportista['tipoDocumento'] ?? '6')
                    ->setNumDoc($transportista['ruc'] ?? '')
                    ->setRznSocial($transportista['razon_social'] ?? '')
                    ->setNroMtc($transportista['nro_mtc'] ?? '0001');

                $envio->setTransportista($transp);
            }
        }

        if (!empty($guia['ruc_conductor'])) {
            $conductor = $this->obtenerConductor($guia['ruc_conductor']);
            if ($conductor) {
                $chofer = (new Driver())
                    ->setTipo('Principal')
                    ->setTipoDoc('1')
                    ->setNroDoc($conductor['ruc'] ?? '')
                    ->setLicencia($conductor['licencia'] ?? '')
                    ->setNombres($conductor['nombres'] ?? '')
                    ->setApellidos($conductor['apellidos'] ?? '');

                $envio->setChoferes([$chofer]);

                if (!empty($guia['placa'])) {
                    $vehiculo = (new Vehicle())->setPlaca($guia['placa']);
                    $envio->setVehiculo($vehiculo);
                }
            }
        }

        return $envio;
    }

    private function obtenerDetallesGuia(int $idGuia): array
    {
        $detalles = DB::table('guia_detalle as g')
            ->leftJoin('product as p', 'g.id_producto', '=', 'p.id')
            ->select('g.*', 'p.code', 'p.description')
            ->where('g.id_guia', $idGuia)
            ->get();

        $items = [];
        foreach ($detalles as $row) {
            $row = (array) $row;
            $descripcion = !empty($row['descripcion_producto'])
                ? $row['descripcion_producto']
                : $row['description'];

            $item = (new DespatchDetail())
                ->setCantidad(floatval($row['cantidad']))
                ->setUnidad($row['unidad'] ?? 'NIU')
                ->setDescripcion($this->sanitizarTexto($descripcion))
                ->setCodigo($row['code'] ?? '');

            $items[] = $item;
        }

        return $items;
    }

    private function obtenerDestinatario(string $ruc): array
    {
        $dest = DB::table('person')->select('no', 'email1', 'phone1', 'address1', 'name')->where('no', $ruc)->first();
        return $dest ? (array) $dest : [];
    }

    private function obtenerTransportista(string $ruc): ?array
    {
        $transp = DB::table('transportistas')->where('ruc', $ruc)->first();
        return $transp ? (array) $transp : null;
    }

    private function obtenerConductor(string $ruc): ?array
    {
        $cond = DB::table('conductores')->where('ruc', $ruc)->first();
        return $cond ? (array) $cond : null;
    }

    /**
     * Consulta el ticket cada POLL_INTERVAL segundos mientras SUNAT responda 98 (en proceso).
     */
    private function consultarTicket(int $idGuia, string $ticket, Despatch $despatch, \Greenter\Api $api): array
    {
        $inicio = time();

        do {
            sleep(self::POLL_INTERVAL);
            $res = $api->getStatus($ticket);
            $enProceso = !$res->isSuccess() && $res->getCode() === '98';
        } while ($enProceso && (time() - $inicio) < self::POLL_MAX_SECONDS);

        if ($enProceso) {
            return [
                'Result' => 'PENDIENTE',
                'Message' => 'SUNAT aún está procesando la guía. Se volverá a consultar en unos segundos.',
            ];
        }

        if (!$res->isSuccess()) {
            $errorMsg = $res->getError()->getMessage();
            $this->guardarRespuestaSunat($idGuia, $errorMsg);
            // Sin CDR no hay respuesta definitiva de SUNAT: se limpia el ticket para poder reenviar
            if (!$res->getCdrZip()) {
                $this->guardarTicket($idGuia, null);
            }
            return ['Result' => 'ERROR', 'Message' => $errorMsg];
        }

        $cdr = $res->getCdrResponse();
        $this->saveCdr($despatch, $res->getCdrZip());
        $respuesta = $this->formatearRespuestaCdr($cdr);
        $this->guardarRespuestaSunat($idGuia, $respuesta);

        if ($cdr->isAccepted()) {
            $this->actualizarGuiaAceptada($idGuia, $ticket);
            return ['Result' => 'OK', 'Message' => $cdr->getDescription()];
        }

        $this->guardarTicket($idGuia, null);
        return ['Result' => 'ERROR', 'Message' => $respuesta];
    }

    private function guardarTicket(int $idGuia, ?string $ticket): void
    {
        DB::table('guia_cabecera')->where('id', $idGuia)->update(['ticket' => $ticket]);
    }

    private function actualizarGuiaAceptada(int $idGuia, string $ticket): void
    {
        DB::table('guia_cabecera')
            ->where('id', $idGuia)
            ->update([
                'estado' => 1,
                'ticket' => $ticket
            ]);
    }

    private function guardarRespuestaSunat(int $idGuia, string $respuesta): void
    {
        try {
            DB::table('respuesta_sunat_guias')->insert([
                'id_guia' => $idGuia,
                'respuesta' => $respuesta
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("guardarRespuestaSunat error: " . $e->getMessage());
        }
    }

    private function formatearRespuestaCdr($cdr): string
    {
        return sprintf(
            "ID: %s | Código: %s | Descripción: %s",
            $cdr->getId(),
            $cdr->getCode(),
            $cdr->getDescription()
        );
    }

    private function sanitizarTexto(string $texto): string
    {
        if (strpos($texto, '<table') === false) {
            return trim(strip_tags(str_replace('<br>', ' ', $texto)));
        }

        $partes = explode('<table', $texto, 2);
        $encabezado = trim(strip_tags(str_replace('<br>', ' ', $partes[0])));

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML('<table' . $partes[1], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $rows = $dom->getElementsByTagName('tr');

        if ($rows->length < 2) {
            return $encabezado;
        }

        $cantidadFila = $rows->item(0)->getElementsByTagName('td');
        $tallaFila = $rows->item(1)->getElementsByTagName('td');
        $pares = [];

        $maxColumnas = min($cantidadFila->length, $tallaFila->length);
        for ($i = 0; $i < $maxColumnas; $i++) {
            $cantidad = trim($cantidadFila->item($i)->nodeValue);
            $talla = trim($tallaFila->item($i)->nodeValue);
            if (!empty($cantidad) && !empty($talla)) {
                $pares[] = "$talla/$cantidad";
            }
        }

        return trim("$encabezado " . implode(' ', $pares));
    }

    private function saveXml(Despatch $despatch, ?string $xml): void
    {
        $path = storage_path('app/sunat/guias/xml');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        file_put_contents($path . DIRECTORY_SEPARATOR . $despatch->getName() . '.xml', $xml);
    }

    private function saveCdr(Despatch $despatch, ?string $zip): void
    {
        $path = storage_path('app/sunat/guias/cdr');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        file_put_contents($path . DIRECTORY_SEPARATOR . 'R-' . $despatch->getName() . '.zip', $zip);
    }
}
