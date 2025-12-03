<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id('id_pembayaran');

                // Foreign key
                $table->unsignedBigInteger('id_pemesanan');

                // Metode pembayaran
                $table->enum('metode_pembayaran', ['va', 'ewallet', 'retail', 'cod'])->default('va');
                $table->string('channel', 100)->nullable()->comment('BCA, BRI, DANA, OVO, etc');

                // Informasi jumlah bayar
                $table->decimal('jumlah_bayar', 12, 2);
                $table->enum('status_pembayaran', ['menunggu', 'belum_bayar', 'sudah_bayar', 'kadaluarsa', 'gagal'])->default('menunggu');

                // External ID untuk referensi internal
                $table->string('external_id')->unique();

                // Data dari Xendit
                $table->string('xendit_id')->nullable()->comment('ID dari Xendit');
                $table->string('xendit_external_id')->nullable()->comment('External ID di Xendit');
                $table->text('xendit_payment_url')->nullable()->comment('URL pembayaran dari Xendit');
                $table->timestamp('xendit_expiry_date')->nullable()->comment('Tanggal kadaluarsa pembayaran');
                $table->string('xendit_merchant_name')->nullable()->comment('Nama merchant di Xendit');
                $table->string('xendit_account_number')->nullable()->comment('Nomor VA/Account dari Xendit');

                // Timestamps
                $table->timestamps();

                // Foreign key constraint
                $table->foreign('id_pemesanan')
                    ->references('id_pemesanan')
                    ->on('pemesanan')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                // Indexes untuk performa
                $table->index('id_pemesanan');
                $table->index('external_id');
                $table->index('xendit_id');
                $table->index('status_pembayaran');
                $table->index('metode_pembayaran');
                $table->index(['status_pembayaran', 'xendit_expiry_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};