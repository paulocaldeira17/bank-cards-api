<?php

namespace App;
use App\CardTransaction;
use Webpatser\Uuid\Uuid;

/**
 * Class Card Model
 * @property string id
 * @property string accountName
 * @property string iban
 * @property string bic
 * @property string user_id
 * @property CardTransaction[] transactions
 * @property MerchantAuthorization[] merchantAuthorizations
 *
 * @package App
 */
class Card extends Base
{
    /**
     * IBAN number max length
     */
    const IBAN_LENGTH = 34;

    /**
     * BIC number max length
     */
    const BIC_LENGTH = 11;

    /**
     * Default currency code
     */
    const DEFAULT_CURRENCY_CODE = 'GBP';

    /**
     * Table name
     * @var string
     */
    protected $table = 'cards';

    /**
     * Create card
     * @param array $data Card data
     * @return Card New card
     */
    public static function create($data = []) {
        $card = new Card();

        $card->id = Uuid::generate();
        $card->accountName = $data['accountName'];
        $card->iban = $data['iban'];
        $card->bic = $data['bic'];
        $card->user_id = $data['user_id'];

        return $card;
    }

    /**
     * Updates card
     * @param string $id Card id
     * @param array $data Card data
     * @return mixed
     */
    public static function updateById($id, $data = []) {
        $card = self::getById($id)->first();

        if (empty($card)) {
            return false;
        }

        if (!empty($data['accountName'])) {
            $card->accountName = $data['accountName'];
        }

        if (!empty($data['iban'])) {
            $card->iban = $data['iban'];
        }

        if (!empty($data['bic'])) {
            $card->bic = $data['bic'];
        }

        return $card->save();
    }

    /**
     * Returns card transactions
     */
    public function transactions()
    {
        return $this->hasMany('App\CardTransaction');
    }

    /**
     * Returns card merchants authorizations (blocked amount)
     */
    public function merchantAuthorizations()
    {
        return $this->hasMany('App\MerchantAuthorization');
    }

    /**
     * Returns authorized balance
     * @return double Authorized balance
     */
    public function getAuthorizedBalance()
    {
        if (!isset($this->authorizationBalance)) {
            $this->authorizationBalance = $this->transactions->sum('amount');
        }

        return  $this->authorizationBalance;
    }

    /**
     * Returns balance
     * @return double Balance
     */
    public function getBalance()
    {
        if (!isset($this->balance)) {
            $this->balance = $this->merchantAuthorizations->sum('amount');
        }

        return $this->balance + $this->getAuthorizedBalance();
    }

    /**
     * Makes transaction using card
     * @param CardTransaction $transaction Transaction
     * @return bool Success
     */
    public function makeTransaction(CardTransaction $transaction) {
        if (empty($transaction)) {
            return false;
        }

        $transaction->card_id = $this->id;
        return $transaction->save();
    }
}