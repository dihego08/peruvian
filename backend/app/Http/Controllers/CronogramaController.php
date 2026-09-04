<?php

namespace App\Http\Controllers;

use App\Models\CronogramaRegistro;
use App\Models\CronogramaRegistroFecha;
use App\Models\TipoCronograma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CronogramaController extends Controller
{
    public function getTipos()
    {
        return response()->json(TipoCronograma::all());
    }

    public function index(Request $request)
    {
        $anio = $request->query('anio', date('Y'));
        $tipo = $request->query('tipo', 2);

        $cronogramas = CronogramaRegistro::with('fechas')
            ->where('anio', $anio)
            ->where('id_tipo', $tipo)
            ->get();

        return response()->json($cronogramas);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $cronograma = CronogramaRegistro::create([
                'curso' => $request->curso,
                'areas' => $request->areas,
                'mes' => $request->mes[0] ?? null,
                'anio' => $request->anio,
                'responsable' => $request->responsable,
                'estado' => 1,
                'dia' => $request->dia[0] ?? null,
                'fecha' => date('Y-m-d'),
                'eficacia' => $request->eficacia,
                'id_tipo' => $request->id_tipo
            ]);

            if ($request->has('mes') && $request->has('dia')) {
                foreach ($request->mes as $index => $mes) {
                    if ($mes !== null && isset($request->dia[$index])) {
                        CronogramaRegistroFecha::create([
                            'id_capacitacion_registro' => $cronograma->id,
                            'mes' => $mes,
                            'dia' => $request->dia[$index],
                            'estado' => 0 // 0 = Planificado
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json($cronograma->load('fechas'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $cronograma = CronogramaRegistro::with('fechas')->findOrFail($id);
        return response()->json($cronograma);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cronograma = CronogramaRegistro::findOrFail($id);
            $cronograma->update([
                'curso' => $request->curso,
                'areas' => $request->areas,
                'anio' => $request->anio,
                'responsable' => $request->responsable,
                'eficacia' => $request->eficacia,
                'id_tipo' => $request->id_tipo
            ]);

            if ($request->has('mes') && $request->has('dia')) {
                // For simplicity, delete old and recreate
                $cronograma->fechas()->delete();
                foreach ($request->mes as $index => $mes) {
                    if ($mes !== null && isset($request->dia[$index])) {
                        CronogramaRegistroFecha::create([
                            'id_capacitacion_registro' => $cronograma->id,
                            'mes' => $mes,
                            'dia' => $request->dia[$index],
                            'estado' => 0
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json($cronograma->load('fechas'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $cronograma = CronogramaRegistro::findOrFail($id);
        $cronograma->fechas()->delete();
        $cronograma->delete();
        return response()->json(null, 204);
    }

    public function actualizarEstadoFecha(Request $request, $id)
    {
        $fecha = CronogramaRegistroFecha::findOrFail($id);
        $fecha->update(['estado' => $request->estado]);
        return response()->json($fecha);
    }
}
