<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('consultation_number');
            $table->string('subject');
            $table->string('customer_name');
            $table->string('customer_phone');  
            $table->string('lawyer_name');
            $table->string('lawyer_phone');
            $table->string('req_date')->nullable();
            $table->string('req_time')->nullable();
            $table->unsignedInteger('status_id')->references('id')->on('status')->nullable();
            $table->float('amount')->default(0);
            $table->unsignedInteger('payment_status_id')->references('id')->on('payment_status')->nullable();
            $table->unsignedInteger('user_id')->references('id')->on('users')->nullable();
            $table->unsignedInteger('lawyer_id')->references('id')->on('lawyers')->nullable();
        //    $table->integer('user_deleted')->default(0);
        //    $table->unsignedInteger('payment_id')->references('id')->on('payments')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultations');
    }
}
