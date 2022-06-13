<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateBirthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lawyer', function (Blueprint $table) {

            $table->string('date')->nullable();
            $table->string('membership_img')->nullable();
            $table->string('civil_img')->nullable();
            $table->enum('gender', [0, 1]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lawyer', function (Blueprint $table) {
            //
        });
    }
}
