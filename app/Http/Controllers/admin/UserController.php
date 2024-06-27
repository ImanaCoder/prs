<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::latest();
        $roles = Role::get();
        $teams = Team::get();

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%');
            }
        }

        $users = $users->paginate(10);

        return view("admin.users", compact('users','roles','teams'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password'=>'required|confirmed',
            'role_id'=>'required|exists:roles,id',
            'team_id' => $request->role_id == 3 ? 'required|exists:teams,id' : 'nullable',

        ]);

        if ($validator->passes()){
            $user = User::create(
                $request->only('name','email','password','team_id'
            ));

            // Assigning role based on role_id from request
            $role_id = $request->input('role_id'); // Assuming 'role_id' is passed in the request

            if ($role_id) {
                $role = Role::findOrFail($role_id); // Retrieve role by ID from Spatie roles table

                if ($role) {
                    $user->assignRole($role->name); // Assign role to the user
                }
            }


            session()->flash('success','User created successfully');

            return response()->json([
                'status' => true,
                'message'=> 'User created successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($userId, Request $request) {
        $user = User::with('roles')->with('team')->find($userId);
        if(empty($user)){
            session()->flash('error','Team not found');

            return redirect()->route('deals.index');
        }

        return response()->json(['user'=>$user]);
    }

    public function getUsersByTeamId($teamId, Request $request) {
        $users = User::where('team_id',$teamId)->orderBy('name')->get();


        return response()->json(['user'=>$users]);
    }
}
