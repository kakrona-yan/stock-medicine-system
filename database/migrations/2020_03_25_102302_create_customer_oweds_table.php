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
            $table->dateTime('receive_date')->nullable();
            $table->integer('status_pay')->length(11)->default(1)->comment('0：no pay、1：some pay, 2: pay all');;
            $table->dateTime('date_pay')->nullable();
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
