<template>
    <div>
        <fieldset class="mb-4">
            <legend>General Information</legend>
            <div class="row">
                <div class="col-md-6">

                                <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Name                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.name"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Exposed                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.exposed"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Public Private Exposure                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.public_private_exposure"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        State                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.state"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Kscounty                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.kscounty"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        City Kcmo                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.city_kcmo"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Zipcode                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.zipcode"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Selfreport Or Other                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.selfreport_or_other"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Whose Symptoms                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.whose_symptoms"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Sex                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.sex"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Age                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.age"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Any Other Symptoms                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.any_other_symptoms"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Symptom Severity                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.symptom_severity"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Immunocompromised                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.immunocompromised"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Symptom Start Date                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.symptom_start_date"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Followup Contact                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.followup_contact"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Preferred Contact Method                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.preferred_contact_method"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Is Voicemail Ok                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.is_voicemail_ok"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Crowded Setting                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.crowded_setting"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        Anything Else                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.anything_else"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        FormVersionId                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.FormVersionId"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        FormId                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.FormId"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        FormVersionNumber                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.FormVersionNumber"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        ExternalId                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.ExternalId"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        ExternalStatus                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.ExternalStatus"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        ExternalRespondentId                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.ExternalRespondentId"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        SourceType                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.SourceType"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        SourceElementId                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.SourceElementId"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        DataConnectionId                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.DataConnectionId"/>
                    </div>
                </div>
                                        <div class="form-group row mb-2 mb-md-0 text-only">
                    <label class="col-md-4 col-form-label text-md-right">
                        CallCounter                    </label>
                    <div class="col-md-8">
                        <dsp-text v-model="record.CallCounter"/>
                    </div>
                </div>
                
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
    export default {
        name: "self-report-show",
        props: {
            record: {
                type: [Boolean, Object],
                default: false,
            },
        },
    };
</script>
