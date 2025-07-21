<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Auth;
use Carbon\Carbon;
use DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Master\MasterLayOut as Layout;
use App\Models\Master\MasterLayOutMain as LayoutMain;
use App\Models\Master\MasterLayoutItem as LayoutItem;
use App\Models\Master\MasterLayoutItemDetil as ItemDetil;
use App\Models\Master\MasterItem as Item;
use App\Models\Master\MasterFormulaVariable as Variable;
use App\Models\Currency;

use App\Models\Master\MasterPort;
use App\Models\Master\MasterKapal;
use App\Models\Master\MasterCountry;

use App\Models\Invoice\InvoiceHeader as Header;

class InvoiceController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['title'] = "Invoice Index";
        $data['search'] = $request->input('search');
        $data['layouts'] = Layout::with('UserCreated')
            ->when($data['search'], function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);
    if ($request->ajax()) {
        return view('invoice.listLayout', $data)->render(); // Partial view
    }
        return view('invoice.index', $data);
    }

    public function dataTable(Request $request)
    {
        $data = Header::get();
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
        ->addColumn('updateStatus', function($data) {
            return '<div title="Untuk next update" style="display:inline-block">
              <button class="btn btn-success" data-id="'.$data->id.'" disabled style="pointer-events: none;">Update Status</button>
            </div>';
        })
        ->rawColumns(['edit', 'cancel', 'reference_no', 'status', 'print', 'updateStatus'])
        ->make(true);
    }

    public function createFirst(Request $request)
    {
        $oldLayout = Layout::find($request->id);
        try {
            $invoice = DB::transaction(function() use($oldLayout){
                $layout = Layout::create([
                    'lock_flag' => 'Y',
                    'name' => '-',
                    'remark' => '-',
                    'user_created' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
                $oldMains = LayoutMain::where('layout_id', $oldLayout->id)->get();
                foreach ($oldMains as $oldMain) {
                    $main = LayoutMain::create([
                        'layout_id' => $layout->id,
                        'name' => $oldMain->name,
                        'currency_flag' => $oldMain->currency_flag,
                        'uid' => $oldMain->uid,
                        'order' => $oldMain->order,
                        'vat' => $oldMain->vat,
                        'admin_nota' => $oldMain->admin_nota,
                        'admin_it' => $oldMain->admin_it,
                        'amount' => $oldMain->amount
                    ]);

                    $oldItems = LayoutItem::where('layout_id', $oldMain->layout_id)->where('layout_main_id', $oldMain->id)->get();
                    foreach ($oldItems as $oldItem) {
                        $layoutItem = LayoutItem::create([
                            'layout_id' => $main->layout_id,
                            'layout_main_id' => $main->id,
                            'item_id' => $oldItem->item_id,
                            'name' => $oldItem->name,
                            'unit' => $oldItem->unit,
                            'remark' => $oldItem->remark,
                            'formula' => $oldItem->formula,
                            'amount' => $oldItem->amount,
                            'order' => $oldItem->order,
                        ]);

                        $oldDetils = ItemDetil::where('layout_id', $oldItem->layout_id)->where('layout_main_id', $oldItem->layout_main_id)->where('layout_item_id', $oldItem->id)->get();
                        foreach ($oldDetils as $detil) {
                            $detil = ItemDetil::create([
                                'layout_id' => $layoutItem->layout_id,
                                'layout_main_id' => $layoutItem->layout_main_id,
                                'layout_item_id' => $layoutItem->id,
                                'key' => $detil->key,
                                'label' => $detil->label,
                                'source_table' => $detil->source_table,
                                'source_field' => $detil->source_field,
                                'amount' => $detil->amount,
                            ]);
                        }
                    }
                }

                $invoice = Header::create([
                    'layout_id' => $layout->id,
                    'status' => 'N',
                    'created_at' => Carbon::now(),
                    'user_id' => Auth::user()->id,
                ]);

                return $invoice;
            });

            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil dilakukan',
                'data' => $invoice
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage() 
            ]);
        }
    }

    public function formIndex($id)
    {
        $invoice = Header::find($id);
        $data['header'] = $invoice;
        $data['title'] = 'Form Invoice ';

        $data['layout'] = Layout::find($invoice->layout_id);
        $data['mains'] = LayoutMain::where('layout_id', $invoice->layout_id)->orderBy('order', 'asc')->get();
        $data['items'] = LayoutItem::where('layout_id', $invoice->layout_id)->orderBy('order', 'asc')->get();
        $data['detils'] = ItemDetil::where('layout_id', $invoice->layout_id)->get();
        
        $data['mitems'] = Item::get();
        $data['variables'] = Variable::get();

        $data['vessels'] = MasterKapal::get();
        $data['ports'] = MasterPort::get();
        $data['countries'] = MasterCountry::get();

        return view('invoice.form-index', $data);
    }

    public function contentDetil($id)
    {
        $invoice = Header::find($id);
        $data['layout'] = Layout::find($invoice->layout_id);
        $data['mains'] = LayoutMain::where('layout_id', $invoice->layout_id)->orderBy('order', 'asc')->get();
        $data['items'] = LayoutItem::where('layout_id', $invoice->layout_id)->orderBy('order', 'asc')->get();
        $data['detils'] = ItemDetil::where('layout_id', $invoice->layout_id)->get();
        return view('invoice.form-content', $data);
    }

    public function formSubmit(Request $request)
    {
        try {
            DB::transaction(function() use($request){
                $details = $request->details;
                $items = $request->items;
                $mains = $request->mains;
                // dd($details, $items, $mains);
                $header = Header::find($request->headerId);
                $ves = MasterKapal::find($request->ves_id);
                $header->update([
                    'ves_id' => $request->ves_id,
                    'ves_name' => $ves->name,
                    'ves_code' => $ves->code,
                    'dwt' => $request->dwt,
                    'grt' => $request->grt,
                    'nrt' => $request->nrt,
                    'loa' => $request->loa,
                    'breadth' => $request->breadth,
                    'owner' => $ves->owner,
                    'country_id' => $request->country_id,
                    'voy' => $request->voy,
                    'exchange_rate' => $request->exchange_rate,
                    'reference_no' => $request->reference_no,
                    'invoice_date' => $request->invoice_date,
                    'port_of_call' => $request->port_of_call,
                    'purpose_of_call' => $request->purpose_of_call,
                    'activity' => $request->activity,
                    'cargo' => $request->cargo,
                    'volume' => $request->volume,
                    'est_port_stay' => $request->est_port_stay,
                    'idr_amount' => $request->idr_amount,
                    'idr_fund_amount' => $request->idr_fund_amount,
                    'idr_balance_due' => $request->idr_balance_due,
                    'usd_amount' => $request->usd_amount,
                    'usd_fund_amount' => $request->usd_fund_amount,
                    'usd_balance_due' => $request->usd_balance_due,
                    'updated_at' => Carbon::now(),
                    'last_user_updated' => Auth::user()->id,
                ]);

                foreach ($details as $detail) {
                    $itemDetail = ItemDetil::where('layout_id', $header->layout_id)->where('layout_main_id', $detail['mainId'])->where('layout_item_id', $detail['itemId'])->where('id', $detail['detilId'])->first();
                    // dd($itemDetail);
                    $itemDetail->update([
                        'amount' => $detail['amount']
                    ]);
                }

                foreach ($items as $item) {
                    $itemFind = LayoutItem::where('layout_id', $header->layout_id)->where('layout_main_id', $item['mainId'])->where('id', $item['itemId'])->first();
                    // dd($itemFind, $item['']);
                    $itemFind->update([
                        'amount' => $item['amount']
                    ]);
                }

                foreach ($mains as $main) {
                    $mainItem = LayoutMain::where('layout_id', $header->layout_id)->where('id', $main['mainId'])->first();
                    $mainItem->update([
                        'amount' => $main['amount']
                    ]);
                }

                $layout = Layout::find($header->layout_id);
                $layout->update([
                    'name' => $layout->name . ' ' . $header->ves_name . ' - ' . $header->voy . ' / ' . $header->activity,
                    'remark' => $header->activity . ' / ' . $header->purpose_of_call
                ]);
            });

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

    public function printPDF($id)
    {
        $invoice = Header::find($id);
        $data['header'] = $invoice;
        $data['title'] = 'Form Invoice ';

        $data['layout'] = Layout::find($invoice->layout_id);
        $data['mains'] = LayoutMain::where('layout_id', $invoice->layout_id)->orderBy('order', 'asc')->get();
        $data['items'] = LayoutItem::where('layout_id', $invoice->layout_id)->orderBy('order', 'asc')->get();
        $data['detils'] = ItemDetil::where('layout_id', $invoice->layout_id)->get();
        
        $data['mitems'] = Item::get();
        $data['variables'] = Variable::get();

        $data['vessels'] = MasterKapal::get();
        $data['ports'] = MasterPort::get();
        $data['countries'] = MasterCountry::get();

        $pdf = Pdf::loadView('invoice.pdf', $data);
        return $pdf->stream('preview.pdf');
    }

    public function cancelFirst(Request $request)
    {
        $data = Header::find($request->id);
        if ($data) {
            try {
                DB::transaction(function() use($data) {
                    $data->update([
                        'status' => 'C',
                        'updated_at' => Carbon::now(),
                        'last_user_updated' => Auth::user()->id,
                    ]);
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Aksi berhasil dilakukan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' > $th->getMessage()
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' > 'Data tidak ditemukan'
            ]);
        }
    }

    public function reactiveFirst(Request $request)
    {
        $data = Header::find($request->id);
        if ($data) {
            try {
                DB::transaction(function() use($data) {
                    $data->update([
                        'status' => 'N',
                        'updated_at' => Carbon::now(),
                        'last_user_updated' => Auth::user()->id,
                    ]);
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Aksi berhasil dilakukan'
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' > $th->getMessage()
                ]);
            }
        }else {
            return response()->json([
                'success' => false,
                'message' > 'Data tidak ditemukan'
            ]);
        }
    }
}
