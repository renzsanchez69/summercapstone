<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('clientId');
            $table->string('nameOfPerson');
            $table->string('nameOfEntity')->nullable();
            $table->string('address');
            $table->string('contactNumber');
            $table->string('faxNumber')->nullable();
            $table->string('emailAddress')->nullable();
            $table->integer('discount')->nullable()->default(0);
            $table->float('deposit')->nullable()->default(0);
            $table->boolean('reclaimSample')->default(0);
            $table->string('testResult')->nullable();
            $table->string('remarks')->nullable();
            $table->dateTime('dateSubmitted');
            $table->string('managedBy');
            $table->dateTime('managedDate');
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
        Schema::dropIfExists('clients');
    }
}
