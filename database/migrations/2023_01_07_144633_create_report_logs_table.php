<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->default(0);
            $table->string('file_id',100);
            $table->longText('file_path')->nullable();
            $table->longText('file_name')->nullable();
            $table->integer('status')->default(0);
            $table->string('report_type',100);
            $table->longText('import_path')->nullable();
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
        Schema::dropIfExists('report_logs');
    }
}
