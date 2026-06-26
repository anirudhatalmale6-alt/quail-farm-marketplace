<?php

namespace App\Http\Controllers;

use App\Models\BalanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = $user->balanceTransactions()->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(20)->withQueryString();

        $totalIn = $user->balanceTransactions()->where('amount', '>', 0)->sum('amount');
        $totalOut = abs($user->balanceTransactions()->where('amount', '<', 0)->sum('amount'));

        return view('wallet.index', compact('user', 'transactions', 'totalIn', 'totalOut'));
    }
}
