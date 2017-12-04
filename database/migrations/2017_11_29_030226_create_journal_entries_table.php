<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pcv_id')->nullable();
            $table->foreign('pcv_id')->references('id')->on('petty_cash_vouchers');

            $table->integer('mrf_entry_id')->nullable();
            $table->foreign('mrf_entry_id')->references('id')->on('material_requisition_form_entries');

            $table->integer('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('other_transactions');

            $table->integer('brf_id')->nullable();
            $table->foreign('brf_id')->references('id')->on('bookstore_requisition_forms');

            $table->integer('prs_id')->nullable();
            $table->foreign('prs_id')->references('id')->on('payment_requisition_slips');

            $table->boolean('adjust')->default(false);
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
        Schema::dropIfExists('journal_entries');
    }
}
