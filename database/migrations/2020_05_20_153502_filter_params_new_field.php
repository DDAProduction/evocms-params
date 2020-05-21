<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilterParamsNewField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filter_params', function (Blueprint $table) {
            $table->string('unit_id')->after('typeinput')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filter_params', function (Blueprint $table) {
            $table->dropColumn(['unit_id']);
        });
    }
}
