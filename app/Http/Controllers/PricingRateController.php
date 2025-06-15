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
        dd($data);
        return Inertia::render('price-rate/Index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
