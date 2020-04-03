<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOwedHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_owed_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_owed_id')->length(11);
            $table->decimal('receive_amount', 10, 2)->nullable();
            $table->string('recipient', 100)->nullable();
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
        Schema::dropIfExists('customer_owed_histories');
    }
}
