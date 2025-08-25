<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\Master\MasterKapal as Kapal;
use App\Models\VVoyage as Voy;

class VoyageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['title'] = 'Vessel Voyage';

        $data['vessels'] = Kapal::get();
        return view('voyage.index', $data);
    }

    public function data(Request $request)
    {
        $data = Voy::with(['Master', 'User'])->get();

        return DataTables::of($data)
        ->addColumn('name', function($data){
            return $data->Master->name ?? '-';
        })
        ->addColumn('code', function($data){
            return $data->Master->code ?? '-';
        })
       ->addColumn('status', function($data){
            $now = Carbon::now();
            
            if (!empty($data->departure_date) && $data->departure_date < $now) {
                $status = '<span class="badge bg-info text-dark">Sudah Berangkat</span>';
            } elseif (!empty($data->arrival_date) && $data->arrival_date < $now) {
                $status = '<span class="badge bg-warning text-dark">Sudah Sandar</span>';
            } else {
                $status = '<span class="badge bg-success text-dark">Belum Sandar</span>';
            }
        
            return $status;
        })
        ->addColumn('user', function($data){
            return $data->User->name ?? '-';
        })
        ->addColumn('edit', function($data){
            return '<button type="button" class="btn btn-warning" data-id="'.$data->id.'" onClick="editVoy(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->rawColumns(['edit', 'status'])
        ->make(true);
    }

    public function post(Request $request)
    {
        try {
            DB::transaction(function() use($request){

                $voyage = Voy::updateOrCreate(
                    ['id' => $request->id],
                    [
                        'master_id' => $request->master_id,
                        'eta' => $request->eta,
                        'etd' => $request->etd,
                        'arrival_date' => $request->arrival_date ?? $request->eta,
                        'departure_date' => $request->departure_date ?? $request->etd,
                        'start_work_date' => $request->start_work_date,
                        'clossing_date' => $request->clossing_date,
                        'cargo_clossing_date' => $request->cargo_clossing_date,
                        'voy_no' => $request->voy_no,
                        'user_id' => Auth::user()->id,
                        'create_at' => Carbon::now(),
                    ]
                );

            });
            return response()->json([
                'success' => true,
                'message'=> 'Aksi Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message'=> $th->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        try {
            $voyage = Voy::find($request->id);  
            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil',
                'data' => $voyage
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message'=> $th->getMessage()
            ]);
        }
    }
}
