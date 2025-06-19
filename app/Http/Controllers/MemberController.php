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
    public function index(): \Inertia\Response
    {
        $members = Member::all();
        return Inertia::render('members/Index', ['members' => $members]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('members/Form', ['member' => null, 'isEdit' => false]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string'
        ]);


        Member::create([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'point' => 0,
            'total_tx' => 0
        ]);

        return redirect()->route('member.show')
            ->with('success', 'Member created successfully.');
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
    public function edit(string $id): \Inertia\Response
    {
        $member = Member::find($id);
        return Inertia::render('members/Form', ['member' => $member, 'isEdit' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::find($id);
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string'
        ]);
        $member->update($validated);

        return redirect()->route('member.show')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    
        $deleted = Member::find($id)->delete();

        return redirect()->route('member.show')->with('success','Member deleted');
    }
}
