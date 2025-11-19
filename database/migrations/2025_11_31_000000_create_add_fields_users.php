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
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable()->after('alamat');
            $table->unsignedBigInteger('city_id')->nullable()->after('province_id');

            $table->string('province_name')->nullable()->after('city_id');
            $table->string('city_name')->nullable()->after('province_name');
            $table->string('kode_pos', 10)->nullable()->after('city_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'province_id',
                'city_id',
                'province_name',
                'city_name',
                'kode_pos',
            ]);
        });
    }
};
