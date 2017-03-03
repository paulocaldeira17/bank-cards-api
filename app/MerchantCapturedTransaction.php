<?php

namespace App;
use Webpatser\Uuid\Uuid;

/**
 * Class Card Transaction Model
 * @property string id
 * @property double amount
 * @property string description
 * @property string authorization_id
 *
 * @package App
 */
class MerchantCapturedTransaction extends Base
{
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

        $transaction = new MerchantCapturedTransaction;

        $transaction->id = Uuid::generate();
        $transaction->amount = $data['amount'];
        $transaction->description = $data['description'];
        $transaction->authorization_id = $data['authorization_id'];

        return $transaction;
    }
}