<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CotizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::with('person')
            ->select(
                '*',
                DB::raw("DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fec_cre")
            )
            ->orderBy('fecha_creacion', 'DESC');

        if ($request->has('person_id') && $request->person_id != 0) {
            $query->where('person_id', $request->person_id);
        }

        $cotizations = $query->get();

        foreach ($cotizations as $cot) {
            $cot->name = $cot->person_id == 0 ? $cot->cliente : ($cot->person ? $cot->person->name : 'N/A');
            
            // Get first image from details for thumbnail
            $detail = CotizacionDetalle::where('codigo_cotizacion', $cot->codigo)->first();
            $cot->imagen = $detail ? $detail->imagen : null;
        }

        return response()->json([
            'Result' => 'OK',
            'Records' => $cotizations
        ]);
    }

    public function show($codigo)
    {
        $cabecera = Cotizacion::with('person')->where('codigo', $codigo)->first();

        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Cotización no encontrada'], 404);
        }

        $detalle = CotizacionDetalle::with('product')->where('codigo_cotizacion', $codigo)->get();

        return response()->json([
            'Result' => 'OK',
            'cabecera' => $cabecera,
            'detalle' => $detalle
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get correlative from kind_doc id=4
            $correlativo_doc = DB::table('kind_doc')->where('id', 4)->first();
            $codigo = (string)$correlativo_doc->numero;

            // Create header using model
            $cotizacion = Cotizacion::create([
                'codigo' => $codigo,
                'tiempo_entrega' => $request->input('tiempo_entrega', ''),
                'obervacion' => $request->input('observacion', ''),
                'servicios' => $request->input('servicios', ''),
                'person_id' => $request->input('person_id', 0),
                'cliente' => $request->input('cliente_extra', ''),
                'validez' => $request->input('validez', ''),
                'forma_pago' => $request->input('forma_pago', ''),
                'tallas_especiales' => $request->input('tallas_especiales', ''),
                'asesor_comercial' => $request->input('asesor_comercial', ''),
                'asesor_celular' => $request->input('asesor_celular', ''),
                'fecha_creacion' => now(),
                'igv_incluye' => $request->input('aplica_igv') === 'yes' ? 1 : 0,
                'total' => '0', // Will update later
                'sub_total' => '0',
                'igv' => '0'
            ]);

            $total_monto = 0;
            $items = json_decode($request->input('items'), true);

            foreach ($items as $item) {
                $productId = $item['product_id'];
                $cantidad = (float)($item['cantidad'] ?? 0);
                $costo = (float)($item['costo'] ?? 0);
                
                $imagen = null;
                $imagen_2 = null;

                // Handle image uploads
                if ($request->hasFile("imagen_$productId")) {
                    $file = $request->file("imagen_$productId");
                    $filename = time() . "_1_" . $file->getClientOriginalName();
                    $destinationPath = base_path('../storage/products');
                    $file->move($destinationPath, $filename);
                    $imagen = $filename;
                } elseif (!empty($item['img_m'])) {
                    $imagen = $item['img_m'];
                }

                if ($request->hasFile("imagen_b_$productId")) {
                    $file = $request->file("imagen_b_$productId");
                    $filename = time() . "_2_" . $file->getClientOriginalName();
                    $destinationPath = base_path('../storage/products');
                    $file->move($destinationPath, $filename);
                    $imagen_2 = $filename;
                } elseif (!empty($item['img_b'])) {
                    $imagen_2 = $item['img_b'];
                }

                CotizacionDetalle::create([
                    'codigo_cotizacion' => $codigo,
                    'id_producto' => $productId,
                    'cantidad' => $cantidad,
                    'imagen' => $imagen,
                    'imagen_2' => $imagen_2,
                    'costo' => (string)$costo,
                    'descripcion' => $item['descripcion'] ?? '',
                    'nombre_producto' => $item['nombre_producto'] ?? '',
                    'servicios' => '' // Can be expanded if needed
                ]);

                $total_monto += ($cantidad * $costo);
            }

            $igv_monto = 0;
            $sub_total_monto = $total_monto;

            if ($request->input('aplica_igv') === 'yes') {
                $igv_monto = $total_monto * 0.18;
                $total_monto = $total_monto + $igv_monto;
            }

            // Update header with totals
            $cotizacion->update([
                'sub_total' => (string)round($sub_total_monto, 2),
                'total' => (string)round($total_monto, 2),
                'igv' => (string)round($igv_monto, 2)
            ]);

            // Update correlative in kind_doc
            DB::table('kind_doc')->where('id', 4)->increment('numero');

            DB::commit();
            return response()->json(['Result' => 'OK', 'codigo' => $codigo]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($codigo)
    {
        DB::beginTransaction();
        try {
            CotizacionDetalle::where('codigo_cotizacion', $codigo)->delete();
            Cotizacion::where('codigo', $codigo)->delete();
            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }
}
