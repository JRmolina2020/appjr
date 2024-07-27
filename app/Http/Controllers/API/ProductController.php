<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $product = DB::table('products as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->select(
                'p.id',
                'p.cost',
                'p.name',
                'p.stock',
                'p.price',
                'p.price_two',
                'p.status',

            )
            ->where('p.user_id', $user_id)
            ->where('p.status', 1)
            ->orderBy('p.id', 'desc')
            ->get();
        return $product;
    }
    public function index2()
    {
        $user_id = Auth::id();
        $product = DB::table('products as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->select(
                'p.id',
                'p.cost',
                'p.name',
                'p.stock',
                'p.price',
                'p.price_two',
                'p.status',

            )
            ->where('p.user_id', $user_id)
            ->where('p.status', 0)
            ->orderBy('p.id', 'desc')
            ->get();
        return $product;
    }
    public function store(Request $request)
    {

        Product::create([
            'user_id' => auth()->id(),
            'name' => $request['name'],
            'cost' => $request['cost'],
            'price' => $request['price'],
            'price_two' => $request['price_two'],
            'status' => 1,

        ]);
        return response()->json(['message' => 'Producto registrado'], 200);
    }
    public function update($id)
    {
        $product = Product::find($id, ['id']);
        $product->fill([
            'name' => request('name'),
            'cost' => request('cost'),
            'price' => request('price'),
            'price_two' => request('price_two'),

        ])->save();
        return response()->json(['message' => 'El producto ha sido modificado'], 201);
    }
    public function available($id)
    {
        $products = Product::findOrFail($id, ['id']);
        $products->status = '1';
        $products->save();
        return response()->json(["message" => "Ha sido activado"]);
    }
    public function locked($id)
    {
        $products = Product::findOrFail($id, ['id']);
        $products->status = '0';
        $products->save();
        return response()->json(["message" => "Ha sido Bloqueado"]);
    }
}
