<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index(Request $request){
        $clients = Client::where('user_id',Auth::id())->latest();

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $clients = $clients->where('name', 'like', '%' . $request->get('keyword') . '%');
            }
        }

        $clients = $clients->paginate(10);

        return view("manager.clients.list", compact('clients'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'nationality' => 'required'
        ]);

        if ($validator->passes()){
            $client = Client::create(
                $request->only('name', 'email', 'contact', 'nationality','user_id'
            ));



            session()->flash('success','Client created successfully');

            return response()->json([
                'status' => true,
                'message'=> 'Client created successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($clientId, Request $request) {
        $client = Client::find($clientId);
        if(empty($client)){
            session()->flash('error','Client not found');

            return redirect()->route('deals.index');
        }

        return response()->json(['client'=>$client]);
    }

    public function update($clientId, Request $request){
        $client = Client::find($clientId);
        if(empty($client)){
            session()->flash('error','Client not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Client not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'nationality' => 'required'
        ]);

        if ($validator->passes()) {
            $client->update(
                $request->only('name', 'email', 'contact', 'nationality'
            ));
            $client->save();


            session()->flash("success","Client updated successfully");

            return response()->json([
                "status"=> true,
                "message"=> 'Client updated successfully'
            ]);

        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }
    }


}
