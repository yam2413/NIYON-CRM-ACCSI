<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmBorrowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_borrowers', function (Blueprint $table) {
            $table->id();
            $table->string('profile_id',100);
            $table->longText('full_name')->nullable();
            $table->longText('birthday')->nullable();
            $table->longText('first_name')->nullable();
            $table->longText('last_name')->nullable();
            $table->longText('middle_name')->nullable();
            $table->longText('address')->nullable();
            $table->longText('email')->nullable();
            $table->longText('home_no')->nullable();
            $table->longText('business_no')->nullable();
            $table->longText('cellphone_no')->nullable();
            $table->longText('other_phone_1')->nullable();
            $table->longText('other_phone_2')->nullable();
            $table->longText('other_phone_3')->nullable();
            $table->longText('other_phone_4')->nullable();
            $table->longText('other_phone_5')->nullable();
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
        Schema::dropIfExists('crm_borrowers');
    }
}
