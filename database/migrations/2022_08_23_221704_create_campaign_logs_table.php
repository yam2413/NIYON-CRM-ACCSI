<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->default(0);
            $table->string('file_id',100);
            $table->integer('leads_id')->default(0);
            $table->string('log_type',100);
            $table->longText('actions')->nullable();
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
        Schema::dropIfExists('campaign_logs');
    }
}
