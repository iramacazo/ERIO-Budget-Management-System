<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListOfPrimaryAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_of_primary_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("budget_id");
            $table->integer("account_id");
            $table->foreign("budget_id")->references("id")->on("budgets");
            $table->foreign("account_id")->references("id")->on("primary_accounts");
            $table->decimal("amount");
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
        Schema::dropIfExists('list_of_primary_accounts');
    }
}
