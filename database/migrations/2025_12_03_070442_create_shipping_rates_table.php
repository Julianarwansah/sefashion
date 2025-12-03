<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('courier'); // JNE, TIKI, POS, SiCepat, JNT
            $table->string('service_type'); // REG, YES, OKE, etc.
            $table->string('service_name'); // Regular, Express, Cargo
            $table->foreignId('origin_zone_id')->constrained('shipping_zones')->onDelete('cascade');
            $table->foreignId('destination_zone_id')->constrained('shipping_zones')->onDelete('cascade');
            $table->integer('min_weight'); // in grams
            $table->integer('max_weight'); // in grams
            $table->decimal('base_rate', 10, 2); // base price for min weight
            $table->decimal('per_kg_rate', 10, 2); // price per additional kg
            $table->string('estimated_days'); // e.g., "2-3 hari"
            $table->timestamps();

            $table->index(['courier', 'service_type']);
            $table->index(['origin_zone_id', 'destination_zone_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
