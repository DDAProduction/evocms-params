<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilterParamsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_params_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id');
            $table->bigInteger('param_id');
            $table->bigInteger('show_in_category')->nullable();
            $table->bigInteger('show_in_filter')->nullable();
            $table->string('type_output')->nullable();
            $table->bigInteger('order')->nullable();
            $table->timestamps();
            $table->index(['category_id','type_output']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('filter_params_category');
    }
}
