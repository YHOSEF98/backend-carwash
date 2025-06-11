<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use App\Enums\EstadoVentaEnum;
use App\Enums\TipoVentaEnum;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();
        $ventas = Venta::where('empresa_id',$usuario->empresa_id)->get();
        if($ventas->isEmpty()){
            return response()->json([
                'message'=>'No hay ventas existentes actualmente',
                'status'=>404
            ],404);
        }
        return response()->json([
            'ventas'=>$ventas,
            'status'=>200
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();
        $validator = Validator::make($request->all(), [
            'placa' => 'required|string|max:6',
            'cliente_id' => 'nullable|exists:clientes,id',
            'fecha' => 'required|date',
            'num_factura' => 'nullable|integer',
            'metodo_pago_id' => 'nullable|exists:metodos_pagos,id',
            'tipo_venta' => ['nullable', new Enum(TipoVentaEnum::class)],
            'estado_venta' => ['nullable', new Enum(EstadoVentaEnum::class)],
            'subtotal' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'iva' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'total' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/']
        ]);

        // Retorna error si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message'=>'Error en la validacion',
                'errors' => $validator->errors()
            ], 422);
        }
        $validateData = $validator->validated();
        $validateData['empresa_id']=$usuario->empresa_id;
        $venta = Venta::create($validateData);

        return response()->json([
            'message'=>'Venta creada con exito',
            'venta'=>$venta,
            'status'=>201
        ],201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Auth::user();
        $venta = Venta::where('empresa_id', $usuario->empresa_id)->find($id);

        if(!$venta){
            return response()->json([
                'message'=>'No se ha encontrado la venta',
                'status'=>404
            ],404);
        }
        return response()->json([
            'venta'=>$venta,
            'status'=>200
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $venta = Venta::where('empresa_id',$usuario->empresa_id)->find($id);

        if (!$venta) {
            return response()->json([
                'message' => 'No se ha encontrado la venta',
                'status' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'placa' => 'required|string|max:6',
            'cliente_id' => 'nullable|exists:clientes,id',
            'fecha' => 'required|date',
            'num_factura' => 'nullable|integer',
            'metodo_pago_id' => 'nullable|exists:metodos_pagos,id',
            'tipo_venta' => ['nullable', new Enum(TipoVentaEnum::class)],
            'estado_venta' => ['nullable', new Enum(EstadoVentaEnum::class)],
            'subtotal' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'iva' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'total' => ['nullable', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/']
        ]);

        // Retorna error si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message'=>'Error en la validacion',
                'errors' => $validator->errors()
            ], 422);
        }
        $validateData = $validator->validated();
        $venta->update($validateData);

        return response()->json([
            'message'=>'Venta actualizada con exito',
            'venta'=>$venta,
            'status'=>200
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $venta = Venta::where('empresa_id',$usuario->empresa_id)->find($id);

        if (!$venta) {
            abort(404, 'No se ha encontrado la venta a eliminar');
        }

        $venta->delete();

        return response()->json([
            'message'=>'Venta eliminada con exito',
            'venta'=>$venta
        ],200);
    }
}
