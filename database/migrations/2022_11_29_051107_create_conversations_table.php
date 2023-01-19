<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->boolean('type')->nullable(); //type 0 ise grup sohbeti ,1 ise tekli sohbet
            $table->string('file')->nullable();
            $table->boolean('everyone_chat')->nullable()->default('1');
            $table->unsignedBigInteger('ders_id')->nullable();
            $table->timestamps();

            //$table->foreign('id')->references('conversation_id')->on('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
