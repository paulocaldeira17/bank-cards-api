<?php

namespace App;
use Illuminate\Support\Facades\DB;

/**
 * Class Card Transaction Model
 * @property string id
 * @property double amount
 * @property string card_id
 * @property string type
 * @property string description
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

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        return DB::transaction(function() use ($options) {
            $this->beforeSave();
            $result = parent::save($options);
            $this->afterSave();

            return $result;
        });
    }

    /**
     * Before save transactions
     */
    protected function beforeSave()
    {
        $this->normalizeAmount();
    }

    /**
     * After save transactions
     */
    protected function afterSave()
    {
        //
    }

    /**
     * Normalizes amount
     */
    protected function normalizeAmount()
    {
        $this->amount = abs($this->amount);
    }
}