<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_list', function (Blueprint $table) {
            $table->bigIncrements('listId');
            $table->unsignedBigInteger('qty');
            $table->timestamps();


            $table->unsignedInteger('inventoryId');
            $table->unsignedInteger('itemId');
            $table->foreign('inventoryId')->references('inventoryId')->on('inventories');
            $table->foreign('itemId')->references('itemId')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_list');
    }
}
