<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantCapturedTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_captured_transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->text('description');
            $table->decimal('amount', 10, 2);

            // Card
            $table->uuid('authorization_id');
            $table->foreign('authorization_id')->references('id')->on('merchant_authorizations');

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merchant_captured_transactions');
    }
}
