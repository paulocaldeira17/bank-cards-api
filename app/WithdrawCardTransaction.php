<?php

namespace App;

/**
 * Class Withdraw Card Transaction Model
 * @package App
 */
class WithdrawCardTransaction extends CardTransaction
{
    /**
     * Defaults attributes
     * @var array
     */
    public $attributes = [
        'type' => self::TYPE_WITHDRAW
    ];

    /**
     * Normalizes amount
     */
    protected function normalizeAmount()
    {
        $this->amount = -abs($this->amount);
    }
}