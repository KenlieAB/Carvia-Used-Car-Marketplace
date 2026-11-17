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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_model_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();

            // Info Utama Iklan
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('price');
            $table->boolean('price_negotiable')->default(true);
            $table->text('description');

            // Tahun & Mesin
            $table->unsignedSmallInteger('manufacture_year'); // Tahun NIK (Perakitan)
            $table->unsignedSmallInteger('registration_year'); // Tahun Pemakaian / Keluar STNK
            $table->enum('transmission', ['manual', 'automatic']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'hybrid', 'electric']);
            $table->unsignedSmallInteger('engine_cc');
            $table->string('color', 50);
            $table->unsignedTinyInteger('seats')->default(5);

            // Kilometer & Servis
            $table->unsignedInteger('mileage_km');
            $table->enum('service_record', ['authorized_dealer', 'specialist', 'self', 'none'])->default('none');
            $table->boolean('has_service_book')->default(false);
            $table->boolean('has_spare_key')->default(false);

            // Legalitas & Surat-Surat
            $table->enum('bpkb_status', ['on_hand', 'in_leasing'])->default('on_hand');
            $table->enum('stnk_status', ['available', 'lost'])->default('available');
            $table->boolean('has_faktur')->default(true);
            $table->boolean('has_form_a')->default(false); // Khusus CBU / impor
            $table->enum('registration_type', ['personal', 'company'])->default('personal');
            $table->unsignedTinyInteger('owner_count')->default(1);

            // Pajak & Plat Nomor
            $table->string('plate_region', 5); // B, D, L, dsb.
            $table->enum('plate_type', ['odd', 'even'])->nullable(); // Ganjil / Genap
            $table->enum('tax_status', ['active', 'expired'])->default('active');
            $table->date('tax_expiry_date')->nullable(); // Jatuh tempo pajak tahunan
            $table->unsignedSmallInteger('plate_expiry_year')->nullable(); // Masa berlaku plat 5 tahunan (kaleng)
            $table->unsignedInteger('annual_tax_amount')->nullable(); // Estimasi PKB tahunan

            // Deklarasi Kondisi Fisik (Trust Checklist)
            $table->boolean('is_accident_free')->default(true);
            $table->boolean('is_flood_free')->default(true);
            $table->enum('modification_status', ['stock', 'light_mod', 'full_mod'])->default('stock');

            // Status Iklan & Masa Tayang
            $table->enum('status', ['active', 'sold', 'rejected', 'expired'])->default('active');
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            // Optimasi Indeks Pencarian & Filter
            $table->index(['status', 'published_at']);
            $table->index(['status', 'price']);
            $table->index(['status', 'city_id']);
            $table->index(['status', 'car_model_id']);
            $table->index(['status', 'manufacture_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
