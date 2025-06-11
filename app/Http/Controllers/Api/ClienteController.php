<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Enums\TipoPersonaEnum;
use Illuminate\Validation\Rules\Enum;


class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();
        $clientes = Cliente::where('empresa_id',$usuario->empresa_id)->get();

        if($clientes->isEmpty()){
            return response()->json([
                'message'=>'No se ecnontraron clientes relacionados a esta empresa',
                'status'=>404
            ],404);
        }
        return response()->json([
            'clientes'=>$clientes,
            'status'=>200
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();
        $validator = Validator::make($request->all(),[
            'tipos_documentos_id' => 'required|integer|exists:tipos_documentos,id',
            'numero_documento' => [
            'required',
            'string',
            Rule::unique('clientes')->where(fn($query) => $query->where('empresa_id', $usuario->empresa_id)),
        ],
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'required|string|max:255',
            'codigo_pais' => 'required|string|max:2',
            'departamento' => 'required|string|max:100',
            'ciudad' => 'required|string|max:200',
            'codigo_municipio' => 'required|string|max:10',
            'regimen_fiscal' => 'required|string|max:50',
            'tipo_persona' => ['required', new Enum(TipoPersonaEnum::class)],
            'obligaciones' => 'nullable|string|max:255',
        ]);
        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['empresa_id']=$usuario->empresa_id;

        // Si la validación es exitosa, crear el cliente
        $cliente = Cliente::create($validatedData);

        return response()->json([
            'message' => 'Cliente creado exitosamente',
            'cliente' => $cliente,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Auth::user();
        $cliente = Cliente::where('empresa_id',$usuario->empresa_id)
                        ->where('id',$id)->first();

        if(!$cliente){
            return response()->json([
                'message'=>'Cliente no encontrado',
                'status'=> 404
            ],404);
        }
        return response()->json([
            'message'=>'Cliente encontrado exitosamente',
            'clienite'=>$cliente,
            'status'=>200
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $cliente = Cliente::where('empresa_id',$usuario->empresa_id)
                        ->where('id',$id)->first();

        if(!$cliente){
            return response()->json([
                'message'=>'Cliente no encontrado',
                'status'=> 404
            ],404);
        }

        $validator = Validator::make($request->all(),[
            'tipos_documentos_id' => 'required|integer|exists:tipos_documentos,id',
            'numero_documento' => [
            'required',
            'string',
            Rule::unique('clientes')->where(fn($query) => $query->where('empresa_id', $usuario->empresa_id)),
        ],
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'required|string|max:255',
            'codigo_pais' => 'required|string|max:2',
            'departamento' => 'required|string|max:100',
            'ciudad' => 'required|string|max:200',
            'codigo_municipio' => 'required|string|max:10',
            'regimen_fiscal' => 'required|string|max:50',
            'tipo_persona' => ['required', new Enum(TipoPersonaEnum::class)],
            'obligaciones' => 'nullable|string|max:255',
        ]);
        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors()
            ], 422);
        }

        $cliente->update($validator->validate());
        return response()->json([
            "message"=>'Cliente actualziado con exito',
            'cliente'=>$cliente,
            'status'=>200
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $cliente = Cliente::where('empresa_id',$usuario->empresa_id)
                        ->where('id',$id)->first();

        if(!$cliente){
            return response()->json([
                'message'=>'Cliente no encontrado',
                'status'=> 404
            ],404);
        }

        $cliente->delete();
        return response()->json([
            'message'=>'Cliente eliminado con exito',
            'cliente'=>$cliente,
            'status'=>200
        ],200);
    }
}
