<?php

namespace App\Http\Controllers;

use App\Card;
use App\CardTransactionFactory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class CardsController
 * @package App\Http\Controllers
 */
class CardsController extends Controller
{
    /**
     * Constants
     */
    const MORE_ENDPOINT = '/cards/';

    /**
     * @api {get} /cards Get all cards
     * @apiName GetCards
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Integer} [limit=10] Limit
     * @apiParam {Integer} [skip=0] Skip (offset)
     *
     * @apiSuccess {Boolean} success Success
     * @apiSuccess {String} data Details
     * @apiSuccess {String} data.items Cards
     */
    public function index()
    {
        $cards = Card::getAllWithBalance();

        $response = [
            'success' => true,
            'data' => [
                'items' => $cards,
            ]
        ];

        return $response;
    }

    /**
     * @api {post} /cards Create Card
     * @apiName StoreCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {String} accountName Account name
     * @apiParam {String} iban IBAN
     * @apiParam {String} bic BIC
     * @apiParam {Double} [balance] Balance
     * @apiParam {String} [currencyCode=GBP] Currency Code
     *
     * @apiSuccess {Boolean} success Created
     * @apiSuccess {String} data Details
     * @apiSuccess {String} data.id Card id
     * @apiSuccess {String} data.more Path to access card information
     *
     * @param Request $request Request object
     * @return array Response
     */
    public function store(Request $request)
    {
        $response = [
            'success' => false,
            'data' => [
                'id' => null,
                'more' => null
            ]
        ];

        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'accountName' => 'required',
            'iban'        => 'required|unique:cards|max:' . Card::IBAN_LENGTH,
            'bic'         => 'required|unique:cards|max:' . Card::BIC_LENGTH,
        );

        // validates request
        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return $response;
        }

        // store
        $data = [
            'accountName' => $request->get('accountName'),
            'iban' => $request->get('iban'),
            'bic' => $request->get('bic'),
            'currencyCode' => $request->get('currencyCode', Card::DEFAULT_CURRENCY_CODE),
        ];

        // Creates card
        if ($card = Card::create($data)) {

            // Deposit if balance is passed
            if ($balance = $request->get('balance')) {
                $transaction = CardTransactionFactory::createTransaction($balance);
                $card->makeTransaction($transaction);
            }

            $response['success'] = true;

            $response['data']['id'] = $card->id;
            $response['data']['more'] = self::MORE_ENDPOINT . $card->id;
        }

        return $response;
    }

    /**
     * @api {get} /cards/:id Get Card
     * @apiName ShowCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Card unique id
     *
     * @apiSuccess {Boolean} success Success
     * @apiSuccess {String} data Card details
     *
     * @param integer $id Card id
     * @return mixed Card
     */
    public function show($id)
    {
        $response = array(
            'success' => false,
            'data' => []
        );

        if ($card = Card::getById($id)) {
            $response['success'] = true;
            $response['data'] = $card;
        }

        return $response;
    }

    /**
     * @api {put} /cards/:id Edit Card
     * @apiName UpdateCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Card unique id
     *
     * @apiParam {String} [accountName] Account name
     * @apiParam {String} [iban] IBAN
     * @apiParam {String} [bic] BIC
     * @apiParam {String} [currencyCode=GBP] Currency Code
     *
     * @apiSuccess {Boolean} success Updated
     * @apiSuccess {String} data Details
     * @apiSuccess {String} data.id Card id
     * @apiSuccess {String} data.more Path to access card information
     *
     * @param Request $request Request
     * @param string $id Card id
     *
     * @return mixed Card updated
     */
    public function update(Request $request, $id)
    {
        $response = [
            'success' => false,
            'data' => [
                'id' => $id,
                'more' => self::MORE_ENDPOINT . $id
            ]
        ];

        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'iban' => 'unique:cards|max:' . Card::IBAN_LENGTH,
            'bic'  => 'unique:cards|max:' . Card::BIC_LENGTH
        );

        // validates request
        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return $response;
        }

        // store
        $data = [
            'accountName' => $request->get('accountName'),
            'iban' => $request->get('iban'),
            'bic' => $request->get('bic'),
            'currencyCode' => $request->get('currencyCode'),
        ];

        $response['success'] = Card::updateById($id, $data);

        return $response;
    }

    /**
     * @api {delete} /cards/:id Remove Card
     * @apiName DeleteCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Card unique id
     *
     * @apiSuccess {Boolean} success Deleted
     *
     * @param integer $id Card id
     * @return mixed Response
     */
    public function destroy($id)
    {
        $response = [
            'success' => false
        ];

        if (Card::deleteById($id)) {
            $response['success'] = true;
        }

        return $response;
    }
}
