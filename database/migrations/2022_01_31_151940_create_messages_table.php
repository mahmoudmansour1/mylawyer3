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
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->references('id')->on('users')->nullable();
            $table->unsignedInteger('lawyer_id')->references('id')->on('lawyers')->nullable();            
            $table->unsignedInteger('consultation_id')->references('id')->on('consultations')->nullable();            
            $table->integer('type_status')->default(0);
            $table->integer('type_message')->default(0);
            $table->integer('status_message')->default(0);
            $table->string('message');
            $table->string('date')->nullable();
            $table->string('time')->nullable();
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
