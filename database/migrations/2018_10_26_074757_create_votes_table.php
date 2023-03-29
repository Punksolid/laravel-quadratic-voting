<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Default table name is votes
     */
    public function up()
    {
        $tableNames = config('laravel_quadratic.table_names'); // array
        $columnNames = config('laravel_quadratic.column_names'); // array

        Schema::create($tableNames['votes'], function (Blueprint $table) use ($columnNames) {
            $table->increments('id');

            $table->integer($columnNames['voter_key'])->index()->nullable();
            $table->integer("votable_id")->index()->nullable();
            $table->string("votable_type")->index()->nullable();
            $table->integer('quantity')->nullable();
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
        $tableNames = config('laravel_quadratic.table_names');

        Schema::dropIfExists($tableNames['votes']);
    }
}
