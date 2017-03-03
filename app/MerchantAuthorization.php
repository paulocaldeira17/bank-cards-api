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
}