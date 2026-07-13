<?php

namespace App\Http\Controllers;

use App\Models\MaquinaMantenimiento;
use Illuminate\Http\Request;

class MaquinaMantenimientoController extends Controller
{
    public function index(Request $request)
    {
        $maquina_id = $request->query('maquina_id');
        return response()->json(MaquinaMantenimiento::where('maquina_id', $maquina_id)->orderBy('maq_mtto_fecha', 'desc')->get());
    }

    public function store(Request $request)
    {
        $mantenimiento = MaquinaMantenimiento::create($request->all());
        return response()->json($mantenimiento, 201);
    }

    public function update(Request $request, $id)
    {
        $mantenimiento = MaquinaMantenimiento::findOrFail($id);
        $mantenimiento->update($request->all());
        return response()->json($mantenimiento);
    }

    public function destroy($id)
    {
        $mantenimiento = MaquinaMantenimiento::findOrFail($id);
        $mantenimiento->delete();
        return response()->json(null, 204);
    }
}
