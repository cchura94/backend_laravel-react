<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limite = isset($request->limit) ? $request->limit : 10;
        $buscar = isset($request->q) ? $request->q : null;
        if ($buscar) {
            $pedidos = Pedido::orderBy('id', 'desc')
                ->where('fecha', 'like', "%$buscar%")
                ->with(["cliente", "user", "productos"])
                ->paginate($limite);
        } else {
            $pedidos = Pedido::orderBy('id', 'desc')
                ->with(["cliente", "user", "productos"])
                ->paginate($limite);
        }

        return response()->json($pedidos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "cliente_id" => "required"
        ]);
        /*
        {
            cliente_id: 4,
            productos: [
                {producto_id: 6, cantidad: 2},
                {producto_id: 2, cantidad: 3},
                {producto_id: 11, cantidad: 5},
            ]
        }
        */

        DB::beginTransaction();

        try {
            $pedido = new Pedido();
            $pedido->fecha = date("Y-m-d H:i:s");
            $pedido->estado = 1;
            $pedido->cliente_id = $request->cliente_id;
            $pedido->user_id = Auth::user()->id;
            $pedido->save();
            // en un pedido puede haber muchos productos
            // un producto puede estar en muchos pedidos
            foreach ($request->productos as $prod) {
                $pedido->productos()->attach($prod["producto_id"], ["cantidad" => $prod["cantidad"]]);
            }
            $pedido->estado = 2;
            $pedido->update();

            DB::commit();

            return response()->json(["message" => "Pedido registrado"], 200);
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(["message" => "Ocurrio un error al registrar el pedido", "error" => $e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
