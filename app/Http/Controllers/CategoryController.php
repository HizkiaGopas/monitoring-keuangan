<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // 1. Menampilkan semua kategori milik user yang sedang login
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())
            ->orderBy('name', 'asc')
            ->get();

        return view('categories.index', compact('categories'));
    }

    // 2. Menyimpan kategori baru ke database
    public function store(Request $request)
    {
        // Validasi inputan form
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:income,expense',
        ]);

        // Simpan data dengan mengikat user_id yang sedang login
        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 3. Menghapus kategori
    public function destroy(Category $category)
    {
        // Pastikan keamanan: Cek apakah kategori ini benar milik user yang sedang login
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kategori ini.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}