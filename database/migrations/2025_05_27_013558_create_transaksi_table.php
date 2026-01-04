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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('spot_id')->constrained('spots')->onDelete('cascade');
            
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();

            $table->string('nama_pelanggan');
            $table->enum('tipe_sesi', ['pagi', 'siang', 'sore', 'malam', 'pagi_sore', 'full']);
            $table->integer('durasi')->default(0);
            $table->decimal('total_harga', 15, 2); 
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai');

            $table->integer('jumlah_ikan_kecil')->nullable();
            $table->decimal('berat_ikan_babon', 10, 2)->nullable()->default(0);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};
