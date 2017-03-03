<?php

use App\MerchantAuthorization;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_authorizations', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->enum('state', [
                MerchantAuthorization::STATE_OPENED,
                MerchantAuthorization::STATE_CLOSED
            ]);

            // Merchant
            $table->uuid('merchant_id');
            // $table->foreign('merchant_id')->references('id')->on('merchants');

            // Card
            $table->uuid('card_id');
            $table->foreign('card_id')->references('id')->on('cards');

            // Blocked transaction
            $table->uuid('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('card_transactions');

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
        Schema::drop('merchant_authorizations');
    }
}
