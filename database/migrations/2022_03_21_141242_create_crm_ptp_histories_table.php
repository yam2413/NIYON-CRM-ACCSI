<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmPtpHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_ptp_histories', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no',100);
            $table->string('profile_id',100);
            $table->integer('assign_group')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('status')->default(0);
            $table->string('payment_date',100)->nullable();
            $table->string('payment_amount',100)->nullable();
            $table->longText('remarks')->nullable();
            $table->string('attempt',100)->nullable();
            $table->string('place_call',100)->nullable();
            $table->string('contact_type',100)->nullable();
            $table->string('call_status',100)->nullable();
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
        Schema::dropIfExists('crm_ptp_histories');
    }
}
