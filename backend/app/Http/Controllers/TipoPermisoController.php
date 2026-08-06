<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoPermiso;
use Carbon\Carbon;


class TipoPermisoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Hola desde TipoPermisoController']);
    }
    public function lista()
    {
        return response()->json(TipoPermiso::all());
    }

    public function insertar(Request $request)
    {
        $id = TipoPermiso::create([
            'tipo' => $request->input('tipo'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'TipoPermiso creado correctamente',
            'id' => $id
        ], 200);
    }

    public function editar($id)
    {
        return response()->json(TipoPermiso::find($id));
    }
    public function eliminar($id)
    {
        $tipoPermiso = TipoPermiso::find($id);

        if (!$tipoPermiso) {
            return response()->json([
                'status' => 'error',
                'message' => 'TipoPermiso no encontrada o no se pudo eliminar'
            ], 404);
        }

        $tipoPermiso->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'TipoPermiso eliminado correctamente'
        ], 200);
    }
    public function actualizar(Request $request, $id)
    {
        TipoPermiso::where('id', $id)
            ->update([
                'tipo' => $request->input('tipo'),
            ]);
        return response()->json([
            'status' => 'success',
            'message' => 'TipoPermiso actualizada correctamente',
            'id' => $id
        ], 200);
    }
}
