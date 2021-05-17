<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 150);
            $table->string('password', 256);
            $table->string('phone', 20);
            $table->string('profile_uri', 255)->nullable();
            $table->timestamp('last_password_reset');
            $table->enum('status',['Active', 'Inactive'])->default('Active');
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
        Schema::dropIfExists('client_users');
    }
}
