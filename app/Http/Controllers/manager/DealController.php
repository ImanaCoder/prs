<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\SourceType;
use App\Models\Team;
use App\Models\TempImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    public function getDealsForVerifier(Request $request){
        $deals = Deal::with('payments','user','client')->latest();
        // return response()->json([$deals]);

        $clients = Client::get();
        $managers = User::role(3)->get();

        $teams = Team::get();
        $sourceTypes =  SourceType::get();

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $deals = $deals->where('name', 'like', '%' . $request->get('keyword') . '%');
            }
        }

        if ($request->get('team_id')) {
            if (!empty($request->get('team_id'))) {
                // Assuming $request->get('team_id') contains the team ID you want to filter by
                $teamId = $request->get('team_id');
                $deals = $deals->whereHas('user', function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                });
            }
        }

        if ($request->get('client_id')) {
            if (!empty($request->get('client_id'))) {
                $deals = $deals->where('client_id',$request->get('client_id'));
            }
        }

        if ($request->get('manager_id')) {
            if (!empty($request->get('manager_id'))) {
                $deals = $deals->where('user_id',$request->get('manager_id'));
            }
        }

        $deals = $deals->paginate(10);

        return view("verifier.deals", compact('deals','teams','clients','sourceTypes','managers'));
    }

    public function getDealsForAdmin(Request $request){
        $deals = Deal::with('payments','user','client')->latest();
        // return response()->json([$deals]);

        $clients = Client::get();
        $managers = User::role(3)->get();

        $teams = Team::get();
        $sourceTypes =  SourceType::get();

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $deals = $deals->where('name', 'like', '%' . $request->get('keyword') . '%');
            }
        }

        if ($request->get('team_id')) {
            if (!empty($request->get('team_id'))) {
                // Assuming $request->get('team_id') contains the team ID you want to filter by
                $teamId = $request->get('team_id');
                $deals = $deals->whereHas('user', function ($query) use ($teamId) {
                    $query->where('team_id', $teamId);
                });
            }
        }

        if ($request->get('client_id')) {
            if (!empty($request->get('client_id'))) {
                $deals = $deals->where('client_id',$request->get('client_id'));
            }
        }

        if ($request->get('manager_id')) {
            if (!empty($request->get('manager_id'))) {
                $deals = $deals->where('user_id',$request->get('manager_id'));
            }
        }

        $deals = $deals->paginate(10);

        return view("admin.deals", compact('deals','teams','clients','sourceTypes','managers'));
    }

    public function index(Request $request){
        $deals = Deal::with('payments')->where('user_id',Auth::id())->latest();
        $clients = Client::get();
        $sourceTypes =  SourceType::get();

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $deals = $deals->where('name', 'like', '%' . $request->get('keyword') . '%');
            }
        }



        $deals = $deals->paginate(10);

        return view("manager.deals.list", compact('deals','clients','sourceTypes'));
    }


    public function store(Request $request){
        if($request->payment_date != null || $request->payment_value != null || $request->receipt_id != null ){
            $validator = Validator::make($request->all(), [

                'client_id'=> 'required|exists:clients,id',
                'name' => 'required',
                'work_type' => 'required',
                'source_type_id' => 'required|exists:source_types,id',
                'deal_value'=>'required|numeric',
                'deal_date'=>'required|date',
                'due_date'=>'required|date',
                'remarks' => 'required',
                'receipt_id' => 'required',
                'payment_date' => 'required|date',
                'payment_value' => 'required|numeric',
                'payment_remarks' => 'required'
                ]);

                if ($validator->passes()){
                    // Retrieve the authenticated user's ID
                    $userId = Auth::id();

                    // Create a new Deal instance with user_id included
                    $deal = Deal::create([
                        'client_id' => $request->input('client_id'),
                        'name' => $request->input('name'),
                        'work_type' => $request->input('work_type'),
                        'remarks' => $request->input('remarks'),
                        'source_type_id' => $request->input('source_type_id'),
                        'deal_value' => $request->input('deal_value'),
                        'deal_date' => $request->input('deal_date'),
                        'due_date' => $request->input('due_date'),
                        'user_id' => $userId, // Assigning the authenticated user's ID
                    ]);

                    $payment = new Payment();
                    $payment->payment_date = $request->payment_date;
                    $payment->payment_value = $request->payment_value;
                    $payment->remarks = $request->payment_remarks;
                    $payment->deal_id = $deal->id;
                    $payment->save();

                    //Save Image Here
                    if(!empty($request->receipt_id)){
                        $tempImage = TempImage::find($request->receipt_id);

                        $extArray = explode('.',$tempImage->name);
                        $ext = last($extArray);

                        $newImageName = $payment->id.'.'.$ext;

                        $sPath = public_path() .'/temp/thumb/'. $tempImage->name;
                        $dPath = 'payments/' . $newImageName;
                        File::copy($sPath, public_path('/storage/' . $dPath)); // Copy file to storage

                        // Move the files within the storage disk
                        Storage::move($dPath, $dPath); // Move original image


                        $payment->receipt_path = $newImageName;
                        $payment->save();
                    }
                    session()->flash('success','Deal created successfully');

                    return response()->json([
                        'status' => true,
                        'message'=> 'Deal created successfully.'
                    ]);
                }else{
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                    ]);
                }
        }else{
            $validator = Validator::make($request->all(), [
                'client_id'=> 'required|exists:clients,id',
                'name' => 'required',
                'work_type' => 'required',
                'source_type_id' => 'required|exists:source_types,id',
                'deal_value'=>'required|numeric',
                'deal_date'=>'required|date',
                'due_date'=>'required|date',
                'remarks' => 'required'
            ]);

            if ($validator->passes()){
                $deal = Deal::create(
                    $request->only('client_id', 'name', 'work_type', 'remarks',
                    'source_type_id', 'deal_value', 'deal_date','due_date'
                ));

                session()->flash('success','Deal created successfully');

                return response()->json([
                    'status' => true,
                    'message'=> 'Deal created successfully.'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                ]);
            }
        }



    }

    public function edit($dealId, Request $request) {
        $deal = Deal::find($dealId);
        if(empty($deal)){
            session()->flash('error','Deal not found');

            return redirect()->route('deals.index');
        }

        return response()->json(['deal'=>$deal]);
    }

    public function update($dealId, Request $request){
        $deal = Deal::find($dealId);
        if(empty($deal)){
            session()->flash('error','Deal not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Deal not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'client_id'=> 'required|exists:clients,id',
            'name' => 'required',
            'work_type' => 'required',
            'source_type_id' => 'required|exists:source_types,id',
            'deal_value'=>'required|numeric',
            'deal_date'=>'required|date',
            'due_date'=>'required|date',
            'remarks' => 'required'
        ]);

        if ($validator->passes()) {
            $deal->update(
                $request->only('client_id', 'name', 'work_type', 'remarks',
                'source_type_id', 'deal_value', 'deal_date','due_date'
            ));
            $deal->deal_version='Edited';
            $deal->save();

            session()->flash("success","Deal updated successfully");

            return response()->json([
                "status"=> true,
                "message"=> 'Deal updated successfully'
            ]);

        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }
    }

    public function destroy($dealId, Request $request){
        $deal =  Deal::find($dealId);
        if(empty($deal)){
            session()->flash('error','Deal not found');
            return response()->json([
                'status' => false,
                'message' => 'Deal not found'
            ]);
        }

        $deal->status=0;
        $deal->save();

        session()->flash('success','Deal deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Deal deleted successfully'
        ]);

    }
}
