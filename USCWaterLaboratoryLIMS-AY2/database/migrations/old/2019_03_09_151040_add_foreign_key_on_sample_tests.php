<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyOnSampleTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample__tests', function (Blueprint $table) {
            $table->foreign('sampleCode')->references('sampleId')->on('samples')->onDelete('cascade');
            $table->foreign('parameters')->references('parameterId')->on('parameters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample__tests', function (Blueprint $table) {
            $table->dropForeign('sampleCode');
            $table->dropForeign('parameters');
        });
    }
}
