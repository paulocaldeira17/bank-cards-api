<?php

namespace App;

/**
 * Class Refund Card Transaction Model
 * @package App
 */
class RefundCardTransaction extends CardTransaction
{
    /**
     * Defaults attributes
     * @var array
     */
    public $attributes = [
        'type' => self::TYPE_REFUND
    ];
}