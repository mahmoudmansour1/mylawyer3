<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialtyLawyerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialty_lawyer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('specialty_id')->references('id')->on('specialty')->nullable();
            $table->unsignedInteger('lawyer_id')->references('id')->on('lawyers')->nullable();
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
        Schema::dropIfExists('specialty_lawyer');
    }
}
