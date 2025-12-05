<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('creator_id', Auth::id())->get();
        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', [
            'catagories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'cover'       => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'path_file'   => ['required', 'file', 'mimes:zip', 'max:10240'], // 10MB
            'about'       => ['required', 'string', 'max:65535'],
            'category_id' => ['required', 'integer'],
            'price'       => ['required', 'integer', 'min:0'],

            'detail_images'   => ['required', 'array', 'min:1', 'max:4'],
            'detail_images.*' => ['image', 'mimes:png,jpg,jpeg', 'max:16096'], // 4MB per image

            'file_formats'   => ['required', 'array', 'min:1'],
            'file_formats.*' => ['string', 'max:50'],
        ]);

        DB::beginTransaction();

        try {
            // 1. Upload Cover
            if ($request->hasFile('cover')) {
                $validated['cover'] = $request->file('cover')->store('product_covers', 'public');
            }

            // 2. Upload ZIP File
            if ($request->hasFile('path_file')) {
                $validated['path_file'] = $request->file('path_file')->store('product_files', 'public');
            }

            // 3. Upload Detail Images
            if ($request->hasFile('detail_images')) {
                $detailPaths = [];
                foreach ($request->file('detail_images') as $image) {
                    $detailPaths[] = $image->store('product_details', 'public');
                }
                $validated['detail_images'] = json_encode($detailPaths);
            }

            // 4. Set additional fields
            $validated['slug'] = Str::slug($request->name);
            $validated['creator_id'] = Auth::id();
            $validated['file_formats'] = implode(',', $request->file_formats);

            // 5. SIMPAN SEKALI SAJA (FIX DUPLIKASI!)
            Product::create($validated);

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System Error: ' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', [
            'product'    => $product,
            'catagories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'cover'       => ['sometimes', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'path_file'   => ['sometimes', 'file', 'mimes:zip', 'max:10240'], // 10MB
            'about'       => ['required', 'string', 'max:65535'],
            'category_id' => ['required', 'integer'],
            'price'       => ['required', 'integer', 'min:0'],

            'detail_images'   => ['sometimes', 'array', 'min:1', 'max:4'],
            'detail_images.*' => ['image', 'mimes:png,jpg,jpeg', 'max:16096'],

            'file_formats'   => ['required', 'array', 'min:1'],
            'file_formats.*' => ['string', 'max:50'],
        ]);

        DB::beginTransaction();

        try {
            // Update cover if new file uploaded
            if ($request->hasFile('cover')) {
                if ($product->cover) {
                    Storage::disk('public')->delete($product->cover);
                }
                $validated['cover'] = $request->file('cover')->store('product_covers', 'public');
            }

            // Update ZIP file if new file uploaded
            if ($request->hasFile('path_file')) {
                if ($product->path_file) {
                    Storage::disk('public')->delete($product->path_file);
                }
                $validated['path_file'] = $request->file('path_file')->store('product_files', 'public');
            }

            // Update detail images if new files uploaded
            if ($request->hasFile('detail_images')) {
                // Delete old images
                if ($product->detail_images) {
                    foreach (json_decode($product->detail_images, true) as $oldPath) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                // Upload new images
                $detailPaths = [];
                foreach ($request->file('detail_images') as $image) {
                    $detailPaths[] = $image->store('product_details', 'public');
                }
                $validated['detail_images'] = json_encode($detailPaths);
            }

            $validated['slug'] = Str::slug($request->name);
            $validated['file_formats'] = implode(',', $request->file_formats ?? []);

            $product->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System Error: ' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete associated files
            if ($product->cover) {
                Storage::disk('public')->delete($product->cover);
            }
            if ($product->path_file) {
                Storage::disk('public')->delete($product->path_file);
            }
            if ($product->detail_images) {
                foreach (json_decode($product->detail_images, true) as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Product Deleted Successfully');
        } catch (\Exception $e) {
            $error = ValidationException::withMessages([
                'system_error' => ['System Error: ' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
}
