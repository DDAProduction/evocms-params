<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilterParamValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_param_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tv_id')->nullable();
            $table->bigInteger('param_id');
            $table->string('alias')->nullable();
            $table->string('value')->nullable();
            $table->string('value_ua')->nullable();
            $table->string('value_ru')->nullable();
            $table->string('value_en')->nullable();
            $table->timestamps();
            $table->index(['alias']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('filter_param_values');
    }
}
