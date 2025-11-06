<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Auth;
use Carbon\Carbon;
use DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Master\MasterLayOut as Layout;
use App\Models\Master\MasterLayoutMain as LayoutMain;
use App\Models\Master\MasterLayoutItem as LayoutItem;
use App\Models\Master\MasterLayoutItemDetil as ItemDetil;
use App\Models\Master\MasterItem as Item;
use App\Models\Master\MasterFormulaVariable as Variable;
use App\Models\Currency;

use App\Models\Master\MasterPort;
use App\Models\Master\MasterKapal;
use App\Models\Master\MasterCountry;

use App\Models\Invoice\InvoiceHeader as Header;
use App\Models\VVoyage as Voy;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['title'] = 'Report Penawaran';

        $data['start'] = $request->start ?? Carbon::now()->format('Y-m-d'); 
        $data['end'] = $request->end ?? Carbon::now()->format('Y-m-d'); 

        return view('report.index', $data);
    }

    public function data(Request $request)
    {
        $status = $request->status;
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();

        $data = Header::whereBetween('created_at', [$start, $end]);

        if ($status && $status !== 'all') {
            $data->where('status', $status);
        }

        $data = $data->get();

        return DataTables::of($data)
        ->addColumn('reference_no', function($data) {
            if ($data->reference_no != null) {
                return $data->reference_no;
            }else {
                return '<span class="badge bg-warning text-dark">Belum Di Terbitkan</span>';
            }
        })
        ->addColumn('status', function($data) {
            if ($data->status == 'C') {
                return '<span class="badge bg-danger text-dark">Canceled</span>';
            }elseif ($data->status == 'Y') {
                return '<span class="badge bg-success text-dark">Berhasil</span>';
            }else {
                return '<span class="badge bg-warning text-dark">Dalam Pengajuan</span>';
            }
        })
        ->addColumn('user', function($data) {
            return $data->User->name;
        })
        ->addColumn('negara', function($data) {
            return $data->Negara->name ?? '-';
        })
        ->addColumn('port', function($data) {
            return $data->Port->name ?? '-';
        })
        ->addColumn('edit', function($data) {
            if ($data->status == 'C') {
                return '<span class="badge bg-danger text-dark">Canceled</span>';
            }else {
                return '<button class="btn btn-warning" data-id="'.$data->id.'" onClick="editInvoiceHeader(this)"><i class="fas fa-pencil"></i></button>';
            }
        })
        ->addColumn('print', function($data) {
            if ($data->status == 'C') {
                return '<span class="badge bg-danger text-dark">Canceled</span>';
            }else {
                // return '<button class="btn btn-danger" data-id="'.$data->id.'" onClick="cancelInvoiceHeader(this)"><i class="fas fa-trash"></i></button>';
                return '<button class="btn btn-primary" data-id="'.$data->id.'" onClick="printPDF(this)"><i class="fas fa-print"></i></button>';
            }
        })
        ->addColumn('cancel', function($data) {
             if ($data->status == 'C') {
                return '<button class="btn btn-success" data-id="'.$data->id.'" onClick="reactiveInvoice(this)">Re-Activate</button>';
            }else {
                return '<button class="btn btn-danger" data-id="'.$data->id.'" onClick="cancelInvoiceHeader(this)"><i class="fas fa-trash"></i></button>';
            }
        })
        ->addColumn('arrival', function($data){
            return $data->Voy->arrival_date ?? '';
        })
        ->addColumn('departure', function($data){
            return $data->Voy->departure_date ?? '';
        })
        ->addColumn('statusKapal', function($data){
            $now = Carbon::now();
            
            if (!empty($data->Voy->departure_date) && $data->Voy->departure_date < $now) {
                $status = '<span class="badge bg-info text-dark">Sudah Berangkat</span>';
            } elseif (!empty($data->Voy->arrival_date) && $data->Voy->arrival_date < $now) {
                $status = '<span class="badge bg-warning text-dark">Sudah Sandar</span>';
            } else {
                $status = '<span class="badge bg-success text-dark">Belum Sandar</span>';
            }
        
            return $status;
        })
        ->addColumn('updateStatus', function($data) {
            return '<div title="Untuk next update" style="display:inline-block">
              <button class="btn btn-success" data-id="'.$data->id.'" disabled style="pointer-events: none;">Update Status</button>
            </div>';
        })
        ->rawColumns(['edit', 'cancel', 'reference_no', 'status', 'print', 'updateStatus', 'statusKapal'])
        ->make(true);
    }

    public function total(Request $request)
    {
        $status = $request->status;
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();    

        $query = Header::whereBetween('created_at', [$start, $end]);    

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }   

        $total = $query->sum('idr_amount'); 

        return response()->json(['total' => number_format($total, 2, ',', '.')]);
    }   

    public function fund(Request $request)
    {
        $status = $request->status;
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();    

        $query = Header::whereBetween('created_at', [$start, $end]);    

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }   

        $total = $query->sum('idr_fund_amount');    

        return response()->json(['total' => number_format($total, 2, ',', '.')]);
    }   

    public function due(Request $request)
    {
        $status = $request->status;
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();    

        $query = Header::whereBetween('created_at', [$start, $end]);    

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }   

        $total = $query->sum('idr_balance_due');    

        return response()->json(['total' => number_format($total, 2, ',', '.')]);
    }   

    public function totalUsd(Request $request)
    {
        $status = $request->status;
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();    

        $query = Header::whereBetween('created_at', [$start, $end]);    

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }   

        $total = $query->sum('usd_amount'); 

        return response()->json(['total' => number_format($total, 2, ',', '.')]);
    }   

    public function fundUsd(Request $request)
    {
        $status = $request->status;
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();    

        $query = Header::whereBetween('created_at', [$start, $end]);    

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }   

        $total = $query->sum('usd_fund_amount');    

        return response()->json(['total' => number_format($total, 2, ',', '.')]);
    }   

    public function dueUsd(Request $request)
    {
        $status = $request->status;
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();    

        $query = Header::whereBetween('created_at', [$start, $end]);    

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }   

        $total = $query->sum('usd_balance_due');    

        return response()->json(['total' => number_format($total, 2, ',', '.')]);
    }

    public function print(Request $request)
    {
        $status = $request->status ?? 'all';
        $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::now()->startOfDay();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfDay();    

        // Ambil header sesuai filter
        $headers = Header::whereBetween('created_at', [$start, $end])
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->get();    

        // Ambil semua layout_id unik agar bisa ambil relasi yang dibutuhkan sekaligus
        $layoutIds = $headers->pluck('layout_id')->unique()->filter()->values();    

        // Ambil semua data layout terkait sekali query saja
        $layouts = Layout::whereIn('id', $layoutIds)->get()->keyBy('id');
        $mains = LayoutMain::whereIn('layout_id', $layoutIds)->orderBy('order')->get()->groupBy('layout_id');
        $items = LayoutItem::whereIn('layout_id', $layoutIds)->orderBy('order')->get()->groupBy('layout_id');
        $detils = ItemDetil::whereIn('layout_id', $layoutIds)->get()->groupBy('layout_id'); 

        $data = [
            'headers' => $headers,
            'layouts' => $layouts,
            'mains' => $mains,
            'items' => $items,
            'detils' => $detils,
            'title' => 'Report Invoice Summary',
        ];  

        return view('report.pdf', $data);
    }

    public function excel(Request $request)
    {
        $status = $request->status ?? 'all';
        $start = $request->start ? \Carbon\Carbon::parse($request->start)->startOfDay() : \Carbon\Carbon::now()->startOfDay();
        $end = $request->end ? \Carbon\Carbon::parse($request->end)->endOfDay() : \Carbon\Carbon::now()->endOfDay();    

        $reports = \App\Models\Invoice\InvoiceHeader::query()
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->whereBetween('created_at', [$start, $end])
            ->get([
                'status',
                'layout_id',
                'ves_id',
                'ves_name',
                'ves_code',
                'dwt',
                'grt',
                'nrt',
                'loa',
                'breadth',
                'owner',
                'country_id',
                'voy',
                'exchange_rate',
                'reference_no',
                'invoice_date',
                'port_of_call',
                'purpose_of_call',
                'activity',
                'cargo',
                'volume',
                'est_port_stay',
                'idr_amount',
                'idr_fund_amount',
                'idr_balance_due',
                'usd_amount',
                'usd_fund_amount',
                'usd_balance_due',
                'created_at',
                'user_id',
                'updated_at',
                'last_user_updated',
                'voy_id'
            ]); 

        // Bangun tabel HTML
        $html = '<table border="1" style="border-collapse:collapse;font-family:Arial;font-size:12px;">';
        $html .= '<thead style="background-color:#f2f2f2;font-weight:bold;"><tr>';
        $headers = [
            'Status', 'Layout ID', 'Vessel ID', 'Vessel Name', 'Vessel Code', 'DWT', 'GRT', 'NRT', 'LOA', 'Breadth',
            'Owner', 'Country ID', 'Voy', 'Exchange Rate', 'Reference No', 'Invoice Date', 'Port of Call', 
            'Purpose of Call', 'Activity', 'Cargo', 'Volume', 'Est Port Stay', 'IDR Amount', 'IDR Fund Amount',
            'IDR Balance Due', 'USD Amount', 'USD Fund Amount', 'USD Balance Due', 'Created At', 'User ID',
            'Updated At', 'Last User Updated', 'Voy ID'
        ];
        foreach ($headers as $h) {
            $html .= "<th>$h</th>";
        }
        $html .= '</tr></thead><tbody>';    

        foreach ($reports as $r) {
            $html .= '<tr>';
            $html .= '<td>' . $r->status . '</td>';
            $html .= '<td>' . $r->layout_id . '</td>';
            $html .= '<td>' . $r->ves_id . '</td>';
            $html .= '<td>' . $r->ves_name . '</td>';
            $html .= '<td>' . $r->ves_code . '</td>';
            $html .= '<td>' . $r->dwt . '</td>';
            $html .= '<td>' . $r->grt . '</td>';
            $html .= '<td>' . $r->nrt . '</td>';
            $html .= '<td>' . $r->loa . '</td>';
            $html .= '<td>' . $r->breadth . '</td>';
            $html .= '<td>' . $r->owner . '</td>';
            $html .= '<td>' . $r->country_id . '</td>';
            $html .= '<td>' . $r->voy . '</td>';
            $html .= '<td>' . $r->exchange_rate . '</td>';
            $html .= '<td>' . $r->reference_no . '</td>';
            $html .= '<td>' . ($r->invoice_date ?? '-') . '</td>';
            $html .= '<td>' . $r->port_of_call . '</td>';
            $html .= '<td>' . $r->purpose_of_call . '</td>';
            $html .= '<td>' . $r->activity . '</td>';
            $html .= '<td>' . $r->cargo . '</td>';
            $html .= '<td>' . $r->volume . '</td>';
            $html .= '<td>' . $r->est_port_stay . '</td>';
            $html .= '<td>' . $r->idr_amount . '</td>';
            $html .= '<td>' . $r->idr_fund_amount . '</td>';
            $html .= '<td>' . $r->idr_balance_due . '</td>';
            $html .= '<td>' . $r->usd_amount . '</td>';
            $html .= '<td>' . $r->usd_fund_amount . '</td>';
            $html .= '<td>' . $r->usd_balance_due . '</td>';
            $html .= '<td>' . ($r->created_at) . '</td>';
            $html .= '<td>' . $r->user_id . '</td>';
            $html .= '<td>' . ($r->updated_at) . '</td>';
            $html .= '<td>' . $r->last_user_updated . '</td>';
            $html .= '<td>' . $r->voy_id . '</td>';
            $html .= '</tr>';
        }   

        $html .= '</tbody></table>';    

        // Kembalikan sebagai response Laravel
        $filename = 'report-' . now()->format('Ymd_His') . '.xls';
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }







}
