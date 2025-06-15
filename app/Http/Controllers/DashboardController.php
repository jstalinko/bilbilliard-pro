<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\BilliardTable;

class DashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        $totalStats = [
            'meja' => BilliardTable::count(),
            'members' => Member::count(),
            'product' => Product::count(),
            'transaksi' => Transaction::count()
        ];
           // Transaction Stats
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        $todayTx = Transaction::whereDate('created_at',$today)->count();
        $yesterdayTx = Transaction::whereDate('created_at',$yesterday)->count();
        $thisMonthTransactions = Transaction::whereBetween('created_at', [
            $thisMonth, 
            Carbon::now()
        ])->count();
        
        $thisMonthRevenue = Transaction::whereBetween('created_at', [
            $thisMonth, 
            Carbon::now()
        ])->sum('total_amount'); 
       
        $lastMonthTransactions = Transaction::whereBetween('created_at', [
            $lastMonth->startOfMonth(), 
            $lastMonthEnd
        ])->count();
        
        $lastMonthRevenue = Transaction::whereBetween('created_at', [
            $lastMonth->startOfMonth(), 
            $lastMonthEnd
        ])->sum('total_amount');

        // Calculate percentage comparisons
        $todayIncrease = $yesterdayTx > 0 
            ? (($todayTx - $yesterdayTx) / $yesterdayTx) * 100 
            : 0;

        $monthComparison = $lastMonthTransactions > 0 
            ? (($thisMonthTransactions - $lastMonthTransactions) / $lastMonthTransactions) * 100 
            : $thisMonthRevenue / 1 * 100;

        
        $pendingTx = Transaction::where('tx_status','pending')->count();
        $completeTx = Transaction::where('tx_status','complete')->count();
        $pendingTotal = Transaction::where('tx_status','pending')->sum('total_amount');
        $completeTotal = Transaction::where('tx_status','complete')->sum('total_amount');

        $transactionStats = [
            'today' => $todayTx,
            'todayIncrease' => round($todayIncrease, 1),
            'thisMonth' => $thisMonthTransactions,
            'thisMonthRevenue' => $thisMonthRevenue,
            'lastMonth' => $lastMonthTransactions,
            'lastMonthRevenue' => $lastMonthRevenue,
            'monthComparison' => round($monthComparison, 1),
            'pending' => $pendingTx,
            'complete' => $completeTx,
            'pendingTotal' => $pendingTotal,
            'completeTotal' => $completeTotal
        ];


        $data['totalStats'] = $totalStats;
        $data['transactionStats'] = $transactionStats;
        return Inertia::render('Dashboard',$data);
    }
}
