<?php

namespace App;

/**
 * Class Refund Card Transaction Model
 * @property string authorization_id
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

    /**
     * Before save transaction
     */
    protected function beforeSave()
    {
        parent::beforeSave();

        $authorization = MerchantAuthorization::getById($this->authorization_id);
        $authorization->state = MerchantAuthorization::STATE_CLOSED;
        $authorization->save();
        unset($this->authorization_id);
    }
}