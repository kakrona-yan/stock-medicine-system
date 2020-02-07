<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id')->length(11);
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('product_code', 100)->nullable();
            $table->string('product_import', 100)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('price_discount', 10, 0)->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('promotion_banner')->nullable();
            $table->text('description')->nullable();
            $table->integer('in_store')->length(11)->default(0)->nullable();
            $table->integer('out_store')->length(11)->default(0)->nullable();
            $table->integer('product_free')->length(11)->default(0)->nullable();
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
        Schema::dropIfExists('products');
    }
}
