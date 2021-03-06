<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminNotificationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_notification_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_notification_id')->references('id')->on('admin_notifications')->nullable();
            $table->unsignedInteger('user_id')->references('id')->on('users')->nullable();
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
        Schema::dropIfExists('admin_notification_users');
    }
}
