<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookstoreRequisitionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookstore_requisition_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('list_pa_id')->nullable();
            $table->foreign('list_pa_id')->references('id')->on('list_of_primary_accounts');
            $table->integer('list_sa_id')->nullable();
            $table->foreign('list_sa_id')->references('id')->on('list_of_secondary_accounts');
            $table->integer('list_ta_id')->nullable();
            $table->foreign('list_ta_id')->references('id')->on('list_of_tertiary_accounts');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('bookstore_requisition_forms');
    }
}
