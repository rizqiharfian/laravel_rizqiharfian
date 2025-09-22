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
    Schema::create('pasiens', function (Blueprint $table) {
        $table->id();
        $table->string('nama_pasien');   // <-- pastikan ada ini
        $table->string('alamat');
        $table->string('no_telpon', 20);
        $table->unsignedBigInteger('rumah_sakit_id');
        $table->timestamps();

        $table->foreign('rumah_sakit_id')->references('id')->on('rumah_sakits')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
