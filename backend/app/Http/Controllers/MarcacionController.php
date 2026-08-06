<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ← 1. Importante: Agregar el Facade de Log
use App\Models\Marcacion;
use Carbon\Carbon;

class MarcacionController extends Controller
{
    const CHUNK_SIZE = 500; // Ajusta según tu servidor

    public function insertarBatch(Request $request)
    {
        $marcaciones = $request->all();

        if (empty($marcaciones)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'No se recibieron registros'
            ], 400);
        }

        // Eliminar duplicados exactos en memoria (del mismo lote)
        $marcaciones = collect($marcaciones)->unique(function ($m) {
            return $m['dni'] . '|' . $m['fecha_hora'] . '|' . $m['reloj_ip'];
        })->values();

        $totalRecibidos = $marcaciones->count();
        $totalChunks = 0;
        $errores = [];

        try {
            DB::beginTransaction();

            // Procesar en chunks
            $marcaciones->chunk(self::CHUNK_SIZE)->each(function($chunk, $index) use (&$totalChunks, &$errores) {
                try {
                    $values = [];
                    $bindings = [];
                    $registrosLog = []; // ← 2. Variable para almacenar los datos a loguear

                    foreach ($chunk as $m) {
                        if (isset($m['reloj_ip']) && $m['reloj_ip'] === 'MANUAL') {
                            $fecha_ajustada = $m['fecha_hora'];
                        } else {
                            $fecha_ajustada = $this->ajustarZonaHoraria($m['fecha_hora']);
                        }
                        
                        $values[] = "(?, ?, ?, ?, NOW())";
                        $bindings[] = $m['dni'];
                        $bindings[] = $fecha_ajustada;
                        $bindings[] = $m['estado'] ?? 0;
                        $bindings[] = $m['reloj_ip'];

                        // ← 3. Preparamos la línea de texto para el log por cada marcación
                        $registrosLog[] = "DNI: {$m['dni']} | Fecha: {$fecha_ajustada} | IP: {$m['reloj_ip']}";
                    }

                    $sql = "INSERT INTO marcaciones (dni, fecha_hora, estado, reloj_ip, created_at) 
                            VALUES " . implode(',', $values) . "
                            ON DUPLICATE KEY UPDATE updated_at = NOW()";

                    DB::statement($sql, $bindings);
                    $totalChunks++;

                    // ← 4. Escribimos en el log indicando qué registros se enviaron a la BD
                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/marcaciones.log'),
                    ])->info("Lote " . ($index + 1) . " procesado con éxito. Registros enviados:", $registrosLog);

                } catch (\Exception $e) {
                    $errores[] = "Chunk " . ($index + 1) . ": " . $e->getMessage();
                    
                    // ← 5. También logueamos si ocurre un error en el lote
                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/marcaciones.log'),
                    ])->error("Error en Lote " . ($index + 1) . ": " . $e->getMessage());
                }
            });

            DB::commit();

            return response()->json([
                'status' => 'success',
                'received' => $totalRecibidos,
                'inserted' => $totalRecibidos,
                'chunks_processed' => $totalChunks,
                'chunk_size' => self::CHUNK_SIZE,
                'errors' => $errores,
                'message' => 'Marcaciones procesadas correctamente'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/marcaciones.log'),
            ])->critical("Error crítico en la transacción completa: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error crítico: ' . $e->getMessage(),
                'errors' => $errores
            ], 500);
        }
    }
    
    private function ajustarZonaHoraria($fecha_hora)
    {
        try {
            return Carbon::parse($fecha_hora)->subHours(5)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return $fecha_hora;
        }
    }
}
/*namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Marcacion;
use Carbon\Carbon;  // ← Agregar al inicio

class MarcacionController extends Controller
{
    const CHUNK_SIZE = 500; // Ajusta según tu servidor

    public function insertarBatch(Request $request)
    {
        $marcaciones = $request->all();

        if (empty($marcaciones)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'No se recibieron registros'
            ], 400);
        }

        // Eliminar duplicados en memoria
        $marcaciones = collect($marcaciones)->unique(function ($m) {
            return $m['dni'] . '|' . $m['fecha_hora'] . '|' . $m['reloj_ip'];
        })->values();

        $totalRecibidos = $marcaciones->count();
        $totalChunks = 0;
        $errores = [];

        try {
            DB::beginTransaction();

            // Procesar en chunks
            $marcaciones->chunk(self::CHUNK_SIZE)->each(function($chunk, $index) use (&$totalChunks, &$errores) {
                try {
                    $values = [];
                    $bindings = [];

                    foreach ($chunk as $m) {
                        // ✅ ANTES DE GUARDAR: Restar 5 horas
                        $fecha_ajustada = $this->ajustarZonaHoraria($m['fecha_hora']);
                        
                        $values[] = "(?, ?, ?, ?, NOW())";
                        $bindings[] = $m['dni'];
                        $bindings[] = $fecha_ajustada;  // ← Usar fecha ajustada
                        $bindings[] = $m['estado'] ?? 0;
                        $bindings[] = $m['reloj_ip'];
                    }

                    $sql = "INSERT INTO marcaciones (dni, fecha_hora, estado, reloj_ip, created_at) 
                            VALUES " . implode(',', $values) . "
                            ON DUPLICATE KEY UPDATE updated_at = NOW()";

                    DB::statement($sql, $bindings);
                    $totalChunks++;

                } catch (\Exception $e) {
                    $errores[] = "Chunk " . ($index + 1) . ": " . $e->getMessage();
                }
            });

            DB::commit();

            return response()->json([
                'status' => 'success',
                'received' => $totalRecibidos,
                'inserted' => $totalRecibidos,
                'chunks_processed' => $totalChunks,
                'chunk_size' => self::CHUNK_SIZE,
                'errors' => $errores,
                'message' => 'Marcaciones procesadas correctamente'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error crítico: ' . $e->getMessage(),
                'errors' => $errores
            ], 500);
        }
    }
    private function ajustarZonaHoraria($fecha_hora)
    {
        try {
            return Carbon::parse($fecha_hora)->subHours(5)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return $fecha_hora;  // Si falla, retorna original
        }
    }
}*/