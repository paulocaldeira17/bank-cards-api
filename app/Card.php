<?php

namespace App;
use Webpatser\Uuid\Uuid;

/**
 * Class Card Model
 * @property string id
 * @property string accountName
 * @property string iban
 * @property string bic
 * @property double balance
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
     * @return mixed
     */
    public static function create($data = []) {
        $card = new Card();

        $card->id = Uuid::generate();
        $card->accountName = $data['accountName'];
        $card->iban = $data['iban'];
        $card->bic = $data['bic'];
        $card->balance = $data['balance'];

        if ($card->save()) {
            return $card->id;
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
}