<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Helper;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\BilliardSession;
use App\Models\TransactionItem;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $data['transactions'] = Transaction::orderBy('created_at', 'desc')->with('items')->with('session')->get();
        // dd($data);
        return Inertia::render('transactions/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $idTx = (isset($request->tx_id)) ? $request->tx_id : null;
        if ($idTx != null) {
            $data['tx'] = Transaction::find($idTx);
        } else {
            $data['tx'] = null;
        }
        $data['action'] = 'create';
        $data['billiard_sessions'] = BilliardSession::select([
            'billiard_sessions.id as session_id',
            'billiard_tables.name as table_name',
            'billiard_tables.number as table_number',
            '*'
        ])
            ->join('billiard_tables', 'billiard_sessions.billiard_table_id', '=', 'billiard_tables.id')
            ->where('billiard_sessions.status', 'finished')
            ->orderBy('billiard_sessions.id', 'desc')
            ->get();
        $data['products'] = Product::all();
        $data['members'] = Member::all();

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

    public function createApi(Request $request)
    {


        // Validasi dan parsing data JSON
        $data = $request->json()->all();
        $billiard_session_id = ($data['session_id']) ? $data['session_id'] : null;
        $tx_id = ($data['tx_id']) ? $data['tx_id'] : null;
        $jml_bayar = floatval($data['jumlah_bayar']);
        $total_bayar = floatval($data['total_bayar']);
        $change = floatval($jml_bayar-$total_bayar);

        // set session billiars to complete
        if ($billiard_session_id != null) {
            BilliardSession::where('id', $billiard_session_id)->update(['status' => 'complete']);
        }

        // Buat atau perbarui transaksi
        $tx = Transaction::updateOrCreate(
            [
                'id' => $tx_id,
                'session_id' => $billiard_session_id
            ],
            [
                'type' => $data['session_id'] ? 'session' : 'direct',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'paid_amount' => $jml_bayar,
                'total_amount' => $total_bayar,
                'change' => $change,
                'note' => $data['note'] ?? 'transaction success',
                'tx_status' => 'complete'
            ]
        );

        // Bersihkan item sebelumnya (jika update)
        TransactionItem::where('transaction_id', $tx->id)->delete();

        // Simpan ulang item transaksi
        foreach ($data['transaction_items'] ?? [] as $item) {
            TransactionItem::create([
                'billiard_session_id' => $data['session_id'] ?? null,
                'transaction_id' => $tx->id,
                'product_id' => $item['kode'] ?? null,
                'quantity' => $item['qty'] ?? 0,
                'price' => $item['price'] ?? 0,
                'subtotal' => floatval(($item['price'] ?? 0) * ($item['qty'] ?? 0))
            ]);
        }
        // if members
        if ($data['member_id'] != null) {
            $member = Member::find($data['member_id']);
            if ($member) {
                $member->point = Helper::calculatePoint($data['total_bayar']);
                $member->total_tx = floatval($member->total_tx + $data['total_bayar']);
                $member->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction saved successfully.',
            'transaction_id' => $tx->id
        ]);
    }
}
