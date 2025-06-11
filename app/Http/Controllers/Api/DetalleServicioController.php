<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DetallesServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DetalleServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();
        $ventas = DetallesServicio::where('empresa_id',$usuario->empresa_id)->get();
        if($ventas->isEmpty()){
            abort(404, 'No hay detalles de servicios relacionados');
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
        $validator = Validator::make($request->all(),[
            'empleado_id' => 'required|integer|exists:empleado,id',
            'tipos_servicio_id' => 'required|integer|exists:tipos_servicio,id',
            'tipos_vehiculo_id' => 'required|integer|exists:tipos_vehiculo,id',
            'precio'=> 'required|numeric',
            'observaciones' => 'nullable|string|max:255',
            'venta_id' => 'required|integer|exists:venta,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors()
            ],422);
        }
        $validateData = $validator->validated();
        $validateData['empresa_id']=$usuario->empresa_id;
        $detServicio = DetallesServicio::create($validateData);
        return response()->json([
            'message'=>'Servicio Creado con exito',
            'detalle_Servicio'=>$detServicio
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Auth::user();
        $detServicio = DetallesServicio::where('empresa_id',$usuario->empresa_id)->find($id);
        if(!$detServicio){
            abort(404, 'Servicio no encontrado');
        }

        return response()->json([
            'servicio'=>$detServicio
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();

        $detServicio = DetallesServicio::where('empresa_id',$usuario->empresa_id)->find($id);

        if(!$detServicio){
            abort(404, 'Servicio no encontrado');
        }

        $validator = Validator::make($request->all(),[
            'empleado_id' => 'required|integer|exists:empleado,id',
            'tipos_servicio_id' => 'required|integer|exists:tipos_servicio,id',
            'tipos_vehiculo_id' => 'required|integer|exists:tipos_vehiculo,id',
            'precio'=> 'required|numeric',
            'observaciones' => 'nullable|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors()
            ],422);
        }
        $validateData = $validator->validated();
        $detServicio->update($validateData);
        return response()->json([
            'message'=>'Servicio actualizado con éxito',
            'detalle_Servicio'=>$detServicio
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Auth::user();

        $detServicio = DetallesServicio::where('empresa_id',$usuario->empresa_id)->find($id);

        if(!$detServicio){
            abort(404, 'Servicio no encontrado');
        }

        $detServicio->delete();

        return response()->json([
            'message'=>'Servicio eliminado con exito',
            'servicio'=>$detServicio
        ],200);
    }
}
