<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 8 produk terbaru untuk ditampilkan di homepage
        $products = Product::where('is_available', true)
            ->latest()
            ->take(8)
            ->with('category')
            ->get();

        // Ambil semua kategori
        $categories = Category::withCount('products')->get();

        return view('home', compact('products', 'categories'));
    }
}
