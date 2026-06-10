<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Menampilkan daftar transaksi milik user
    public function index()
    {
        // Ambil transaksi milik user yang login, di-join dengan kategorinya
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil juga semua kategori milik user untuk keperluan drop-down di form input
        $categories = Category::where('user_id', Auth::id())->orderBy('name', 'asc')->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id', // Wajib pilih kategori agar sistem tahu jenisnya
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // Ambil data kategori untuk mendeteksi secara otomatis apakah ini Pemasukan atau Pengeluaran
        $category = Category::findOrFail($request->category_id);

        Transaction::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'type' => $category->type, // Otomatis mengisi 'income' atau 'expense' dari kategori
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat!');
    }
    // Menghapus transaksi
    public function destroy(Transaction $transaction)
    {
        // Proteksi keamanan isolasi data
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}