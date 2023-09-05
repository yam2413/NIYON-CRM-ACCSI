<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('profile_id',100);
            $table->integer('leads_id')->default(0);
            $table->string('file_id',100);
            $table->integer('status')->default(0);
            $table->integer('priority')->default(0);
            $table->integer('idle')->default(0);
            $table->integer('assign_user')->default(0);
            $table->integer('assign_group')->default(0);
            $table->longText('payment_date')->nullable();
            $table->longText('ptp_amount')->nullable();
            $table->longText('remarks')->nullable();
            $table->longText('account_number')->nullable();
            $table->longText('endo_date')->nullable();
            $table->longText('due_date')->nullable();
            $table->longText('loan_amount')->nullable();
            $table->longText('outstanding_balance')->nullable();
            $table->longText('cycle_day')->nullable();
            $table->integer('deleted')->default(0);
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
        Schema::dropIfExists('crm_leads');
    }
}
