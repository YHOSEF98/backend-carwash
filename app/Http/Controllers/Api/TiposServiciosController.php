<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\TiposServicio;

class TiposServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $usuario = Auth::user();
        $servicios = TiposServicio::where('empresa_id',$usuario->empresa_id)->get();

        if($servicios->isEmpty()){
            $data=[
                'message'=>'No hay servicios relacionados',
                'status'=>404
            ];
            return response()->json($data,404);
        }
        $data=[
            'Servicios'=> $servicios,
            'status'=>200
        ];
        return response()->json($data,200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nombre'=>'required|string|max:255',
            'descripcion'=>'nullable|string|max:255'
        ]);
        if($validator->fails()){
            $data = [
                'message'=>'error en la validacion de datos',
                'errors'=>$validator->errors(),
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $usuario = Auth::user();

        if (!$usuario->empresa_id) {
            abort(404, "No es posible asignar una empresa a este servicio.");
        }

        try {
            $servicio = TiposServicio::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'empresa_id' => $usuario->empresa_id
            ]);

            return response()->json([
                'message' => 'Servicio creado con éxito',
                'servicio' => $servicio,
                'status' => 201
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el servicio',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Auth::user();
        $servicio = TiposServicio::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();

        if(!$servicio){
            abort(404, "No se ha encontrado el servicio seleccionado o no pertenece a tu empresa");
        }
        return response()->json([
                'message'=>'Servicio encontrado con exito',
                'servicio'=>$servicio,
                'status'=>200
            ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'nombre'=>'required|string|max:255',
            'descripcion'=>'nullable|string|max:255'
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Error al validar los datos',
                'status'=>404
            ],404);
        }

        $usuario = Auth::user();
        $servicio = TiposServicio::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();
        if(!$servicio){
            abort(404, 'No se encontro el servicio a actualizar');
        }
        $servicio->update([
                'nombre'=>$request->nombre,
                'descripcion'=>$request->descripcion,
            ]);

        return response()->json([
                'message'=>'Servicio actualizado con exito',
                'servicio'=>$servicio,
                'status'=>200
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $servicio = TiposServicio::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();
        if(!$servicio){
            abort(404, 'No se encontro el servicio a eliminar');
        }

        $servicio->delete();

        return response()->json([
            'message'=>'Servicio eliminado con exito',
            'servicio'=>$servicio
        ],200);

    }
}
