<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\TempImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(Request $request){
        $payments = Payment::with('deal')->latest();
        $deals=Deal::get();
        $clients= Client::get();

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $payments = $payments->where('remarks', 'like', '%' . $request->get('keyword') . '%');
            }
        }

        $dealId=$request->get('deal_id');

        if ($request->get('client_id')) {
            if (!empty($request->get('client_id'))) {
                // Assuming $request->get('team_id') contains the team ID you want to filter by
                $clientId = $request->get('client_id');
                $payments = $payments->whereHas('deal', function ($query) use ($clientId) {
                    $query->where('client_id', $clientId);
                });
            }

            $deals=Deal::where('client_id',$request->get('client_id'))->get();
            $dealIds = $deals->pluck('id')->toArray(); // Extracting deal ids from $deals collection

            if (!in_array($dealId, $dealIds)) {
                $dealId = null;
            }
        }
        if ( $dealId) {
            if (!empty( $dealId)) {
                $payments = $payments->where('deal_id', $dealId);
            }
        }



        if ($request->get('verification_status')) {
            if (!empty($request->get('verification_status'))) {
                // Assuming $request->get('team_id') contains the team ID you want to filter by
                $verificationStatus = $request->get('verification_status');
                switch ($verificationStatus) {
                    case 'denied':
                        $payments = $payments->where('status', 0);

                        break;
                    case 'verified':
                        $payments = $payments->where('status', 1);

                        break;
                    case 'pending':
                        $payments = $payments->where('status', 3);

                        break;
                    default:
                        // Handle other cases if needed
                        break;
                }

            }
        }


        $payments = $payments->paginate(10);

        return view("verifier.payments", compact('payments','clients','deals'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'deal_id'=> 'required|exists:deals,id',
            'receipt_id' => 'required',
            'payment_date' => 'required|date',
            'payment_value' => 'required|numeric',
            'remarks' => 'required'
        ]);

        if ($validator->passes()){
            $payment = Payment::create(
                $request->only('deal_id', 'payment_date', 'payment_value', 'remarks'
            ));

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

            session()->flash('success','Payment created successfully');

            return response()->json([
                'status' => true,
                'message'=> 'Payment created successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    public function paymentDetails($paymentId, Request $request) {
        $payment = Payment::find($paymentId);
        if(empty($payment)){
            session()->flash('error','Payment not found');

            return redirect()->route('deals.index');
        }

        return view('payment_detail',compact('payment'));
    }

    public function edit($paymentId, Request $request) {
        $payment = Payment::with('verified_by')->find($paymentId);
        if(empty($payment)){
            session()->flash('error','Payment not found');

            return redirect()->route('deals.index');
        }

        return response()->json(['payment'=>$payment]);
    }

    public function update($paymentId, Request $request){
        $payment = Payment::find($paymentId);
        if(empty($payment)){
            session()->flash('error','Payment not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Payment not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'deal_id'=> 'required|exists:deals,id',
            'receipt_id' => 'nullable',
            'payment_date' => 'required|date',
            'payment_value' => 'required|numeric',
            'remarks' => 'required'
        ]);

        if ($validator->passes()) {
            $payment->update(
                $request->only('deal_id', 'payment_date', 'payment_value', 'remarks'
            ));
            $payment->payment_version='Edited';
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

            session()->flash("success","Payment updated successfully");

            return response()->json([
                "status"=> true,
                "message"=> 'Payment updated successfully'
            ]);

        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }
    }

    public function destroy($paymentId, Request $request){
        $payment =  Payment::find($paymentId);
        if(empty($payment)){
            session()->flash('error','Payment not found');
            return response()->json([
                'status' => true,
                'message' => 'Payment not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'payment_delete' => 'required|in:DELETE'

        ]);

            if ($validator->passes()) {

            $payment->delete();

            session()->flash('success','Payment deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Payment deleted successfully'
            ]);
        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }


    }

    public function verify($paymentId, Request $request){

        $payment =  Payment::find($paymentId);
        if(empty($payment)){
            session()->flash('error','Payment not found');
            return response()->json([
                'status' => false,
                'message' => 'Payment not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'verification_remarks' => 'required|max:255',
            'status' => 'required',
            'verification_receipt_id' => 'required_if:status,1', // validation rule for verification_receipt_id
        ]);

        if ($validator->passes()) {

            $payment->verified_by_id=Auth::id();
            $payment->verified_at = Carbon::now();
            $payment->verification_remarks = $request->verification_remarks;
            $payment->save();

            if(!empty($request->verification_receipt_id)){
                $tempImage = TempImage::find($request->verification_receipt_id);

                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $payment->id.'.'.$ext;

                $sPath = public_path() .'/temp/thumb/'. $tempImage->name;
                $dPath = 'payments/verification-receipts/' . $newImageName;

                File::copy($sPath, public_path('/storage/' . $dPath)); // Copy file to storage

                // Move the files within the storage disk
                Storage::move($dPath, $dPath); // Move original image


                $payment->verification_receipt_path = $newImageName;
                $payment->save();
            }

            if($request->input('status') == 1 || $request->input('status')=='1'){
                $payment->status=1;

                session()->flash('success','Payment verified successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Payment verified successfully'
                ]);
            }else{
                $payment->status=0;

                session()->flash('success','Payment denied successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Payment denied successfully'
                ]);
            }

        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }

    }

}
