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
    Schema::table('transaction_details', function (Blueprint $table) {
        $table->string('payment_method')->after('notes');
        $table->decimal('amount_given', 10, 2)->after('payment_method');
        $table->decimal('change_amount', 10, 2)->after('amount_given');
    });
}

public function down()
{
    Schema::table('transaction_details', function (Blueprint $table) {
        $table->dropColumn(['payment_method', 'amount_given', 'change_amount']);
    });
}

};
