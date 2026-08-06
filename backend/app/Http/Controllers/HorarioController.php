<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\HorarioDias;
use Carbon\Carbon;


class HorarioController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Hola desde HorarioController']);
    }
    public function lista()
    {
        $horarios = Horario::with('dias')->get();
        return response()->json($horarios);
    }

    public function insertar(Request $request)
    {
        /*$id = Horario::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'estado' => $request->input('estado'),
            'id_usuario_creacion' => $request->input('id_usuario_creacion'),
            'fecha_creacion' => Carbon::now(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Horario creado correctamente',
            'id' => $id
        ], 200);*/
        \DB::beginTransaction();
        try {
            // Guardamos la cabecera de la compra
            $horario = Horario::create([
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'estado' => $request->input('estado'),
                'id_usuario_creacion' => $request->input('id_usuario_creacion'),
                'tolerancia_min' => $request->input('tolerancia_min'),
                'fecha_creacion' => Carbon::now(),
            ]);

            // Guardamos el detalle (carrito)
            $dias = $request->input('dias');
            foreach ($dias as $item) {
                $detalle = HorarioDias::create([
                    'id_horario' => $horario->id,
                    'dia_semana' => $item['dia'],
                    'hora_entrada' => $dia['hora_entrada'],
                    'hora_salida' => $dia['hora_salida'],
                    'descanso' => $item['usa_refrigerio'] ? 1 : 0,
                    'activo' => $item['activo'],
                    'hora_fin_refrigerio' => $item['hora_fin_refrigerio'],
                    'hora_inicio_refrigerio' => $item['hora_inicio_refrigerio'],
                    'id_usuario_creacion' => $request->input('id_usuario_creacion'),
                    'fecha_creacion' => Carbon::now(),
                ]);
            }
            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Horario creado correctamente',
                'id' => $horario->id
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error al registrar el horario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editar($id)
    {
        return response()->json(Horario::find($id));
    }
    public function eliminar($id)
    {
        $horario = Horario::find($id);

        if (!$horario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Horario no encontrada o no se pudo eliminar'
            ], 404);
        }

        $horario->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Horario eliminado correctamente'
        ], 200);
    }
    public function editarEstado(Request $request, $id)
    {
        $horario = Horario::find($id);

        if (!$horario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Horario no encontrada o no se pudo editar'
            ], 404);
        }
        $horario->update([
            'estado' => $request->input('estado'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Horario actualizado correctamente',
            'id' => $id
        ], 200);
    }
    public function actualizar(Request $request, $id)
    {
        \DB::beginTransaction();

        try {
            // 1️⃣ Actualizamos la tabla principal
            Horario::where('id', $id)->update([
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'estado' => $request->input('estado'),
                'id_usuario_modificacion' => $request->input('id_usuario_modificacion'),
                'tolerancia_min' => $request->input('tolerancia_min'),
                'fecha_modificacion' => Carbon::now(),
            ]);

            // 2️⃣ Borramos los días actuales para reemplazarlos
            HorarioDias::where('id_horario', $id)->delete();

            // 3️⃣ Insertamos los días enviados desde el frontend
            $dias = $request->input('dias', []);

            foreach ($dias as $dia) {
                $detalle = HorarioDias::create([
                    'id_horario' => $id,
                    'dia_semana' => $dia['dia'],
                    'hora_entrada' => $dia['hora_entrada'],
                    'hora_salida' => $dia['hora_salida'],
                    'descanso' => $dia['usa_refrigerio'] ? 1 : 0,
                    'activo' => $dia['activo'],
                    'hora_fin_refrigerio' => $dia['hora_fin_refrigerio'],
                    'hora_inicio_refrigerio' => $dia['hora_inicio_refrigerio'],
                    'id_usuario_creacion' => $request->input('id_usuario_creacion'),
                    'fecha_creacion' => Carbon::now(),
                ]);
            }

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Horario actualizado correctamente',
                'id' => $id
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el horario: ' . $e->getMessage(),
            ], 500);
        }
    }
}
