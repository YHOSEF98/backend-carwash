<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();

        $empleados = Empleado::where('empresa_id',$usuario->empresa_id)->get();

        if($empleados->isEmpty()){
            $data=[
                'message'=>'No hay empleados actualmente',
                'status'=>404
            ];
            return response()->json($data,404);
        }
        $data = [
            'empleados'=>$empleados,
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
            'nombre'=> 'required|string',
            'telefono'=> 'required|string',
            'email'=> 'nullable|email',
        ]);

        if($validator->fails()){
            $data = [
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors(),
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $usuario = Auth::user();

        // Verificar que el usuario tenga empresa asignada
        if (!$usuario->empresa_id) {
            return response()->json([
                'message' => 'El usuario no tiene una empresa asignada',
                'status' => 400
            ], 400);
        }

        $empleado = Empleado::create([
            'nombre'=>$request->nombre,
            'telefono'=>$request->telefono,
            'email'=>$request->email,
            'empresa_id'=>$usuario->empresa_id
        ]);

        if(!$empleado){
            $data = [
                'message'=>'Error al crear el empleado',
                'status'=>500
            ];
            return response()->json($data,500);
        }

        $data = [
            'message'=>'Empleado creado con exito',
            'empleado'=>$empleado,
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

        $empleado = Empleado::where('empresa_id',$usuario->empresa_id)
                            ->where('id', $id)->first();

        if(!$empleado){
            $data=[
                'message'=>'Empleado no encontrado o no existe',
                'status'=>404
            ];
            return response()->json($data, 404);
        }
        $data=[
            'message'=>'Empleado encontrado',
            'empleado'=>$empleado,
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
        $empleado = Empleado::where('empresa_id',$usuario->empresa_id)
                            ->where('id',$id)->first();
        if(!$empleado){
            $data =[
                'message'=>'Empleado no encontrado, imposible de actualizar',
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'nombre'=> 'required|string',
            'telefono'=> 'required|string',
            'email'=> 'nullable|email',
            'empresa'=> 'nullable|string'
        ]);

        if($validator->fails()){
            $data = [
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors(),
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $empleado->update([
            'nombre'=>$request->nombre,
            'telefono'=>$request->telefono,
            'email'=>$request->email,
            'empresa'=>$request->empresa
        ]);

        $data= [
            'message'=>'Empleado actualizado correctamente',
            'empleado'=>$empleado,
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
        $empleado = Empleado::where('empresa_id',$usuario->empresa_id)
                            ->where('id',$id)->first();

        if(!$empleado){
            $data= [
                'message'=>'No se encontro el empleado que desea eliminar',
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $empleado->delete();
        $data=[
            'message'=>'Empleado eliminado con exito',
            'empleado'=>$empleado,
            'status'=>200
        ];
        return response()->json($data,200);
    }
}
