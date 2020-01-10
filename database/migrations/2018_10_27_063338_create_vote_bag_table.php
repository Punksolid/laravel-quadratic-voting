<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteBagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel_quadratic.table_names');
        $columnNames = config('laravel_quadratic.column_names');

        Schema::create($tableNames['vote_credits'], function (Blueprint $table) use ($columnNames) {
            $table->increments('id');
            $table->integer($columnNames['voter_key']);
            $table->integer('credits')->default(0);
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

        Schema::dropIfExists($tableNames['vote_credits']);
    }
}
