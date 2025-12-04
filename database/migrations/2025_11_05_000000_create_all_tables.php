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
        // 1. Admin
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

        // 2. Customers
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id('id_customer');
                $table->string('nama');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('no_hp')->nullable();
                $table->text('alamat')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 3. Produk
        if (!Schema::hasTable('produk')) {
            Schema::create('produk', function (Blueprint $table) {
                $table->id('id_produk');
                $table->string('nama_produk');
                $table->text('deskripsi');
                $table->string('kategori');
                $table->string('gambar')->nullable();
                $table->integer('total_stok')->default(0);
                $table->timestamps();
            });
        }

        // 4. Detail Warna
        if (!Schema::hasTable('detail_warna')) {
            Schema::create('detail_warna', function (Blueprint $table) {
                $table->id('id_warna');
                $table->unsignedBigInteger('id_produk');
                $table->string('nama_warna');
                $table->string('kode_warna');
                // timestamps false
                $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            });
        }

        // 5. Detail Ukuran
        if (!Schema::hasTable('detail_ukuran')) {
            Schema::create('detail_ukuran', function (Blueprint $table) {
                $table->id('id_ukuran');
                $table->unsignedBigInteger('id_produk');
                $table->unsignedBigInteger('id_warna');
                $table->string('ukuran');
                $table->decimal('harga', 12, 2);
                $table->integer('stok');
                $table->string('tambahan')->nullable();
                // timestamps false
                $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
                $table->foreign('id_warna')->references('id_warna')->on('detail_warna')->onDelete('cascade');
            });
        }

        // 6. Produk Gambar
        if (!Schema::hasTable('produk_gambar')) {
            Schema::create('produk_gambar', function (Blueprint $table) {
                $table->id('id_gambar');
                $table->unsignedBigInteger('id_produk');
                $table->unsignedBigInteger('id_warna')->nullable();
                $table->unsignedBigInteger('id_ukuran')->nullable();
                $table->string('gambar');
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
                $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
                $table->foreign('id_warna')->references('id_warna')->on('detail_warna')->onDelete('cascade');
                $table->foreign('id_ukuran')->references('id_ukuran')->on('detail_ukuran')->onDelete('cascade');
            });
        }

        // 7. Cart
        if (!Schema::hasTable('cart')) {
            Schema::create('cart', function (Blueprint $table) {
                $table->id('id_cart');
                $table->unsignedBigInteger('id_customer');
                $table->unsignedBigInteger('id_ukuran');
                $table->integer('jumlah');
                $table->dateTime('tanggal_ditambahkan');
                // timestamps false
                $table->foreign('id_customer')->references('id_customer')->on('customers')->onDelete('cascade');
                $table->foreign('id_ukuran')->references('id_ukuran')->on('detail_ukuran')->onDelete('cascade');
            });
        }

        // 8. Pemesanan
        if (!Schema::hasTable('pemesanan')) {
            Schema::create('pemesanan', function (Blueprint $table) {
                $table->id('id_pemesanan');
                $table->unsignedBigInteger('id_customer');
                $table->dateTime('tanggal_pemesanan');
                $table->decimal('total_harga', 12, 2);
                $table->string('status');
                $table->timestamps();
                $table->foreign('id_customer')->references('id_customer')->on('customers')->onDelete('cascade');
            });
        }

        // 9. Detail Pemesanan
        if (!Schema::hasTable('detail_pemesanan')) {
            Schema::create('detail_pemesanan', function (Blueprint $table) {
                $table->id('id_detail');
                $table->unsignedBigInteger('id_pemesanan');
                $table->unsignedBigInteger('id_ukuran');
                $table->integer('jumlah');
                $table->decimal('subtotal', 12, 2);
                $table->timestamps();
                $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan')->onDelete('cascade');
                $table->foreign('id_ukuran')->references('id_ukuran')->on('detail_ukuran')->onDelete('cascade');
            });
        }

        // 10. Pengiriman
        if (!Schema::hasTable('pengiriman')) {
            Schema::create('pengiriman', function (Blueprint $table) {
                $table->id('id_pengiriman');
                $table->unsignedBigInteger('id_pemesanan');
                $table->string('nama_penerima');
                $table->string('no_hp_penerima');
                $table->text('alamat_tujuan');
                $table->string('ekspedisi');
                $table->string('layanan');
                $table->decimal('biaya_ongkir', 12, 2);
                $table->string('no_resi')->nullable();
                $table->string('status_pengiriman');
                $table->dateTime('tanggal_dikirim')->nullable();
                $table->dateTime('tanggal_diterima')->nullable();
                $table->timestamps();
                $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
        Schema::dropIfExists('detail_pemesanan');
        Schema::dropIfExists('pemesanan');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('produk_gambar');
        Schema::dropIfExists('detail_ukuran');
        Schema::dropIfExists('detail_warna');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('admins');
    }
};
