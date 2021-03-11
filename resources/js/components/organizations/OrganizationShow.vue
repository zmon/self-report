<template>
    <div>
        <fieldset class="mb-4">
            <legend>General Information</legend>
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Name </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.name"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Alias </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.alias"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Url Code </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.url_code"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Contact Name </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.contact_name"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Title </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.title"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Phone 1 </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.phone_1"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Email </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.email"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Notes </label>
                        <div class="col-md-8">
                            <dsp-text v-model="record.notes"/>
                        </div>
                    </div>
                    <div class="form-group row mb-2 mb-md-0 text-only">
                        <label class="col-md-4 col-form-label text-md-right">
                            Active </label>
                        <div class="col-md-8">
                            <dsp-boolean v-model="record.active" true-display="Yes" false-display="NO"/>
                        </div>
                    </div>

                </div>
            </div>
            <legend>Instructions to configure OpenCities OpenForms</legend>
            <div class="row">
                <div class="col-md-12">
                    <h3>Login to OpenForms</h3>
                    You should have the information to do this.

                    <h3>Create your form and set the field's External ID</h3>

                    Each field you wish to save, you will need to assign an External ID,
                    in the Advance section of the field.

                    <img src="/img/open-city-field-advance.png">

                    <p>You will want to create a list of all of the External IDs.</p>

                    <p>The following are the defined IDs</p>

                    <code>
                        _exposed,_public_private_exposure,_state,_kscounty,_city_kcmo,
                        _mocount,_zipcode,_selfreport_or_other,_whose_symptoms,_sex,_age,
                        _race_ethnicity,_symptoms,_any_other_symptoms,_symptom_severity,
                        _preexisting_conditions,_immunocompromised,_symptom_start_date,
                        _followup_contact,_preferred_contact_method,_is_voicemail_ok,
                        _crowded_setting,_anything_else
                    </code>

                    <p>If you use an ID that is not in this list you will need to let KCDD know
                        so they can add it to the database.</p>


                    <h3>Verify that you have a connector, or add one</h3>
                    Click on the Connectors menu item.
                    If self-report is there, goto "Connecting Your Form to This Service".
                    <p>Other wise enter the following information</p>

                    <ul>
                        <li>Connection name: <span class="form-input">self-report</span></li>
                        <li>Connection description: <span class="form-input">Captures form submission</span></li>
                        <li>URL: <span class="form-input">{{ url }}</span></li>
                        <li>Connection type: <span class="form-input">Send data only</span></li>
                        <li>Form fields to send: If you are not using the standard from, use the list created in the
                            above step, other wise use these fields:
                            <code>
                                _exposed,_public_private_exposure,_state,_kscounty,_city_kcmo,
                                _mocount,_zipcode,_selfreport_or_other,_whose_symptoms,_sex,_age,
                                _race_ethnicity,_symptoms,_any_other_symptoms,_symptom_severity,
                                _preexisting_conditions,_immunocompromised,_symptom_start_date,
                                _followup_contact,_preferred_contact_method,_is_voicemail_ok,
                                _crowded_setting,_anything_else
                            </code>
                        </li>
                        <li>How long to wait before timing out: <span class="form-input">30</span></li>
                        <li>Version of code to use: <span class="form-input">Version 2</span></li>
                        <li>Custom headers to send (the second line is very long):<br>
                            <code>{{ header1 }}</code>
                            <br>
                            <pre style="color:red">{{ header2 }}</pre>
                            <br>
                        </li>
                        <li>When an error occurs: <span class="form-input">Allow the user to continue</span></li>
                        <li>Error message to display: <span class="form-input">We had a small issue, you can continue</span></li>
                        <li>Save Changes</li>
                    </ul>

                    <h3>Connecting Your Form to This Service</h3>
                    <ul>
                        <li>Goto the Forms menu item.</li>
                        <li>Click on your Form's name to edit it</li>
                        <li>Click on Setting</li>
                        <li>Click on Data connections</li>
                        <li>In the "When the form is submitted" section's "Data connections you've added" select: <span class="form-input">self-report</span></li>
                        <li>Click "Save and preview"</li>
                    </ul>

                    Goto your Form and edit it.
                </div>
            </div>
        </fieldset>
    </div>
</template>

<script>
    export default {
        name: "organization-show",
        props: {
            record: {
                type: [Boolean, Object],
                default: false,
            },
            url: {
                type: [String],
                default: '',
            },
            header1: {
                type: [String],
                default: '',
            },
            header2: {
                type: [String],
                default: '',
            },
        },
    };
</script>
<style>
    .form-input {
        font-family: monospace;
        font-weight: bolder;
        font-size: 125%;
        margin-left: 1em;
    }
</style>
