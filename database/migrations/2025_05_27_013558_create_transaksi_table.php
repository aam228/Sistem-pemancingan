<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('meja_id')
                  ->constrained('meja')
                  ->onDelete('cascade');

            $table->string('nama_pelanggan');

            $table->enum('tipe_sesi', [
                'pagi',
                'siang',
                'sore',
                'malam'
            ]);

            $table->integer('durasi')->default(0);

            // Harga FIX dari sesi
            $table->decimal('total_harga', 10, 2);

            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai');

            // Hasil pancing
            $table->integer('jumlah_ikan_kecil')->default(0);
            $table->decimal('berat_ikan_babon', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};
