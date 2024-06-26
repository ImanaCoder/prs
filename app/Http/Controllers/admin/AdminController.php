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

class AdminController extends Controller
{
    public function dashboardv1(Request $request)
    {
        // Get today's date from URL parameter or default to today
        $todayDate = $request->input('today', date('Y-m-d'));
        if ($request->get('today_team_id')) {
            $todayTeamId=$request->get('team_id');

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


            $managerId=$request->get('manager_id');

            $pendingPayments = Payment::where('user_id',$managerId)->where('status', 3)->paginate(10);
            $verifiedPayments = Payment::where('user_id',$managerId)->where('status', 1)->paginate(10);
            $rejectedPayments = Payment::where('user_id',$managerId)->where('status', 0)->paginate(10);
            $payments = Payment::where('user_id',$managerId)->paginate(10);


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


        return view('admin.dashboardv1', compact('teams', 'managers', 'payments', 'verifiedPayments', 'pendingPayments', 'rejectedPayments', 'data', 'salesCountToday', 'totalSales', 'salesThisMonth','salesToday'));
    }

    public function dashboardv2(Request $request)
    {
        $todayDate = Carbon::today()->toDateString();


        $salesToday = Deal::whereDate('created_at', $todayDate)
        ->withSum('payments', 'payment_value')
        ->with('client')
        ->latest()
        ->paginate(15);

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

        $totalSales = Deal::whereBetween('created_at', [$startDate, $endDate])->count();
        $salesThisMonth = Deal::whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
        ->latest()->paginate(15);


        $salesThisMonth->getCollection()->transform(function ($deal) {
            // Calculate due_amount as deal_value - sum of payment_value
            $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
            $deal->due_amount = $dueAmount;
            return $deal;
        });

        // return response()->json($salesThisMonth);


        if ($request->get('team_id')) {

            $teamId=$request->get('team_id');


            $salesThisMonth = Deal::whereHas('user', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            })->whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
            ->latest()->paginate(15);


            $salesThisMonth->getCollection()->transform(function ($deal) {
                // Calculate due_amount as deal_value - sum of payment_value
                $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
                $deal->due_amount = $dueAmount;
                return $deal;
            });

        }

        if ($request->get('manager_id')) {

            $userId=$request->get('manager_id');

            $salesThisMonth = Deal::where('user_id',$userId)->whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
            ->latest()->paginate(15);


            $salesThisMonth->getCollection()->transform(function ($deal) {
                // Calculate due_amount as deal_value - sum of payment_value
                $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
                $deal->due_amount = $dueAmount;
                return $deal;
            });

        }

        $teams = Team::get();
        $managers = User::role(3)->get();


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
