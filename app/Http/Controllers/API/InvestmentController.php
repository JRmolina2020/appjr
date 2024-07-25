<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvestmentController extends Controller
{
    public function index($date, $datetwo)
    {
        $user_id = Auth::id();
        $investment = DB::table('investments as i')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->select(
                'i.id',
                'p.name as product',
                'p.cost',
                'p.stock',
                'i.quantity',
                'i.date_investment'
            )
            ->whereBetween('i.date_investment', [$date, $datetwo])
            ->where('i.user_id', $user_id)
            ->orderBy('i.id', 'desc')->get();
        return $investment;
    }
    public function store(Request $request)
    {
        $mytime = Carbon::now('America/Bogota');
        $income = new Investment();
        $income->user_id = auth()->id();
        $income->product_id = $request['product_id'];
        $income->quantity = $request['quantity'];
        $income->date_investment =  $mytime->toDateString();
        $income->save();
        $id = $income->id;
        $this->update_stock($id, 'add');
        return response()->json(['message' => 'Inversióm registrada'], 200);
    }
    public function update_stock($id, $type)
    {
        $income = Investment::find($id);
        $product = Product::find($income->product_id);
        if ($type == 'add') {
            $stock = DB::raw("stock + $income->quantity");
            $product->stock = $stock;
        } else {
            $stock = DB::raw("stock - $income->quantity");
            $product->stock = $stock;
        }

        $product->save();
    }


    public function update($id)
    {
        $investment = Investment::find($id, ['id']);
        $investment->fill([
            'product_id' => request('product_id'),
            'quantity' => request('quantity'),

        ])->save();
        return response()->json(['message' => 'La inversión ha sido modificada'], 201);
    }
    public function destroy($id)
    {

        $investment = Investment::find($id);
        if (!$investment) {
            return response()->json(["message" => "Dato no encontrado"], 404);
        }
        $this->update_stock($id, 'delete');
        $investment->delete();
        return response()->json(["message" => "Inversión eliminada"]);
    }
    public function sumTot($date, $datetwo)
    {
        $user_id = Auth::id();
        $investment_tot = DB::table('investments as i')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->select(
                DB::raw('SUM((p.cost)*(i.quantity)) as tot'),
            )
            ->whereBetween('i.date_investment', [$date, $datetwo])
            ->where('i.user_id', $user_id)
            ->get();
        return $investment_tot;
    }
}
