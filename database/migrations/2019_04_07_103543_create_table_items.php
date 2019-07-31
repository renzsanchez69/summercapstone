<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('itemId');
            $table->string('itemName');
            $table->string('containerType')->nullable();
            $table->string('volumeCapacity')->nullable();
            $table->integer('quantity')->nullable()->default(1);
            $table->unsignedInteger('supplier')->nullable();
            $table->string('managedBy')->nullable();
            $table->dateTime('managedDate')->nullable();
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
        Schema::dropIfExists('items');
    }
}
