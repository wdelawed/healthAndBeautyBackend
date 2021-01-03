<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id') ;
            $table->foreignId('component_id') ;
            $table->timestamps();

            $table->foreign('prescription_id')->references('id')->on('prescriptions') ;
            $table->foreign('component_id')->references('id')->on('components') ;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescription_components', function(BluePrint $table){
            $table->dropForeign(['prescription_id']);
            $table->dropForeign(['component_id']);
        });
        Schema::dropIfExists('prescription_components');
    }
}
