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
        Schema::create('billiard_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billiard_table_id')->constrained()->onDelete('cascade');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->decimal('rate_per_hour', 10, 2);
            $table->decimal('total_price', 10, 2)->nullable();
            $table->enum('status', ['ongoing', 'finished'])->default('ongoing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billiard_sessions');
    }
};
