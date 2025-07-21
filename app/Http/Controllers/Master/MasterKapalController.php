<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\MasterKapal as Kapal;
use App\Models\Master\MasterCountry as Country;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DataTables;
use Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use PhpOffice\PhpSpreadsheet\IOFactory;

class MasterKapalController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = 'Master Kapal';
        $data['countries'] = Country::get();
        return view('master.master_kapal.index', $data);
    }

    public function data(Request $request)
    {
        $data = Kapal::get();
        return DataTables::of($data)
        ->addColumn('country', function($data){
            return $data->Negara->name ?? '-';
        })
        ->addColumn('edit', function($data){
            return '<button class="btn btn-warning" data-id="'.$data->id.'" onClick="editVessel(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->addColumn('delete', function($data){
            return '<button class="btn btn-danger" data-id="'.$data->id.'" onClick="deleteVessel(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }

    public function edit(Request $request)
    {
        $vessel = Kapal::find($request->id);
        if ($vessel) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $vessel,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function post(Request $request)
    {
        // var_dump($request->all());
        // die();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            $allErrors = $validator->errors()->all();
            $errorMessage = implode(' ', $allErrors);

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ]);
        }
        try {
            if (!empty($request->id)) {
                $ves = Kapal::find($request->id);
                DB::transaction(function() use ($ves, $request) {
                    $ves->update([
                        'name' => $request->name,
                        'code' => $request->code,
                        'dwt' => $request->dwt,
                        'grt' => $request->grt,
                        'nrt' => $request->nrt,
                        'loa' => $request->loa,
                        'breadth' => $request->breadth,
                        'owner' => $request->owner,
                        'country_id' => $request->country,
                    ]);
                });
            }else {
                DB::transaction(function() use($request){
                    Kapal::create([
                        'name' => $request->name,
                        'code' => $request->code,
                        'dwt' => $request->dwt,
                        'grt' => $request->grt,
                        'nrt' => $request->nrt,
                        'loa' => $request->loa,
                        'breadth' => $request->breadth,
                        'owner' => $request->owner,
                        'country_id' => $request->country,
                    ]);
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di record'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }

    }

    public function delete(Request $request)
    {
        $ves = Kapal::find($request->id);
        if ($ves) {
            $ves->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di hapus'
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
