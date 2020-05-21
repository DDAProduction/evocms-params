<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilterParamsUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_params_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('desc')->nullable();
            $table->string('desc_ua')->nullable();
            $table->string('desc_ru')->nullable();
            $table->string('desc_en')->nullable();
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
        Schema::drop('filter_params_units');
    }
}
