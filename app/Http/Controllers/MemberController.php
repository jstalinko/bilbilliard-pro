<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():\Inertia\Response
    {
        $members = Member::all();
        return Inertia::render('members/Index',['members' => $members]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():\Inertia\Response
    {
        return Inertia::render('members/Form' , ['member' => null,'isEdit' => false]);
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
    public function edit(string $id):\Inertia\Response
    {
        $member = Member::find($id);
        return Inertia::render('members/Form' , ['member' => $member , 'isEdit' => true]);
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
