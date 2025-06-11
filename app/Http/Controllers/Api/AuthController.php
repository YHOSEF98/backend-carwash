<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(){
        $usuarios = User::all();
        $data = [
            'usuarios'=>$usuarios,
            'status'=>200
        ];
        return response()->json($data,200);
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required|string|min:6',
        ]);
        if($validator->fails()){
            $data= [
                'message'=>'error de validacion',
                'errors'=>$validator->errors(),
                'status'=>422
            ];
            return response()->json($data,422);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password)
        ]);
        if(!$user){
            $data= [
                'message'=>'Error al crear el usuario',
                'status'=>500
            ];
            return response()->json($data,500);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $data=[
            'message'=>'usuario creado exitosamente',
            'usuario'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'status'=>201
        ];
        return response()->json($data,201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            $data = [
                'message'=>'Error de validacion',
                'errors'=>$validator->errors(),
                'status'=>422
            ];
            return response()->json($data,422);
        }
        if(!Auth::attempt($request->only('email','password'))){
            $data=[
                'message'=>'Credenciales invalidas',
                'status'=>401
            ];
            return response()->json($data,401);
        }
        $user= Auth::user();
        $token= $user->createToken('auth_token')->plainTextToken;

        $data = [
            'message'=>'Login exitoso',
            'user'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'status'=>200
        ];
        return response()->json($data,200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        $data = [
            'message'=>'Cerraste sesión exitosamente',
            'status'=>200
        ];
        return response()->json($data,200);
    }

    public function me(Request $request){
        return response()->json([
            'usuario'=>$request->user(),
            'status'=>200
        ],200);
    }
}
