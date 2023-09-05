<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->default(0);
            $table->string('file_id',100);
            $table->integer('active_dialer')->default(0);
            $table->integer('group')->default(0);
            $table->string('start_time',100);
            $table->string('end_time',100);
            $table->longText('campaign_name')->nullable();
            $table->integer('one_day_before')->default(0);
            $table->integer('prioritize_new_leads')->default(0);
            $table->integer('filter_cycle_day')->default(0);
            $table->integer('filter_outstanding_balance')->default(0);
            $table->integer('auto_assign')->default(1);    
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
        Schema::dropIfExists('campaigns');
    }
}
