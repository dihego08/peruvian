<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Person;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index()
    {
        return response()->json(Cargo::with('client')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cargo' => 'required|string|max:255',
            'id_referencia' => 'nullable|integer'
        ]);

        $cargo = Cargo::create($validated);
        return response()->json($cargo, 201);
    }

    public function show($id)
    {
        return response()->json(Cargo::with('client')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $cargo = Cargo::findOrFail($id);
        $validated = $request->validate([
            'cargo' => 'string|max:255',
            'id_referencia' => 'nullable|integer'
        ]);

        $cargo->update($validated);
        return response()->json($cargo);
    }

    public function destroy($id)
    {
        $cargo = Cargo::findOrFail($id);
        $cargo->delete();
        return response()->json(null, 204);
    }

    public function getClients()
    {
        return response()->json(Person::where('kind', 1)->get(['id', 'name']));
    }
}
