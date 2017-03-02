<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * Constants
     */
    const MORE_ENDPOINT = '/api/v1/users/';

    /**
     * @api {get} /users Get users
     * @apiName GetUsers
     * @apiGroup Users
     * @apiVersion 0.1.0
     *
     * @apiParam {Integer} [limit=10] Limit
     * @apiParam {Integer} [skip=0] Skip (offset)
     *
     * @apiSuccess {Boolean} success Success
     * @apiSuccess {String} data Details
     * @apiSuccess {String} data.items Users
     */
    public function index()
    {
        $users = User::all();

        $response = [
            'success' => true,
            'data' => [
                'items' => $users
            ]
        ];

        return $response;
    }

    /**
     * @api {post} /users Create user
     * @apiName StoreUser
     * @apiGroup Users
     * @apiVersion 0.1.0
     *
     * @apiParam {String} name User name
     * @apiParam {String} email User email
     *
     * @apiSuccess {Boolean} success Created
     * @apiSuccess {String} data Details
     * @apiSuccess {String} data.id User id
     * @apiSuccess {String} data.more Path to access user information
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
            'name' => 'required|max:' . User::NAME_LENGTH,
            'email' => 'required|email|unique:users|max:' . User::EMAIL_LENGTH,
        );

        // validates request
        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return $response;
        }

        // store
        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email')
        ];

        // Creates card
        $user = User::create($data);
        if ($user->save()) {
            $response['success'] = true;

            $response['data']['id'] = $user->id;
            $response['data']['more'] = self::MORE_ENDPOINT . $user->id;
        }

        return $response;
    }

    /**
     * @api {get} /users/:id Get user
     * @apiName ShowUser
     * @apiGroup Users
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id User unique id
     *
     * @apiSuccess {Boolean} success Success
     * @apiSuccess {String} data User details
     *
     * @param integer $id User id
     * @return mixed User
     */
    public function show($id)
    {
        $response = array(
            'success' => false,
            'data' => []
        );

        if ($user = User::getById($id)) {
            $response['success'] = true;
            $response['data'] = $user;
        }

        return $response;
    }

    /**
     * @api {get} /users/:id/token Generates token
     * @apiName GenerateTokenUser
     * @apiGroup Users
     * @apiVersion 0.1.0
     *
     * @apiParam {Number} id User unique id
     *
     * @apiSuccess {Boolean} success Success
     * @apiSuccess {String} data User details
     * @apiSuccess {String} data.token User details
     *
     * @param integer $id User id
     * @return mixed User
     */
    public function generateToken($id)
    {
        $response = array(
            'success' => false,
            'data' => []
        );

        /**
         * @var User $user
         */
        if ($user = User::getById($id)) {
            // regenerates token
            $user->regenerateToken();

            $response['success'] = true;
            $response['data']['token'] = (string) $user->api_token;
        }

        return $response;
    }
}
