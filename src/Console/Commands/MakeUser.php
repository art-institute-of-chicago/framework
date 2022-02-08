<?php

namespace Aic\Hub\Foundation\Console\Commands;

use Aic\Hub\Foundation\User;
use Illuminate\Support\Str;

use Aic\Hub\Foundation\AbstractCommand as BaseCommand;

class MakeUser extends BaseCommand
{
    protected $signature = 'make:user
                            {username : Username for the user, for admin use only}
                            {--token= : Provide a token instead of generating it}
                            ';

    protected $description = 'Create a new user';

    public function handle()
    {
        $username = $this->argument('username');
        $user = User::where('username', '=', $username)->first();

        if ($user && !$this->confirm('User "' . $username . '" already exists. Update token?')) {
            exit(1);
        }

        if ($user) {
            $successPrefix = 'User "' . $username . '" has been updated!';
        } else {
            $successPrefix = 'New API user "' . $username . '" created!';
        }

        if (!$user) {
            $user = new User();
            $user->username = $username;
        }

        $token = $this->option('token') ?? Str::random('32');

        $user->api_token = hash('sha256', $token);
        $user->save();

        $this->info($successPrefix . ' Their API token is as follows:');
        $this->warn($token);
        $this->info('Do not lose this token! It cannot be recovered.');
    }
}
