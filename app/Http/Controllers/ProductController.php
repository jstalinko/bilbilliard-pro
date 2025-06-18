<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():\Inertia\Response
    {
        $data['products'] = Product::all()->sortBy('id');
        return Inertia::render('products/Index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():\Inertia\Response
    {
        return Inertia::render('products/Form' , ['isEdit' => false, 'product' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $product = Product::find($id);
        return Inertia::render('products/Form', ['isEdit' => true, 'product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        
           $product->update($validated);

        return redirect()->route('product.show')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Product::find($id)->delete();
    }
}
