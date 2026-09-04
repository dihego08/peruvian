<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use Illuminate\Http\Request;

class MaquinaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', '1');
        return response()->json(Maquina::where('maquina_estado', $status)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->all();
        if (!isset($validated['maquina_fecha_registro'])) {
            $validated['maquina_fecha_registro'] = now();
        }

        if ($request->hasFile('maquina_imagen')) {
            $file = $request->file('maquina_imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $validated['maquina_imagen'] = $filename;
        }

        if ($request->hasFile('factura_compra')) {
            $file = $request->file('factura_compra');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $validated['factura_compra'] = $filename;
        }

        $maquina = Maquina::create($validated);
        return response()->json($maquina, 201);
    }

    public function show($id)
    {
        return response()->json(Maquina::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $maquina = Maquina::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('maquina_imagen')) {
            $file = $request->file('maquina_imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $data['maquina_imagen'] = $filename;
        }

        if ($request->hasFile('factura_compra')) {
            $file = $request->file('factura_compra');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $data['factura_compra'] = $filename;
        }

        $maquina->update($data);
        return response()->json($maquina);
    }

    public function destroy($id)
    {
        $maquina = Maquina::findOrFail($id);
        $maquina->maquina_estado = '0';
        $maquina->save();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $maquina = Maquina::findOrFail($id);
        $maquina->maquina_estado = '1';
        $maquina->save();
        return response()->json($maquina);
    }
}
