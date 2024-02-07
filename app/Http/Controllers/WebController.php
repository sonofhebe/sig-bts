<?php

namespace App\Http\Controllers;

use App\Models\Bts;
use App\Models\User;
use App\Models\Produk;
use App\Models\Report;
use App\Models\TProduk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $totalBts = Bts::count();
        $totalReport = Report::whereYear('created_at', date('Y'))->count();

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $totalData = Report::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $month)
                ->count();

            $monthlyData[] = $totalData;
        }

        $totalReportByBts = Report::selectRaw('nama_bts, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('nama_bts')
            ->get();

        $response = [
            "response" => "success",
            "data" => [
                "totalBts" => $totalBts,
                "totalReport" => $totalReport,
                "chartData" => $monthlyData,
                "totalReportByBts" =>  $totalReportByBts,
            ]
        ];

        return response()->json($response);
    }

    // USER
    public function get_user()
    {
        $user = User::all();

        $response = [
            "response" => "success",
            "data" => $user
        ];
        return response()->json($response);
    }
    public function user_store(Request $request)
    {
        $request->merge(['password' => Hash::make($request->input('password'))]);
        $data_user = $request->input();
        if ($user = User::create($data_user)) {
            $response = [
                "response" => "success",
                "data" => $user
            ];
            return response()->json($response);
        }
    }
    public function user_update(Request $request)
    {
        $data_user = User::find($request->input('id'));
        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->input('password'))]);
        }
        if ($user = $data_user->update($request->input())) {
            $response = [
                "response" => "success",
                "data" => $user
            ];
            return response()->json($response);
        }
    }
    public function user_delete($id)
    {
        $user = User::find($id)->forceDelete();
        if ($user) {
            $response = [
                "response" => "success",
            ];
            return response()->json($response);
        }
    }
    public function user_getById($id)
    {
        $user = User::find($id);
        $response = [
            "response" => "success",
            "data" => $user
        ];
        return response()->json($response);
    }

    // BTS
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

    // REPORT
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
