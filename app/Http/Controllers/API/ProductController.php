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
        $investment = DB::table('products as p')
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
            ->orderBy('p.id', 'desc')->get();
        return $investment;
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
        $bill = Product::find($id, ['id']);
        $bill->fill([
            'name' => request('name'),
            'cost' => request('cost'),
            'price' => request('price'),
            'price_two' => request('price_two'),

        ])->save();
        return response()->json(['message' => 'El producto ha sido modificado'], 201);
    }
}
