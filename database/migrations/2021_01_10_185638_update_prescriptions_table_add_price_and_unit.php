<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePrescriptionsTableAddPriceAndUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function($table){
            $table->string('unit') ;
            $table->integer('price') ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('components', function($table){
            $table->dropColumn('unit') ;
            $table->dropColumn('price') ;
        });
    }
}
