<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
                $table->foreignId('session_id')->nullable()->constrained('billiard_sessions')->onDelete('set null');
            $table->enum('type', ['session', 'direct']);
            $table->string('payment_method');
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('total_amount',10,2);
            $table->decimal('change', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
