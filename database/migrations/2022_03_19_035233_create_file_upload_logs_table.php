<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_upload_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->default(0);
            $table->string('upload_type',100);
            $table->longText('job_id')->nullable();
            $table->string('file_id',100);
            $table->integer('status')->default(0);
            $table->integer('data_type')->default(0);
            $table->longText('path')->nullable();
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
        Schema::dropIfExists('file_upload_logs');
    }
}
