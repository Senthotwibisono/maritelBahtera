<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Auth;
use Carbon\Carbon;
use DataTables;

use App\Models\Master\MasterLayOut as Layout;
use App\Models\Master\MasterLayOutMain as LayoutMain;
use App\Models\Master\MasterLayoutItem as LayoutItem;
use App\Models\Master\MasterLayoutItemDetil as ItemDetil;
use App\Models\Master\MasterItem as Item;
use App\Models\Master\MasterFormulaVariable as Variable;
use App\Models\Currency;

class MasterLayoutController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['title'] = 'Master Layout';
        $data['search'] = $request->input('search');
        $data['layouts'] = Layout::with('UserCreated')->whereNot('lock_flag', 'Y')->orWhere('lock_flag', null)
            ->when($data['search'], function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        return view('master.master_layout.index', $data);
    }

    public function data(Request $request)
    {

    }

    public function create(Request $request)
    {
        try {
            $layOut = DB::transaction(function() use($request) {
               return Layout::create([
                    'name' => '-',
                    'remark' => '-',
                    'user_created' => Auth::user()->id,
                    'user_edit' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Aksi Berhasil, tungggu Sebentar anda akan segera di arahkan',
                'data' => $layOut
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function indexDetil($id)
    {
        $data['title'] = 'Master Layot';
        $data['layout'] = Layout::find($id);
        $data['mains'] = LayoutMain::where('layout_id', $id)->orderBy('order', 'asc')->get();
        $data['items'] = LayoutItem::where('layout_id', $id)->orderBy('order', 'asc')->get();
        $data['detils'] = ItemDetil::where('layout_id', $id)->get();
        
        $data['mitems'] = Item::get();
        $data['variables'] = Variable::get();
        return view('master.master_layout.detil.index', $data);
    }

    public function profileDetil($id)
    {
        $data['layout'] = Layout::find($id);
        return view('master.master_layout.detil.profile', $data);
    }

    public function contentDetil($id)
    {
        $data['layout'] = Layout::find($id);
        $data['mains'] = LayoutMain::where('layout_id', $id)->orderBy('order', 'asc')->get();
        $data['items'] = LayoutItem::where('layout_id', $id)->orderBy('order', 'asc')->get();
        $data['detils'] = ItemDetil::where('layout_id', $id)->get();
        return view('master.master_layout.detil.content', $data);
    }

    public function profileUpdate(Request $request)
    {
        $layout = Layout::find($request->id);
        if ($layout) {
            try {
                DB::transaction(function() use($layout, $request){
                    $layout->update([
                        'name' => $request->name,
                        'remark' => $request->remark,
                        'user_edit' => Auth::user()->id,
                    ]);
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di simpan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function postLayoutMain(Request $request)
    {
        try {
            $layoutMain = layoutMain::find($request->mainId);
            if ($layoutMain) {
                DB::transaction(function() use($layoutMain, $request) {
                    $layoutMain->update([
                        'name' => $request->name,
                        'currency_flag' => $request->currency_flag,
                        'vat' => $request->vat ?? 0,
                        'admin_nota' => ($request->notaAdmin == true) ? 'Y' : 'N',
                        'admin_it' => ($request->notaIt == true) ? 'Y' : 'N',
                    ]);

                    $this->additionalMain($layoutMain, $request);
                });
            }else {     
                $order = $this->getLastMainOrder($request);
                DB::transaction(function() use($request, $order){
                    $layoutMain = LayoutMain::create([
                        'layout_id' => $request->layoutId,
                        'name' => $request->name,
                        'currency_flag' => $request->currency_flag,
                        'vat' => $request->vat,
                        'admin_nota' => ($request->notaAdmin == true) ? 'Y' : 'N',
                        'admin_it' => ($request->notaIt == true) ? 'Y' : 'N',
                        'uid' => Auth::user()->id,
                        'order' => $order
                    ]);

                    $this->additionalMain($layoutMain, $request);
                });
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Aksi Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getLastMainOrder($request)
    {
        $layout = Layout::find($request->layoutId);
        if ($layout) {
            $main = LayoutMain::where('layout_id', $layout->id)->orderBy('order', 'desc')->first();
            if ($main) {
                $lastOrder = $main->order + 1;
                return $lastOrder;
            }else {
                return 1;
            }
        }else{
            return null;
        }
    }

    public function additionalMain($layoutMain, $request)
    {
        // check VAT
        if ($layoutMain->vat > 0) {
            $oldVAT = LayoutItem::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('name', 'VAT')->first();
            if ($oldVAT) {
                $vatDetil = ItemDetil::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('layout_item_id', $oldVAT->id)->where('key', 'vat')->first();
                if ($vatDetil) {
                    $vatDetil->update([
                        'amount' => 0
                    ]);
                }else {
                    $vatDetil = ItemDetil::create([
                        'layout_id' => $layoutMain->layout_id,
                        'layout_main_id' => $layoutMain->id,
                        'layout_item_id' => $oldVAT->id,
                        'key' => 'vat',
                        'label' => 'VAT',
                        'source_table' => 'manual_input',
                        'source_field' => 'manual_input',
                        'amount' => 0,
                    ]);
                }
            }else {
                $vat = LayoutItem::create([
                    'layout_id' => $layoutMain->layout_id,
                    'layout_main_id' => $layoutMain->id,
                    'name' => 'VAT',
                    'unit' => '%',
                    'remark' => '-',
                    'formula' => 'vat',
                    'amount' => 0,
                ]);
                $vatDetil = ItemDetil::create([
                    'layout_id' => $layoutMain->layout_id,
                    'layout_main_id' => $layoutMain->id,
                    'layout_item_id' => $vat->id,
                    'key' => 'vat',
                    'label' => 'VAT',
                    'source_table' => 'manual_input',
                    'source_field' => 'manual_input',
                    'amount' => 0,
                ]);
            }
        }else {
            $oldVAT = LayoutItem::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('name', 'VAT')->first();
            if ($oldVAT) {
                $vatDetil = ItemDetil::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('layout_item_id', $oldVAT->id)->where('key', 'vat')->first();
                if ($vatDetil) {
                    $vatDetil->delete();
                }
                $oldVAT->delete();
            }
        }

        if ($layoutMain->admin_nota == 'Y') {
            $oldNota = LayoutItem::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('name', 'Nota Administration')->first();
            if ($oldNota) {
                $adminDetil = ItemDetil::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('layout_item_id', $oldNota->id)->where('key', 'admin_nota')->first();
                if (!$adminDetil) {
                    $adminDetil = ItemDetil::create([
                        'layout_id' => $layoutMain->layout_id,
                        'layout_main_id' => $layoutMain->id,
                        'layout_item_id' => $oldNota->id,
                        'key' => 'admin_nota',
                        'label' => 'Nota Administration',
                        'source_table' => 'manual_input',
                        'source_field' => 'manual_input',
                        'amount' => 0,
                    ]);
                }
            }else {
                $adminNota = LayoutItem::create([
                    'layout_id' => $layoutMain->layout_id,
                    'layout_main_id' => $layoutMain->id,
                    'name' => 'Nota Administration',
                    'unit' => '-',
                    'remark' => 'admin_nota',
                    'formula' => 'admin_nota',
                    'amount' => 0,
                ]);
                $adminDetil = ItemDetil::create([
                    'layout_id' => $layoutMain->layout_id,
                    'layout_main_id' => $layoutMain->id,
                    'layout_item_id' => $adminNota->id,
                    'key' => 'admin_nota',
                    'label' => 'Nota Administration',
                    'source_table' => 'manual_input',
                    'source_field' => 'manual_input',
                    'amount' => 0,
                ]);
            }
        }else {
            $oldNota = LayoutItem::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('name', 'Nota Administration')->first();
            if ($oldNota) {
                $adminDetil = ItemDetil::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('layout_item_id', $oldNota->id)->where('key', 'admin_nota')->first();
                if ($adminDetil) {
                    $adminDetil->delete();
                }
                $oldNota->delete();
            }
        }

        if ($layoutMain->admin_it == 'Y') {
            $oldIT = LayoutItem::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('name', 'IT Administration')->first();
            if ($oldIT) {
                $itDetil = ItemDetil::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('layout_item_id', $oldIT->id)->where('key', 'admin_it')->first();
                if (!$itDetil) {
                    $itDetil = ItemDetil::create([
                        'layout_id' => $layoutMain->layout_id,
                        'layout_main_id' => $layoutMain->id,
                        'layout_item_id' => $oldIT->id,
                        'key' => 'admin_it',
                        'label' => 'IT Administration',
                        'source_table' => 'manual_input',
                        'source_field' => 'manual_input',
                        'amount' => 0,
                    ]);
                }
            }else {
                $adminIt = LayoutItem::create([
                    'layout_id' => $layoutMain->layout_id,
                    'layout_main_id' => $layoutMain->id,
                    'name' => 'IT Administration',
                    'unit' => '-',
                    'remark' => '-',
                    'formula' => 'admin_it',
                    'amount' => 0,
                ]);
                $adminDetil = ItemDetil::create([
                    'layout_id' => $layoutMain->layout_id,
                    'layout_main_id' => $layoutMain->id,
                    'layout_item_id' => $adminIt->id,
                    'key' => 'admin_it',
                    'label' => 'IT Administration',
                    'source_table' => 'manual_input',
                    'source_field' => 'manual_input',
                    'amount' => 0,
                ]);
            }
        }else {
            $oldIt = LayoutItem::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('name', 'IT Administration')->first();
            if ($oldIt) {
                $itDetil = ItemDetil::where('layout_id', $layoutMain->layout_id)->where('layout_main_id', $layoutMain->id)->where('layout_item_id', $oldIt->id)->where('key', 'admin_it')->first();
                if ($itDetil) {
                    $itDetil->delete();
                }
                $oldIt->delete();
            }
        }
    }

    public function editLayoutMain(Request $request)
    {
        $layoutMain = LayoutMain::find($request->mainId);
        if ($layoutMain) {
            return response()->json([
                'success' => true,
                'data' => $layoutMain
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function deleteLayoutMain(Request $request)
    {
        $layoutMain = LayoutMain::where('layout_id', $request->layoutId)->where('id', $request->mainId)->first();
        try {
            DB::transaction(function() use($layoutMain, $request) {
                $items = LayoutItem::where('layout_main_id', $layoutMain->id)->get();
                if (!empty($items)) {
                    foreach ($items as $item) {
                        ItemDetil::where('layout_item_id', $item->id)->delete();
                        $item->delete();
                    }
                }
                $layoutMain->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil dilakukan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]); 
        }
    }

    public function updateMainOrder(Request $request){
       $orders = $request->input('order');
       try {
            DB::transaction(function() use($orders){
                foreach ($orders as $order) {
                    $layoutMain = LayoutMain::where('layout_id', $order['layoutId'])->where('id', $order['mainId'])->first();
                    if ($layoutMain) {
                        $layoutMain->update([
                            'order' => $order['position']
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
           return response()->json([
               'success' => false,
               'message' => $th->getMessage()
           ]);
       }

    }

    public function updateDetilOrder(Request $request)
    {
        $orders = $request->input('order');
        try {
             DB::transaction(function() use($orders){
                 foreach ($orders as $order) {
                     $layoutMain = LayoutItem::where('layout_id', $order['layoutId'])->where('layout_main_id', $order['mainId'])->where('id', $order['itemId'])->first();
                     if ($layoutMain) {
                         $layoutMain->update([
                             'order' => $order['position']
                         ]);
                     }
                 }
             });

             return response()->json([
                 'success' => true
             ]);
         } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }

    }

    public function submitItem(Request $request)
    {
        try {
            $mitem = Item::find($request->mitem);
            if ($request->itemId != null) {
                $item = LayoutItem::find($request->itemId);
                DB::transaction(function() use($item, $request, $mitem) {
                    $item->update([
                        'layout_id',
                        'layout_main_id',
                        'item_id' => $mitem->id,
                        'name' => $mitem->name,
                        'unit' => $request->unitItem,
                        'remark' => $request->remarkItem,
                        'formula' => $request->formulaItem,
                    ]);

                    $this->itemDetil($item);
                });
            }else {
                $order = $this->getLastDetilOrder($request);
                DB::transaction(function() use($request, $mitem, $order) {
                    $item = LayoutItem::create([
                        'layout_id' => $request->layoutId,
                        'layout_main_id' => $request->mainId,
                        'item_id' => $mitem->id,
                        'name' => $mitem->name,
                        'unit' => $request->unitItem,
                        'remark' => $request->remarkItem,
                        'formula' => $request->formulaItem,
                        'amount' => 0,
                        'order' => $order,
                    ]);

                    $this->itemDetil($item);
                }); 
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil disimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function itemDetil($item)
    {
        // delete Old
        ItemDetil::where('layout_id', $item->layout_id)->where('layout_main_id', $item->layout_main_id)->where('layout_item_id', $item->id)->delete();

        $formulaItem = $item->formula;
        $parts = preg_split('/\W+/', $formulaItem);
        $parts = array_filter(array_map('trim', $parts));
        $parts = array_values($parts);
        foreach ($parts as $part) {
            $mitem = Item::find($item->item_id);
            $variable = Variable::where('key', $part)->first();
            $amount = 0;

            switch ($variable->source_table) {
                case 'master_item':
                    switch ($variable->source_field) {
                        case 'tarif_dasar':
                            $amount = $mitem->tarif_dasar;
                            break;
                        
                        default:
                            $amount = 0;
                            break;
                    }
                    break;
                
                default:
                    $amount = 0;
                    break;
            } 
            $itemDetil = ItemDetil::create([
                'layout_id' => $item->layout_id,
                'layout_main_id' => $item->layout_main_id,
                'layout_item_id' => $item->id,
                'key' => $variable->key,
                'label' => $variable->label,
                'source_table' => $variable->source_table,
                'source_field' => $variable->source_field,
                'amount' => $amount,
            ]);
        }
    }

    public function getLastDetilOrder($request)
    {
        $layout = Layout::find($request->layoutId);
        if ($layout) {
            $main = LayoutMain::where('layout_id', $layout->id)->where('id', $request->mainId)->first();
            if ($main) {
               $detil = LayoutItem::where('layout_id', $layout->id)->where('layout_main_id', $main->id)->orderBy('order', 'desc')->first();
               if ($detil) {
                    $order = $detil->order + 1;
                    return $order;
               }else{
                return 1;
               }
            }else {
                return null;
            }
        }else{
            return null;
        }
    }

    public function deleteItem(Request $request)
    {
        $item = LayoutItem::where('layout_id', $request->layoutId)->where('layout_main_id', $request->mainId)->where('id', $request->id)->first();
        // var_dump($item, $request->all());
        // die();
        if ($item) {
            try {
                DB::transaction(function() use($item){
                    ItemDetil::where('layout_id', $item->layout_id)->where('layout_main_id', $item->layout_main_id)->where('layout_item_id', $item->id)->delete();
                    $item->delete();
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Aksi Berhasil dilakukan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function updateDetilAmount(Request $request)
    {
        $datas = $request->all();
        try {
            DB::transaction(function() use($datas){
                foreach ($datas as $data) {
                    $detil = ItemDetil::where('layout_id', $data['layoutId'])->where('layout_main_id', $data['mainId'])->where('layout_item_id', $data['itemId'])->where('id', $data['detilId'])->first();
                    $detil->update([
                        'amount' => $data['amount']
                    ]);
                    $item = LayoutItem::where('layout_id', $data['layoutId'])->where('layout_main_id', $data['mainId'])->where('id', $data['itemId'])->first();
                    if (in_array($detil->key, ['vat', 'admin_nota', 'admin_it'])) {
                        $item->update([
                            'amount' => $data['amount']
                        ]);
                    }
                }
            });
            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil'
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
        $layout = Layout::find($request->id);
        if ($layout) {
            try {
                DB::transaction(function() use($layout) {
                    ItemDetil::where('layout_id', $layout->id)->delete();
                    LayoutItem::where('layout_id', $layout->id)->delete();
                    LayoutMain::where('layout_id', $layout->id)->delete();
                    $layout->delete();
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Aksi berhasil dilakukan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage()
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data tidak di temukan'
            ]);
        }
    }
}
