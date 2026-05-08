<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        return response()->json(Person::providers()->orderBy('name')->orderBy('lastname')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'email1' => 'nullable|email|max:255',
            'phone1' => 'nullable|string|max:255',
            'no' => 'nullable|string|max:255',
            'banco' => 'nullable|string|max:255',
            'nro_cuenta' => 'nullable|string|max:255'
        ]);

        $validated['kind'] = 2; // 2 = Provider
        $validated['created_at'] = now();

        $provider = Person::create($validated);
        return response()->json($provider, 201);
    }

    public function show($id)
    {
        return response()->json(Person::providers()->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $provider = Person::providers()->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'lastname' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'email1' => 'nullable|email|max:255',
            'phone1' => 'nullable|string|max:255',
            'no' => 'nullable|string|max:255',
            'banco' => 'nullable|string|max:255',
            'nro_cuenta' => 'nullable|string|max:255'
        ]);

        $provider->update($validated);
        return response()->json($provider);
    }

    public function destroy($id)
    {
        Person::providers()->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
