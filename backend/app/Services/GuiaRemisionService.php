<?php

namespace App\Services;

use DateTime;
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

// Load legacy Greenter autoload if present (keeps global classes available)

class GuiaRemisionService
{
    private const RUC_EMPRESA = '20455175781';
    private const RAZON_SOCIAL_EMPRESA = 'PERUVIAN DRESS TPX S.A.C.';
    private const SLEEP_TIME = 60;


    public function createSee(): See
    {
        $see = new See();
        $see->setService(SunatEndpoints::FE_PRODUCCION);
        $see->setCertificate(file_get_contents(base_path('../guias/resources/certificate_pv_2024.pem')));
        $see->setClaveSOL('20455175781', 'PERUVI11', 'Omcipier11');
        return $see;
    }

    

    public function procesarGuia(array $guia, array $detalles, array $destinatario, array $transportista = null, array $conductor = null): array
    {
        $despatch = $this->construirDespatch($guia, $detalles, $destinatario, $transportista, $conductor);

        $see = $this->createSee();
        $res = $see->send($despatch);
        $this->saveXml($despatch, $see->getFactory()->getLastXml());

        if (!$res->isSuccess()) {
            return ['success' => false, 'message' => $res->getError()->getMessage()];
        }

        $ticket = $res->getTicket();
        sleep(self::SLEEP_TIME);

        $res = $see->getStatus($ticket);
        if (!$res->isSuccess()) {
            return ['success' => false, 'message' => $res->getError()->getMessage()];
        }

        $cdr = $res->getCdrResponse();
        $this->saveCdr($despatch, $res->getCdrZip());

        return [
            'success' => $cdr->isAccepted(),
            'code' => $cdr->getCode(),
            'message' => $cdr->getDescription(),
            'accepted' => $cdr->isAccepted(),
        ];
    }

    private function construirDespatch(array $guia, array $detalles, array $destinatario, ?array $transportista, ?array $conductor): Despatch
    {
        // Normalize inputs: allow stdClass or arrays
        if (is_object($guia)) {
            $guia = json_decode(json_encode($guia), true);
        }
        if (is_object($destinatario)) {
            $destinatario = (array) $destinatario;
        }
        foreach ($detalles as $k => $row) {
            if (is_object($row)) {
                $detalles[$k] = (array) $row;
            }
        }
        if (is_object($transportista)) {
            $transportista = (array) $transportista;
        }
        if (is_object($conductor)) {
            $conductor = (array) $conductor;
        }

        [$serie, $correlativo] = explode('-', $guia['num_guia']);

        $company = (new Company())
            ->setRuc(self::RUC_EMPRESA)
            ->setRazonSocial(self::RAZON_SOCIAL_EMPRESA);

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
            ->setEnvio($this->construirShipment($guia, $transportista, $conductor));

        $items = [];
        foreach ($detalles as $row) {
            $items[] = (new DespatchDetail())
                ->setCantidad(floatval($row['cantidad']))
                ->setUnidad($row['unidad'] ?? 'NIU')
                ->setDescripcion(trim(strip_tags(str_replace('<br>', ' ', $row['descripcion_producto'] ?? $row['description'] ?? ''))))
                ->setCodigo($row['code'] ?? '');
        }

        $despatch->setDetails($items);
        return $despatch;
    }

    private function construirShipment(array $guia, ?array $transportista, ?array $conductor): Shipment
    {
        $totalPeso = $guia['total_neto'] > 0 ? $guia['total_neto'] : $guia['total_bruto'];

        $shipment = (new Shipment())
            ->setCodTraslado($guia['motivo_traslado'])
            ->setModTraslado($guia['modalidad_trasnporte'])
            ->setFecTraslado(new DateTime($guia['fecha_traslado']))
            ->setPesoTotal(floatval($totalPeso))
            ->setUndPesoTotal('KGM')
            ->setLlegada(new Direction($guia['ubigeo_destino'], $guia['destino']))
            ->setPartida(new Direction($guia['ubigeo'], $guia['origen']));

        if ($guia['motivo_traslado'] === '13') {
            $shipment->setDesTraslado($guia['descripcion_motivo'] ?? '');
        }

        if (!empty($transportista)) {
            $shipment->setTransportista((new Transportist())
                ->setTipoDoc($transportista['tipoDocumento'] ?? '6')
                ->setNumDoc($transportista['ruc'] ?? '')
                ->setRznSocial($transportista['razon_social'] ?? '-')
                ->setNroMtc($transportista['nro_mtc'] ?? '0001'));
        }

        if (!empty($conductor)) {
            $driver = (new Driver())
                ->setTipo('Principal')
                ->setTipoDoc('1')
                ->setNroDoc($conductor['ruc'] ?? '')
                ->setLicencia($conductor['licencia'] ?? '')
                ->setNombres($conductor['nombres'] ?? '')
                ->setApellidos($conductor['apellidos'] ?? '');
            $shipment->setChoferes([$driver]);
        }

        if (!empty($guia['placa'])) {
            $shipment->setVehiculo((new Vehicle())->setPlaca($guia['placa']));
        }

        return $shipment;
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
