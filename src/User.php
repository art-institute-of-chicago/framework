<?php

namespace Aic\Hub\Foundation;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthorizableContract
{
    use Authorizable;

    protected $fillable = [
        'username',
        'api_token',
    ];
}
