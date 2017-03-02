<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Webpatser\Uuid\Uuid;

/**
 * User Model
 * @property string id Uuid
 * @property string name
 * @property string email
 * @property string api_token
 * @property Card[] cards
 */
class User extends Base implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * Constants
     */
    const NAME_LENGTH = 75;
    const EMAIL_LENGTH = 150;

    /**
     * Table name
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'api_token',
    ];

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
     * Creates a new user
     * @param $data User data
     * @return User User created
     */
    public static function create($data)
    {
        if (empty($data)) {
            return false;
        }

        $user = new User;

        $user->id = Uuid::generate();
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['api_token'])) {
            $user->api_token = Uuid::import($data['api_token']);
        }

        return $user;
    }

    /**
     * Regenerates api token
     */
    public function regenerateToken() {
        $this->api_token = self::generateToken();
        return $this->save();
    }

    /**
     * Generates a new api token
     */
    protected static function generateToken() {
        return Uuid::generate();
    }

    /**
     * Returns user cards
     */
    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    /**
     * Returns a user cards
     * @return Collection cards
     */
    public function getCards() {
        if ($cards = $this->cards) {
            return $cards->map(function (Card $c) {
                $c->authorizedBalance = $c->transactions->sum('amount');
                $c->balance = $c->transactions->sum('amount');
                return $c;
            });
        }

        return null;
    }

    /**
     * Returns a user card
     * @param integer $id Card id
     * @return Card card
     */
    public function getCard($id) {
        if ($card = $this->cards()->where('id', $id)->first()) {
            $card->authorizedBalance = $card->transactions->sum('amount');
            $card->balance = $card->transactions->sum('amount');

            return $card;
        }

        return null;
    }
}
