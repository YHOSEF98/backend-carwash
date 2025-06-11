<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();
        $productos = Producto::where('empresa_id',$usuario->empresa_id)->get();
        if($productos->isEmpty()){
            return response()->json([
                'message'=>'No hay productos relacionados',
                'status'=>404
            ],404);
        }
        return response()->json([
            'productos'=>$productos,
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
            'nombre'=> 'required|string|max:255',
            'descripcion'=> 'nullable|string|max:255',
            'costo'=> 'required|numeric',
            'precio_venta'=> 'required|numeric',
            'stock'=> 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors()
            ], 422);
        }
        $validatedData = $validator->validated();
        $validatedData['empresa_id']=$usuario->empresa_id;

        $producto = Producto::create($validatedData);

        return response()->json([
            'message'=>'Producto creado exitosamente',
            'producto'=>$producto,
            'status'=>201
        ],201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario =Auth::user();
        $producto = Producto::where('empresa_id', $usuario->empresa_id)
                            ->where('id',$id)->first();
        if(!$producto){
            return response()->json([
                'message'=>'producto no encontrado',
                'status'=>404
            ],404);
        }
        return response()->json([
            'producto'=>$producto,
            'status'=>200
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario =Auth::user();
        $producto = Producto::where('empresa_id', $usuario->empresa_id)
                            ->where('id',$id)->first();
        if(!$producto){
            return response()->json([
                'message'=>'producto no encontrado',
                'status'=>404
            ],404);
        }
        $validator = Validator::make($request->all(),[
            'nombre'=> 'required|string|max:255',
            'descripcion'=> 'nullable|string|max:255',
            'costo'=> 'required|numeric',
            'precio_venta'=> 'required|numeric',
            'stock'=> 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors()
            ], 422);
        }
        $validatedData = $validator->validated();
        $producto->update($validatedData);

        return response()->json([
            'message'=>'Producto actualziado con exito',
            'producto'=>$producto,
            'status'=>200
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario =Auth::user();
        $producto = Producto::where('empresa_id', $usuario->empresa_id)
                            ->where('id',$id)->first();
        if(!$producto){
            return response()->json([
                'message'=>'producto no encontrado',
                'status'=>404
            ],404);
        }
        $producto->delete();
        return response()->json([
            'message'=>'Producto eliminado con exito',
            'producto'=>$producto,
            'status'=>200
        ],200);
    }
}
