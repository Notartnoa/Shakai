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
            'file_url'    => ['required', 'url', 'max:500'], // Google Drive link
            'about'       => ['required', 'string', 'max:65535'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:0'],

            'detail_images'   => ['required', 'array', 'min:1', 'max:4'],
            'detail_images.*' => ['image', 'mimes:png,jpg,jpeg', 'max:4096'], // 4MB per image

            'file_formats'   => ['required', 'array', 'min:1'],
            'file_formats.*' => ['string', 'max:50'],
        ]);

        DB::beginTransaction();

        try {
            // 1. Upload Cover
            if ($request->hasFile('cover')) {
                $validated['cover'] = $request->file('cover')->store('product_covers', 'public');
            }

            // 2. Upload Detail Images
            if ($request->hasFile('detail_images')) {
                $detailPaths = [];
                foreach ($request->file('detail_images') as $image) {
                    $detailPaths[] = $image->store('product_details', 'public');
                }
                $validated['detail_images'] = json_encode($detailPaths);
            }

            // 3. Set additional fields
            $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);
            $validated['creator_id'] = Auth::id();
            $validated['file_formats'] = implode(',', $request->file_formats);

            // 4. Create product
            Product::create($validated);

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error: ' . $e->getMessage()],
            ]);
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
            'file_url'    => ['required', 'url', 'max:500'], // Google Drive link
            'about'       => ['required', 'string', 'max:65535'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:0'],

            'detail_images'   => ['sometimes', 'array', 'min:1', 'max:4'],
            'detail_images.*' => ['image', 'mimes:png,jpg,jpeg', 'max:4096'],

            'file_formats'   => ['required', 'array', 'min:1'],
            'file_formats.*' => ['string', 'max:50'],
        ]);

        DB::beginTransaction();

        try {
            // Update cover if new file uploaded
            if ($request->hasFile('cover')) {
                // Delete old cover
                if ($product->cover) {
                    Storage::disk('public')->delete($product->cover);
                }
                $validated['cover'] = $request->file('cover')->store('product_covers', 'public');
            }

            // Update detail images if new files uploaded
            if ($request->hasFile('detail_images')) {
                // Delete old images
                if ($product->detail_images) {
                    $oldImages = is_array($product->detail_images)
                        ? $product->detail_images
                        : json_decode($product->detail_images, true);

                    foreach ($oldImages as $oldPath) {
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

            // Update slug only if name changed
            if ($product->name !== $request->name) {
                $validated['slug'] = Str::slug($request->name) . '-' . Str::random(5);
            }

            $validated['file_formats'] = implode(',', $request->file_formats ?? []);

            $product->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error: ' . $e->getMessage()],
            ]);
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
            if ($product->detail_images) {
                $images = is_array($product->detail_images)
                    ? $product->detail_images
                    : json_decode($product->detail_images, true);

                foreach ($images as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Product Deleted Successfully');
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'system_error' => ['System Error: ' . $e->getMessage()],
            ]);
        }
    }
}
