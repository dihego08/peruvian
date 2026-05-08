<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\InsumoStock;
use App\Models\Familia;
use App\Models\Clase;
use App\Models\Subclase;
use App\Models\Unidad;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::all();
        $records = [];

        foreach ($insumos as $insumo) {
            $stocks = InsumoStock::where('id_insumo', $insumo->id)->get();
            $suma_unidades = $stocks->sum('stock');
            $suma_precio = $stocks->sum('precio');

            $records[] = [
                'id' => $insumo->id,
                'familia' => $insumo->familia,
                'clase' => $insumo->clase,
                'subclase' => $insumo->subclase,
                'codigo' => $insumo->codigo,
                'insumo' => $insumo->insumo,
                'precio_total' => $suma_precio,
                'stock_total' => $suma_unidades,
                'total_to' => $suma_unidades * $suma_precio
            ];
        }

        return response()->json([
            'Result' => 'OK',
            'Records' => $records
        ]);
    }

    public function show($id)
    {
        $insumo = Insumo::findOrFail($id);
        return response()->json($insumo);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            Insumo::create([
                'insumo' => $request->insumo,
                'familia' => $request->familia,
                'clase' => $request->clase,
                'subclase' => $request->subclase,
                'codigo' => $request->codigo
            ]);

            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $insumo = Insumo::findOrFail($id);
            $insumo->update([
                'insumo' => $request->insumo,
                'familia' => $request->familia,
                'clase' => $request->clase,
                'subclase' => $request->subclase,
                'codigo' => $request->codigo
            ]);
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Insumo::findOrFail($id)->delete();
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    // Stock Management
    public function getStock($id_insumo)
    {
        $insumo = Insumo::findOrFail($id_insumo);
        $stocks = InsumoStock::where('id_insumo', $id_insumo)->get();
        
        $stockRecords = [];
        foreach ($stocks as $stock) {
            $provider = Person::find($stock->id_proveedor);
            $stockRecords[] = [
                'id' => $stock->id,
                'proveedor' => $provider ? $provider->name : 'N/A',
                'descripcion' => trim($stock->descripcion),
                'codigo_unidad' => $stock->codigo_unidad,
                'stock' => $stock->stock,
                'precio' => $stock->precio,
                'fecha' => trim($stock->fecha)
            ];
        }

        return response()->json([
            'insumo' => $insumo->insumo,
            'stock' => $stockRecords
        ]);
    }

    public function storeStock(Request $request)
    {
        try {
            InsumoStock::create([
                'id_insumo' => $request->id_insumo,
                'stock' => $request->stock,
                'codigo_unidad' => $request->codigo_unidad,
                'precio' => $request->precio,
                'id_proveedor' => $request->id_proveedor,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha
            ]);
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStock(Request $request, $id)
    {
        try {
            $stock = InsumoStock::findOrFail($id);
            $stock->update([
                'stock' => $request->stock,
                'codigo_unidad' => $request->codigo_unidad,
                'precio' => $request->precio,
                'id_proveedor' => $request->id_proveedor,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha
            ]);
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyStock($id)
    {
        try {
            InsumoStock::findOrFail($id)->delete();
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    public function editStock($id)
    {
        return response()->json(InsumoStock::findOrFail($id));
    }

    // Combos
    public function getFamilias() { return response()->json(['Result' => 'OK', 'Records' => Familia::all()]); }
    public function getClases() { return response()->json(['Result' => 'OK', 'Records' => Clase::all()]); }
    public function getSubclases() { return response()->json(['Result' => 'OK', 'Records' => Subclase::all()]); }
    public function getUnidades() { return response()->json(['Result' => 'OK', 'Records' => Unidad::all()]); }
    public function getProviders() { return response()->json(['Result' => 'OK', 'Records' => Person::providers()->get()]); }
}
