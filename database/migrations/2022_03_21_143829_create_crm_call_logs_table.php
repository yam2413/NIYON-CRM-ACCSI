<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmCallLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('call_id',100);
            $table->string('profile_id',100);
            $table->string('contact_no',100);
            $table->string('extension',100);
            $table->integer('call_by')->default(0);
            $table->integer('leads_id')->default(0);
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
        Schema::dropIfExists('crm_call_logs');
    }
}
