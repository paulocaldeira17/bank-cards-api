<?php

use App\CardTransaction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->decimal('amount', 10, 2);
            $table->uuid('card_id');
            $table->foreign('card_id')->references('id')->on('cards');
            $table->enum('type', [CardTransaction::TYPE_DEPOSIT, CardTransaction::TYPE_WITHDRAW]);

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
        Schema::drop('card_transactions');
    }
}
