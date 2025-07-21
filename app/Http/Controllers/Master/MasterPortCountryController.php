<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterCountry as Country;
use App\Models\Master\MasterPort as Port;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DataTables;
use Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use PhpOffice\PhpSpreadsheet\IOFactory;


class MasterPortCountryController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function indexPort()
    {
        $data['title'] = "Master Port";
        $data['countries'] = Country::get();
        return view('master.master_port.index', $data);
    }

    public function dataPort(Request $request)
    {
        $query = Port::with('Negara');

        $data = $query->get();

        return DataTables::of($data)
        ->addColumn('countryName', function($data){
            return $data->Negara->name ?? '-';
        })
        ->addColumn('countryCode', function($data){
            return $data->Negara->code ?? '-';
        })
        ->addColumn('edit', function($data){
            return '<button class="btn btn-warning" data-id="'.$data->id.'" onClick="editPort(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->addColumn('delete', function($data){
            return '<button class="btn btn-danger" data-id="'.$data->id.'" onClick="deletePort(this)"><i class="fas fa-trash"></i></button>';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }

    public function editPort(Request $request)
    {
        try {
            $port = Port::find($request->id);
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $port
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function postFilePort(Request $request)
    {
        // var_dump($request->all(), $request->allFiles());
        // die();
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        $datas = array_slice($rows, 2);
        foreach ($datas as $data) {
            $name = $data[1];
            $code = $data[3];
            $desc = $data[5];

            // country
            $unCountry = $data[2];
            $nameCountry = $data[4];
            $postData = [
                'name' => $name,
                'code' => $code,
                'desc' => $desc,
                'unCountry' => $unCountry,
                'nameCountry' => $nameCountry,
            ];
        
            $this->createPort($postData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data disimpan',
        ]);
        
    }

    public function postPort(Request $request)
    {
        $country = Country::find($request->country);
        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Country Not Found'
            ]);
        }

        $postData = [
            'name' => $request->name,
            'code' => $request->code,
            'desc' => $request->desc,
            'unCountry' => $country->code,
            'nameCountry' => $country->name,
        ];

        $oldPort = Port::find($request->id);
        try {
            if ($oldPort) {
                $this->updatePort($country, $postData, $oldPort);
            }else {
                $this->createPort($postData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil dilakukan',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    private function createPort($postData)
    {
        $country = Country::where('code', $postData['unCountry'])->first();
        if (!$country) {
            $country = $this->createCountry($postData);
        }
        $oldPort = Port::where('code', $postData['code'])->where('country_id', $country->id)->first();
        if (!$oldPort) {
            try {
                DB::transaction(function() use($country, $postData) {
                    Port::create([
                        'code' => $postData['code'],
                        'name' => $postData['name'],
                        'description' => $postData['desc'],
                        'country_id' => $country->id,
                    ]);
                });
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ]);
            }
        }else {
            $this->updatePort($country, $postData, $oldPort);
        }
    }

    private function updatePort($country, $postData, $oldPort)
    {
        return DB::transaction(function() use($country, $postData, $oldPort) {
            $oldPort->update([
                'code' => $postData['code'],
                'name' => $postData['name'],
                'description' => $postData['desc'],
                'country_id' => $country->id,
            ]);
        });
    }

    public function deletePort(Request $request)
    {
        $port = Port::find($request->id);
        try {
            DB::transaction(function() use($port) {
                $port->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di hapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function indexCountry(Request $request)
    {
        $data['title'] = 'Master Country';

        return view('master.master_country.index', $data);
    }

    public function dataCountry(Request $request)
    {
        $data = Country::get();
        return DataTables::of($data)
        ->addColumn('edit', function($data) {
            return '<button class="btn btn-warning" data-id="'.$data->id.'" onClick="editCountry(this)"><i class="fas fa-pencil"></i></button>';
        })
        ->rawColumns(['edit'])
        ->make(true);
    }

    public function postCountry(Request $request)
    {
        $postData = [
            'nameCountry' => $request->name,
            'unCountry' => $request->code,
        ];

        $oldCountry = Country::find($request->id);
        try {
            if ($oldCountry) {
                $this->updateCountry($postData, $oldCountry);
            }else {
                $sameCountry = Country::where('name', $request->name)->orWhere('code', $request->code)->first();
                if ($sameCountry) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data sudah tersedia'
                    ]);
                }
                // var_dump($postData);
                // die();
                $this->createCountry($postData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil di buat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function editCountry(Request $request)
    {
        $country = Country::find($request->id);
        if ($country) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $country
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    
    private function createCountry($postData)
    {
        try {
            $country =  DB::transaction(function() use($postData)  {
               return Country::create([
                    'name' => $postData['nameCountry'],    
                    'code' => $postData['unCountry'],    
                ]);
            });
            return $country;
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function updateCountry($postData, $oldCountry)
    {
        try {
            $country = DB::transaction(function() use($postData, $oldCountry) {
                return $oldCountry->update([
                    'name' => $postData['nameCountry'],    
                    'code' => $postData['unCountry'],    
                ]);
            });

            return $country;
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
