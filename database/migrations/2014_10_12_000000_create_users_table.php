<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email',100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('level')->default(0);
            $table->string('lastname',100);
            $table->string('firstname',100);
            $table->string('extension',100);
            $table->string('coll',100);
            $table->integer('call_option')->default(0);
            $table->integer('group')->default(0);
            $table->longText('avatar')->nullable();
            $table->longText('mobile')->nullable();
            $table->integer('dialer_loggin')->default(0);
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
        Schema::dropIfExists('users');
    }
}
