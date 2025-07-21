<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;

use Carbon\Carbon;
use DataTables;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['title'] = 'User Management';
        $data['roles'] = Role::get();
         return view('userSystem.user.index', $data);
    }

    public function indexData(Request $request)
    {
        $data = User::get();

        return DataTables::of($data)
        ->addColumn('role', function($data){
            return $data->getRoleNames()->implode(', ');
        })
        ->addColumn('edit', function($data){
            return '<div class="btn btn-warning" data-id="'.$data->id.'" onClick="editUser(this)"><i class="fas fa-pencil"></i></div>';
        })
        ->addColumn('delete', function($data){
            return '<div class="btn btn-danger" data-id="'.$data->id.'" onClick="deleteUser(this)"><i class="fas fa-trash"></i></div>';
        })
        ->rawColumns(['edit', 'delete'])
        ->make(true);
    }

    public function userData(Request $request)
    {
        // var_dump($request->all());
        // die()
        $data = User::with('roles')->find($request->id);
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => [
                    'user' => $data,
                    'roles' => $data->roles->first(),
                ],
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak di temukan!!'
            ]);
        }
    }

    public function userStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'role' => 'required',
        ]);

        if ($request->id == null) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|',
            ]);
        }

        if ($validator->fails()) {
            $allErrors = $validator->errors()->all();
            $errorMessage = implode(' ', $allErrors);

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ]);
        }
        try {
            $role = Role::where('name', $request->role)->first();
    
            if ($request->id) {
                $user = User::find($request->id);
                DB::transaction(function() use($request, $user, $role) {
                    $user->update([
                        'name'=> $request->name, 
                        'email'=> $request->email, 
                    ]);
                    if ($request->password != null) {
                        $user->update([
                            'password' => Hash::make($request->password),
                        ]);
                    }
                    $user->syncRoles($role->id);
                });
            }else {
                DB::transaction(function() use($request, $role) {
                    $user = User::create([
                        'name'=> $request->name, 
                        'email'=> $request->email, 
                        'password'=> Hash::make($request->password), 
                    ]);
                    $user->syncRoles($role->id);
                });
            }
            return response()->json([
                'success' => true,
                'message' => 'Action berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong in ' . $th->getMessage()
            ]);
        }
    }

    public function userDelete(Request $request)
    {
        $user = User::find($request->id);
        try {
            DB::transaction(function() use($user) {
                $user->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di update'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong in ' . $th->getMessage(),
            ]);
        }
    }
}
