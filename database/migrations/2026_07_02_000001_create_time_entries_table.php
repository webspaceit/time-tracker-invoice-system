<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('total_seconds')->default(0);
            $table->boolean('billable')->default(true);
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
