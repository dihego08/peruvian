<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return response()->json(Brand::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['created_at'] = now();

        $brand = Brand::create($validated);
        return response()->json($brand, 201);
    }

    public function show($id)
    {
        return response()->json(Brand::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand->update($validated);
        return response()->json($brand);
    }

    public function destroy($id)
    {
        Brand::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
