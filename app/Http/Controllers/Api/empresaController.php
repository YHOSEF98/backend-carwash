<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class empresaController extends Controller
{
    public function index(){
        $empresas = Empresa::all();

        $data = [
            'empresas'=> $empresas,
            'status'=> 200
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nombre'=>'required',
            'email'=>'required|email'
        ]);

        if($validator->fails()){
            $data = [
                'message'=>'Error en la validacion',
                'errors'=>$validator->errors(),
                'status'=>400
            ];
            return response()->json($data,400);
        }
        $empresa = Empresa::create([
            'nombre'=>$request->nombre,
            'email'=>$request->email
        ]);

        if(!$empresa){
            $data = [
                'message'=>'error al crear la empresa',
                'status'=>500
            ];
            return response()->json($data,500);
        }

        $data = [
            'empresa'=>$empresa,
            'status'=>201
        ];
        return response()->json($data,201);
    }

    public function show($id){
        $empresa = Empresa::find($id);

        if(!$empresa){
            $data = [
                'message'=>'Empresa no encontrada',
                'status'=>404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'empresa'=>$empresa,
            'status'=>200
        ];
        return response()->json($data,200);
    }

    public function update(Request $request, $id){
        $empresa = Empresa::find($id);

        if(!$empresa){
            $data = [
                'message'=>'Empresa no encontrada',
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'nombre'=> 'required|string|max:255',
            'email'=> 'required|email|max:255'
        ]);

        if($validator->fails()){
            $data = [
                'message'=>'Eror en la validacion',
                'errors'=>$validator->errors(),
                'status'=>400
            ];
            return response()->json($data,400);
        }

        $empresa->update([
            'nombre'=>$request->nombre,
            'email'=>$request->email,
        ]);

        $data = [
            'message'=> 'Empresa actualizada con exito',
            'empresa'=>$empresa,
            'status' =>200
        ];

        return response()->json($data,200);
    }

    public function destroy(Request $request, $id){

        $empresa = Empresa::find($id);

        if(!$empresa){
            $data = [
                'message'=>'Empresa no encontrada',
                'status'=>404
            ];
            return response()->json($data,404);
        }

        $empresa->delete();
        $data = [
            'message'=>'Empresa eliminada con exito',
            'empresa'=> $empresa,
            'status'=>200
        ];
        return response()->json($data,200);
    }
}
