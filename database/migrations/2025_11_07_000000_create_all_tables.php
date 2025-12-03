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
        // Skipped because tables already exist and migration was failing
        // 1. Admin dan Customer terpisah
        /*
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id('id_admin');
                $table->string('nama', 100);
                $table->string('email', 100)->unique();
                $table->string('password', 255);
                $table->string('no_hp', 20)->nullable();
                $table->text('alamat')->nullable();
                $table->timestamps();
            });
        }

        // ... (rest of the code commented out)
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('detail_pemesanan');
        Schema::dropIfExists('pemesanan');
        Schema::dropIfExists('produk_gambar');
        Schema::dropIfExists('detail_ukuran');
        Schema::dropIfExists('detail_warna');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('admins');
    }
};
