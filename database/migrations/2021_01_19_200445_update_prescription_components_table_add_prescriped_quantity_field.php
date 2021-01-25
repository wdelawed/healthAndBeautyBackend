<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePrescriptionComponentsTableAddPrescripedQuantityField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescription_components', function (Blueprint $table) {
            $table->integer("prescribed_quantity");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescription_components', function (Blueprint $table) {
            $table->dropColumn("prescribed_quantity") ;
        });
    }
}
