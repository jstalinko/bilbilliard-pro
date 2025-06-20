<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\WaitingList;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():\Inertia\Response
    {
        
        $data['waitinglists'] = WaitingList::orderBy('id','desc')->get();
        return Inertia::render('waitinglists/Index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['action'] = 'create';
        $data['isEdit'] = false;
        $data['waitinglist'] = null;
        return Inertia::render('waitinglists/Form' , $data);
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
        $data['isEdit'] = true;
        $data['waitinglist'] = WaitingList::find($id);
        return Inertia::render('waitinglists/Form' , $data);
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
