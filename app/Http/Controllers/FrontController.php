<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        $category = Category::all();
        return view('front.index', [
            'products' => $products,
            'categories' => $category,
        ]);
    }

    public function details(Product $product)
    {
        $other_products = Product::where('id', '!=', $product->id)
            ->latest()
            ->take(8)
            ->get();

        $creator_id = $product->creator_id;
        $creator_products = Product::where('slug', $product->slug)->get();

        return view('front.details', [
            'product' => $product,
            'other_products' => $other_products,
            'creator_products' => $creator_products,
            'total_products' => Product::where('creator_id', $creator_id)->get(),
        ]);
    }

    public function category(Category $category)
    {
        $product_categories = Product::where('category_id', $category->id)->get();
        return view('front.category', [
            'category' => $category,
            'product_categories' => $product_categories,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $products = Product::where('name', 'LIKE', '%' . $keyword . '%')
            ->orWhereHas('Category', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            })->get();

        return view('front.search', [
            'products' => $products,
            'keyword' => $keyword,
        ]);
    }

    /**
     * Handle free product download
     */
    public function download(Product $product)
    {
        // Cek apakah produk gratis
        if ($product->price > 0) {
            return redirect()->route('front.checkout', $product->slug)
                ->with('error', 'This product requires payment.');
        }

        // Cek apakah file_url ada
        if (empty($product->file_url)) {
            return back()->with('error', 'Download link is not available.');
        }

        // Optional: Log download untuk tracking/analytics
        // Uncomment jika sudah buat tabel downloads
        // \App\Models\Download::create([
        //     'user_id' => auth()->id(),
        //     'product_id' => $product->id,
        //     'ip_address' => request()->ip(),
        //     'downloaded_at' => now(),
        // ]);

        // Redirect ke Google Drive
        // Pakai redirect()->away() supaya tidak kena CSRF
        return redirect()->away($product->file_url);
    }
}
