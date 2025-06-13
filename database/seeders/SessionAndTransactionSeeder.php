<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BilliardSession;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use App\Models\BilliardTable;
use Carbon\Carbon;

class SessionAndTransactionSeeder extends Seeder
{
    public function run()
    {
        $tables = BilliardTable::all();
        $products = Product::all();

        foreach ($tables as $table) {
            // Simulasi sesi selesai
            $start = Carbon::now()->subHours(rand(1, 5));
            $end = $start->copy()->addMinutes(rand(30, 120));
            $rate = $table->rate_per_hour;

            $durationInHours = $start->diffInMinutes($end) / 60;
            $total = $durationInHours * $rate;

            $session = BilliardSession::create([
                'billiard_table_id' => $table->id,
                'start_time' => $start,
                'end_time' => $end,
                'rate_per_hour' => $rate,
                'total_price' => $total,
                'status' => 'finished',
            ]);

            $transaction = Transaction::create([
                'session_id' => $session->id,
                'type' => 'session',
                'payment_method' => 'cash',
                'paid_amount' => $total + 15000,
                'change' => 15000,
            ]);

            // Tambah produk ke transaksi
            $randomProducts = $products->random(rand(1, 3));
            foreach ($randomProducts as $product) {
                $qty = rand(1, 2);
                TransactionItem::create([
                    'billiard_session_id' => $session->id,
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                ]);
            }
        }

        // Transaksi langsung (tanpa meja)
        for ($i = 0; $i < 3; $i++) {
            $transaction = Transaction::create([
                'session_id' => null,
                'type' => 'direct',
                'payment_method' => 'qris',
                'paid_amount' => 20000,
                'change' => 0,
            ]);

            $randomProducts = $products->random(rand(1, 2));
            foreach ($randomProducts as $product) {
                $qty = rand(1, 3);
                TransactionItem::create([
                    'billiard_session_id' => null,
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                ]);
            }
        }
    }
}
