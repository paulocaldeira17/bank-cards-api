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
    const MORE_ENDPOINT = '/api/v1/cards/';

    /**
     * Errors
     */
    const UNABLE_TO_CREATE_CARD = 'UnableCreateCard';
    const CARD_NOT_FOUND = 'CardNotFound';

    /**
     * @api {get} /cards Get all cards
     * @apiName GetCards
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiSuccess {Boolean} success Success
     * @apiSuccess {String} data Details
     * @apiSuccess {String} data.items Cards
     *
     * @param Request $request Request
     * @return string Response
     */
    public function index(Request $request)
    {
        $cards = $request->user()->getCards();
        return $this->response->pagination($cards);
    }

    /**
     * @api {post} /cards Create card
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
            $errors = $e->getResponse()->getData();
            return $this->response->error(self::UNABLE_TO_CREATE_CARD, $errors);
        }

        // store
        $data = [
            'accountName' => $request->get('accountName'),
            'iban' => $request->get('iban'),
            'bic' => $request->get('bic'),
            'currencyCode' => $request->get('currencyCode', Card::DEFAULT_CURRENCY_CODE),
            'user_id' => $request->user()->id
        ];

        // Creates card
        $card = Card::create($data);
        if ($card->save()) {
            // Deposit if balance is passed
            if ($balance = $request->get('balance')) {
                $transaction = CardTransactionFactory::createTransaction($balance);
                $card->makeTransaction($transaction);
            }

            $response = [
                'id' => (string) $card->id,
                'more' => self::MORE_ENDPOINT . $card->id
            ];

            return $this->response->success($response);
        }

        return $this->response->error(self::UNABLE_TO_CREATE_CARD);
    }

    /**
     * @api {get} /cards/:id Get card
     * @apiName ShowCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Card unique id
     *
     * @apiSuccess {Boolean} success Success
     * @apiSuccess {String} data Card details
     *
     * @param Request $request Request
     * @param integer $id Card id
     * @return mixed Card
     */
    public function show(Request $request, $id)
    {
        if ($card = $request->user()->getCard($id)) {
            return $this->response->success($card);
        }

        return $this->response->error(self::CARD_NOT_FOUND);
    }

    /**
     * @api {put} /cards/:id Edit card
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
            $errors = $e->getResponse()->getData();
            return $this->response->error(self::UNABLE_TO_CREATE_CARD, $errors);
        }

        // store
        $data = [
            'accountName' => $request->get('accountName'),
            'iban' => $request->get('iban'),
            'bic' => $request->get('bic'),
            'currencyCode' => $request->get('currencyCode'),
        ];

        if (Card::updateById($id, $data)) {
            $response = [
                'id' => (string) $card->id,
                'more' => self::MORE_ENDPOINT . $card->id
            ];

            return $this->response->success($response);
        }

        return $this->response->error(self::CARD_NOT_FOUND);
    }

    /**
     * @api {delete} /cards/:id Remove card
     * @apiName DeleteCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Card unique id
     *
     * @apiSuccess {Boolean} success Deleted
     *
     * @param Request $request Request
     * @param integer $id Card id
     * @return mixed Response
     */
    public function destroy(Request $request, $id)
    {
        if ($card = $request->user()->getCard($id)) {

            if ($card->delete()) {
                return $this->response->success();
            }
        }

        return $this->response->error(self::CARD_NOT_FOUND);
    }

    /**
     * @api {get} /cards/:id/balance Card balance
     * @apiName BalanceCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Card unique id
     *
     * @apiSuccess {Boolean} success Deleted
     * @apiSuccess {Boolean} data.authorizedBalance Authorized Balance
     * @apiSuccess {Boolean} data.balance Deleted Balance
     *
     * @param Request $request Request
     * @param integer $id Card id
     * @return mixed Response
     */
    public function balance(Request $request, $id)
    {
        if ($card = $request->user()->getCard($id)) {
            $response = [
                'authorizedBalance' => $card->authorizedBalance,
                'balance' => $card->balance,
            ];

            return $this->response->success($response);
        }

        return $this->response->error(self::CARD_NOT_FOUND);
    }

    /**
     * @api {get} /cards/:id/transactions Card transactions
     * @apiName DeleteCard
     * @apiGroup Cards
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id Card unique id
     *
     * @apiSuccess {Boolean} success Deleted
     * @apiSuccess {Boolean} data.items Transactions
     *
     * @param Request $request Request
     * @param integer $id Card id
     * @return mixed Response
     */
    public function transactions(Request $request, $id)
    {
        $transactions = $request->user()->getCard($id)->transactions;
        return $this->response->pagination($transactions);
    }
}
