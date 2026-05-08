<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\Clase;
use App\Models\Subclase;
use App\Models\Unidad;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Exception;

class InsumoConfigController extends Controller
{
    // Familias
    public function getFamilias() { return response()->json(['Result' => 'OK', 'Records' => Familia::all()]); }
    public function storeFamilia(Request $request) {
        Familia::create($request->all());
        return response()->json(['Result' => 'OK']);
    }
    public function updateFamilia(Request $request, $codigo) {
        Familia::where('codigo', $codigo)->update($request->only(['codigo', 'descripcion']));
        return response()->json(['Result' => 'OK']);
    }
    public function destroyFamilia($codigo) {
        if (Insumo::where('familia', $codigo)->exists()) return response()->json(['Result' => 'ERROR', 'message' => 'Esta Familia está siendo usada.'], 400);
        Familia::where('codigo', $codigo)->delete();
        return response()->json(['Result' => 'OK']);
    }

    // Clases
    public function getClases() { return response()->json(['Result' => 'OK', 'Records' => Clase::all()]); }
    public function storeClase(Request $request) {
        Clase::create($request->all());
        return response()->json(['Result' => 'OK']);
    }
    public function updateClase(Request $request, $id) {
        Clase::findOrFail($id)->update($request->only(['codigo', 'descripcion']));
        return response()->json(['Result' => 'OK']);
    }
    public function destroyClase($id) {
        $clase = Clase::findOrFail($id);
        if (Insumo::where('clase', $clase->codigo)->exists()) return response()->json(['Result' => 'ERROR', 'message' => 'Esta Clase está siendo usada.'], 400);
        $clase->delete();
        return response()->json(['Result' => 'OK']);
    }

    // Subclases
    public function getSubclases() { return response()->json(['Result' => 'OK', 'Records' => Subclase::all()]); }
    public function storeSubclase(Request $request) {
        Subclase::create($request->all());
        return response()->json(['Result' => 'OK']);
    }
    public function updateSubclase(Request $request, $id) {
        Subclase::findOrFail($id)->update($request->only(['codigo', 'descripcion']));
        return response()->json(['Result' => 'OK']);
    }
    public function destroySubclase($id) {
        $subclase = Subclase::findOrFail($id);
        if (Insumo::where('subclase', $subclase->codigo)->exists()) return response()->json(['Result' => 'ERROR', 'message' => 'Esta Subclase está siendo usada.'], 400);
        $subclase->delete();
        return response()->json(['Result' => 'OK']);
    }

    // Unidades
    public function getUnidades() { return response()->json(['Result' => 'OK', 'Records' => Unidad::all()]); }
    public function storeUnidad(Request $request) {
        Unidad::create($request->all());
        return response()->json(['Result' => 'OK']);
    }
    public function updateUnidad(Request $request, $codigo) {
        Unidad::where('codigo', $codigo)->update($request->only(['codigo', 'unidad']));
        return response()->json(['Result' => 'OK']);
    }
    public function destroyUnidad($codigo) {
        Unidad::where('codigo', $codigo)->delete();
        return response()->json(['Result' => 'OK']);
    }
}
