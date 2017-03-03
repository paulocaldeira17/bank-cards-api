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
     * Scope a query to only opened authorizations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpened($query)
    {
        return $query->where('state', '=', self::STATE_OPENED);
    }

    /**
     * Scope a query to only closed authorizations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->where('state', '=', self::STATE_CLOSED);
    }

    /**
     * Returns true if it is opened
     * @return bool Opened
     */
    public function isOpened() {
        return $this->state == self::STATE_OPENED;
    }

    /**
     * Returns true if it is closed
     * @return bool Closed
     */
    public function isClosed() {
        return $this->state == self::STATE_CLOSED;
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