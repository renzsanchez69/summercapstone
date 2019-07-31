<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->increments('sampleId');
            $table->unsignedInteger('risNumber');
            $table->string('clientsCode');
            $table->string('sampleType');
            $table->dateTime('sampleCollection');
            $table->string('samplePreservation')->nullable();
            $table->string('purposeOfAnalysis')->nullable();
            $table->string('sampleSource');
            $table->dateTime('dueDate');
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
        Schema::dropIfExists('samples');
    }
}
