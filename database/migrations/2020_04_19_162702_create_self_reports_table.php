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
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->default(0);

            //      $table->string('name', 60)->unique();


            $table->string('name', 100)->default("");  // ResponseReferenceId
            $table->string('exposed', 20)->nullable()->default("");
            $table->string('public_private_exposure', 60)->nullable()->default("");
            $table->string('state', 60)->nullable()->default("");
            $table->string('kscounty', 60)->nullable()->default("");
            $table->string('mocounty', 60)->nullable()->default("");
            $table->string('city_kcmo', 60)->nullable()->default("");
            $table->string('zipcode', 60)->nullable()->default("");
            $table->string('selfreport_or_other', 60)->nullable()->default("");
            $table->string('whose_symptoms', 60)->nullable()->default("");
            $table->string('sex', 60)->nullable()->default("");
            $table->string('age', 60)->nullable()->default("");
            $table->string('any_other_symptoms', 60)->nullable()->default("");
            $table->string('symptom_severity', 60)->nullable()->default("");
            $table->string('immunocompromised', 60)->nullable()->default("");
            $table->string('symptom_start_date', 60)->nullable()->default("");
            $table->string('followup_contact', 60)->nullable()->default("");
            $table->string('preferred_contact_method', 60)->nullable()->default("");
            $table->string('is_voicemail_ok', 60)->nullable()->default("");
            $table->string('crowded_setting', 60)->nullable()->default("");
            $table->string('anything_else', 60)->nullable()->default("");

            $table->integer('FormVersionId')->nullable()->default(0);
            $table->integer('FormId')->nullable()->default(0);
            $table->string('FormVersionNumber', 24)->nullable()->default("");
//            $table->string('ResponseReferenceId', 36)->nullable()->default("");  // Name
            $table->integer('ExternalId')->nullable()->default(0);

            $table->string('ExternalStatus', 20)->nullable()->default("");
            $table->string('ExternalRespondentId', 20)->nullable()->default("");
            $table->string('SourceType', 20)->nullable()->default("");
            $table->string('SourceElementId', 20)->nullable()->default("");
            $table->string('DataConnectionId', 20)->nullable()->default("");
            $table->string('CallCounter', 20)->nullable()->default("");

            $table->string('county_calc',40)->nullable()->default("");
            $table->timestamp('form_received_at')->nullable();
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
