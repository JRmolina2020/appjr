<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillController extends Controller
{
    public function index($date, $datetwo)
    {
        $user_id = Auth::id();
        $bills = DB::table('bills as b')
            ->join('users as u', 'u.id', '=', 'b.user_id')
            ->select(
                'b.id',
                'b.name',
                'b.price',
                'u.name as users',
            )
            ->whereBetween('b.date_bill', [$date, $datetwo])
            ->where('b.user_id', $user_id)
            ->orderBy('b.id', 'desc')->get();
        return $bills;
    }

    public function store(Request $request)
    {
        $mytime = Carbon::now('America/Bogota');
        Bill::create([
            'user_id' => auth()->id(),
            'name' => $request['name'],
            'price' => $request['price'],
            'date_bill' => $mytime->toDateString(),


        ]);
        return response()->json(['message' => 'Gasto registrado'], 200);
    }
    public function update($id)
    {
        $bill = Bill::find($id, ['id']);
        $bill->fill([
            'name' => request('name'),
            'price' => request('price'),

        ])->save();
        return response()->json(['message' => 'El gasto ha sido modificado'], 201);
    }
    public function destroy($id)
    {

        $bill = Bill::find($id);
        if (!$bill) {
            return response()->json(["message" => "Gasto no encontrado"], 404);
        }
        $bill->delete();
        return response()->json(["message" => "Gasto eliminado"]);
    }
    public function sumTot($date, $datetwo)
    {
        $user_id = Auth::id();
        $bill_tot = DB::table('bills')
            ->select(
                DB::raw('SUM(price) as price'),
            )
            ->whereBetween('date_bill', [$date, $datetwo])
            ->where('user_id', $user_id)
            ->get();
        return $bill_tot;
    }
}
