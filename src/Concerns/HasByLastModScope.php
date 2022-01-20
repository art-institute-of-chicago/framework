<?php

namespace Aic\Hub\Foundation\Concerns;

trait HasByLastModScope
{

    /**
     * WEB-1903: For use in API by AbstractController.
     */
    public function scopeByLastMod($query)
    {
        return $query->orderBy(self::getTableName() . '.updated_at', 'desc');
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

        return with(new static())->getTable();

    }

}
