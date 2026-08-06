<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feriado;
use Carbon\Carbon;


class FeriadoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Hola desde FeriadoController']);
    }
    public function lista()
    {
        return response()->json(Feriado::all());
    }

    public function insertar(Request $request)
    {
        $id = Feriado::create([
            'nombre' => $request->input('nombre'),
            'fecha' => $request->input('fecha'),
            'descripcion' => $request->input('descripcion'),
            /*'id_tipo' => $request->input('id_tipo'),*/
            'estado' => $request->input('estado'),
            'id_usuario_creacion' => $request->input('id_usuario_creacion'),
            'fecha_creacion' => Carbon::now(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Feriado creado correctamente',
            'id' => $id
        ], 200);
    }

    public function editar($id)
    {
        return response()->json(Feriado::find($id));
    }
    public function eliminar($id)
    {
        $feriado = Feriado::find($id);

        if (!$feriado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Feriado no encontrada o no se pudo eliminar'
            ], 404);
        }

        $feriado->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Feriado eliminado correctamente'
        ], 200);
    }
    public function editarEstado(Request $request, $id)
    {
        $feriado = Feriado::find($id);

        if (!$feriado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Feriado no encontrada o no se pudo editar'
            ], 404);
        }
        $feriado->update([
            'estado' => $request->input('estado'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Feriado actualizado correctamente',
            'id' => $id
        ], 200);
    }
    public function actualizar(Request $request, $id)
    {
        Feriado::where('id', $id)
            ->update([
                'fecha' => $request->input('fecha'),
                'descripcion' => $request->input('descripcion'),
                /*'id_tipo' => $request->input('id_tipo'),*/
                'estado' => $request->input('estado'),
                'id_usuario_modificacion' => $request->input('id_usuario_modificacion'),
                'fecha_modificacion' => Carbon::now(),
            ]);
        //return response()->json(['ok' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Feriado actualizada correctamente',
            'id' => $id
        ], 200);
    }
}
