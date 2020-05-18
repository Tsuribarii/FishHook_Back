<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_informations', function (Blueprint $table) {
        //location,time,weather_status,temperature,wind_direction,wind_speed,wave_height,wave_direction,wave_period,humidity
            $table->bigIncrements('id')->comment("날씨 번호");
            $table->string('location')->comment("위치");
            $table->foreign('location')->references('id')->on('tide_locations');
            $table->string('time')->comment("시간");
            $table->string('weather_status')->comment("날씨상태");
            $table->string('temperature')->comment("온도");
            $table->string('wind_direction')->comment("풍향");
            $table->string('wind_speed')->comment("풍속");
            $table->string('wave_height')->comment("파고");
            $table->string('wave_direction')->comment("파향");
            $table->string('wave_period')->comment("파주기");
            $table->string('humidity')->comment("습도");
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
        Schema::dropIfExists('weather_informations');
    }
}
