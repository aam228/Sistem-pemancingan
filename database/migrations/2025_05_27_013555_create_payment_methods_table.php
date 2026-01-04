<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('nama_metode');
            $table->enum('tipe', ['cash', 'transfer', 'qris']);
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('nama_pemilik')->nullable();
            $table->string('qr_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
