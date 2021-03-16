<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreexistingConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preexisting_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->default('')->unique();
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('modified_by')->default(0);
            $table->integer('purged_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preexisting_conditions');
    }
}
