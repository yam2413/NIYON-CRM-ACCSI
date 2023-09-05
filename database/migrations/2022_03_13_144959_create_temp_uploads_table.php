<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->default(0);
            $table->string('upload_type',100);
            $table->integer('header')->default(0);
            $table->string('file_id',100);
            $table->string('profile_id',100);
            $table->integer('error')->default(0);
            $table->integer('success')->default(0);
            $table->longText('error_msg')->nullable();
            $table->longText('data1')->nullable();
            $table->longText('data2')->nullable();
            $table->longText('data3')->nullable();
            $table->longText('data4')->nullable();
            $table->longText('data5')->nullable();
            $table->longText('data6')->nullable();
            $table->longText('data7')->nullable();
            $table->longText('data8')->nullable();
            $table->longText('data9')->nullable();
            $table->longText('data10')->nullable();
            $table->longText('data11')->nullable();
            $table->longText('data12')->nullable();
            $table->longText('data13')->nullable();
            $table->longText('data14')->nullable();
            $table->longText('data15')->nullable();
            $table->longText('data16')->nullable();
            $table->longText('data17')->nullable();
            $table->longText('data18')->nullable();
            $table->longText('data19')->nullable();
            $table->longText('data20')->nullable();
            $table->longText('data21')->nullable();
            $table->longText('data22')->nullable();
            $table->longText('data23')->nullable();
            $table->longText('data24')->nullable();
            $table->longText('data25')->nullable();
            $table->longText('data26')->nullable();
            $table->longText('data27')->nullable();
            $table->longText('data28')->nullable();
            $table->longText('data29')->nullable();
            $table->longText('data30')->nullable();
            $table->longText('data31')->nullable();
            $table->longText('data32')->nullable();
            $table->longText('data33')->nullable();
            $table->longText('data34')->nullable();
            $table->longText('data35')->nullable();
            $table->longText('data36')->nullable();
            $table->longText('data37')->nullable();
            $table->longText('data38')->nullable();
            $table->longText('data39')->nullable();
            $table->longText('data40')->nullable();
            $table->longText('data41')->nullable();
            $table->longText('data42')->nullable();
            $table->longText('data43')->nullable();
            $table->longText('data44')->nullable();
            $table->longText('data45')->nullable();
            $table->longText('data46')->nullable();
            $table->longText('data47')->nullable();
            $table->longText('data48')->nullable();
            $table->longText('data49')->nullable();
            $table->longText('data50')->nullable();
            $table->longText('data51')->nullable();
            $table->longText('data52')->nullable();
            $table->longText('data53')->nullable();
            $table->longText('data54')->nullable();
            $table->longText('data55')->nullable();
            $table->longText('data56')->nullable();
            $table->longText('data57')->nullable();
            $table->longText('data58')->nullable();
            $table->longText('data59')->nullable();
            $table->longText('data60')->nullable();
            $table->longText('data61')->nullable();
            $table->longText('data62')->nullable();
            $table->longText('data63')->nullable();
            $table->longText('data64')->nullable();
            $table->longText('data65')->nullable();
            $table->longText('data66')->nullable();
            $table->longText('data67')->nullable();
            $table->longText('data68')->nullable();
            $table->longText('data69')->nullable();
            $table->longText('data70')->nullable();
            $table->longText('data71')->nullable();
            $table->longText('data72')->nullable();
            $table->longText('data73')->nullable();
            $table->longText('data74')->nullable();
            $table->longText('data75')->nullable();
            $table->longText('data76')->nullable();
            $table->longText('data77')->nullable();
            $table->longText('data78')->nullable();
            $table->longText('data79')->nullable();
            $table->longText('data80')->nullable();
            $table->longText('data81')->nullable();
            $table->longText('data82')->nullable();
            $table->longText('data83')->nullable();
            $table->longText('data84')->nullable();
            $table->longText('data85')->nullable();
            $table->longText('data86')->nullable();
            $table->longText('data87')->nullable();
            $table->longText('data88')->nullable();
            $table->longText('data89')->nullable();
            $table->longText('data90')->nullable();
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
        Schema::dropIfExists('temp_uploads');
    }
}
