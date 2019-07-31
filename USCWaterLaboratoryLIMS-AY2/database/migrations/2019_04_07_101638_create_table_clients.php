<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClients extends Migration
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
            $table->string('risNumber')->nullable()->default('0');
            $table->string('nameOfPerson');
            $table->string('nameOfEntity')->nullable();
            $table->string('address');
            $table->string('contactNumber')->nullable();
            $table->string('telephone')->nullable();
            $table->string('faxNumber')->nullable();
            $table->string('emailAddress', 191)->nullable();
            $table->string('paid', 3)->default('No');
            $table->string('sendText', 3)->default('No');
            $table->string('readyForPickUp')->default('No');
            $table->integer('discount')->nullable()->default(0);
            $table->float('deposit')->nullable()->default(0);
            $table->boolean('reclaimSample')->default(0);
            $table->string('testResult')->nullable();
            $table->string('remarks')->nullable();
            $table->dateTime('followUp');
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
