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
        if ($request->get('today_team_id')) {
            $todayTeamId=$request->get('team_id');

            // Get deals count created today
            $salesCountToday = Deal::where('user_id',Auth::id())->whereHas('user', function ($query) use ($todayTeamId) {
                $query->where('team_id', $todayTeamId);
            })->whereDate('created_at', $todayDate)->count();

            $salesToday = Deal::where('user_id',Auth::id())->whereHas('user', function ($query) use ($todayTeamId) {
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

        }



        $salesDateRange = $request->input('sales_daterange', date('Y-m-d') . ' - ' . date('Y-m-d'));
        $salesDates = explode(' - ', $salesDateRange);
        $startDate = Carbon::createFromFormat('Y-m-d', $salesDates[0])->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $salesDates[1])->endOfDay();

        if ($request->get('team_id')) {

            $teamId=$request->get('team_id');
            $totalSales = Deal::where('user_id',Auth::id())->whereHas('user', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            })->whereBetween('created_at', [$startDate, $endDate])->count();

            $salesThisMonth = Deal::where('user_id',Auth::id())->whereHas('user', function ($query) use ($teamId) {
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
            $totalSales = Deal::where('user_id',Auth::id())->whereBetween('created_at', [$startDate, $endDate])->count();
            $salesThisMonth = Deal::where('user_id',Auth::id())->whereBetween('created_at', [$startDate, $endDate])->withSum('payments', 'payment_value')->with('client')
            ->latest()->paginate(5);


            $salesThisMonth->getCollection()->transform(function ($deal) {
                // Calculate due_amount as deal_value - sum of payment_value
                $dueAmount = $deal->deal_value - $deal->payments_sum_payment_value;
                $deal->due_amount = $dueAmount;
                return $deal;
            });
        }



        $managerId =Auth::id();

        // Get deals associated with the manager
        $dealIds = Deal::where('user_id', $managerId)->pluck('id');

        // Fetch payments related to these deals
        $pendingPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 3)->paginate(10);
        $verifiedPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 1)->paginate(10);
        $rejectedPayments = Payment::whereIn('deal_id', $dealIds)->where('status', 0)->paginate(10);
        $payments = Payment::whereIn('deal_id', $dealIds)->paginate(10);



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




        return view('manager.dashboard', compact( 'payments', 'verifiedPayments', 'pendingPayments', 'rejectedPayments', 'data', 'salesCountToday', 'totalSales', 'salesThisMonth','salesToday'));

    }
}
