<?php

namespace Aic\Hub\Foundation\Models;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthorizableContract
{
    use Authorizable, HasApiTokens;

    protected $connection = 'userdata';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'api_token',
    ];

    /**
     * We don't implement Authenticatable, because we don't use passwords or remember tokens,
     * but we need this work-around for a bug with ThrottleRequest.
     *
     * @link https://github.com/laravel/framework/issues/21118
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }
}
