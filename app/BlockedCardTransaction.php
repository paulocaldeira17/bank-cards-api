<?php

namespace App;
use App\MerchantAuthorization;

/**
 * Class Blocked Card Transaction Model
 * @property string authorization_id
 * @package App
 */
class BlockedCardTransaction extends CardTransaction
{
    /**
     * Defaults attributes
     * @var array
     */
    public $attributes = [
        'type' => self::TYPE_BLOCKED
    ];

    /**
     * Normalizes amount
     */
    protected function normalizeAmount()
    {
        $this->amount = -abs($this->amount);
    }

    /**
     * After save transaction
     */
    protected function afterSave()
    {
        parent::afterSave();

        $data = [
            'transaction_id' => $this->id,
            'card_id' => $this->card_id,
            'description' => $this->description,
            'amount' => abs($this->amount)
        ];

        $authorization = MerchantAuthorization::create($data);
        $authorization->save();

        $this->authorization_id = $authorization->id;
    }
}