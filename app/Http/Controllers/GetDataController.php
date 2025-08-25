<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master\MasterItem as Item;
use App\Models\Master\MasterFormulaVariable as Variable;

use App\Models\Master\MasterKapal as Kapal;
use App\Models\VVoyage as Voy;

class GetDataController extends Controller
{
    
    public function getItem(Request $request)
    {
        try {
            $item = Item::find($request->id);
            if ($item) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data di temukan',
                    'data' => $item
                ]);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]); 
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]); 
        }
    }

    public function getVessel(Request $request)
    {
        $voy = Voy::find($request->id);
        $vessel = Kapal::find($voy->master_id);
        if ($vessel) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $vessel,
                'voy' => $voy
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

}
