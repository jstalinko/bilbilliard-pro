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
    public function index(): \Inertia\Response
    {

        $data['waitinglists'] = WaitingList::orderBy('id', 'desc')->get();
        return Inertia::render('waitinglists/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['action'] = 'create';
        $data['isEdit'] = false;
        $data['waitinglist'] = null;
        return Inertia::render('waitinglists/Form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|numeric',
            'status' => 'required|in:waiting,played,cancel', // adjust values as needed
        ]);

        // Create new record
        WaitingList::create([
            'name' => $validated['name'],
            'number' => $validated['number'],
            'status' => $validated['status'],
        ]);

        // Return response
        return redirect()->route('waitinglist.show')->with('success','Sucessfully create waitinglist');
    }


    /**
     * Display the specified resource.
     */
   
public function updateStatus(Request $request, string $id)
{
    // Validasi status yang dikirim dari request
    $validated = $request->validate([
        'status' => 'required|in:played,waiting,cancel', // sesuaikan dengan allowed values
    ]);

    // Temukan dan update status
    $updated = WaitingList::findOrFail($id)->update([
        'status' => $validated['status'],
    ]);

    // Respon (bisa redirect atau JSON sesuai kebutuhan)
    return redirect()->route('waitinglist.show')->with('success', 'Status berhasil diperbarui!');
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['action'] = 'edit';
        $data['isEdit'] = true;
        $data['waitinglist'] = WaitingList::find($id);
        return Inertia::render('waitinglists/Form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|numeric',
            'status' => 'required|in:waiting,played,cancel', // adjust as needed
        ]);

        // Find the record by ID
        $waitingList = WaitingList::findOrFail($id);

        // Update the record
        $waitingList->update([
            'name' => $validated['name'],
            'number' => $validated['number'],
            'status' => $validated['status'],
        ]);

        // Redirect or respond
        return redirect()->route('waitinglist.show')->with('success', 'Data berhasil diperbarui!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the record
        $waitingList = WaitingList::findOrFail($id);

        // Delete the record
        $waitingList->delete();

        // Redirect or respond
        return redirect()->route('waitinglist.show')->with('success', 'Data berhasil dihapus!');
    }
}
