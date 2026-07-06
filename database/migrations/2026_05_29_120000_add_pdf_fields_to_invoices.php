<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('project_title')->nullable()->after('notes');
            $table->text('work_details')->nullable()->after('project_title');
            $table->string('total_duration', 20)->nullable()->after('work_details');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('duration', 20)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['project_title', 'work_details', 'total_duration']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
