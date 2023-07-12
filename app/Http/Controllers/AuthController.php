<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function funIngresar(Request $request){
        // validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if (!Auth::attempt($credenciales)) {
            return response()->json(["message" => "No Autenticado"], 401);
        }
        // generamos token
        $usuario = Auth::user();
        $token = $usuario->createToken("token personal")->plainTextToken;

        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "usuario" => $usuario
        ]);

    }

    public function funRegistrar(Request $request){
        // validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "c_password" => "some:password"
        ]);
        // registrar
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();
        // responder
        return response()->json(["message" => "Usuario Registrado"], 201);
    }

    public function funPerfil(){
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function funSalir(){
        Auth::user()->tokens()->delete();

        return response()->json(["message" => "Salio"], 200);
    }
}
