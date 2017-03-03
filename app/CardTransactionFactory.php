<?php

namespace App;
use Webpatser\Uuid\Uuid;

/**
 * Class Card Transaction Factory
 * @package App
 */
class CardTransactionFactory
{
    /**
     * Returns a Card Transaction depending on amount
     * @param $amount Amount
     * @return CardTransaction Transaction
     */
    public static function createTransactionByAmount($amount) {
        if (!isset($amount)) {
            return null;
        }

        $type = $amount >= 0 ? CardTransaction::TYPE_DEPOSIT : CardTransaction::TYPE_WITHDRAW;;

        return self::createTransaction($amount, $type);
    }

    /**
     * Creates a transaction
     * @param $amount Amount
     * @param string $type Transactions type
     * @return CardTransaction Card transactions
     */
    public static function createTransaction($amount, $type = CardTransaction::TYPE_DEPOSIT)
    {
        if (!isset($amount) || empty($type)) {
            return null;
        }

        switch ($type) {
            case CardTransaction::TYPE_WITHDRAW:
                $transaction = new WithdrawCardTransaction;
                break;
            case CardTransaction::TYPE_BLOCKED:
                $transaction = new BlockedCardTransaction;
                break;
            case CardTransaction::TYPE_REFUND:
                $transaction = new RefundCardTransaction;
                break;
            default:
            case CardTransaction::TYPE_DEPOSIT:
                $transaction = new DepositCardTransaction;
                break;
        }

        $transaction->id = Uuid::generate();
        $transaction->amount = $amount;

        return $transaction;
    }
}