<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('staff_id')->length(11);
            $table->integer('customer_id')->length(11);
            $table->string('quotaion_no', 100)->nullable();
            $table->decimal('money_change', 10, 2);
            $table->decimal('total_quantity', 10, 0)->nullable();
            $table->decimal('total_discount', 10, 0)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->dateTime('sale_date');
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(1)->comment('0：in-active、1：active');
            $table->boolean('is_delete')->default(1)->comment('0：delete、1：no delete');
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
        Schema::dropIfExists('sales');
    }
}
