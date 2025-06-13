<?php

namespace App\Http\Controllers;

use App\Models\BilliardTable;
use Inertia\Inertia;
use Illuminate\Http\Request;

class BilliardTableController extends Controller
{

    public function monitor():\Inertia\Response
    {
        $data['billiardTables'] = BilliardTable::all()->sortBy('number');
        
        return Inertia::render('billiard/Monitor',$data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index():\Inertia\Response
    {
        return Inertia::render('billiard/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():\Inertia\Response
    {
        $data['action'] = 'create';
        return Inertia::render('billiard/Form',$data);
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
        $data['action'] = 'edit';
        return Inertia::render('billiard/Form',$data);
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
