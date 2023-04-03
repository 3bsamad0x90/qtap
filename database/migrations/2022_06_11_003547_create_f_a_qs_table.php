<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFAQsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_a_qs', function (Blueprint $table) {
            $table->id();
            $table->string('question_ar');
            $table->string('question_en')->nullable();
            $table->string('question_fr')->nullable();
            $table->string('answer_ar');
            $table->string('answer_en')->nullable();
            $table->string('answer_fr')->nullable();
            $table->string('ranking');
            $table->string('type')->nullable();
            $table->string('section')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('f_a_qs');
    }
}
