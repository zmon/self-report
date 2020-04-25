<template>
    <form @submit.prevent="handleSubmit" class="form-horizontal">

        <div v-if="server_message !== false" class="alert alert-danger" role="alert">
            {{ this.server_message}}  <a v-if="try_logging_in" href="/login">Login</a>
        </div>

                    <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Name" label-for="name" :errors="form_errors.name" :required="true">
                        <fld-input
                                name="name"
                                v-model="form_data.name"
                                required
                        />
                        <template slot="help">
                            Name must be unique.
                        </template>
                    </std-form-group>
                </div>
            </div>
                

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Exposed" label-for="exposed" :errors="form_errors.exposed">
                        <fld-input
                                name="exposed"
                                v-model="form_data.exposed"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Public Private Exposure" label-for="public_private_exposure" :errors="form_errors.public_private_exposure">
                        <fld-input
                                name="public_private_exposure"
                                v-model="form_data.public_private_exposure"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="State" label-for="state" :errors="form_errors.state">
                        <fld-input
                                name="state"
                                v-model="form_data.state"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Kscounty" label-for="kscounty" :errors="form_errors.kscounty">
                        <fld-input
                                name="kscounty"
                                v-model="form_data.kscounty"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Mocounty" label-for="mocounty" :errors="form_errors.mocounty">
                        <fld-input
                                name="mocounty"
                                v-model="form_data.mocounty"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="City Kcmo" label-for="city_kcmo" :errors="form_errors.city_kcmo">
                        <fld-input
                                name="city_kcmo"
                                v-model="form_data.city_kcmo"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Zipcode" label-for="zipcode" :errors="form_errors.zipcode">
                        <fld-input
                                name="zipcode"
                                v-model="form_data.zipcode"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Selfreport Or Other" label-for="selfreport_or_other" :errors="form_errors.selfreport_or_other">
                        <fld-input
                                name="selfreport_or_other"
                                v-model="form_data.selfreport_or_other"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Whose Symptoms" label-for="whose_symptoms" :errors="form_errors.whose_symptoms">
                        <fld-input
                                name="whose_symptoms"
                                v-model="form_data.whose_symptoms"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Sex" label-for="sex" :errors="form_errors.sex">
                        <fld-input
                                name="sex"
                                v-model="form_data.sex"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Age" label-for="age" :errors="form_errors.age">
                        <fld-input
                                name="age"
                                v-model="form_data.age"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Any Other Symptoms" label-for="any_other_symptoms" :errors="form_errors.any_other_symptoms">
                        <fld-input
                                name="any_other_symptoms"
                                v-model="form_data.any_other_symptoms"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Symptom Severity" label-for="symptom_severity" :errors="form_errors.symptom_severity">
                        <fld-input
                                name="symptom_severity"
                                v-model="form_data.symptom_severity"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Immunocompromised" label-for="immunocompromised" :errors="form_errors.immunocompromised">
                        <fld-input
                                name="immunocompromised"
                                v-model="form_data.immunocompromised"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Symptom Start Date" label-for="symptom_start_date" :errors="form_errors.symptom_start_date">
                        <fld-input
                                name="symptom_start_date"
                                v-model="form_data.symptom_start_date"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Followup Contact" label-for="followup_contact" :errors="form_errors.followup_contact">
                        <fld-input
                                name="followup_contact"
                                v-model="form_data.followup_contact"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Preferred Contact Method" label-for="preferred_contact_method" :errors="form_errors.preferred_contact_method">
                        <fld-input
                                name="preferred_contact_method"
                                v-model="form_data.preferred_contact_method"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Is Voicemail Ok" label-for="is_voicemail_ok" :errors="form_errors.is_voicemail_ok">
                        <fld-input
                                name="is_voicemail_ok"
                                v-model="form_data.is_voicemail_ok"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Crowded Setting" label-for="crowded_setting" :errors="form_errors.crowded_setting">
                        <fld-input
                                name="crowded_setting"
                                v-model="form_data.crowded_setting"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Anything Else" label-for="anything_else" :errors="form_errors.anything_else">
                        <fld-input
                                name="anything_else"
                                v-model="form_data.anything_else"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="FormVersionId" label-for="FormVersionId" :errors="form_errors.FormVersionId">
                        <fld-input
                                name="FormVersionId"
                                v-model="form_data.FormVersionId"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="FormId" label-for="FormId" :errors="form_errors.FormId">
                        <fld-input
                                name="FormId"
                                v-model="form_data.FormId"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="FormVersionNumber" label-for="FormVersionNumber" :errors="form_errors.FormVersionNumber">
                        <fld-input
                                name="FormVersionNumber"
                                v-model="form_data.FormVersionNumber"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="ExternalId" label-for="ExternalId" :errors="form_errors.ExternalId">
                        <fld-input
                                name="ExternalId"
                                v-model="form_data.ExternalId"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="ExternalStatus" label-for="ExternalStatus" :errors="form_errors.ExternalStatus">
                        <fld-input
                                name="ExternalStatus"
                                v-model="form_data.ExternalStatus"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="ExternalRespondentId" label-for="ExternalRespondentId" :errors="form_errors.ExternalRespondentId">
                        <fld-input
                                name="ExternalRespondentId"
                                v-model="form_data.ExternalRespondentId"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="SourceType" label-for="SourceType" :errors="form_errors.SourceType">
                        <fld-input
                                name="SourceType"
                                v-model="form_data.SourceType"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="SourceElementId" label-for="SourceElementId" :errors="form_errors.SourceElementId">
                        <fld-input
                                name="SourceElementId"
                                v-model="form_data.SourceElementId"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="DataConnectionId" label-for="DataConnectionId" :errors="form_errors.DataConnectionId">
                        <fld-input
                                name="DataConnectionId"
                                v-model="form_data.DataConnectionId"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="CallCounter" label-for="CallCounter" :errors="form_errors.CallCounter">
                        <fld-input
                                name="CallCounter"
                                v-model="form_data.CallCounter"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="County Calc" label-for="county_calc" :errors="form_errors.county_calc">
                        <fld-input
                                name="county_calc"
                                v-model="form_data.county_calc"
                        />
                    </std-form-group>
                </div>
            </div>
        

                            <div class="row">
                <div class="col-md-12">
                    <std-form-group label="Form Received At" label-for="form_received_at" :errors="form_errors.form_received_at">
                        <fld-input
                                name="form_received_at"
                                v-model="form_data.form_received_at"
                        />
                    </std-form-group>
                </div>
            </div>
        

        <div class="form-group mt-4">
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" :disabled="processing">
                        <span v-if="this.form_data.id">Change SelfReports</span>
                        <span v-else="this.form_data.id">Add SelfReports</span>
                    </button>
                </div>
                <div class="col-md-6 text-md-right mt-2 mt-md-0">
                    <a href="/self-report" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "self-report-form",
        props: {
            record: {
                type: [Boolean, Object],
                default: false,
            },
            csrf_token: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                form_data: {
                    // _method: 'patch',
                    _token: this.csrf_token,
                      id: 0,
                                    organization_id: 0,
                              name: '',
                                exposed: '',
                                public_private_exposure: '',
                                state: '',
                                kscounty: '',
                                mocounty: '',
                                city_kcmo: '',
                                zipcode: '',
                                selfreport_or_other: '',
                                whose_symptoms: '',
                                sex: '',
                                age: '',
                                any_other_symptoms: '',
                                symptom_severity: '',
                                immunocompromised: '',
                                symptom_start_date: '',
                                followup_contact: '',
                                preferred_contact_method: '',
                                is_voicemail_ok: '',
                                crowded_setting: '',
                                anything_else: '',
                                  FormVersionId: 0,
                                FormId: 0,
                              FormVersionNumber: '',
                                  ExternalId: 0,
                              ExternalStatus: '',
                                ExternalRespondentId: '',
                                SourceType: '',
                                SourceElementId: '',
                                DataConnectionId: '',
                                CallCounter: '',
                                county_calc: '',
                                      form_received_at: '',
                  },
                form_errors: {
                id: false,
                organization_id: false,
                name: false,
                exposed: false,
                public_private_exposure: false,
                state: false,
                kscounty: false,
                mocounty: false,
                city_kcmo: false,
                zipcode: false,
                selfreport_or_other: false,
                whose_symptoms: false,
                sex: false,
                age: false,
                any_other_symptoms: false,
                symptom_severity: false,
                immunocompromised: false,
                symptom_start_date: false,
                followup_contact: false,
                preferred_contact_method: false,
                is_voicemail_ok: false,
                crowded_setting: false,
                anything_else: false,
                FormVersionId: false,
                FormId: false,
                FormVersionNumber: false,
                ExternalId: false,
                ExternalStatus: false,
                ExternalRespondentId: false,
                SourceType: false,
                SourceElementId: false,
                DataConnectionId: false,
                CallCounter: false,
                county_calc: false,
                form_received_at: false,
                },
                server_message: false,
                try_logging_in: false,
                processing: false,
            }
        },
        mounted() {
            if (this.record !== false) {
                // this.form_data._method = 'patch';
                Object.keys(this.record).forEach(
                    i => (this.$set(this.form_data, i, this.record[i]))
                )
            } else {
                // this.form_data._method = 'post';
            }

        },
        methods: {
            async handleSubmit() {

                this.server_message = false;
                this.processing = true;
                let url = '';
                let amethod = '';
                if (this.form_data.id) {
                    url = '/self-report/' + this.form_data.id;
                    amethod = 'put';
                } else {
                    url = '/self-report';
                    amethod = 'post';
                }
                await axios({
                    method: amethod,
                    url: url,
                    data: this.form_data
                })
                    .then((res) => {
                        if (res.status === 200) {
                            window.location = '/self-report';
                        } else {
                            this.server_message = res.status;
                        }
                    }).catch(error => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                // Clear errors out
                                Object.keys(this.form_errors).forEach(
                                    i => (this.$set(this.form_errors, i, false))
                                );
                                // Set current errors
                                Object.keys(error.response.data.errors).forEach(
                                    i => (this.$set(this.form_errors, i, error.response.data.errors[i]))
                                );
                            } else  if (error.response.status === 404) {  // Record not found
                                this.server_message = 'Record not found';
                                window.location = '/self-report';
                            } else  if (error.response.status === 419) {  // Unknown status
                                this.server_message = 'Unknown Status, please try to ';
                                this.try_logging_in = true;
                            } else  if (error.response.status === 500) {  // Unknown status
                                this.server_message = 'Server Error, please try to ';
                                this.try_logging_in = true;
                            } else {
                                this.server_message = error.response.data.message;
                            }
                        } else {
                            console.log(error.response);
                            this.server_message = error;
                        }
                        this.processing = false;
                    });
            }
        },
    }
</script>

