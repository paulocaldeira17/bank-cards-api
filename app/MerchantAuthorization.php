<?php

namespace App;
use Webpatser\Uuid\Uuid;

/**
 * Class Card Transaction Model
 * @property string id
 * @property double amount
 * @property string description
 * @property string card_id
 * @property string merchant_id
 * @property string transaction_id
 * @property string state
 * @property MerchantCapturedTransaction[] captures
 *
 * @package App
 */
class MerchantAuthorization extends Base
{
    /**
     * States constants
     */
    const STATE_OPENED = 'opened';
    const STATE_CLOSED = 'closed';

    /**
     * Returns resource by id
     * @param $id Resource id
     * @return mixed Resource
     */
    public static function getById($id)
    {
        if ($query = parent::getById($id)) {
            return $query->first();
        }

        return null;
    }

    /**
     * Creates a new merchant authorization
     * @param $data Data
     * @return MerchantAuthorization
     */
    public static function create($data)
    {
        if (empty($data)) {
            return null;
        }

        $authorization = new MerchantAuthorization;

        $authorization->id = Uuid::generate();
        $authorization->amount = $data['amount'];
        $authorization->description = $data['description'];

        // TODO: Replace to a real merchant
        $authorization->merchant_id = Uuid::generate();

        $authorization->card_id = $data['card_id'];
        $authorization->transaction_id = $data['transaction_id'];
        $authorization->state = self::STATE_OPENED;

        return $authorization;
    }

    /**
     * Merchant captures
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function captures() {
        return $this->hasMany('App\MerchantCapturedTransaction', 'authorization_id');
    }

    /**
     * Returns remaining amount
     * @return Remaining Amount
     */
    public function getRemainingAmount() {
        return $this->amount - $this->captures->sum('amount');
    }
}