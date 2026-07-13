<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Insumo;
use App\Models\InsumoStock;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $query = Compra::with('provider')->orderBy('id', 'desc');

        if ($request->desde && $request->hasta) {
            $query->whereBetween(DB::raw('DATE(fecha_creacion)'), [$request->desde, $request->hasta]);
        }

        if ($request->id_proveedor && $request->id_proveedor != 0) {
            $query->where('id_proveedor', $request->id_proveedor);
        }

        if ($request->tipo_documento && $request->tipo_documento != -1) {
            $query->where('tipo_documento', $request->tipo_documento);
        }

        if ($request->tipo_pago && $request->tipo_pago != -1) {
            $query->where('id_forma_pago', $request->tipo_pago);
        }

        if ($request->fproceso_desde && $request->fproceso_hasta) {
            $query->whereBetween(DB::raw('DATE(fproceso)'), [$request->fproceso_desde, $request->fproceso_hasta]);
        }

        $compras = $query->get();

        // Map names for display if needed or handle in frontend
        return response()->json([
            'Result' => 'OK',
            'Records' => $compras
        ]);
    }

    public function show($id)
    {
        $compra = Compra::with(['provider', 'details.insumo'])->find($id);
        if (!$compra) return response()->json(['Result' => 'ERROR', 'Message' => 'Compra no encontrada'], 404);

        return response()->json([
            'Result' => 'OK',
            'Record' => $compra
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            
            // Create the purchase header
            $compra = Compra::create($data);

            // Process details
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $item['id_compra'] = $compra->id;
                    $item['codigo_compra'] = $compra->codigo;
                    CompraDetalle::create($item);

                    // Update insumos_2.stock
                    $insumo = Insumo::find($item['id_insumo']);
                    if ($insumo) {
                        $insumo->stock += $item['cantidad'];
                        $insumo->save();
                    }

                    // Update or create insumo_stock (by unit)
                    $stockUnit = InsumoStock::where('id_insumo', $item['id_insumo'])
                        ->where('codigo_unidad', $item['unidad'])
                        ->first();

                    if ($stockUnit) {
                        $stockUnit->stock += $item['cantidad'];
                        $stockUnit->save();
                    } else {
                        InsumoStock::create([
                            'id_insumo' => $item['id_insumo'],
                            'codigo_unidad' => $item['unidad'],
                            'stock' => $item['cantidad'],
                            'precio' => $item['precio']
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['Result' => 'OK', 'id' => $compra->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'Message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $compra = Compra::with('details')->findOrFail($id);

            foreach ($compra->details as $detail) {
                // Revert stock in insumos_2
                $insumo = Insumo::find($detail->id_insumo);
                if ($insumo) {
                    $insumo->stock -= $detail->cantidad;
                    $insumo->save();
                }

                // Revert stock in insumo_stock
                $stockUnit = InsumoStock::where('id_insumo', $detail->id_insumo)
                    ->where('codigo_unidad', $detail->unidad)
                    ->first();
                if ($stockUnit) {
                    $stockUnit->stock -= $detail->cantidad;
                    $stockUnit->save();
                }
            }

            $compra->details()->delete();
            $compra->delete();

            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'Message' => $e->getMessage()], 500);
        }
    }

    public function getTiposDocumento()
    {
        $tipos = DB::table('kind_doc')->whereIn('modulo', [1, 3])->get();
        return response()->json($tipos);
    }
}
