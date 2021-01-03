<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id") ;
            $table->foreignId("prescription_id") ;
            $table->text("notes") ;
            $table->date("presc_date") ;
            $table->timestamps();

            $table->foreign("customer_id")->references("id")->on("customers");
            $table->foreign("prescription_id")->references("id")->on("prescriptions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("customer_prescriptions", function(BluePrint $table){
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['prescription_id']);
        });
        Schema::dropIfExists('customer_prescriptions');
    }
}
