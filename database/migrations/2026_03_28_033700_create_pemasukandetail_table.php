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
        Schema::create('pemasukandetail', function (Blueprint $table) {
            $table->id(); 
            $table->bigInteger('terima_id') ;
            $table->string('periode_bayar'); // contoh: 2026-03
            $table->boolean('aktifyn')->default(true);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukandetail');
    }
};


