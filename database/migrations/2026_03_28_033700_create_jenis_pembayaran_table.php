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
        Schema::create('jenispembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('jenisbayar'); // IPL, Kas, dll
            $table->decimal('defaultbayar', 15, 2)->default(0);
            $table->string('tipepembayaran')->nullable(); // bulanan / tahunan
            $table->string('debetkredit')->default('D'); // D = debit, K = kredit
            $table->boolean('aktifyn')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenispembayaran');
    }
};