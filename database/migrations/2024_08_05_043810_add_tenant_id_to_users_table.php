<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('tenant_id')->nullable()->after('password');
        
        // Menambahkan foreign key constraint
        $table->foreign('tenant_id')->references('id')->on('tenant')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['tenant_id']);
        $table->dropColumn('tenant_id');
    });
}

};
