<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use Carbon\Carbon;


class PermisoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Hola desde PermisoController']);
    }
    public function lista()
    {
        return response()->json(Permiso::with(array('colaborador', 'tipo'))->get());
    }

    public function insertar(Request $request)
    {
        $id = Permiso::create([
            'id_colaborador' => $request->input('id_colaborador'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
            'motivo' => $request->input('motivo'),
            'id_tipo' => $request->input('id_tipo'),
            'hora_inicio' => $request->input('hora_inicio'),
            'hora_fin' => $request->input('hora_fin'),
            'estado' => $request->input('estado'),
            'id_usuario_creacion' => $request->input('id_usuario_creacion'),
            'fecha_creacion' => Carbon::now(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Permiso creado correctamente',
            'id' => $id
        ], 200);
    }

    public function editar($id)
    {
        return response()->json(Permiso::find($id));
    }
    public function eliminar($id)
    {
        $permiso = Permiso::find($id);

        if (!$permiso) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permiso no encontrada o no se pudo eliminar'
            ], 404);
        }

        $permiso->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permiso eliminado correctamente'
        ], 200);
    }
    public function editarEstado(Request $request, $id)
    {
        $permiso = Permiso::find($id);

        if (!$permiso) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permiso no encontrada o no se pudo editar'
            ], 404);
        }
        $permiso->update([
            'estado' => $request->input('estado'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Permiso actualizado correctamente',
            'id' => $id
        ], 200);
    }
    public function actualizar(Request $request, $id)
    {
        Permiso::where('id', $id)
            ->update([
                'id_colaborador' => $request->input('id_colaborador'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_fin' => $request->input('fecha_fin'),
                'motivo' => $request->input('motivo'),
                'id_tipo' => $request->input('id_tipo'),
                'hora_inicio' => $request->input('hora_inicio'),
                'hora_fin' => $request->input('hora_fin'),
                'estado' => $request->input('estado'),
                'id_usuario_modificacion' => $request->input('id_usuario_modificacion'),
                'fecha_modificacion' => Carbon::now(),
            ]);
        //return response()->json(['ok' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Permiso actualizada correctamente',
            'id' => $id
        ], 200);
    }
}
