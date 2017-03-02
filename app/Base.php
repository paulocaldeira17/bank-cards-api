<?php

namespace App;

use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Base Model
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
    public $hidden = [self::DELETED_AT];

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
     * Returns all resources
     * @param int $limit Limit
     * @param int $skip Skip
     * @return Collection
     */
    public static function getAll($limit = 10, $skip = 0) {
        return self::limit($limit)
            ->offset($skip);
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
     * Deletes resource by id
     * @param $id Resource id
     * @return bool Deleted
     */
    public static function deleteById($id) {
        $resource = self::getById($id);

        if (empty($resource)) {
            return false;
        }

        $resource->deleted_at = date('Y-m-d H:i:s');
        return $resource->save();
    }
}