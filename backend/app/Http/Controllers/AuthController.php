<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->username;
        // Hashing legacy: sha1(md5($pass))
        $password = sha1(md5($request->password));

        $user = User::where(function($query) use ($username) {
            $query->where('email', $username)
                  ->orWhere('username', $username);
        })
        ->where('password', $password)
        ->where('status', 1)
        ->first();

        if ($user) {
            // Generar un token simple (en producción usar Sanctum o JWT)
            $token = base64_encode($user->id . '|' . time());
            
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'username' => $user->username,
                    'kind' => $user->kind,
                ]
            ]);
        }

        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    public function logout()
    {
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
