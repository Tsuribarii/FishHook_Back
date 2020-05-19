<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTideInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tide_informations', function (Blueprint $table) {
            $table->bigIncrements('id')->comment("물때 번호");
            $table->string('location')->comment("위치");
            // $table->foreign('location')->references('id')->on('tide_locations')->onDelete('cascade');
            $table->string('date')->comment("날짜");
            $table->string('hide_tide')->comment("물때");
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
        Schema::dropIfExists('tide_informations');
    }
}
