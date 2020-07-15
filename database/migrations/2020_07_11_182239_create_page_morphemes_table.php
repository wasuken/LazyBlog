<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageMorphemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_morphemes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('morpheme_id');
            $table->bigInteger('page_id');
            $table->double('tf');
            $table->double('idf');
            $table->double('score');

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
        Schema::dropIfExists('page_morphemes');
    }
}
