<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Registra un nuevo usuario
     * POST /api/register
     */
    public function register(RegisterRequest $request){
        // Datos validados
        $validatedData = $request->validated();

        $user = User::create(
            [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            [
            'user' => $user,
            'token' => $token,
            ],'Usuario registrado exitosamente', 201
        );
    }

    /**
     * Inicia sesion para un usuario existente
     * POST /api/login
     */
    public function login(LoginRequest $request){
        $credentials = $request->validated();

        if(!Auth::attempt($credentials)) {
            return $this->errorResponse('Credenciales incorectas', 401);
        }

        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            [
            'user' => $user,
            'token' => $token,
            ],'Inicio de sesion exitoso'
        );

    }

    /**
     * Cierra la sesion del usuario
     * POST /api/logout
     */
    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return $this->successResponse(null, 'Cierre de sesion exitoso');
    }
}
