<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HorarioDias;
use Carbon\Carbon;


class HorarioDiasController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Hola desde HorarioDiasController']);
    }
    public function lista()
    {
        return response()->json(HorarioDias::all());
    }

    public function insertar(Request $request)
    {
        $id = HorarioDias::create([
            'id_horario' => $request->input('id_horario'),
            'dia_semana' => $request->input('dia_semana'),
            'hora_entrada' => $request->input('hora_entrada'),
            'hora_salida' => $request->input('hora_salida'),
            'tolerancia_min' => $request->input('tolerancia_min'),
            'descanso' => $request->input('descanso'),
            'activo' => $request->input('activo'),
            'hora_fin_refrigerio' => $request->input('hora_fin_refrigerio'),
            'hora_inicio_refrigerio' => $request->input('hora_inicio_refrigerio'),
            'id_usuario_creacion' => $request->input('id_usuario_creacion'),
            'fecha_creacion' => Carbon::now(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'HorarioDias creado correctamente',
            'id' => $id
        ], 200);
    }

    public function editar($id)
    {
        return response()->json(HorarioDias::find($id));
    }
    public function eliminar($id)
    {
        $horario = HorarioDias::find($id);

        if (!$horario) {
            return response()->json([
                'status' => 'error',
                'message' => 'HorarioDias no encontrada o no se pudo eliminar'
            ], 404);
        }

        $horario->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'HorarioDias eliminado correctamente'
        ], 200);
    }
    public function actualizar(Request $request, $id)
    {
        HorarioDias::where('id', $id)
            ->update([
                'id_horario' => $request->input('id_horario'),
                'dia_semana' => $request->input('dia_semana'),
                'hora_entrada' => $request->input('hora_entrada'),
                'hora_salida' => $request->input('hora_salida'),
                'tolerancia_min' => $request->input('tolerancia_min'),
                'descanso' => $request->input('descanso'),
                'activo' => $request->input('activo'),
                'estado' => $request->input('estado'),
                'id_usuario_modificacion' => $request->input('id_usuario_modificacion'),
                'fecha_modificacion' => Carbon::now(),
            ]);
        //return response()->json(['ok' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'HorarioDias actualizada correctamente',
            'id' => $id
        ], 200);
    }
}
