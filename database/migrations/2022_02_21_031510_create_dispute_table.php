<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisputeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispute', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('consultation_id')->references('id')->on('consultations')->nullable();            
            $table->integer('type_status')->default(0);
            $table->integer('type_accept')->default(0);
            $table->integer('refund_method')->default(1);
            $table->string('message');
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
        Schema::dropIfExists('dispute');
    }
}
