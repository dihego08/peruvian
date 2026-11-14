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

        $user = User::where(function($query) use ($username) {
            $query->where('email', $username)
                  ->orWhere('username', $username);
        })
        ->where('status', 1)
        ->first();

        if ($user && $this->passwordMatches($request->password, $user->password)) {
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

    /**
     * Soporta cuentas creadas con bcrypt (Hash::make, desde el panel de usuarios)
     * y cuentas antiguas creadas con el esquema legacy sha1(md5($pass)).
     */
    private function passwordMatches(string $plain, string $hash): bool
    {
        if (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2a$') || str_starts_with($hash, '$2b$')) {
            return Hash::check($plain, $hash);
        }

        return hash_equals($hash, sha1(md5($plain)));
    }

    public function logout()
    {
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
