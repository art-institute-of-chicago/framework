<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    private $schema;

    /**
     * Use custom connection for these tables to ensure that they
     * don't get reset when we version our data using prefixes.
     */
    public function __construct()
    {
        $this->schema = Schema::connection('userdata');
    }

    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->id();
            // not used for authentication. it's just here for us admins!
            $table->text('username')->nullable();
            // hashed tokens are sha256, i.e. 64-digit hex numbers
            $table->string('api_token', 64)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('users');
    }
}
