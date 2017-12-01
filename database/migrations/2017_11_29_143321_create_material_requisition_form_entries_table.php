<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequisitionFormEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_requisition_form_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mrf_id');
            $table->foreign('mrf_id')->references('id')->on('material_requisition_form');

            //to be filled up by requester
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('est_amount')->nullable();
            $table->integer('list_sa_id')->nullable();
            $table->integer('list_ta_id')->nullable();
            $table->foreign('list_sa_id')->references('id')->on('list_of_secondary_accounts');
            $table->foreign('list_ta_id')->references('id')->on('list_of_tertiary_accounts');

            //each entry can have a different prs number since iba iba supplier (payable to)
            $table->integer('entry_id')->nullable();
            $table->foreign('entry_id')->references('id')->on('journal_entries');

            //input once returned
            $table->string('supplies')->nullable();
            $table->decimal('unit_price')->nullable();
            $table->integer('prs_id');
            $table->foreign('prs_id')->references('id')->on('payment_requisition_slips');

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
        Schema::dropIfExists('material_requisition_form_entries');
    }
}
