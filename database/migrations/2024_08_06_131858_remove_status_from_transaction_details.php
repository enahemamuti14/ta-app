<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStatusFromTransactionDetails extends Migration
{
    /**
     * Jalankan migrasi untuk menghapus kolom 'status'.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Balikkan migrasi jika diperlukan.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->string('status')->default('pending'); // Tambahkan kolom dengan default value jika perlu
        });
    }
}
