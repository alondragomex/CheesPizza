<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('category')
            ->orderBy('name')
            ->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:pizza,additional',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
        ]);

        $data = $request->except('image_file');

        // Almacenar en storage/app/public/products usando el disco 'public' de Laravel
        $path = $request->file('image_file')->store('products', 'public');
        $data['image_url'] = '/storage/' . $path;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', '¡Producto creado exitosamente!');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:pizza,additional',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
        ]);

        $data = $request->except('image_file');

        if ($request->hasFile('image_file')) {
            // Eliminar imagen anterior local del storage
            if ($product->image_url && str_starts_with($product->image_url, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $product->image_url);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
            }

            // Almacenar nueva imagen
            $path = $request->file('image_file')->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', '¡Producto actualizado exitosamente!');
    }

    public function destroy(Product $product)
    {
        // Eliminar imagen local del storage
        if ($product->image_url && str_starts_with($product->image_url, '/storage/')) {
            $relativePath = str_replace('/storage/', '', $product->image_url);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', '¡Producto eliminado exitosamente!');
    }
}
