<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Menambahkan kolom yang hilang
            $table->date('tanggalmulaisewa')->after('id')->nullable();
            $table->date('tanggalberakhirsewa')->after('tanggalmulaisewa')->nullable();
            $table->string('namatenant')->after('tanggalberakhirsewa')->nullable();
            $table->string('statussewa')->after('namatenant')->nullable();
            $table->string('kontak')->after('statussewa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Menghapus kolom yang ditambahkan jika rollback migration
            $table->dropColumn('tanggalmulaisewa');
            $table->dropColumn('tanggalberakhirsewa');
            $table->dropColumn('namatenant');
            $table->dropColumn('statussewa');
            $table->dropColumn('kontak');
        });
    }
}
