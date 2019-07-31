<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSampleTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample__tests', function (Blueprint $table) {
            $table->increments('testId');
            $table->unsignedInteger('sampleCode');
            $table->unsignedInteger('parameters');
            $table->string('status', 15)->default('Not Started');
            $table->dateTime('timeReceived')->nullable();
            $table->dateTime('timeCompleted')->nullable();
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
        Schema::dropIfExists('sample__tests');
    }
}
