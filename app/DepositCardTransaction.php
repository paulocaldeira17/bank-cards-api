<?php

namespace App;

/**
 * Class Deposit Card Transaction Model
 * @package App
 */
class DepositCardTransaction extends CardTransaction
{
    /**
     * Defaults attributes
     * @var array
     */
    public $attributes = [
        'type' => self::TYPE_DEPOSIT
    ];
}