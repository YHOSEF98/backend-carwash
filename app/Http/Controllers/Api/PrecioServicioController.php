<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PrecioServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PrecioServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();
        $preciosServicios = PrecioServicio::where('empresa_id',$usuario->empresa_id)->get();

        if($preciosServicios->isEmpty()){
            return response()->json([
                'message'=>'No se encontraron servicios relacionados a la empresa',
                'status'=>404
            ],404);
        }
        return response()->json([
            'precios_servicios'=>$preciosServicios,
            'status'=>200
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'tipos_servicios_id'=> 'required|integer|exists:tipos_servicios,id',
            'tipos_vehiculos_id'=> 'required|integer|exists:tipos_vehiculos,id',
            'precio'=> 'required|numeric',
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Error de validacion',
                'errors'=>$validator->errors(),
                'status'=>422
            ]);
        }
        $usuario = Auth::user();
        if (!$usuario->empresa_id) {
            abort(422, 'El usuario no tiene una empresa asignada');
        }

        // Crear el registro en la base de datos
        $precio = PrecioServicio::create([
            'tipos_servicios_id' => $request->tipos_servicios_id,
            'tipos_vehiculos_id' => $request->tipos_vehiculos_id,
            'precio' => $request->precio,
            'empresa_id' => $usuario->empresa_id
        ]);

        return response()->json([
            'message' => 'Precio de servicio registrado con éxito',
            'precio_servicio' => $precio,
            'status' => 201 // Código correcto para creación exitosa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Auth::user();
        $precio = PrecioServicio::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();

        if(!$precio){
            return response()->json([
                'message'=>'No se encontro ese precio para ese servicio',
                'status'=>404
            ],404);
        }
        return response()->json([
            'message'=>'Encontrado con exito',
            'precio_vehiculo'=>$precio,
            'status'=>200
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $precio = PrecioServicio::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();

        if(!$precio){
            return response()->json([
                'message'=>'No se encontro el item a actualizar',
                'status'=>404
            ],404);
        }
        $validator = Validator::make($request->all(),[
            'tipos_servicios_id'=> 'required|integer|exists:tipos_servicios,id',
            'tipos_vehiculos_id'=> 'required|integer|exists:tipos_vehiculos,id',
            'precio'=> 'required|numeric',
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Error de validacion',
                'errors'=>$validator->errors(),
                'status'=>422
            ]);
        }
        $precio->update([
            'tipos_servicios_id'=>$request->tipos_servicios_id,
            'tipos_vehiculos_id'=>$request->tipos_vehiculos_id,
            'precio'=>$request->precio
        ]);
        return response()->json([
            'message'=>'Actualizado con exito',
            'precio_servicio'=>$precio,
            'status'=>200
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $precio = PrecioServicio::where('empresa_id',$usuario->empresa_id)
                                ->where('id',$id)->first();

        if(!$precio){
            return response()->json([
                'message'=>'No se encontro el item a eliminar',
                'status'=>404
            ],404);
        }
        $precio->delete();
        return response()->json([
            'message'=>'item eliminado',
            'precio_vehiculo'=>$precio,
            'status'=>200
        ],200);
    }
}
