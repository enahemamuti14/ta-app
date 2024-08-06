<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTenantIdToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom tenant_id dengan foreign key
            $table->foreignId('tenant_id')->constrained('tenant')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom tenant_id jika migration di-rollback
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};