<?php

namespace App\Http\Controllers;

use App\Models\BilliardSession;
use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $data['transactions'] = Transaction::orderBy('created_at', 'desc')->with('items')->with('session')->get();
       // dd($data);
        return Inertia::render('transactions/Index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['action'] = 'create';
        $data['billiard_sessions']=BilliardSession::select([
    'billiard_sessions.id',
    'billiard_tables.name as table_name',
    'billiard_tables.number as table_number'
])
->join('billiard_tables', 'billiard_sessions.billiard_table_id', '=', 'billiard_tables.id')
->where('billiard_sessions.status', 'finished')
->orderBy('billiard_sessions.id', 'desc')
->get();
 return Inertia::render('transactions/Form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json($request->all());
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
        return Inertia::render('transactions/Form', $data);
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
