<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('employeeId');
            $table->string('username');
            $table->string('password');
            $table->string('employeeName');
            $table->string('position');
            $table->string('idNumber');
            $table->string('licenseNumber');
            $table->string('userType');
            $table->string('managedBy')->nullable();
            $table->dateTime('managedDate')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('employees');
    }
}
