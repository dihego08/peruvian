<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\RegistroDispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispositivoController extends Controller
{
    public function index()
    {
        // Equivalent to getting the device with max records (similar to legacy)
        $dispositivos = Dispositivo::all()->map(function ($dispositivo) {
            $latestRegistro = RegistroDispositivo::where('id_dispositivo', $dispositivo->id)
                ->orderBy('id', 'desc')
                ->first();

            $dispositivo->fecha_entrega = $latestRegistro ? $latestRegistro->fecha_entrega : $dispositivo->fecha;
            $dispositivo->recibido_por = $latestRegistro ? $latestRegistro->recibido_por : $dispositivo->responsable;
            $dispositivo->cantidad_actual = $latestRegistro ? $latestRegistro->cantidad : $dispositivo->cantidad;
            return $dispositivo;
        });

        return response()->json($dispositivos);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/dispositivos'), $filename);
            $data['imagen'] = $filename;
        }

        $dispositivo = Dispositivo::create($data);
        return response()->json($dispositivo, 201);
    }

    public function show($id)
    {
        $dispositivo = Dispositivo::with('registros')->findOrFail($id);
        return response()->json($dispositivo);
    }

    public function update(Request $request, $id)
    {
        $dispositivo = Dispositivo::findOrFail($id);
        $data = $request->all();
        
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/dispositivos'), $filename);
            $data['imagen'] = $filename;
        }

        $dispositivo->update($data);
        return response()->json($dispositivo);
    }

    public function destroy($id)
    {
        $dispositivo = Dispositivo::findOrFail($id);
        // Also delete related records
        $dispositivo->registros()->delete();
        $dispositivo->delete();
        return response()->json(null, 204);
    }
}
