<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('samples', function (Blueprint $table) {
            $table->foreign('risNumber')->references('clientId')->on('clients')->onDelete('cascade');
        });

        Schema::table('parameters', function (Blueprint $table) {
            $table->foreign('station')->references('stationId')->on('stations')->onDelete('cascade');
        });

        Schema::table('sample__tests', function (Blueprint $table) {
            $table->foreign('sampleCode')->references('sampleId')->on('samples')->onDelete('cascade');
            $table->foreign('parameters')->references('parameterId')->on('parameters')->onDelete('cascade');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('supplier')->references('supplierId')->on('suppliers')->onDelete('cascade');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('client')->references('clientId')->on('clients')->onDelete('cascade');
            $table->foreign('approvedBy')->references('employeeId')->on('employees')->onDelete('cascade');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->foreign('itemUsed')->references('itemId')->on('items')->onDelete('cascade');
            $table->foreign('usedBy')->references('employeeId')->on('employees')->onDelete('cascade');
        });

        Schema::table('inventory_list', function (Blueprint $table) {
            $table->foreign('inventoryId')->references('inventoryId')->on('inventories')->onDelete('cascade');
            $table->foreign('itemId')->references('itemId')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_list', function (Blueprint $table) {
            $table->dropForeign(['itemId']);
            $table->dropForeign(['inventoryId']);
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['itemUsed']);
            $table->dropForeign(['usedBy']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['client']);
            $table->dropForeign(['approvedBy']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['supplier']);
        });

        Schema::table('sample__tests', function (Blueprint $table) {
            $table->dropForeign(['sampleCode']);
            $table->dropForeign(['parameters']);
        });
        
        Schema::table('parameters', function (Blueprint $table) {
            $table->dropForeign(['station']);
        });
        
        Schema::table('samples', function (Blueprint $table) {
            $table->dropForeign(['risNumber']);
        });
    }
}
