<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from')->unsigned()->nullable();
            $table->foreign('from')
                ->references('id')
                ->on('users');
            $table->integer('to')->unsigned()->nullable();
            $table->foreign('to')
                ->references('id')
                ->on('users');
            $table->integer('conversations_id')->unsigned()->nullable();
            $table->foreign('conversations_id')
                    ->references('id')
                    ->on('conversations');
            $table->integer('seen');
            $table->text('members')->nullable();
            $table->text('message');
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
        Schema::dropIfExists('messages');
    }
}
