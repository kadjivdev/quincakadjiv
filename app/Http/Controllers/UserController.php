<?php

namespace App\Http\Controllers;

use App\Models\PointVente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function listUsers()
    {
        // $users = User::with('roles')->paginate(20);
        // return response()->json([
        //     'users'  => $users
        // ]);

        $users = User::with('roles')->get();

        return DataTables::of($users)
            ->addColumn('role', function ($user) {
                // Access the 'roles' relationship as if it were an attribute
                return $user->roles->pluck('name')->implode(', ');
            })
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();

        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $points = PointVente::all();
        return view('pages.users.create', compact('roles', 'points'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'address' => 'required|string',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'point_vente_id' => 'required'
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'point_vente_id' => $request->point_vente_id,
            'is_active' => true,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'Agent créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();

        return view('pages.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:users,phone' . $id,
            'address' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required'
        ]);

        $input = $request->all();

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function usersByPoint($pointId)
    {
        $users = User::where('point_vente_id', $pointId)->get();
        return response()->json([
            "users" => $users
        ], 200);
    }
}
