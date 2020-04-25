<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('alias', 16)->nullable()->default('');
            $table->string('url_code', 16)->nullable()->default('');

            $table->string('contact_name', 42)->nullable();
            $table->string('title')->nullable();


            $table->string('phone_1')->nullable();
            $table->string('email')->nullable();

            $table->text('notes')->nullable();

            $table->boolean('active')->default(true);

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
        Schema::dropIfExists('organizations');
    }
}
