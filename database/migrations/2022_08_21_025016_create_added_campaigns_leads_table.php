<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddedCampaignsLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('added_campaigns_leads', function (Blueprint $table) {
            $table->id();
            $table->string('file_id',100);
            $table->integer('leads_id')->default(0);
            $table->integer('collector_id')->default(0);
            $table->integer('dial')->default(0);
            $table->integer('process')->default(0);
            $table->longText('campaign_name')->nullable();
            $table->timestamps('dial_date')->nullable();
            $table->timestamps('process_date')->nullable();
            $table->string('process_time',100);
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
        Schema::dropIfExists('added_campaigns_leads');
    }
}
