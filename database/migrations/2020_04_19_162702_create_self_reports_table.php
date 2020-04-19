<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelfReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('self_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->unique();
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
        Schema::dropIfExists('self_reports');
    }
}
