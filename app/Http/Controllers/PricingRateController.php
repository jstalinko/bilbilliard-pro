<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\PricingRate;
use Illuminate\Http\Request;

class PricingRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():\Inertia\Response
    {
        $data['pricingrates'] = PricingRate::all();

        return Inertia::render('price-rate/Index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():\Inertia\Response
    {
        return Inertia::render('price-rate/Form',['isEdit' =>false,'pricingrate' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    
    // $validated = $request->validate([
    //     'name' => 'required|string|max:255',
    //     'price_per_hour' => 'required|numeric|min:0',
    // ]);
  

    PricingRate::create($request->all());

    return redirect()->route('price-rate.show')->with('success', 'Pricing rate berhasil ditambahkan.');
}

  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pricingrate = PricingRate::find($id);
        return Inertia::render('price-rate/Form',['isEdit' => true,'pricingrate' => $pricingrate]);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    dd($request);
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price_per_hour' => 'required|numeric|min:0',
        'start_at' => 'nullable|date_format:H:i',
        'end_at' => 'nullable|date_format:H:i',
    ]);

    $rate = PricingRate::findOrFail($id);
    $rate->update($validated);

    return redirect()->route('price-rate.show')->with('success', 'Pricing rate berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $rate = PricingRate::findOrFail($id);
    $rate->delete();

    return redirect()->route('price-rate.show')->with('success', 'Pricing rate berhasil dihapus.');
}
}
