<?php

namespace App;

/**
 * Class Blocked Card Transaction Model
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
}