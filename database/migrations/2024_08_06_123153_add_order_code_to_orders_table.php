<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderCodeToOrdersTable extends Migration
{
    /**
     * Tambahkan kolom order_code ke tabel orders.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_code')->after('id'); // Menambahkan kolom order_code
        });
    }

    /**
     * Hapus kolom order_code dari tabel orders.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_code'); // Menghapus kolom order_code jika rollback
        });
    }
}