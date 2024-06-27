<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index(Request $request){
        $teams = Team::latest();

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $teams = $teams->where('name', 'like', '%' . $request->get('keyword') . '%');
            }
        }

        $teams = $teams->paginate(10);

        return view("admin.teams", compact('teams'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required|max:500',
        ]);

        if ($validator->passes()){
            $team = Team::create(
                $request->only('name','description'
            ));



            session()->flash('success','Team created successfully');

            return response()->json([
                'status' => true,
                'message'=> 'Team created successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($teamId, Request $request) {
        $team = Team::find($teamId);
        if(empty($team)){
            session()->flash('error','Team not found');

            return redirect()->route('deals.index');
        }

        return response()->json(['team'=>$team]);
    }

    public function update($teamId, Request $request){
        $team = Team::find($teamId);
        if(empty($team)){
            session()->flash('error','Team not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Team not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required|max:500',

        ]);

        if ($validator->passes()) {
            $team->update(
                $request->only('name', 'description'
            ));
            $team->save();


            session()->flash("success","Team updated successfully");

            return response()->json([
                "status"=> true,
                "message"=> 'Team updated successfully'
            ]);

        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }
    }
}
