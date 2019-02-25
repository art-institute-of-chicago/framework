<?php

namespace Aic\Hub\Foundation\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Aic\Hub\Foundation\AbstractCommand as BaseCommand;

class DatabaseReset extends BaseCommand
{

    protected $signature = 'db:reset';

    protected $description = 'Removes all tables in current database';

    public function handle()
    {

        if( $this->confirmReset() )
        {

            $this->dropTables();

        } else {

            $this->info('Database reset command aborted. Whew!');

        }

    }

    private function confirmReset()
    {

        return (
            $this->confirm('Are you sure you want to drop all tables in `'.env('DB_DATABASE').'`? [y|N]')
        ) && (
            env('APP_ENV') === 'local' || $this->confirm('You aren\'t running in `local` environment. Are you really sure? [y|N]')
        ) && (
            env('APP_ENV') !== 'production' || $this->confirm('You are in production! Are you really, really sure? [y|N]')
        ) && (
            !empty(DB::getTablePrefix()) || $this->confirm('Your table prefix is empty. All prefixed tables will be dropped. Continue? [y|N]')
        );

    }

    private function dropTables()
    {

        // In case we get interrupted midway
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Specifying `FULL` returns `Table_type`
        $tables = DB::select("SHOW FULL TABLES;");

        // For trimming and ignoring
        $table_prefix = DB::getTablePrefix();

        foreach( $tables as $table )
        {

            $table_array = get_object_vars( $table );
            $table_name = $table_array[ key( $table_array ) ];

            // TODO: Require laravel\helpers upon upgrade to [5.8]?
            if (!empty($table_prefix) && !starts_with($table_name, $table_prefix))
            {
                $this->line('<fg=blue>Skipping ' . $table_name . '</>');
                continue;
            }

            switch( $table_array['Table_type'] )
            {
                case 'VIEW':
                    $this->warn( 'Dropping view ' . $table_name );
                    DB::statement('DROP VIEW `' . $table_name . '`;');
                break;
                default:
                    $this->info( 'Dropping table ' . $table_name );
                    $table_name = substr($table_name, strlen($table_prefix));
                    Schema::drop( $table_name );
                break;
            }

        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }

}
