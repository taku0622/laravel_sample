<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAllToWholeTags2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags2', function (Blueprint $table) {
            $table->id();
            $table->boolean('whole'); // 1
            $table->boolean('important'); // 2
            $table->boolean('cs'); // 3
            $table->boolean('bs'); // 4
            $table->boolean('es'); // 5
            $table->boolean('ms'); // 6
            $table->boolean('hs'); // 7
            $table->boolean('ds'); // 8
            $table->boolean('me'); // 9
            $table->boolean('ee'); // 10
            $table->boolean('ac'); // 11
            $table->boolean('nu'); // 12
            $table->boolean('ce'); // 13
            $table->boolean('pt'); // 14
            $table->boolean('ot'); // 15
            $table->boolean('mt'); // 16
            $table->boolean('inhachi'); // 17
            $table->boolean('inds'); // 18
            $table->boolean('inkogaku'); // 19
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
        Schema::table('tags2', function (Blueprint $table) {
            //
        });
    }
}
