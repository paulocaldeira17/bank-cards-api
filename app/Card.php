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
 * @property CardTransaction[] transactions
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

        if ($card->save()) {
            return $card;
        }

        return false;
    }

    /**
     * Updates card
     * @param string $id Card id
     * @param array $data Card data
     * @return mixed
     */
    public static function updateById($id, $data = []) {
        $card = self::getById($id);

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
     * Returns cards with balance
     * @param int $limit Limit
     * @param int $skip Skip
     * @return mixed Cards with balance
     */
    public static function getAllWithBalance($limit = 10, $skip = 0)
    {
        return self::getAll($limit, $skip)->get()->map(function (Card $card) {
            $card->balance = $card->transactions->sum('amount');
            return $card;
        });
    }

    /**
     * Returns card by id with balance
     * @param $id Card id
     * @return mixed Card
     */
    public static function getByIdWithBalance($id)
    {
        return self::getById($id)->get()->map(function (Card $card) {
            $card->balance = $card->transactions->sum('amount');
            return $card;
        });
    }

    /**
     * Returns card transactions
     */
    public function transactions()
    {
        return $this->hasMany('App\CardTransaction');
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
        $transaction->save();
    }
}