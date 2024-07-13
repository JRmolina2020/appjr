<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investment;
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
            ->select(
                'i.id',
                'i.name',
                'i.price',
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
        Investment::create([
            'user_id' => auth()->id(),
            'name' => $request['name'],
            'price' => $request['price'],
            'quantity' => $request['quantity'],
            'date_investment' => $mytime->toDateString(),


        ]);
        return response()->json(['message' => 'Inversióm registrada'], 200);
    }
    public function update($id)
    {
        $investment = Investment::find($id, ['id']);
        $investment->fill([
            'name' => request('name'),
            'price' => request('price'),
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
        $investment->delete();
        return response()->json(["message" => "Inversión eliminada"]);
    }
    public function sumTot($date, $datetwo)
    {
        $user_id = Auth::id();
        $investment_tot = DB::table('investments as i')
            ->select(
                DB::raw('SUM((i.price)*(i.quantity)) as tot'),
            )
            ->whereBetween('i.date_investment', [$date, $datetwo])
            ->where('i.user_id', $user_id)
            ->get();
        return $investment_tot;
    }
}
