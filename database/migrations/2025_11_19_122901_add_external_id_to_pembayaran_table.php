<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        if (Schema::hasTable('pembayaran') && !Schema::hasColumn('pembayaran', 'external_id')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->string('external_id')->nullable()->after('channel');
            });
        }
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }
};
