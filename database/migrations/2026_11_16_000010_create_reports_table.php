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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('reason', [
                'scam_dp',          // Penipuan minta transfer DP
                'fake_price',       // Harga palsu/jebakan kredit
                'already_sold',     // Unit sudah laku tapi iklan masih ada
                'wrong_info',       // Informasi/spesifikasi salah atau palsu
                'duplicate',        // Iklan duplikat/spam
                'other',            // Alasan lain
            ]);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
