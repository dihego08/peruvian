<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Person::clients()->orderBy('name')->orderBy('lastname')->get());
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
            'credit_limit' => 'nullable|numeric'
        ]);

        $validated['kind'] = 1; // 1 = Client
        $validated['created_at'] = now();
        $validated['has_credit'] = $request->input('has_credit', 0);

        $client = Person::create($validated);
        return response()->json($client, 201);
    }

    public function show($id)
    {
        return response()->json(Person::clients()->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $client = Person::clients()->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'lastname' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'email1' => 'nullable|email|max:255',
            'phone1' => 'nullable|string|max:255',
            'no' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric'
        ]);

        if ($request->has('has_credit')) {
            $validated['has_credit'] = $request->has_credit;
        }

        $client->update($validated);
        return response()->json($client);
    }

    public function destroy($id)
    {
        Person::clients()->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
