<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('change_amount'); // Menambahkan kolom tenant_id setelah kolom change_amount
        });
    }   
    
        public function down()
        {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
};
