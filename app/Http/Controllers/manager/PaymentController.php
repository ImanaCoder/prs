<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
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

        if ($request->get('keyword')) {
            if (!empty($request->get('keyword'))) {
                $payments = $payments->where('remarks', 'like', '%' . $request->get('keyword') . '%');
            }
        }

        if ($request->get('deal')) {
            if (!empty($request->get('deal'))) {
                $payments = $payments->where('deal.name', 'like', '%' . $request->get('keyword') . '%');
            }
        }


        $payments = $payments->paginate(10);

        return view("verifier.payments", compact('payments'));
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

    public function edit($paymentId, Request $request) {
        $payment = Payment::find($paymentId);
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
            'receipt_id' => 'required',
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
                'status' => false,
                'message' => 'Payment not found'
            ]);
        }

        $payment->status=0;
        $payment->save();

        session()->flash('success','Payment deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Payment deleted successfully'
        ]);

    }

    public function verify($paymentId, Request $request){
        if(Auth::check()){
            session()->flash('error','You are not authorized');
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized'
            ]);
        }
        $payment =  Payment::find($paymentId);
        if(empty($payment)){
            session()->flash('error','Payment not found');
            return response()->json([
                'status' => false,
                'message' => 'Payment not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'verification_remarks'=> 'required|max:255',
            'status' => 'required',
            'verification_receipt_id' => 'required',

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
}
