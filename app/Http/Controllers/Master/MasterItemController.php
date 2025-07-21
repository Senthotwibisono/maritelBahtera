<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\Master\MasterItem as Item;
use App\Models\Master\MasterFormulaVariable as Variable;
use App\Models\Currency;

class MasterItemController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function indexItem()
    {
        $data['title'] = 'Master Item Component';
        $data['variables'] = Variable::get();

        return view('master.master_item.index', $data);
    }

    public function dataItem(Request $request)
    {   
        $data = Item::get();
        return DataTables::of($data)
        ->addColumn('remark', function($data){
            return '<textarea class="form-control" cols="5" rows="3" readonly>'.$data->remark.'</textarea>';
        })
        ->addColumn('formula', function($data){
            return '<textarea class="form-control" cols="5" rows="3" readonly>'.$data->formula.'</textarea>';
        })
        ->addColumn('edit', function($data){
            return '<button type="button" class="btn btn-warning" data-id="'.$data->id.'" onClick="editItem(this)"><i class="fas fa-edit"></i></button>';
        })
        ->addColumn('delete', function($data){
            return '<button type="button" class="btn btn-danger" data-id="'.$data->id.'" onClick="deleteItem(this)"><i class="fas fa-trash"></i></button>';
        })
        ->rawColumns(['remark', 'edit', 'delete', 'formula'])
        ->make(true);
    }

    public function editItem(Request $request)
    {
        $item = Item::find($request->id);
        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $item,
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function postItem(Request $request)
    {
        // var_dump($request->all());
        // die();
        
        try {
            if (!empty($request->id)) {
                $item = Item::find($request->id);
                $this->updateItem($item, $request);
            }else {
                $this->createItem($request);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di unngah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deleteItem(Request $request)
    {
        $item = Item::find($request->id);
        try {
            DB::transaction(function() use($item) {
                $item->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di hapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message'  => $th->getMessage()
            ]);
        }
    }

    private function updateItem($item, $request)
    {
        $result = DB::transaction(function() use($item, $request) {
            $item->update([
                'name' => $request->name,
                'tarif_dasar' => $request->tarif_dasar,
                'unit' => $request->unit,
                'remark' => $request->remark,
                'formula' => $request->formula,
            ]);
        });

        return $result;
    }

    private function createItem($request)
    {
        $result = DB::transaction(function() use($request) {
            Item::create([
                'name' => $request->name,
                'tarif_dasar' => $request->tarif_dasar,
                'unit' => $request->unit,
                'remark' => $request->remark,
                'formula' => $request->formula,
            ]);
        });

        return $result;
    }

    public function postVariable(Request $request)
    {
        try {
            DB::transaction(function() use($request) {
                Variable::create([
                    'key' => $request->key,
                    'label' => $request->label,
                    'source_table' => $request->source_table,
                    'source_field' => $request->source_field,
                    'description' => $request->description
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di update'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getVariable()
    {
        $variables = Variable::all();
        return response()->json($variables);
    }
}
