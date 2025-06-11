<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TiposVehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TiposVehiculosController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $vehiculos = TiposVehiculo::where('empresa_id',$usuario->empresa_id)->get();

        if($vehiculos->isEmpty()){
            $data = [
                'message'=>'No hay vehiculos',
                'status'=>404
            ];
            return response()->json($data,404);
        }
        $data=[
            'vehiculos'=>$vehiculos,
            'status'=>200
        ];
        return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'nombre'=> 'required|string',
        ]);

        if($validator->fails()){
            $data =[
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors(),
                'status'=>404
            ];
            return response()->json($data,404);
        }
        $usuario = Auth::user();
        if(!$usuario->empresa_id){
            abort(404, "No es posible asignar una empresa a este vehículo");
        }

        $vehiculo = TiposVehiculo::create([
            'nombre'=> $request->nombre,
            'empresa_id'=>$usuario->empresa_id
        ]);

        if(!$vehiculo){
            $data = [
                'message'=>'Error al crear el vehiculo',
                'status'=>500
            ];
            return response()->json($data,500);
        }

        $data=[
            'message'=>'Tipo de vehiculo creado con exito',
            'vehiculo'=>$vehiculo,
            'status'=>201
        ];
        return response()->json($data,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Auth::user();
        $vehiculo = TiposVehiculo::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();

        if(!$vehiculo){
            $data=[
                'message'=>'Tipo de vehiculo no encontrado',
                'status'=>404
            ];
            return response()->json($data,404);
        }
        $data=[
            'vehiculo'=>$vehiculo,
            'status'=>200
        ];
        return response()->json($data,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $vehiculo = TiposVehiculo::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();

        if(!$vehiculo){
            $data = [
                'message'=>'No se encontro el vehiculo',
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'nombre'=>'required|string|max:255',
        ]);

        if($validator->fails()){
            $data=[
                'message'=>'Error al validar la informacion',
                'errors'=>$validator->errors()
            ];
            return response()->json($data,422);
        }

        $vehiculo->update([
            'nombre'=>$request->nombre,
        ]);

        $data = [
            'message'=>'Vehiculo actualizado con exito',
            'vehiculo'=>$vehiculo,
            'status'=>200
        ];
        return response()->json($data,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $vehiculo = TiposVehiculo::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();

        if(!$vehiculo){
            $data= [
                'message'=>'No se encontro el tipo de vehiculo a eliminar',
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $vehiculo->delete();
        $data=[
            'message'=>'Tipo de Vehiculo eliminado',
            'vehiculo'=>$vehiculo,
            'status'=>200
        ];
        return response()->json($data, 200);
    }
}
