<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment primary key
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('location', 255)->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamp('expired_at')->nullable();
            $table->enum('status', ['Available', 'Reserved', 'Taken'])->default('Available');
            $table->boolean('is_approved')->default(false);
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
