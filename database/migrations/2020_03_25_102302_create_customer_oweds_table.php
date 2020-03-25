<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOwedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_oweds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sale_id')->length(11);
            $table->integer('customer_id')->length(11);
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('receive_amount', 10, 2)->nullable();
            $table->decimal('owed_amount', 10, 2)->nullable();
            $table->dateTime('receive_date');
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
        Schema::dropIfExists('customer_oweds');
    }
}
