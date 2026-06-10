<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Hitung Total Pemasukan
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        // 2. Hitung Total Pengeluaran
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        // 3. Hitung Sisa Saldo
        $balance = $totalIncome - $totalExpense;

        // 4. Ambil Data Pengeluaran per Kategori untuk Grafik (Chart)
        $expenseByCategory = Transaction::select('categories.name as category_name', DB::raw('SUM(transactions.amount) as total'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('transactions.type', 'expense')
            ->groupBy('categories.name')
            ->get();

        // Siapkan data array untuk dibaca oleh Chart.js di frontend
        $chartLabels = $expenseByCategory->pluck('category_name')->toArray();
        $chartData = $expenseByCategory->pluck('total')->toArray();

        return view('dashboard', compact('totalIncome', 'totalExpense', 'balance', 'chartLabels', 'chartData'));
    }
}