<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sale_id')->length(11);
            $table->integer('product_id')->length(11);
            $table->decimal('rate', 10, 2);
            $table->decimal('quantity', 10, 0)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('discount', 10, 0)->nullable();
            $table->boolean('discount_type')->default(1)->comment('0：%、1：fix');
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
        Schema::dropIfExists('sale_products');
    }
}
