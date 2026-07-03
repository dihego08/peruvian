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
            'name'          => 'required|string|max:255',
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
            'imgbordado'    => 'nullable|image|max:4096',
            'secuencia'     => 'nullable|file|max:4096',
            'is_active'     => 'boolean'
        ]);

        $validated['created_at'] = now();
        $validated['fecact'] = $request->input('fecact', now()->toDateString());
        $validated['is_active'] = $request->input('is_active', 1);
        $validated['kind'] = $request->input('kind', 1);
        $validated['user_id'] = $this->resolveUserId($request) ?? 1;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/products');
            $file->move($destinationPath, $filename);
            $validated['image'] = $filename;
        }

        if ($request->hasFile('imgbordado')) {
            $file = $request->file('imgbordado');
            $filename = time() . '_bordado_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/products');
            $file->move($destinationPath, $filename);
            $validated['imgbordado'] = $filename;
        }

        if ($request->hasFile('secuencia')) {
            $file = $request->file('secuencia');
            $filename = time() . '_secuencia_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/products');
            $file->move($destinationPath, $filename);
            $validated['secuencia'] = $filename;
        }

        if (isset($validated['pre_bor_in'])) {
            $validated['prebor_in'] = $validated['pre_bor_in'];
            unset($validated['pre_bor_in']);
        }
        if (isset($validated['pre_bor_out'])) {
            $validated['prebor_out'] = $validated['pre_bor_out'];
            unset($validated['pre_bor_out']);
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
            'imgbordado'    => 'nullable|image|max:4096',
            'secuencia'     => 'nullable|file|max:4096',
        ]);

        $validated['fecact'] = $request->input('fecact', now()->toDateString());

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/products');
            $file->move($destinationPath, $filename);
            $validated['image'] = $filename;
        }

        if ($request->hasFile('imgbordado')) {
            $file = $request->file('imgbordado');
            $filename = time() . '_bordado_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/products');
            $file->move($destinationPath, $filename);
            $validated['imgbordado'] = $filename;
        }

        if ($request->hasFile('secuencia')) {
            $file = $request->file('secuencia');
            $filename = time() . '_secuencia_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/products');
            $file->move($destinationPath, $filename);
            $validated['secuencia'] = $filename;
        }

        if (isset($validated['pre_bor_in'])) {
            $validated['prebor_in'] = $validated['pre_bor_in'];
            unset($validated['pre_bor_in']);
        }
        if (isset($validated['pre_bor_out'])) {
            $validated['prebor_out'] = $validated['pre_bor_out'];
            unset($validated['pre_bor_out']);
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

    private function resolveUserId(Request $request): ?int
    {
        $header = $request->header('Authorization', '');
        if (preg_match('/Bearer\s+(.+)/i', $header, $m)) {
            $decoded = base64_decode($m[1], true);
            if ($decoded !== false && str_contains($decoded, '|')) {
                $id = (int) explode('|', $decoded, 2)[0];
                return $id > 0 ? $id : null;
            }
        }

        $idUsuario = $request->query('idUsuario');
        return $idUsuario ? (int) $idUsuario : null;
    }
}
