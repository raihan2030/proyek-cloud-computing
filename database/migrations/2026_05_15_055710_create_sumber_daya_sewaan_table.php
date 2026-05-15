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
        Schema::create('sumber_daya_sewaan', function (Blueprint $table) {
            $table->uuid('id_sewa')->primary();
            
            // Foreign Keys
            $table->foreignUuid('id_pengguna')->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->foreignId('id_paket')->constrained('paket_langganan', 'id_paket')->restrictOnDelete();
            
            $table->string('nama_bucket')->unique();
            $table->string('access_key');
            
            $table->text('secret_key'); 
            
            $table->boolean('status_aktif')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sumber_daya_sewaan');
    }
};
