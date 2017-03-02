<?php

namespace App;
use Webpatser\Uuid\Uuid;

/**
 * Class Card Transaction Factory
 * @package App
 */
class CardTransactionFactory extends Base
{
    /**
     * Returns a Card Transaction depending on amount
     * @param $amount Amount
     * @return CardTransaction Transaction
     */
    public static function createTransaction($amount) {
        if (!isset($amount)) {
            return false;
        }

        $transaction = new CardTransaction;

        $transaction->id = Uuid::generate();
        $transaction->amount = $amount;
        $transaction->type = $amount >= 0 ?
            CardTransaction::TYPE_DEPOSIT : CardTransaction::TYPE_WITHDRAW;

        return $transaction;
    }
}