<?php

namespace App;

use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Base Model
 * @property string deleted_at Deleted at
 * @package App
 */
class Base extends Model
{
    /**
     * Uuid usage
     */
    use Uuids;

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const DELETED_AT = 'deleted_at';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Hidden fields
     *
     * @var bool
     */
    protected $hidden = [self::DELETED_AT];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActiveScope);
    }

    /**
     * Returns resource by id
     * @param $id Resource id
     * @return mixed Resource
     */
    public static function getById($id)
    {
        if (empty($id)) {
            return false;
        }

        return self::where('id', $id);
    }

    /**
     * Deletes resource
     * @return bool Deleted
     */
    public function delete() {
        $this->deleted_at = date('Y-m-d H:i:s');
        return $this->save();
    }
}