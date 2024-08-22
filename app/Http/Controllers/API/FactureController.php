<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\DetailFacture;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    public function store(Request $request)
    {
        try {
            $mytime = Carbon::now('America/Bogota');
            $facture = new Facture();
            $facture->date_facture = $mytime->toDateString();
            $facture->user_id =  auth()->id();
            $facture->client_id = $request->client_id;
            $facture->tot = $request->tot;
            $facture->status = 1;
            $facture->efecty = $request->efecty;
            $facture->other = $request->other;
            $facture->save();
            $details = $request->product;
            foreach ($details as $ep => $det) {
                $details = new DetailFacture();
                $details->facture_id = $facture->id;
                $details->product_id = $det['product_id'];
                $details->cant = $det['cant'];
                $details->sub = $det['sub'];
                $details->discount = $det['discount'];
                $details->save();
                $id = $details->id;
                $this->update_stock($id, 'add');
            }
            DB::commit();
            return response()->json(['message' => 'Venta registrada'], 200);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    public function update_stock($id, $type)
    {
        $detail = DetailFacture::find($id);
        $product = Product::find($detail->product_id);
        if ($type == 'add') {
            $stock = DB::raw("stock - $detail->cant");
        } else {
            $stock = DB::raw("stock + $detail->cant");
        }
        $product->stock = $stock;
        $product->save();
    }
    public function destroy($id)
    {
        $facture = Facture::find($id);
        $details = DetailFacture::where('facture_id', $id)->get();
        foreach ($details as $items) {
            $this->update_stock($items->id, 'delete');
        }
        $facture->delete();
        return response()->json(["message" => "Factura eliminada"]);
    }
    public function index($date, $datetwo)
    {
        $user_id = Auth::id();
        $fac = DB::table('factures as f')
            ->join('users as u', 'u.id', '=', 'f.user_id')
            ->join('clients as c', 'c.id', '=', 'f.client_id')
            ->select(
                'f.id',
                'c.nit as nit',
                'c.name as name_client',
                'c.tel',
                'f.tot',
                'f.status',
                'f.efecty',
                'f.other',
                'f.date_facture'
            )
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.user_id', $user_id)
            ->orderBy('f.id', 'desc')->get();
        return $fac;
    }
    public function indexDetail($id)
    {
        $fac = DB::table('detail_factures as d')
            ->join('factures as f', 'f.id', '=', 'd.facture_id')
            ->join('products as p', 'd.product_id', '=', 'p.id')
            ->select(
                'p.name as name_product',
                'p.price as price',
                'p.price as price_two',
                'd.sub',
                'd.cant',
                'd.discount',

                'p.id',

            )
            ->where('d.facture_id', $id)
            ->orderBy('d.id', 'desc')->get();
        return $fac;
    }
    public function gain($date, $datetwo)
    {
        $user_id = Auth::id();
        $gain = DB::table('detail_factures as fd')
            ->join('factures as f', 'f.id', '=', 'fd.facture_id')
            ->join('products as p', 'p.id', '=', 'fd.product_id')
            ->select(
                'p.name',
                'p.id',
                DB::raw('SUM(fd.cant) as quantity'),
                DB::raw('SUM(fd.sub) as tot'),
                DB::raw('p.cost as cost'),
                DB::raw('SUM(fd.sub)-SUM(p.cost*fd.cant) as gain'),
            )
            ->groupBy('p.name', 'p.cost', 'p.id')
            ->whereBetween('f.date_facture', [$date, $datetwo])
            ->where('f.status', 1)
            ->where('f.user_id', $user_id)
            ->get();
        return $gain;
    }
    public function type_sale($date, $datetwo)
    {
        $user_id = Auth::id();
        $facture_tot = DB::table('factures')
            ->select(
                DB::raw('SUM(tot) as tot'),
                DB::raw('SUM(efecty) as efecty'),
                DB::raw('SUM(other) as other'),
            )
            ->whereBetween('date_facture', [$date, $datetwo])
            ->where('status', 1)
            ->where('user_id', $user_id)
            ->get();
        return $facture_tot;
    }
}
