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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
    public function report_getById($id)
    {
        $report = Report::find($id);
        $response = [
            "response" => "success",
            "data" => $report
        ];
        return response()->json($response);
    }
    public function report_update(Request $request)
    {
        $data_report = Report::find($request->input('id'));

        if (!$data_report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        $data = $request->all();

        if ($data['status'] == "selesai") {
            $data['tanggal_selesai'] = now();
        } else {
            $data['tanggal_selesai'] = null;
        }

        if ($data_report->update($data)) {
            $response = [
                "response" => "success",
                "data" => $data_report
            ];
            return response()->json($response);
        } else {
            return response()->json(['error' => 'Update failed'], 500);
        }
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
    public function report_download(Request $request)
    {
        $input = $request->all();

        $query = Report::whereYear('created_at', $input['tahun'])
            ->whereMonth('created_at', $input['bulan']);
        if ($input['nama_bts'] != "SEMUA BTS") {
            $query->where("nama_bts", $input['nama_bts']);
        }

        $total_terdaftar = clone $query; // Clone the query to avoid modifying the original query
        $total_proses = clone $query;
        $total_selesai = clone $query;
        $total_tunda = clone $query;
        $total_batal = clone $query;

        $total_terdaftar = $total_terdaftar->where("status", "terdaftar")->count();
        $total_proses = $total_proses->where("status", "proses")->count();
        $total_selesai = $total_selesai->where("status", "selesai")->count();
        $total_tunda = $total_tunda->where("status", "tunda")->count();
        $total_batal = $total_batal->where("status", "batal")->count();
        $data = $query->get();

        //Create Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Style header
        $header = [
            'font' => [
                'color' => ['rgb' => 'FFFFFF'], // White font color
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '000000'], // Black background color
            ],
        ];

        $sheet->getStyle('A4:I4')->applyFromArray($header);
        $sheet->getStyle('K4:O4')->applyFromArray($header);
        // Set the width of the cells
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(25);

        $sheet->getColumnDimension('K')->setWidth(14);
        $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->getColumnDimension('M')->setWidth(14);
        $sheet->getColumnDimension('N')->setWidth(14);
        $sheet->getColumnDimension('O')->setWidth(14);

        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama BTS');
        $sheet->setCellValue('C4', 'Tingkat Kepentingan');
        $sheet->setCellValue('D4', 'Kategori');
        $sheet->setCellValue('E4', 'Deskripsi');
        $sheet->setCellValue('F4', 'Tanggal Daftar');
        $sheet->setCellValue('G4', 'Tanggal Selesai');
        $sheet->setCellValue('H4', 'Status');
        $sheet->setCellValue('I4', 'Catatan');

        $sheet->setCellValue('K4', 'Total Terdaftar');
        $sheet->setCellValue('L4', 'Total Proses');
        $sheet->setCellValue('M4', 'Total Selesai');
        $sheet->setCellValue('N4', 'Total Tunda');
        $sheet->setCellValue('O4', 'Total Batal');

        $sheet->setCellValue('K5', $total_terdaftar);
        $sheet->setCellValue('L5', $total_proses);
        $sheet->setCellValue('M5', $total_selesai);
        $sheet->setCellValue('N5', $total_tunda);
        $sheet->setCellValue('O5', $total_batal);

        // title
        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 18,
            ],
        ];
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $sheet->getStyle('A2')->applyFromArray($titleStyle);
        $sheet->setCellValue('A1', "Laporan BTS : " . $input['nama_bts']);
        $sheet->setCellValue('A2', date("F", mktime(0, 0, 0, $input['bulan'], 1)) . " " . $input['tahun']);

        $lastRow = 4;
        foreach ($data as $key => $detail) {
            $row = $key + 5;
            // $sheet->setCellValueExplicit('A' . ($key + 2), $detail->sku, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $detail->nama_bts);
            $sheet->setCellValue('C' . $row, $detail->tingkat_kepentingan);
            $sheet->setCellValue('D' . $row, $detail->kategori);
            $sheet->setCellValue('E' . $row, $detail->deskripsi);
            $sheet->setCellValue('F' . $row, $detail->created_at);
            $sheet->setCellValue('G' . $row, $detail->tanggal_selesai);
            $sheet->setCellValue('H' . $row, $detail->status);
            $sheet->setCellValue('I' . $row, $detail->catatan);
            $lastRow++;
        }

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Set border color to black
                ],
            ],
        ];
        $sheet->getStyle('A4:I' . $lastRow)->applyFromArray($borderStyle);
        $sheet->getStyle('K4:O5')->applyFromArray($borderStyle);

        $fileNameExcl = 'Report BTS - ' . time() . '.xlsx';

        // Set headers for downloading the Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileNameExcl . '"');
        header('Cache-Control: max-age=0');

        // Create a PHPExcel_IOFactory instance to output to the browser
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

        // Optionally, you can capture the output buffer into a variable
        $blob = ob_get_clean();

        return $blob;
    }
}
