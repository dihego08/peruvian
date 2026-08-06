<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reloj;
use Carbon\Carbon;


class RelojController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Hola desde RelojController']);
    }
    public function lista()
    {
        return response()->json(Reloj::all());
    }

    public function insertar(Request $request)
    {
        $id = Reloj::create([
            'nombre' => $request->input('nombre'),
            'ip' => $request->input('ip'),
            'puerto' => $request->input('puerto'),
            'ubicacion' => $request->input('ubicacion'),
            'estado' => $request->input('estado'),
            'id_usuario_creacion' => $request->input('id_usuario_creacion'),
            'fecha_creacion' => Carbon::now(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Reloj creado correctamente',
            'id' => $id
        ], 200);
    }

    public function editar($id)
    {
        return response()->json(Reloj::find($id));
    }
    public function eliminar($id)
    {
        $reloj = Reloj::find($id);

        if (!$reloj) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reloj no encontrada o no se pudo eliminar'
            ], 404);
        }

        $reloj->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reloj eliminado correctamente'
        ], 200);
    }
    public function editarEstado(Request $request, $id)
    {
        $reloj = Reloj::find($id);

        if (!$reloj) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reloj no encontrada o no se pudo editar'
            ], 404);
        }
        $reloj->update([
            'estado' => $request->input('estado'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Reloj actualizado correctamente',
            'id' => $id
        ], 200);
    }
    public function actualizar(Request $request, $id)
    {
        Reloj::where('id', $id)
            ->update([
                'nombre' => $request->input('nombre'),
                'ip' => $request->input('ip'),
                'puerto' => $request->input('puerto'),
                'ubicacion' => $request->input('ubicacion'),
                'estado' => $request->input('estado'),
                'id_usuario_modificacion' => $request->input('id_usuario_modificacion'),
                'fecha_modificacion' => Carbon::now(),
            ]);
        //return response()->json(['ok' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reloj actualizada correctamente',
            'id' => $id
        ], 200);
    }
}
