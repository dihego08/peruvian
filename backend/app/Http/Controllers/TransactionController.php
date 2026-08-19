<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SunatService;
use Illuminate\Support\Facades\Http;
use Dompdf\Dompdf;

class TransactionController extends Controller
{
    // ====== VENTAS (SALES) ====== //

    public function getCorrelativo(Request $request)
    {
        $tipo = $request->query('tipo_documento');
        $prefix = '';
        $tabla = '';

        if ($tipo == '1') {
            $tabla = 'boleta';
            $prefix = 'B001-';
        } elseif ($tipo == '2') {
            $tabla = 'factura';
            $prefix = 'F001-';
        } elseif ($tipo == '3') {
            $tabla = 'nota_pedido';
            $prefix = 'NP001-';
        } else {
            return response()->json(['correlativo' => '']);
        }

        $aux = DB::table('aux')->where('tabla', $tabla)->first();
        $nextId = $aux ? ((int) $aux->id + 1) : 1;

        return response()->json([
            'correlativo' => $prefix . $nextId
        ]);
    }

    /**
     * Enviar factura/venta a SUNAT usando Greenter (integra archivo legacy facturador_v3)
     */
    public function sendToSunat($codigo, SunatService $sunatService)
    {
        try {
            $see = $sunatService->createSee();

            $cabecera = DB::table('ventas_cabecera as vc')
                ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
                ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
                ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
                ->leftJoin('f', 'f.id', '=', 'vc.id_forma_pago')
                ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
                ->where('vc.codigo_venta', $codigo)
                ->select([
                    'vc.*',
                    DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_emision_fmt"),
                    DB::raw("DATE_FORMAT(vc.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento_fmt"),
                    DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                    'pe.no as ruc',
                    'pe.address1 as direccion',
                    'p.name as pago',
                    'd.name as entrega',
                    'f.name as tipo_pago',
                    'k.tipo_documento as tipo_doc_nombre',
                ])
                ->first();

            if (!$cabecera) {
                return response()->json(['Result' => 'ERROR', 'message' => 'Venta no encontrada'], 404);
            }

            $detalle = DB::table('ventas_detalle as vd')
                ->leftJoin('product as pr', 'pr.id', '=', 'vd.id_producto')
                ->where('vd.codigo_venta_cabecera', $codigo)
                ->select([
                    'vd.*',
                    'pr.name as producto_nombre',
                    'pr.code as producto_codigo',
                    'pr.image as producto_imagen'
                ])
                ->get();

            $customerData = [
                'ruc' => $cabecera->ruc ?? $cabecera->ruc_add ?? '',
                'razon_social' => $cabecera->person ?? '-',
            ];

            $client = $sunatService->buildClient($customerData);
            $company = $sunatService->buildCompany();
            $items = $sunatService->buildItems($detalle->all());
            $invoice = $sunatService->buildInvoice((array) $cabecera, $items, $client, $company);

            $sendResult = $sunatService->sendInvoice($invoice, $see);

            if ($sendResult['success']) {
                $code = $sendResult['code'];
                if ($code === 0) {
                    DB::table('ventas_cabecera')->where('codigo_venta', $codigo)->update(['envio_sunat' => 1]);
                    return response()->json(['Result' => 'SUCCESS']);
                } else if ($code >= 2000 && $code <= 3999) {
                    return response()->json([
                        'Result' => 'ERROR',
                        'message' => 'Comprobante rechazado: ' . ($sendResult['message'] ?? 'Sin mensaje')
                    ]);
                }
            }

            return response()->json([
                'Result' => 'ERROR',
                'code' => $sendResult['code'] ?? null,
                'message' => $sendResult['message'] ?? 'Error de conexión'
            ]);

        } catch (\Exception $e) {
            Log::error('sendToSunat error: ' . $e->getMessage());
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }
    public function getSells()
    {
        // Consulta la tabla legacy ventas_cabecera con los mismos joins que clsVenta.php lista_ventas()
        $sells = DB::table('ventas_cabecera as vc')
            ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
            ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
            ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
            ->leftJoin('f', 'f.id', '=', 'vc.id_forma_pago')
            ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
            ->select([
                'vc.*',
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_creacion"),
                DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                'p.name as pago',
                'd.name as entrega',
                'f.name as tipo_pago',
                'k.tipo_documento as tipo_doc_nombre',
            ])
            ->orderBy('vc.fecha_emision', 'desc')
            ->limit(500)
            ->get();

        return response()->json([
            'Result' => 'OK',
            'Records' => $sells,
        ]);
    }

    public function getSellDetail($codigo_venta)
    {
        // Cabecera
        $cabecera = DB::table('ventas_cabecera as vc')
            ->leftJoin('person as pe', 'pe.id', '=', 'vc.id_person')
            ->leftJoin('p', 'p.id', '=', 'vc.id_estado_pago')
            ->leftJoin('d', 'd.id', '=', 'vc.id_estado_entrega')
            ->leftJoin('f', 'f.id', '=', 'vc.id_forma_pago')
            ->leftJoin('kind_doc as k', 'k.id', '=', 'vc.tipo_documento')
            ->where('vc.codigo_venta', $codigo_venta)
            ->select([
                'vc.*',
                DB::raw("DATE_FORMAT(vc.fecha_emision, '%d-%m-%Y') as fecha_emision_fmt"),
                DB::raw("DATE_FORMAT(vc.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento_fmt"),
                DB::raw("COALESCE(pe.name, vc.ruc_add) as person"),
                'pe.no as ruc',
                'pe.address1 as direccion',
                'p.name as pago',
                'd.name as entrega',
                'f.name as tipo_pago',
                'k.tipo_documento as tipo_doc_nombre',
            ])
            ->first();

        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Venta no encontrada'], 404);
        }

        // Detalle de productos
        $detalle = DB::table('ventas_detalle as vd')
            ->leftJoin('product as pr', 'pr.id', '=', 'vd.id_producto')
            ->where('vd.codigo_venta_cabecera', $codigo_venta)
            ->select([
                'vd.*',
                'pr.name as producto_nombre',
                'pr.code as producto_codigo',
                'pr.image as producto_imagen',
                DB::raw('ROUND(vd.precio_unitario * vd.cantidad, 2) as subtotal_item'),
            ])
            ->get();

        return response()->json([
            'Result' => 'OK',
            'cabecera' => $cabecera,
            'detalle' => $detalle,
        ]);
    }


