<?php

namespace App\Http\Controllers\api;

use App\Enums\Enums\EstadoProductoVentaEnum;
use App\Http\Controllers\Controller;
use App\Models\DetallesProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\DB;


class DetalleProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $ventaId = $request->query('venta_id');
        $productos = DetallesProducto::where('empresa_id',$usuario->empresa_id)
                                    ->where('venta_id',$ventaId)->get();

        if($ventaId->isEmpty()){
            abort(404, 'No hay una venta relacionada');
        }

        if($productos->isEmpty()){
            abort(404, 'No hay detalles de productos relacionados');
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
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer',
            'observaciones' => 'nullable|string|max:255',
            'estado_venta_producto' => ['required', new Enum(EstadoProductoVentaEnum::class)],
            'venta_id' => 'required|integer|exists:ventas,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors()
            ],422);
        }
        $validateData = $validator->validated();
        $validateData['empresa_id']=$usuario->empresa_id;
        
        $productoInv = Producto::where('empresa_id',$usuario->empresa_id)
                        ->where('id',$validateData['producto_id'])->firstOrFail();
                        
        if($productoInv->stock < $validateData['cantidad']){
            abort(400, 'No hay estock disponible del producto');
        }

        // Usamos una transacción para asegurar consistencia
        DB::transaction(function () use ($validateData, $productoInv) {
            // Primero actualizamos el stock
            $productoInv->update([
                'stock' => $productoInv->stock - $validateData['cantidad']
            ]);

            // Luego registramos la venta del producto
            DetallesProducto::create($validateData);
        });
        
        $data = [
            'message'=>'Venta de producto añadida con exito',
            'message_inv'=>'Stock actualizado de ' . $productoInv->nombre,
            'detalle_producto' => $validateData
        ];
        
        return response()->json($data,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Auth::user();
        $detProducto = DetallesProducto::where('empresa_id',$usuario->empresa_id)->find($id);
        if(!$detProducto){
            abort(404, 'Producto no encontrado');
        }

        return response()->json([
            'servicio'=>$detProducto
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();

        $detProducto = DetallesProducto::where('empresa_id',$usuario->empresa_id)
                                    ->where('id', $id)->firstOrFail();
                                    
        $productoInv = Producto::where('empresa_id', $usuario->empresa_id)
                            ->where('id', $detProducto->producto_id)
                            ->firstOrFail();

        $validator = Validator::make($request->all(),[
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer',
            'subtotal'=> 'required|numeric',
            'observaciones' => 'nullable|string|max:255',
            'estado_venta_producto' => ['required', new Enum(EstadoProductoVentaEnum::class)],
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors()
            ],422);
        }

        $validateData = $validator->validated();
        $diferencia = $validateData['cantidad'] - $detProducto->cantidad;

        if ($diferencia > 0) {
            if ($productoInv->stock < $diferencia) {
                return response()->json([
                    'message' => 'No hay suficiente stock disponible para aumentar la cantidad vendida.'
                ], 400);
            }
            $productoInv->stock -= $diferencia;

        } elseif ($diferencia < 0) {
            $productoInv->stock += abs($diferencia); // o simplemente -$diferencia
        }

        $productoInv->save();
        $detProducto->update($validateData);
        

        return response()->json([
            'message'=>'Venta de producto actualizada con exito',
            'venta_producto'=>$detProducto
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Auth::user();

        $detProducto = DetallesProducto::where('empresa_id',$usuario->empresa_id)->find($id);

        if(!$detProducto){
            abort(404, 'Servicio no encontrado');
        }

        $productoInv = Producto::where('empresa_id', $usuario->empresa_id)
                            ->where('id', $detProducto->producto_id)
                            ->firstOrFail();
        
        $productoInv->stock += $detProducto->cantidad;
        $productoInv->save();

        $detProducto->delete();

        return response()->json([
            'message'=>'Producto eliminado con exito',
            'detalle_producto'=>$detProducto
        ],200);
    }
}
