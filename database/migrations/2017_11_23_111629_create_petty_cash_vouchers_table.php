<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePettyCashVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petty_cash_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal("amount");
            $table->string("purpose");
            $table->integer("requested_by");
            $table->foreign("requested_by")->references("id")->on("users");
            $table->string("status");
            $table->integer("account_id");
            $table->foreign("account_id")->references("id")->on("primary_accounts");

            $table->integer("approved_by")->nullable();
            $table->foreign("approved_by")->references("id")->on("users");

            /* would only be used on Meeting Expenses */
            $table->integer("num_of_participants")->nullable();
            $table->time("duration")->nullable();
            /* would only be used on Transportation Expenses */
            $table->string("destination")->nullable();
            $table->decimal("distance")->nullable();

            $table->decimal("amount_spent")->nullable();
            $table->dateTime("date_returned")->nullable();
            $table->integer("received_by")->nullable();
            $table->foreign("received_by")->references("id")->on("users");
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
        Schema::dropIfExists('petty_cash_vouchers');
    }
}
