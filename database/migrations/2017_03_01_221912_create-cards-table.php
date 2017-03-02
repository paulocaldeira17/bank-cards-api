<?php

use App\Card;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('accountName');
            $table->string('iban', Card::IBAN_LENGTH)->unique();
            $table->string('bic',  Card::BIC_LENGTH)->unique();
            $table->string('currencyCode', 3)->default(Card::DEFAULT_CURRENCY_CODE);
            $table->decimal('balance', 10, 2)->default(0);
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
        Schema::drop('cards');
    }
}
