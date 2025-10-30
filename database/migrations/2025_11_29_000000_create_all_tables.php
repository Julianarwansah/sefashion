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
        // 1. Admin dan Customer terpisah
        Schema::create('admins', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id('id_customer');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // 2. Produk (dengan total_stok)
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk', 100);
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 50)->nullable();
            $table->string('gambar', 255)->nullable();
            $table->integer('total_stok')->default(0);
            $table->timestamps();
        });

        // 3. Detail warna per produk
        Schema::create('detail_warna', function (Blueprint $table) {
            $table->id('id_warna');
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_warna', 100);
            $table->string('kode_warna', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // 4. Detail ukuran (varian produk)
        Schema::create('detail_ukuran', function (Blueprint $table) {
            $table->id('id_ukuran');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_warna');
            $table->string('ukuran', 50);
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->text('tambahan')->nullable();
            $table->timestamps();

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_warna')
                ->references('id_warna')
                ->on('detail_warna')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // 5. Gambar produk / varian
        Schema::create('produk_gambar', function (Blueprint $table) {
            $table->id('id_gambar');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_warna')->nullable();
            $table->unsignedBigInteger('id_ukuran')->nullable();
            $table->string('gambar', 255);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('id_produk')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_warna')
                ->references('id_warna')
                ->on('detail_warna')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('id_ukuran')
                ->references('id_ukuran')
                ->on('detail_ukuran')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        // 6. Pemesanan
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->dateTime('tanggal_pemesanan')->useCurrent();
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['pending', 'diproses', 'dikirim', 'selesai', 'batal'])->default('pending');
            $table->timestamps();

            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        // 7. Detail pemesanan
        Schema::create('detail_pemesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_ukuran');
            $table->integer('jumlah');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_ukuran')
                ->references('id_ukuran')
                ->on('detail_ukuran')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        // 8. Pembayaran (disesuaikan untuk Xendit/Midtrans)
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_pemesanan');
            $table->enum('metode_pembayaran', ['transfer', 'cod', 'ewallet', 'va', 'qris', 'credit_card'])->default('transfer');
            $table->string('channel', 100)->nullable();
            $table->string('external_id', 100)->unique();
            $table->string('invoice_id', 100)->unique()->nullable();
            $table->string('payment_url', 255)->nullable();
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->decimal('jumlah_bayar', 12, 2);
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu', 'sudah_bayar', 'gagal', 'expired', 'refund'])->default('belum_bayar');
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // 9. Keranjang Belanja
        Schema::create('cart', function (Blueprint $table) {
            $table->id('id_cart');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_ukuran');
            $table->integer('jumlah')->default(1);
            $table->dateTime('tanggal_ditambahkan')->useCurrent();
            $table->unique(['id_customer', 'id_ukuran']);

            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_ukuran')
                ->references('id_ukuran')
                ->on('detail_ukuran')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // 10. Pengiriman
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->unsignedBigInteger('id_pemesanan');
            $table->string('nama_penerima', 100);
            $table->string('no_hp_penerima', 20);
            $table->text('alamat_tujuan');
            $table->string('ekspedisi', 100);
            $table->string('layanan', 100);
            $table->decimal('biaya_ongkir', 12, 2)->default(0);
            $table->string('no_resi', 100)->unique()->nullable();
            $table->enum('status_pengiriman', ['menunggu', 'dikirim', 'diterima', 'gagal'])->default('menunggu');
            $table->dateTime('tanggal_dikirim')->nullable();
            $table->dateTime('tanggal_diterima')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
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
