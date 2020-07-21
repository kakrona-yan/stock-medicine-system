<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffGpsMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_gps_maps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->length(11)->default(1);
            $table->integer('staff_id')->length(11)->default(1);
            $table->string('latitude', 255)->nullable();
            $table->string('longitude', 255)->nullable();
            $table->dateTime('start_date_place');
            $table->dateTime('end_date_place')->nullable();
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
        Schema::dropIfExists('staff_gps_maps');
    }
}
