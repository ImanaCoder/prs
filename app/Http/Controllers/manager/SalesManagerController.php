<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\SourceType;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesManagerController extends Controller
{
    public function index(Request $request){
        // Get today's date from URL parameter or default to today
        $todayDate = $request->input('today', date('Y-m-d'));

        // Get deals count created today
        $salesCountToday = Deal::where('user_id',Auth::id())->whereDate('created_at', $todayDate)->count();
        $salesToday = Deal::where('user_id',Auth::id())->whereDate('created_at', $todayDate)
        ->withSum('payments', 'payment_value')
        ->with('client')
        ->latest()
        ->paginate(5);

        $salesToday->getCollection()->transform(function ($deal) {
            // Calculate due_amount as deal_value - sum of payment_value
            $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
            $deal->due_amount = $dueAmount;
            return $deal;
        });



        $salesDateRange = $request->input('sales_daterange', date('Y-m-d') . ' - ' . date('Y-m-d'));
        $salesDates = explode(' - ', $salesDateRange);
        $startDate = Carbon::createFromFormat('Y-m-d', $salesDates[0])->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $salesDates[1])->endOfDay();



        $totalSales = Deal::where('user_id',Auth::id())->whereBetween('created_at', [$startDate, $endDate])->count();
        $salesThisMonth = Deal::where('user_id',Auth::id())->whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
        ->latest()->paginate(5);


        $salesThisMonth->getCollection()->transform(function ($deal) {
            // Calculate due_amount as deal_value - sum of payment_value
            $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
            $deal->due_amount = $dueAmount;
            return $deal;
        });

        $managerId =Auth::id();

        // Get deals associated with the manager
        $dealIds = Deal::where('user_id', $managerId)->pluck('id');

        // Fetch payments related to these deals
        $pendingPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 3)->latest()->paginate(5);
        $verifiedPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 1)->latest()->paginate(5);
        $rejectedPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 0)->latest()->paginate(5);
        $payments = Payment::whereIn('deal_id', $dealIds)->latest()->paginate(5);



        // Get deal count between additionalStartDate and additionalEndDate
        $dealsCountAdditional = Deal::where('user_id',Auth::id())->where('user_id',$managerId)->count();

        // Get source types with deals count created between additionalStartDate and additionalEndDate
        $sourceTypes = SourceType::withCount(['deals' => function ($query) use ($managerId) {
            $query->where('user_id',Auth::id());
        }])->get();

        // Calculate percentage of deals for each source type
        $data = [['SourceType', 'Used']];

        if($dealsCountAdditional > 0 ){
            foreach ($sourceTypes as $sourceType) {
                // Calculate percentage
                $percentage = ($sourceType->deals_count / $dealsCountAdditional) * 100;
                $data[] = [$sourceType->name, $percentage];
            }
        }

        // Parse date ranges from query string or default to today
        if ($request->filled('top_works_daterange_v1')) {
            $v1DateRange = explode(' - ', $request->input('top_works_daterange_v1'));
            $v1StartDate = Carbon::parse(trim($v1DateRange[0]))->startOfDay();
            $v1EndDate = Carbon::parse(trim($v1DateRange[1]))->endOfDay();
        } else {
            $v1StartDate = Carbon::today()->startOfDay();
            $v1EndDate = Carbon::today()->endOfDay();
        }

        if ($request->filled('top_works_daterange_v2')) {
            $v2DateRange = explode(' - ', $request->input('top_works_daterange_v2'));
            $v2StartDate = Carbon::parse(trim($v2DateRange[0]))->startOfDay();
            $v2EndDate = Carbon::parse(trim($v2DateRange[1]))->endOfDay();
        } else {
            $v2StartDate = Carbon::today()->startOfDay();
            $v2EndDate = Carbon::today()->endOfDay();
        }
        // Query to get count of deals in each date range for IP and FP work types
        $v1CountIP = Deal::where('user_id', Auth::id())
                        ->where('work_type', 'IP')
                        ->whereBetween('created_at', [$v1StartDate, $v1EndDate])
                        ->count();

        $v2CountIP = Deal::where('user_id', Auth::id())
                        ->where('work_type', 'IP')
                        ->whereBetween('created_at', [$v2StartDate, $v2EndDate])
                        ->count();

        $v1CountFP = Deal::where('user_id', Auth::id())
                        ->where('work_type', 'FP')
                        ->whereBetween('created_at', [$v1StartDate, $v1EndDate])
                        ->count();

        $v2CountFP = Deal::where('user_id', Auth::id())
                        ->where('work_type', 'FP')
                        ->whereBetween('created_at', [$v2StartDate, $v2EndDate])
                        ->count();

        // Calculate increase or decrease and percentage change for IP and FP
        $increaseOrDecreaseIP = $v1CountIP - $v2CountIP;
        $percentageIP = ($v2CountIP != 0) ? (($v1CountIP - $v2CountIP) / abs($v2CountIP)) * 100 : ($v1CountIP != 0 ? 100 : 0);

        $increaseOrDecreaseFP = $v1CountFP - $v2CountFP;
        $percentageFP = ($v2CountFP != 0) ? (($v1CountFP - $v2CountFP) / abs($v2CountFP)) * 100 : ($v1CountFP != 0 ? 100 : 0);

        // Create JSON arrays for IP and FP
        $ipData = [

                'name' => 'IP',
                'v1Count' => $v1CountIP,
                'v2Count' => $v2CountIP,
                'increase_or_decrease'=>$increaseOrDecreaseIP,
                'percentage'=>$percentageIP

        ];

        $fpData = [

                'name' => 'FP',
                'v1Count' => $v1CountFP,
                'v2Count' => $v2CountFP,
                'increase_or_decrease'=>$increaseOrDecreaseFP,
                'percentage'=>$percentageFP

        ];

        // Combine IP and FP arrays into a single list
        $jsonList = [
            $ipData,
            $fpData
        ];


        // return response()->json($jsonList);




        return view('manager.dashboard', compact( 'payments', 'verifiedPayments', 'pendingPayments', 'rejectedPayments', 'data', 'salesCountToday', 'totalSales', 'salesThisMonth','salesToday','jsonList'));

    }
}
