<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\PricingRate;
use Illuminate\Http\Request;
use App\Models\BilliardTable;
use App\Models\BilliardSession;
use Illuminate\Http\JsonResponse;

class BilliardTableController extends Controller
{

    public function monitor(): \Inertia\Response
    {
        $data['billiardTables'] = BilliardTable::with('session')->orderBy('number','asc')->get();

        $data['availableTable'] = BilliardTable::where('status', 'available')->count();
        $data['maintenanceTable'] = BilliardTable::where('status', 'maintenance')->count();
        $data['occupiedTable'] = BilliardTable::where('status', 'occupied')->count();
        $data['pricingrates'] = PricingRate::all();

        return Inertia::render('billiard/Monitor', $data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {

        $data['billiardTables'] = BilliardTable::all()->sortBy('number');
        return Inertia::render('billiard/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $data['action'] = 'create';
        return Inertia::render('billiard/Form', $data);
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
        return Inertia::render('billiard/Form', $data);
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
    public function sessionCreate(Request $request): JsonResponse
    {
        if ($request->table_id == '' || $request->rate_per_hour == '') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed open table'
                ],
                201,
                [],
                JSON_PRETTY_PRINT
            );
        }
        
        $table = BilliardTable::find($request->table_id);
        $table->status = 'occupied';
        $table->save();

        $sestable = new BilliardSession();
        $sestable->billiard_table_id = $table->id;
        $sestable->start_time = Carbon::now()->format('Y-m-d H:i:s');
        $sestable->end_time = null;
        $sestable->rate_per_hour = floatval($request->rate_per_hour);
        $sestable->status = 'ongoing';
        $sestable->save();

        

        return response()->json([
            'success' => true,
            'message' => 'Sucessfully open tables.'
        ], 200, [], JSON_PRETTY_PRINT);
    }
    public function sessionClose(Request $request): JsonResponse
    {
        $table_id = $request->table_id;
        $session = BilliardSession::where('billiard_table_id', $table_id)->where('status', 'ongoing')->first();
        if (!$session) {
            return response()->json([
                '
            success' => false,
                'message' => 'No session table active'
            ], 200, [], JSON_PRETTY_PRINT);
        }

        $table = BilliardTable::find($table_id);
        $table->status = 'available';
        $table->save();


        /** JIKA ADA TANGGAL.
         * $start = Carbon::parse($session->start_time);
$end = Carbon::parse($session->end_time);
         */

        $end_time = Carbon::now()->format('Y-m-d H:i:s');
        $start = Carbon::createFromTimeString($session->start_time);
        $end = Carbon::createFromTimeString($end_time);
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $session->end_time = $end_time;
        $durationInHours = $end->floatDiffInHours($start);
        if($durationInHours < 1) {
            $durationInHours =1;
        }
        $session->total_price = floatval($durationInHours * $session->rate_per_hour);
        $session->status = 'finished';
        $session->save();

        /**
         *       $table->foreignId('session_id')->nullable()->constrained('billiard_sessions')->onDelete('set null');
            $table->enum('type', ['session', 'direct']);
            $table->string('payment_method');
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('total_amount',10,2);
            $table->decimal('change', 10, 2)->default(0);
            $table->text('note')->nullable();
         */
        $tx = new Transaction();
        $tx->session_id = $session->id;
        $tx->type = 'session';
        $tx->payment_method = 'Cash';
        $tx->paid_amount = floatval($durationInHours * $session->rate_per_hour);
        $tx->total_amount = 0;
        $tx->change = 0;
        $tx->note = 'Pending transactions';
        $tx->tx_status = 'pending';
        $tx->save();

        if ($request->action == 'continue') {
            $redirect_url = url('/dashboard/transactions/create');
        } else {
            $redirect_url = url('/dashboard/billiards/tables');
        }


        return response()->json([
            'success' => true,
            'message' => 'Successfully create transactions',
            'data' => ['redirect_url' => $redirect_url]
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function updateAll(Request $request)
    {
        $status = $request->status;
        if(!in_array($status,['available','maintenance']))
        {
            return redirect('/dashboard');
        }
        BilliardTable::where('status','maintenance')->update(['status' => 'available']);
        BilliardTable::where('status','occupied')->update(['status' => 'available']);
        
        return redirect('/dashboard/billiards/tables');
    }
}
