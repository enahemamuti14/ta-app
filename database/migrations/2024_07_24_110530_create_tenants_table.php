<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tenant', function (Blueprint $table) {
            $table->id();
            $table->date('tanggalmulaisewa');
            $table->date('tanggalberakhirsewa');
            $table->string('namatenant');
            $table->string('statussewa');
            $table->string('kontak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant');
    }
};
