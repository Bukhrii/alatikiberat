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
        Schema::create('inventory_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained()->onDelete('cascade');
            $table->string('report_name');
            $table->string('report_type'); // Valuasi Aset, Pergerakan Stok, dll.
            $table->date('start_period');
            $table->date('end_period');
            $table->json('report_data')->nullable(); // Snapshot of report data
            $table->string('file_path')->nullable(); // Lokasi file Excel/PDF hasil export
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_reports');
    }
};
