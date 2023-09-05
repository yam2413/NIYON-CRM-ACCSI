<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddedCampaignsAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('added_campaigns_agents', function (Blueprint $table) {
            $table->id();
            $table->string('file_id',100);
            $table->integer('current_account')->default(0);
            $table->integer('collector_id')->default(0);
            $table->integer('account_status')->default(0);
            $table->integer('logged_in')->default(0);
            $table->string('contact_no',100);
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
        Schema::dropIfExists('added_campaigns_agents');
    }
}
