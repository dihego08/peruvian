<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Traemos productos con info del cliente, tal como hacía ProductData::getAll()
        return response()->json(Product::with('client')->active()->orderBy('fecact', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'price_in' => 'nullable|numeric',
            'price_out' => 'nullable|numeric',
            'cliente_id' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['created_at'] = now();
        $validated['fecact'] = now();
        $validated['is_active'] = $request->input('is_active', 1);
        $validated['kind'] = $request->input('kind', 1);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Guardar en la carpeta legacy de imagenes de productos
            $destinationPath = base_path('../storage/products');
            $file->move($destinationPath, $filename);
            $validated['image'] = $filename;
        }

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function show($id)
    {
        return response()->json(Product::with('client')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'string|max:255',
            'code'          => 'nullable|string|max:255',
            'barcode'       => 'nullable|string|max:255',
            'price_in'      => 'nullable|numeric',
            'price_in_2'    => 'nullable|numeric',
            'price_out'     => 'nullable|numeric',
            'pre_bor_in'    => 'nullable|numeric',
            'pre_bor_out'   => 'nullable|numeric',
            'cliente_id'    => 'nullable|integer',
            'brand_id'      => 'nullable|integer',
            'kind'          => 'nullable|integer',
            'unit'          => 'nullable|string|max:100',
            'presentation'  => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'large'         => 'nullable|string|max:50',
            'width'         => 'nullable|string|max:50',
            'height'        => 'nullable|string|max:50',
            'weight'        => 'nullable|string|max:50',
            'inventary_min' => 'nullable|numeric',
            'fecact'        => 'nullable|date',
            'image'         => 'nullable|image|max:4096',
        ]);

        $validated['fecact'] = $request->input('fecact', now()->toDateString());

        // Procesar imagen solo si se sube una nueva
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = base_path('../storage/products');
            $file->move($destinationPath, $filename);
            $validated['image'] = $filename;
        }

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // Soft delete legacy
        $product->update(['is_active' => 0]);
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', "%{$query}%")
                           ->orWhere('code', 'like', "%{$query}%")
                           ->orWhere('barcode', 'like', "%{$query}%")
                           ->limit(20)
                           ->get();

        return response()->json($products);
    }
}
