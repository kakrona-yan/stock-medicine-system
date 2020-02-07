<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('logo')->nullable();
            $table->string('copy_right')->nullable();
            $table->string('sns_gmail')->nullable();
            $table->string('sns_facebook')->nullable();
            $table->string('sns_instagram')->nullable();
            $table->string('sns_twitter')->nullable();
            $table->string('phone')->nullable();
            $table->string('map')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
