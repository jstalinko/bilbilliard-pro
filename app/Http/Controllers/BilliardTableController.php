<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\PricingRate;
use App\Models\Transaction;
use App\Models\WaitingList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BilliardTable;
use App\Models\BilliardSession;
use Illuminate\Http\JsonResponse;

class BilliardTableController extends Controller
{

    public function monitor(): \Inertia\Response
    {
        $data['billiardTables'] = BilliardTable::with('session')->get();

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
        $data['billiard'] = null;
        $data['isEdit'] = false;
        return Inertia::render('billiard/Form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'number' => 'required|string'
        ]);

        BilliardTable::create([
            'name' => $validated['name'],
            'number' => $validated['number'],
            'status' => 'available'
        ]);

        return redirect()->route('billiard.show')
            ->with('success', 'Billiard Table created successfully.');
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
        $data['billiard'] = BilliardTable::find($id);
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
        BilliardTable::find($id)->delete();
        return redirect()->route('billiard.show')
            ->with('success', 'Billiard table deleted successfully.');
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
      //  $table->save();


        
         

        $end_time = Carbon::now()->format('Y-m-d H:i:s');
        $start = Carbon::parse($session->start_time);
        $end = Carbon::parse($end_time);
       
        $session->end_time = $end_time;
        $durationInHours = $end->diffInHours($start,true);
        if ($durationInHours < 1) {
            $durationInHours = 1;
        }
        $session->total_price = floatval(round($durationInHours) * $session->rate_per_hour);
        $session->status = 'finished';
        $session->save();


        $tx = new Transaction();
        $tx->session_id = $session->id;
        $tx->type = 'session';
        $tx->payment_method = 'Cash';
        $tx->paid_amount = floatval($durationInHours * $session->rate_per_hour);
        $tx->total_amount = 0;
        $tx->change = 0;
        $tx->note = 'Close session billiard table '.$table->number;
        $tx->tx_status = 'pending';
        $tx->save();

        if ($request->action == 'continue') {
            $redirect_url = url('/dashboard/transactions/create?tx_id='.$tx->id.'&session_id='.$session->id);
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
        if (!in_array($status, ['available', 'maintenance'])) {
            return redirect('/dashboard');
        }
        BilliardTable::where('status', 'maintenance')->update(['status' => 'available']);
        BilliardTable::where('status', 'occupied')->update(['status' => 'available']);

        return redirect('/dashboard/billiards/tables');
    }


    public function publicMonitor(Request $request): \Inertia\Response
    {
        $show = $request?->show; // today, yesterday, or date range like dd/mm/YYYY-dd/mm/YYYY

        // Parse date range based on $show
        $waitingListQuery = WaitingList::orderBy('number', 'asc');

        if ($show) {
            if ($show === 'today') {
                $waitingListQuery->whereDate('created_at', Carbon::today());
            } elseif ($show === 'yesterday') {
                $waitingListQuery->whereDate('created_at', Carbon::yesterday());
            } elseif (Str::contains($show, '-')) {
                $range = explode('-', $show);
                if (count($range) === 2) {
                    try {
                        $startDate = Carbon::createFromFormat('d/m/Y', trim($range[0]))->startOfDay();
                        $endDate = Carbon::createFromFormat('d/m/Y', trim($range[1]))->endOfDay();
                        $waitingListQuery->whereBetween('created_at', [$startDate, $endDate]);
                    } catch (\Exception $e) {
                        // Optionally log or handle invalid date format
                    }
                }
            }
        }

        $data['billiard_tables'] = BilliardTable::orderBy('number', 'asc')->get();
        $data['waiting_lists'] = $waitingListQuery->get();

        return Inertia::render('Monitor', $data);
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $billiard = BilliardTable::find($id);
        $billiard->status = $request->status;
        $billiard->save();

        return redirect()->route('billiard.show')->with('success','Updated status successfully');
    }
}
