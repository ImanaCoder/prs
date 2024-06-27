<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\SourceType;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboardv1(Request $request)
    {
        // Get today's date from URL parameter or default to today
        $todayDate = $request->input('today', date('Y-m-d'));
        if ($request->get('today_team_id')) {
            $todayTeamId=$request->get('today_team_id');

            // Get deals count created today
            $salesCountToday = Deal::whereHas('user', function ($query) use ($todayTeamId) {
                $query->where('team_id', $todayTeamId);
            })->whereDate('created_at', $todayDate)->count();

            $salesToday = Deal::whereHas('user', function ($query) use ($todayTeamId) {
                $query->where('team_id', $todayTeamId);
            })->whereDate('created_at', $todayDate)
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

        }else{
            // Get deals count created today
            $salesCountToday = Deal::whereDate('created_at', $todayDate)->count();
            $salesToday = Deal::whereDate('created_at', $todayDate)
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

        }



        $salesDateRange = $request->input('sales_daterange', date('Y-m-d') . ' - ' . date('Y-m-d'));
        $salesDates = explode(' - ', $salesDateRange);
        $startDate = Carbon::createFromFormat('Y-m-d', $salesDates[0])->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $salesDates[1])->endOfDay();

        if ($request->get('team_id')) {

            $teamId=$request->get('team_id');
            $totalSales = Deal::whereHas('user', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            })->whereBetween('created_at', [$startDate, $endDate])->count();

            $salesThisMonth = Deal::whereHas('user', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            })->whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
            ->latest()->paginate(5);


            $salesThisMonth->getCollection()->transform(function ($deal) {
                // Calculate due_amount as deal_value - sum of payment_value
                $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
                $deal->due_amount = $dueAmount;
                return $deal;
            });

        }else{
            $totalSales = Deal::whereBetween('created_at', [$startDate, $endDate])->count();
            $salesThisMonth = Deal::whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
            ->latest()->paginate(5);


            $salesThisMonth->getCollection()->transform(function ($deal) {
                // Calculate due_amount as deal_value - sum of payment_value
                $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
                $deal->due_amount = $dueAmount;
                return $deal;
            });
        }

        if ($request->get('manager_id')) {


            $managerId = $request->get('manager_id');

            // Get deals associated with the manager
            $dealIds = Deal::where('user_id', $managerId)->pluck('id');

            // Fetch payments related to these deals
            $pendingPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 3)->orderBy('payment_date')->paginate(5);
            $verifiedPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 1)->orderBy('payment_date')->paginate(5);
            $rejectedPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 0)->orderBy('payment_date')->paginate(5);
            $payments = Payment::whereIn('deal_id', $dealIds)->orderBy('payment_date')->paginate(5);



            // Get deal count between additionalStartDate and additionalEndDate
            $dealsCountAdditional = Deal::where('user_id',$managerId)->count();

            // Get source types with deals count created between additionalStartDate and additionalEndDate
            $sourceTypes = SourceType::withCount(['deals' => function ($query) use ($managerId) {
                $query->where('user_id',$managerId);
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
        }else{
            // Populate $additionalData as needed, e.g., for a chart


            $pendingPayments = Payment::where('status', 3)->orderBy('payment_date')->paginate(5);
            $verifiedPayments = Payment::where('status', 1)->orderBy('payment_date')->paginate(5);
            $rejectedPayments = Payment::where('status', 0)->orderBy('payment_date')->paginate(5);
            $payments = Payment::paginate(5);


            // Get deal count between additionalStartDate and additionalEndDate
            $dealsCountAdditional = Deal::count();

            // Get source types with deals count created between additionalStartDate and additionalEndDate
            $sourceTypes = SourceType::withCount('deals')->get();

            // Calculate percentage of deals for each source type
            $data = [['SourceType', 'Used']];

            if($dealsCountAdditional > 0 ){
                foreach ($sourceTypes as $sourceType) {
                    // Calculate percentage
                    $percentage = ($sourceType->deals_count / $dealsCountAdditional) * 100;
                    $data[] = [$sourceType->name, $percentage];
                }
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


        if ($request->get('manager_id')) {


            $managerId = $request->get('manager_id');

            // Query to get count of deals in each date range for IP and FP work types
            $v1CountIP = Deal::where('user_id',$managerId)
                    ->where('work_type', 'IP')
                    ->whereBetween('created_at', [$v1StartDate, $v1EndDate])
                    ->count();

            $v2CountIP = Deal::where('user_id',$managerId)
                    ->where('work_type', 'IP')
                    ->whereBetween('created_at', [$v2StartDate, $v2EndDate])
                    ->count();

            $v1CountFP = Deal::where('user_id',$managerId)
                    ->where('work_type', 'FP')
                    ->whereBetween('created_at', [$v1StartDate, $v1EndDate])
                    ->count();

            $v2CountFP = Deal::where('user_id',$managerId)
                    ->where('work_type', 'FP')
                    ->whereBetween('created_at', [$v2StartDate, $v2EndDate])
                    ->count();
        }else{

            // Query to get count of deals in each date range for IP and FP work types
            $v1CountIP = Deal::where('work_type', 'IP')
                ->whereBetween('created_at', [$v1StartDate, $v1EndDate])
                ->count();

            $v2CountIP = Deal::where('work_type', 'IP')
                    ->whereBetween('created_at', [$v2StartDate, $v2EndDate])
                    ->count();

            $v1CountFP = Deal::where('work_type', 'FP')
                    ->whereBetween('created_at', [$v1StartDate, $v1EndDate])
                    ->count();

            $v2CountFP = Deal::where('work_type', 'FP')
                    ->whereBetween('created_at', [$v2StartDate, $v2EndDate])
                    ->count();

        }



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


        $teams = Team::get();
        $managers = User::role(3)->get();


        return view('admin.dashboardv1', compact('teams', 'managers', 'payments', 'verifiedPayments', 'pendingPayments', 'rejectedPayments', 'data', 'salesCountToday', 'totalSales', 'salesThisMonth','salesToday','jsonList'));
    }

    public function dashboardv2(Request $request)
    {
        $todayDate = Carbon::today()->toDateString();


        $salesToday = Deal::whereDate('created_at', $todayDate)
        ->withSum('payments', 'payment_value')
        ->with('client')
        ->latest();


        $salesDateRange = $request->input('sales_daterange', date('Y-m-d') . ' - ' . date('Y-m-d'));
        $salesDates = explode(' - ', $salesDateRange);
        $startDate = Carbon::createFromFormat('Y-m-d', $salesDates[0])->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $salesDates[1])->endOfDay();

        $salesThisMonth = Deal::whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
        ->latest();


        // return response()->json($salesThisMonth);


        if ($request->get('team_id')) {

            $teamId=$request->get('team_id');
            $managers = User::role(3)->where('team_id',$teamId)->get();


            $salesThisMonth = $salesThisMonth->whereHas('user', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            });

            $salesToday = $salesThisMonth->whereHas('user', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            });
        }else{
            $managers = User::role(3)->get();
        }

        if ($request->get('manager_id')) {
            $userId=$request->get('manager_id');

            $salesThisMonth = $salesThisMonth->where('user_id',$userId);

            $salesToday = $salesThisMonth->where('user_id',$userId);
        }

        $teams = Team::get();


        $salesToday= $salesToday->paginate(10);
        $salesThisMonth= $salesThisMonth->paginate(10);
        $salesToday->getCollection()->transform(function ($deal) {
            // Calculate due_amount as deal_value - sum of payment_value
            $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
            $deal->due_amount = $dueAmount;
            // Determine due_status based on due_amount
            if ($dueAmount < 0) {
                $deal->due_status = 2; // Negative due_amount
            } elseif ($dueAmount == 0) {
                $deal->due_status = 1; // Zero due_amount
            } else {
                $deal->due_status = 0; // Positive due_amount
            }
            return $deal;
        });

        $salesThisMonth->getCollection()->transform(function ($deal) {
            // Calculate due_amount as deal_value - sum of payment_value
            $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
            $deal->due_amount = $dueAmount;
            // Determine due_status based on due_amount
            if ($dueAmount < 0) {
                $deal->due_status = 2; // Negative due_amount
            } elseif ($dueAmount == 0) {
                $deal->due_status = 1; // Zero due_amount
            } else {
                $deal->due_status = 0; // Positive due_amount
            }
            return $deal;
        });





        return view('admin.dashboardv2', compact('salesToday','salesThisMonth','teams','managers'));
    }

    public function dashboardv3(Request $request)
    {
        // Get today's date from URL parameter or default to today
        $todayDate = $request->input('today', date('Y-m-d'));

        // Get deals count created today
        $salesCountToday = Deal::whereDate('created_at', $todayDate)->count();
        $salesToday = Deal::whereDate('created_at', $todayDate)
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

        // Get total number of sales within sales_daterange

        $salesDateRange = $request->input('sales_daterange', date('Y-m-d') . ' - ' . date('Y-m-d'));
        $salesDates = explode(' - ', $salesDateRange);
        $startDate = Carbon::createFromFormat('Y-m-d', $salesDates[0])->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $salesDates[1])->endOfDay();

        $totalSales = Deal::whereBetween('created_at', [$startDate, $endDate])->count();
        $salesThisMonth = Deal::whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
        ->latest()->paginate(5);


        $salesThisMonth->getCollection()->transform(function ($deal) {
            // Calculate due_amount as deal_value - sum of payment_value
            $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
            $deal->due_amount = $dueAmount;
            return $deal;
        });

        if ($request->get('additional_daterange')) {

            // Get additional data for the chart using additional_daterange

            $additionalDateRange = $request->input('additional_daterange', date('Y-m-d') . ' - ' . date('Y-m-d'));
            $additionalDates = explode(' - ', $additionalDateRange);
            $additionalStartDate = Carbon::createFromFormat('Y-m-d', $additionalDates[0])->startOfDay();
            $additionalEndDate = Carbon::createFromFormat('Y-m-d', $additionalDates[1])->endOfDay();

            $additionalData = [];

            // return response()->json($additionalStartDate);


            // Populate $additionalData as needed, e.g., for a chart


            $pendingPayments = Payment::whereBetween('created_at', [$additionalStartDate, $additionalEndDate])->where('status', 3)->paginate(10);
            $verifiedPayments = Payment::whereBetween('created_at', [$additionalStartDate, $additionalEndDate])->where('status', 1)->paginate(10);
            $rejectedPayments = Payment::whereBetween('created_at', [$additionalStartDate, $additionalEndDate])->where('status', 0)->paginate(10);
            $payments = Payment::whereBetween('created_at', [$additionalStartDate, $additionalEndDate])->paginate(10);


            // Get deal count between additionalStartDate and additionalEndDate
            $dealsCountAdditional = Deal::whereBetween('created_at', [$additionalStartDate, $additionalEndDate])->count();

            // Get source types with deals count created between additionalStartDate and additionalEndDate
            $sourceTypes = SourceType::withCount(['deals' => function ($query) use ($additionalStartDate, $additionalEndDate) {
                $query->whereBetween('created_at', [$additionalStartDate, $additionalEndDate]);
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
        }else{
            // Populate $additionalData as needed, e.g., for a chart


            $pendingPayments = Payment::where('status', 3)->paginate(10);
            $verifiedPayments = Payment::where('status', 1)->paginate(10);
            $rejectedPayments = Payment::where('status', 0)->paginate(10);
            $payments = Payment::paginate(10);


            // Get deal count between additionalStartDate and additionalEndDate
            $dealsCountAdditional = Deal::count();

            // Get source types with deals count created between additionalStartDate and additionalEndDate
            $sourceTypes = SourceType::withCount('deals')->get();

            // Calculate percentage of deals for each source type
            $data = [['SourceType', 'Used']];

            if($dealsCountAdditional > 0 ){
                foreach ($sourceTypes as $sourceType) {
                    // Calculate percentage
                    $percentage = ($sourceType->deals_count / $dealsCountAdditional) * 100;
                    $data[] = [$sourceType->name, $percentage];
                }
            }
        }

        $teams = Team::get();
        $managers = User::role(3)->get();


        return view('admin.dashboardv3', compact('teams', 'managers', 'payments', 'verifiedPayments', 'pendingPayments', 'rejectedPayments', 'data', 'salesCountToday', 'totalSales', 'salesThisMonth','salesToday'));
    }

}
