<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment("회원번호");
            $table->string('email')->unique()->comment("이메일");
            $table->string('password')->comment("비밀번호");
            $table->string('name')->comment("이름");
            $table->integer('roles')->default(1)->comment("역할");

            $table->integer('phone_number')->nullable()->comment("휴대전화번호");
            $table->string('profile_photo')->default('default.jpg')->comment("프로필사진");
            // $table->timestamp('email_verified_at')->nullable();
                   
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
