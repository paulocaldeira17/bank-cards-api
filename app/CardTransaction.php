<?php

namespace App;
use Webpatser\Uuid\Uuid;

/**
 * Class Card Transaction Model
 * @property string id
 * @property double amount
 * @property string card_id
 * @property string type
 *
 * @package App
 */
class CardTransaction extends Base
{
    /**
     * Transactions types
     */
    const TYPE_DEPOSIT  = "deposit";
    const TYPE_WITHDRAW = "withdraw";
    const TYPE_REFUND   = "refund";
    const TYPE_BLOCKED  = "blocked";

    /**
     * Table name
     * @var string
     */
    protected $table = 'card_transactions';

    /**
     * Returns transaction card
     */
    public function card()
    {
        return $this->belongsTo('App\Card');
    }
}