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
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->bigInteger('warga_id');
            $table->bigInteger('jenisbayar_id');
            $table->decimal('totalbayar', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('carabayar')->nullable(); // cash / transfer
            $table->boolean('aktifyn')->default(true);
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};
