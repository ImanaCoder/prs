<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\SourceType;
use App\Models\Team;
use Illuminate\Http\Request;

class SalesManagerController extends Controller
{
    public function index(){
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
}
