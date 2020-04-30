<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilterParams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_params', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tv_id')->nullable();
            $table->string('prefix');
            $table->string('alias');
            $table->string('desc');
            $table->string('desc_ua');
            $table->string('desc_ru');
            $table->string('desc_en');
            $table->string('typeinput');
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
        Schema::drop('filter_params');
    }
}
