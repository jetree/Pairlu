<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('todo');
            $table->unsignedInteger('status');  //0:未実施　1:完了
            $table->unsignedInteger('friend_id')->nullable();
            $table->timestamps();
            $table
              ->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
            $table
              ->foreign('friend_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
