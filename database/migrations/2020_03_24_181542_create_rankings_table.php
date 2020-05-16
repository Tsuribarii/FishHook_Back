<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->bigIncrements('id')->comment("랭킹 번호");

            $table->unsignedBigInteger('user_id')->comment("회원 번호");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('fish_name')->comment("어종 이름");
            $table->integer('length')->comment("길이");
            $table->string('location')->comment("위치");
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
        Schema::dropIfExists('rankings');
    }
}
