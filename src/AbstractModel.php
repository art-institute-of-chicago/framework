<?php

namespace Aic\Hub\Foundation;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{

    /**
     * Indicates if the IDs are auto-incrementing. We don't want this in most cases.
     * Whenever possible, we want to preserve the IDs as found in the source system.
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable. Contrary to Laravel's defaults, we want all attributes
     * to be mass assignable for import. This is safe, because we never use user input to modify models.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope('lastmod', function ($builder) {
            $builder->orderBy( self::getTableName() . '.updated_at', 'desc');
        });

    }

    /**
     * Get this model's table name statically.
     *
     * @link https://stackoverflow.com/questions/14082682/how-to-return-database-table-name-in-laravel
     *
     * @return string
     */
    public static function getTableName()
    {

        return with(new static)->getTable();

    }

    /**
     * Validate an id. Useful for validating routes or query string params.
     *
     * By default, only numeric ids greater than zero are accepted. Override this
     * method in child classes to implement different validation rules (e.g. UUID).
     *
     * @param mixed $id
     * @return boolean
     */
    public static function validateId( $id )
    {

        // By default, only allow numeric ids greater than 0
        return is_numeric($id) && intval($id) > 0;

    }

}
