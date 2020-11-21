<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTags3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags3', function (Blueprint $table) {
            $table->id();
            $table->boolean('all')->nullable()->change(); // 1
            $table->boolean('important')->nullable()->change(); // 2
            $table->boolean('cs')->nullable()->change(); // 3
            $table->boolean('bs')->nullable()->change(); // 4
            $table->boolean('es')->nullable()->change(); // 5
            $table->boolean('ms')->nullable()->change(); // 6
            $table->boolean('hs')->nullable()->change(); // 7
            $table->boolean('ds')->nullable()->change(); // 8
            $table->boolean('me')->nullable()->change(); // 9
            $table->boolean('ee')->nullable()->change(); // 10
            $table->boolean('ac')->nullable()->change(); // 11
            $table->boolean('nu')->nullable()->change(); // 12
            $table->boolean('ce')->nullable()->change(); // 13
            $table->boolean('pt')->nullable()->change(); // 14
            $table->boolean('ot')->nullable()->change(); // 15
            $table->boolean('mt')->nullable()->change(); // 16
            $table->boolean('inhachi')->nullable()->change(); // 17
            $table->boolean('inds')->nullable()->change(); // 18
            $table->boolean('inkogaku')->nullable()->change(); // 19
            $table->integer('information_id');
            $table->unique('information_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags3');
    }
}
