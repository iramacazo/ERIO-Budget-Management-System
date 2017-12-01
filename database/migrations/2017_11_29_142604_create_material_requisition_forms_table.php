<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequisitionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_requisition_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('form_num');
            $table->dateTime('date_needed');
            $table->integer('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->string('status')->default('Pending');

            $table->string('contact_person');
            $table->string('contact_person_email');

            //Primary Account for (Budget Item/Account Allocation)
            $table->integer('list_pa_id')->nullable();
            $table->foreign('list_pa_id')->references('id')->on('list_of_primary_accounts');

            //when entering entries would the system only enable the user to enter sub-accounts if needed
            $table->string('place_of_delivery');
            $table->string('dept');
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
        Schema::dropIfExists('material_requisition_forms');
    }
}
