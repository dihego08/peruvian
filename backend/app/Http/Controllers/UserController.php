<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:user',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'kind' => 'required|integer'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['created_at'] = now();

        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'lastname' => 'nullable|string|max:255',
            'username' => 'string|max:255|unique:user,username,'.$id,
            'email' => 'email|max:255',
            'password' => 'nullable|string|min:6',
            'kind' => 'integer'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
