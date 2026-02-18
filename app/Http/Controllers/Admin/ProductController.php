<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan daftar produk
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_available', $request->status);
        }

        $products = $query->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    // Form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'required|integer|min:0',
            'price_6_hours' => 'required|numeric|min:0',
            'price_12_hours' => 'required|numeric|min:0',
            'price_24_hours' => 'required|numeric|min:0',
            'price_1_5_days' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = $filename;
        }

        // Konversi specifications ke JSON
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $specs = [];
            foreach ($request->spec_keys as $index => $key) {
                if (!empty($key) && !empty($request->spec_values[$index])) {
                    $specs[$key] = $request->spec_values[$index];
                }
            }
            $data['specifications'] = json_encode($specs);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    // Tampilkan detail produk (ADMIN)
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    // Form edit produk
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stock' => 'required|integer|min:0',
            'price_6_hours' => 'required|numeric|min:0',
            'price_12_hours' => 'required|numeric|min:0',
            'price_24_hours' => 'required|numeric|min:0',
            'price_1_5_days' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
                unlink(public_path('uploads/products/' . $product->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = $filename;
        }

        // Konversi specifications ke JSON
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $specs = [];
            foreach ($request->spec_keys as $index => $key) {
                if (!empty($key) && !empty($request->spec_values[$index])) {
                    $specs[$key] = $request->spec_values[$index];
                }
            }
            $data['specifications'] = json_encode($specs);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
        // Cek apakah produk sedang ada di order yang belum selesai
        $activeOrders = $product->orderItems()
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'processing']);
            })
            ->count();

        if ($activeOrders > 0) {
            return redirect()->back()
                ->with('error', 'Produk tidak bisa dihapus karena masih ada pesanan aktif');
        }

        // Hapus gambar jika ada
        if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            unlink(public_path('uploads/products/' . $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
