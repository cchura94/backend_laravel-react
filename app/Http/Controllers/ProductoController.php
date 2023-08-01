<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // /api/producto?page=2&limit=5&q=tec
        $limite = isset($request->limit)?$request->limit:10;
        $buscar = isset($request->q)?$request->q:null;
        if($buscar){
            $productos = Producto::orderBy('id', 'desc')
                                    ->where('nombre', 'like', "%$buscar%")
                                    ->with("categoria")
                                    ->paginate($limite);    
        }else{
            $productos = Producto::orderBy('id', 'desc')->with("categoria")->paginate($limite);
        }

        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nombre"=>"required",
            "categoria_id" => "required"
        ]);

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->categoria_id = $request->categoria_id;
        $producto->save();

        return response()->json(["message" => "Producto Registrado"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::findOrFail($id);

        return response()->json($producto, 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "nombre"=>"required",
            "categoria_id" => "required"
        ]);

        $producto = Producto::findOrFail($id);
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->update();

        return response()->json(["message" => "Producto Actualizado"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(["message" => "Producto Eliminado"], 200);
    }

    public function actualizarImagen(Request $request, $id){
        if($file = $request->file("imagen")){
            $direccion_imagen = time() . "-" . $file->getClientOriginalName();
            $file->move("imagen/", $direccion_imagen);

            $direccion_imagen = "imagen/". $direccion_imagen;

            $producto = Producto::find($id);
            $producto->imagen = $direccion_imagen;
            $producto->update();

            return response()->json(["message" => "Imagen Actualizada"], 201);
        }
        return response()->json(["message" => "Se require Imagen"], 422);
    }
}
