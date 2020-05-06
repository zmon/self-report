<template>
    <form @submit.prevent="handleSubmit" class="form-horizontal">

        <div v-if="server_message !== false" class="alert alert-danger" role="alert">
            {{ this.server_message}} <a v-if="try_logging_in" href="/login">Login</a>
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
                        Name must be unique and 120 characters or less.
                    </template>
                </std-form-group>
            </div>
        </div>


        <div class="row">
            <div class="col-md-3">
                <std-form-group label="Alias" label-for="alias" :errors="form_errors.alias">
                    <fld-input
                        name="alias"
                        v-model="form_data.alias"
                    />
                    <template slot="help">
                        Alias must be unique and 16 characters or less.
                    </template>
                </std-form-group>
            </div>

            <div class="col-md-3">
                <std-form-group label="Url Code" label-for="url_code" :errors="form_errors.url_code">
                    <fld-input
                        name="url_code"
                        v-model="form_data.url_code"
                    />
                    <template slot="help">
                        URL code must be unique and 16 characters or less.

                        You will use the URL code to identify that you want the
                        data to be saved to this organization when setting up your form.
                    </template>
                </std-form-group>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <std-form-group label="Contact Name" label-for="contact_name" :errors="form_errors.contact_name">
                    <fld-input
                        name="contact_name"
                        v-model="form_data.contact_name"
                    />
                </std-form-group>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <std-form-group label="Title" label-for="title" :errors="form_errors.title">
                    <fld-input
                        name="title"
                        v-model="form_data.title"
                    />
                </std-form-group>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">
                <std-form-group label="Phone 1" label-for="phone_1" :errors="form_errors.phone_1">
                    <fld-input
                        name="phone_1"
                        v-model="form_data.phone_1"
                    />
                </std-form-group>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">
                <std-form-group label="Email" label-for="email" :errors="form_errors.email">
                    <fld-input
                        name="email"
                        v-model="form_data.email"
                    />
                </std-form-group>
            </div>
        </div>


<!--        <div class="row">-->
<!--            <div class="col-md-6">-->
<!--                <std-form-group label="Notes" label-for="notes" :errors="form_errors.notes">-->
<!--                    <fld-text-editor name="notes" v-model="form_data.notes" required/>-->
<!--                </std-form-group>-->
<!--            </div>-->
<!--        </div>-->


        <div class="row">
            <div class="col-md-12">
                <std-form-group
                    display="inline"
                    label="Active"
                    label-for="active"
                    :errors="form_errors.active"
                >
                    <fld-checkbox
                        name="active"
                        v-model="form_data.active"
                    />
                    <template slot="help">
                        Organization is active or not.
                    </template>
                </std-form-group>
            </div>
        </div>


        <div class="form-group mt-4">
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" :disabled="processing">
                        <span v-if="this.form_data.id">Change Organizations</span>
                        <span v-else="this.form_data.id">Add Organizations</span>
                    </button>
                </div>
                <div class="col-md-6 text-md-right mt-2 mt-md-0">
                    <a href="/organization" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "organization-form",
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
                    name: '',
                    alias: '',
                    url_code: '',
                    contact_name: '',
                    title: '',
                    phone_1: '',
                    email: '',
                    notes: '',
                    active: 0,
                },
                form_errors: {
                    id: false,
                    name: false,
                    alias: false,
                    url_code: false,
                    contact_name: false,
                    title: false,
                    phone_1: false,
                    email: false,
                    notes: false,
                    active: false,
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
                    url = '/organization/' + this.form_data.id;
                    amethod = 'put';
                } else {
                    url = '/organization';
                    amethod = 'post';
                }
                await axios({
                    method: amethod,
                    url: url,
                    data: this.form_data
                })
                    .then((res) => {
                        if (res.status === 200) {
                            console.log(res);
                            window.location = '/organization/' + res.data.id;
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
                            } else if (error.response.status === 404) {  // Record not found
                                this.server_message = 'Record not found';
                                window.location = '/organization';
                            } else if (error.response.status === 419) {  // Unknown status
                                this.server_message = 'Unknown Status, please try to ';
                                this.try_logging_in = true;
                            } else if (error.response.status === 500) {  // Unknown status
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

