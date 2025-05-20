<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto increment
            $table->string('name', 255);
            $table->string('username', 255)->nullable();
            $table->string('email', 255)->unique();
            $table->string('phone', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken(); // varchar(100), nullable
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
