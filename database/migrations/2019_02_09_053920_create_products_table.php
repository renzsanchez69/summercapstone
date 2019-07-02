<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            // $table->string('image');
            $table->float('price', 8, 2);
            $table->string('desc');
            $table->integer('status')->default(0);
            $table->integer('qty')->nullable();
            $table->integer('total')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
            $table->unsignedInteger('sellers_id');
            $table->foreign('sellers_id')
                        ->references('id')
                        ->on('sellers')
                        ->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
