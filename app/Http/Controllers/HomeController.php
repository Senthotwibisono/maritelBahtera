<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Invoice\InvoiceHeader as Header;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['title'] = 'Dashboard Admin';
        $headers = Header::where('user_id', Auth::user()->id)->get(); 

        $data['lunas'] = $headers->where('status', 'Y')->count();
        $data['piutang'] = $headers->where('status', 'N')->count();
        $data['cancel'] = $headers->where('status', 'C')->count();
        return view('home', $data);
    }
}