    public function storeSell(Request $request)
    {
        $request->validate([
            'person_id' => 'nullable|integer',
            'invoice_code' => 'required|string',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'tipo_documento' => 'nullable|string',
            'operations' => 'required|array',
            'operations.*.product_id' => 'required|integer',
            'operations.*.q' => 'required|numeric',
            'operations.*.price_out' => 'required|numeric',
            'operations.*.pedido' => 'nullable|string',
            'operations.*.tipo' => 'nullable|string',
            'operations.*.codigo_producto' => 'nullable|string',
            'operations.*.unidad' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // ===== Resolver cliente: registrado o nuevo RUC =====
            $person_id = $request->input('person_id') ?: 0;
            $nuevo_ruc = $request->input('nuevo_ruc', '');

            if (!$person_id && $request->has('ruc_result') && $request->input('ruc_result.nombre')) {
                $rucData = $request->input('ruc_result');
                $existing = DB::table('person')->where('no', $nuevo_ruc)->first();
                if ($existing) {
                    $person_id = $existing->id;
                } else {
                    $person_id = DB::table('person')->insertGetId([
                        'no' => $nuevo_ruc,
                        'name' => $rucData['nombre'],
                        'address1' => $rucData['direccion'] ?? '',
                        'kind' => 1,
                    ]);
                }
                $nuevo_ruc = ''; // ya está registrado
            }

            // ===== Cálculos de pago (igual que clsVenta.php insertar_venta) =====
            $total = (float) $request->input('total');
            $detraccion_p = (float) $request->input('detraccion_p', 0);
            $tipos_pago = (int) $request->input('tipos_pago', 2);
            $valor_pagar = $total - round($detraccion_p);

            switch ($tipos_pago) {
                case 2:
                    $pagado = $valor_pagar;
                    break; // contado
                case 3:
                case 4:
                    $pagado = 0;
                    break; // crédito
                default:
                    $pagado = $valor_pagar;
            }
            $a_cuenta = $valor_pagar - $pagado;

            // ===== Insertar en ventas_cabecera =====
            // Recopilar todos los pedidos para la cabecera (legacy behaviour)
            $pedidos = [];
            foreach ($request->input('operations') as $op) {
                if (!empty($op['pedido'])) {
                    $pedidos[] = $op['pedido'];
                }
            }
            $pedido_cod_cabecera = !empty($pedidos) ? '0--' . implode('--', $pedidos) : '0';

            DB::table('ventas_cabecera')->insert([
                'codigo_venta' => $request->input('invoice_code'),
                'tipo_documento' => (int) $request->input('tipo_documento', 2),
                'id_person' => $person_id,
                'id_forma_pago' => (int) $request->input('forma_pago', 2),
                'id_estado_pago' => (int) $request->input('tipos_pago', 2),
                'id_estado_entrega' => (int) $request->input('tipos_entrega', 1),
                'almacen' => 1,
                'descuento' => (float) $request->input('discount', 0),
                'desc_descuento' => '',
                'detraccion' => $request->input('detraccion', 'no'),
                'detraccion_p' => round($detraccion_p),
                'igv_p' => (float) $request->input('igv', 0) - round($detraccion_p),
                'subtotal' => (float) $request->input('subtotal'),
                'subtotal_2' => (float) $request->input('subtotal'),
                'igv' => (float) $request->input('igv'),
                'igv_2' => (float) $request->input('igv'),
                'total' => $total,
                'total_2' => $total,
                'valor_pagar' => $valor_pagar,
                'pagado' => $pagado,
                'a_cuenta' => $a_cuenta,
                'guia' => $request->input('guia', ''),
                'fecha_emision' => $request->input('fecha_emision', now()->toDateString()),
                'fecha_vencimiento' => $request->input('fecha_vencimiento'),
                'pedido_cod' => $pedido_cod_cabecera,
                'ruc_add' => $nuevo_ruc,
                'incluye_igv' => (int) $request->input('incluye_igv', 1),
                'fecha_creacion' => now(),
            ]);

            // ===== Insertar en ventas_detalle =====
            $incluye_igv = (int) $request->input('incluye_igv', 1);
            $codigo_venta = $request->input('invoice_code');

            foreach ($request->input('operations') as $op) {
                $precio_unit = (float) $op['price_out'];
                // Si el precio ya incluye IGV, dividimos entre 1.18 para guardar el neto
                if ($incluye_igv === 1) {
                    $precio_unit = round($precio_unit / 1.18, 6);
                }

                DB::table('ventas_detalle')->insert([
                    'codigo_venta_cabecera' => $codigo_venta,
                    'id_producto' => (int) $op['product_id'],
                    'cantidad' => (float) $op['q'],
                    'pedido_cod' => $op['pedido'] ?? '',
                    'codigo_unidad' => $op['codigo_producto'] ?? '',
                    'unidad' => $op['unidad'] ?? '',
                    'precio_unitario' => $precio_unit,
                    'precio_bordado' => (float) ($op['price_bordado'] ?? 0),
                    'tipo' => $op['tipo'] ?? 'Producto',
                ]);
            }

            // ===== Incrementar correlativo =====
            $tipo = (int) $request->input('tipo_documento', 2);
            $tabla = '';
            if ($tipo == 1)
                $tabla = 'boleta';
            elseif ($tipo == 2)
                $tabla = 'factura';
            elseif ($tipo == 3)
                $tabla = 'nota_pedido';

            if ($tabla) {
                DB::table('aux')->where('tabla', $tabla)->increment('id');
            }

            DB::commit();

            return response()->json([
                'Result' => 'OK',
                'message' => 'Venta registrada correctamente',
                'codigo_venta' => $codigo_venta,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'Result' => 'ERROR',
                'message' => 'No se pudo registrar en base de datos. ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadSunatFiles($codigo)
    {
        $ruc = env('SUNAT_RUC', '20611081651');
        $tipoDoc = '01'; // Default

        $num_factura = explode('-', $codigo);
        $serie = $num_factura[0] ?? '';
        $correlativo = $num_factura[1] ?? '';

        $invoiceName = "{$ruc}-{$tipoDoc}-{$serie}-{$correlativo}";
        $filename = "{$serie}-{$correlativo}"; // e.g. F001-000123

        // Rutas del nuevo sistema
        $sunatPath = storage_path('app/sunat');
        $xmlFile = $sunatPath . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . $invoiceName . '.xml';
        $cdrFile = $sunatPath . DIRECTORY_SEPARATOR . 'cdr' . DIRECTORY_SEPARATOR . 'R-' . $invoiceName . '.zip';

        // Rutas del sistema legacy
        $legacyXml1 = 'https://omcar.peruviandress.com/facturador_v2/files/' . $invoiceName . '.xml';
        $legacyXml2 = 'https://omcar.peruviandress.com/facturador_v3/' . $invoiceName . '.xml';

        $zipName = $invoiceName . '_SUNAT.zip';
        $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $hasFiles = false;

            // Contexto para omitir verificación SSL
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            // XML: Buscar en nuevo sistema, luego en legacy (via HTTP)
            if (file_exists($xmlFile)) {
                $zip->addFile($xmlFile, basename($xmlFile));
                $hasFiles = true;
            } else {
                $xmlContent = @file_get_contents($legacyXml1, false, $context);
                if ($xmlContent !== false) {
                    $zip->addFromString(basename($legacyXml1), $xmlContent);
                    $hasFiles = true;
                } else {
                    $xmlContent = @file_get_contents($legacyXml2, false, $context);
                    if ($xmlContent !== false) {
                        $zip->addFromString(basename($legacyXml2), $xmlContent);
                        $hasFiles = true;
                    }
                }
            }

            // CDR: Solo existe en el nuevo sistema local? Y en legacy?
            // En legacy facturador_v3, el CDR está en 'cdr/' o en la misma carpeta como R-20455175781-01-F001-509.zip
            $legacyCdr1 = 'https://omcar.peruviandress.com/facturador_v2/files/R-' . $invoiceName . '.zip';
            $legacyCdr2 = 'https://omcar.peruviandress.com/facturador_v3/cdr/R-' . $invoiceName . '.zip';
            $legacyCdr3 = 'https://omcar.peruviandress.com/facturador_v3/R-' . $invoiceName . '.zip';

            if (file_exists($cdrFile)) {
                $zip->addFile($cdrFile, basename($cdrFile));
                $hasFiles = true;
            } else {
                $cdrContent = @file_get_contents($legacyCdr1, false, $context);
                if ($cdrContent !== false) {
                    $zip->addFromString('R-' . $invoiceName . '.zip', $cdrContent);
                    $hasFiles = true;
                } else {
                    $cdrContent = @file_get_contents($legacyCdr2, false, $context);
                    if ($cdrContent !== false) {
                        $zip->addFromString('R-' . $invoiceName . '.zip', $cdrContent);
                        $hasFiles = true;
                    } else {
                        $cdrContent = @file_get_contents($legacyCdr3, false, $context);
                        if ($cdrContent !== false) {
                            $zip->addFromString('R-' . $invoiceName . '.zip', $cdrContent);
                            $hasFiles = true;
                        }
                    }
                }
            }

            $zip->close();

            if (!$hasFiles) {
                if (file_exists($zipPath))
                    unlink($zipPath);
                return response()->json(['message' => 'Archivos no encontrados en ninguna ruta (XML ni CDR)'], 404);
            }
        } else {
            return response()->json(['message' => 'No se pudo crear el archivo ZIP temporal'], 500);
        }

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}
