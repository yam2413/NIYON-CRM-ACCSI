<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_activities', function (Blueprint $table) {
            $table->id();
            $table->string('cron_id',100);
            $table->integer('user')->default(0);
            $table->string('profile_id',100);
            $table->string('type',100);
            $table->longText('to')->nullable();
            $table->longText('body')->nullable();
            $table->integer('status')->default(0);
            $table->longText('error_msg')->nullable();
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
        Schema::dropIfExists('cron_activities');
    }
}
