<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $client = DB::table('clients as c')
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->select(
                'c.id',
                'c.nit',
                'c.name',
                'c.tel',
                'c.status',


            )
            ->where('c.user_id', $user_id)
            ->orderBy('c.id', 'desc')->get();
        return $client;
    }
    public function store(Request $request)
    {

        Client::create([
            'user_id' => auth()->id(),
            'nit' => $request['nit'],
            'name' => $request['name'],
            'tel' => $request['tel'],
            'status' => 1,

        ]);
        return response()->json(['message' => 'Cliente registrado'], 200);
    }
    public function update($id)
    {
        $client = Client::find($id, ['id']);
        $client->fill([
            'nit' => request('nit'),
            'name' => request('name'),
            'tel' => request('tel'),

        ])->save();
        return response()->json(['message' => 'El cliente ha sido modificado'], 201);
    }
    public function available($id)
    {
        $products = Client::findOrFail($id, ['id']);
        $products->status = '1';
        $products->save();
        return response()->json(["message" => "Ha sido activado"]);
    }
    public function locked($id)
    {
        $products = Client::findOrFail($id, ['id']);
        $products->status = '0';
        $products->save();
        return response()->json(["message" => "Ha sido Bloqueado"]);
    }
}
