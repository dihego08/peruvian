<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function asistencia(Request $request)
    {
        $id = $request->input('id_colaborador');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Validaciones básicas
        if (!$id || !$fechaInicio || !$fechaFin) {
            return response()->json(['error' => 'Parámetros faltantes'], 400);
        }

        // Ejecutar el procedimiento almacenado
        $reporte = DB::select("CALL sp_reporte_asistencia(?, ?, ?)", [
            $id,
            $fechaInicio,
            $fechaFin
        ]);

        return response()->json($reporte);
    }
    public function asistencia_dia(Request $request)
    {
        $fecha = $request->input('fecha');

        // Validaciones básicas
        if (!$fecha) {
            return response()->json(['error' => 'Parámetros faltantes'], 400);
        }

        // Ejecutar el procedimiento almacenado
        $reporte = DB::select("CALL sp_reporte_asistencia_dia (?)", [
            $fecha
        ]);

        return response()->json($reporte);
    }
    public function asistencia_dias(Request $request)
    {
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Validaciones básicas
        if (!$mes || !$anio) {
            return response()->json(['error' => 'Parámetros faltantes'], 400);
        }

        // Ejecutar el procedimiento almacenado
        $reporte = DB::select("CALL sp_reporte_asistencia_complex(?, ?)", [
            $mes,
            $anio
        ]);

        return response()->json($reporte);
    }
}
