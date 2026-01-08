<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_spot'); 
            $table->enum('status', ['tersedia', 'digunakan', 'perawatan'])->default('tersedia');
            $table->decimal('tarif_pagi', 10, 2)->default(25000);
            $table->decimal('tarif_siang', 10, 2)->default(20000);
            $table->decimal('tarif_sore', 10, 2)->default(30000);
            $table->decimal('tarif_malam', 10, 2)->default(40000);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spots');
    }
};
