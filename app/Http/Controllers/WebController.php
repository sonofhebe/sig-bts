<?php

namespace App\Http\Controllers;

use App\Models\Bts;
use App\Models\Produk;
use App\Models\Report;
use App\Models\TProduk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WebController extends Controller
{
    public function login_attempt(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect('/');
        } else {
            Session::flash('failed');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function check_role()
    {
        if (Auth::user()->role == "admin") {
            return redirect('/bts');
        } elseif (Auth::user()->role == "kasir") {
            return redirect('/bts');
        }
    }

    public function get_dashboard()
    {
        $produk = Produk::all();
        $total_terjual = TProduk::all()->sum('jumlah');
        $produkSort = [];

        foreach ($produk as $p) {
            $produkSort[] = [
                "nama" => $p->nama,
                "terjual" => $p->tproduk->sum('jumlah')
            ];
        }

        usort($produkSort, function ($a, $b) {
            return $a['terjual'] < $b['terjual'];
        });

        $produkTerlaris = array_slice($produkSort, 0, 5);

        $chart_data = [];
        foreach ($produkSort as $ps) {
            $chart_data["label"][] = $ps["nama"];
            $chart_data["value"][] = $ps["terjual"];
        }

        $response = [
            "response" => "success",
            "produk_terlaris" => $produkTerlaris,
            "total_terjual" => $total_terjual,
            "total_produk" => $produk->count(),
            "chart_data" => $chart_data
        ];
        return response()->json($response);
    }

    public function get_bts()
    {
        $bts = Bts::all();

        $response = [
            "response" => "success",
            "data" => $bts
        ];
        return response()->json($response);
    }
    public function bts_store(Request $request)
    {
        $data_bts = $request->input();
        if ($bts = Bts::create($data_bts)) {
            $response = [
                "response" => "success",
                "data" => $bts
            ];
            return response()->json($response);
        }
    }
    public function bts_update(Request $request)
    {
        $data_bts = Bts::find($request->input('id'));
        if ($bts = $data_bts->update($request->input())) {
            $response = [
                "response" => "success",
                "data" => $bts
            ];
            return response()->json($response);
        }
    }
    public function bts_delete($id)
    {
        $bts = Bts::find($id)->forceDelete();
        if ($bts) {
            $response = [
                "response" => "success",
            ];
            return response()->json($response);
        }
    }
    public function bts_getById($id)
    {
        $bts = Bts::find($id);
        $response = [
            "response" => "success",
            "data" => $bts
        ];
        return response()->json($response);
    }


    public function report_get()
    {
        $bts = Report::all();

        $response = [
            "response" => "success",
            "data" => $bts
        ];
        return response()->json($response);
    }
    public function report_store(Request $request)
    {
        $data_report = $request->input();
        if ($report = Report::create($data_report)) {
            $response = [
                "response" => "success",
                "data" => $report
            ];
            return response()->json($response);
        }
    }
    public function report_delete($id)
    {
        $report = Report::find($id)->forceDelete();
        if ($report) {
            $response = [
                "response" => "success",
            ];
            return response()->json($response);
        }
    }
}
