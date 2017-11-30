<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookstoreRequisitionFormEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookstore_requisition_form_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brf_id');
            $table->foreign('brf_id')->references('id')->on('bookstore_requisition_form');
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('amount')->nullable();
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
        Schema::dropIfExists('bookstore_requisition_form_entries');
    }
}
