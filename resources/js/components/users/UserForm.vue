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
                        Name must be unique.
                    </template>
                </std-form-group>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <std-form-group label="Email" label-for="email" :errors="form_errors.email">
                    <fld-input
                        name="email"
                        v-model="form_data.email"
                    />
                </std-form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <std-form-group
                    display="inline"
                    label="Role"
                    label-for="role"
                    :errors="form_errors.role"
                >
                    <ui-pick-roles
                        url="/api-user/role-options"
                        v-model="form_data.selected_roles"
                        :selected_roles="roles"
                        name="user"
                    >
                    </ui-pick-roles>
                </std-form-group>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <std-form-group
                    label="Organization"
                    label-for="organization_id"
                    :errors="form_errors.organization_id">
                    <ui-select-pick-one
                        url="/api-organization/options"
                        v-model="form_data.organization_id"
                        :selected_id="form_data.organization_id"
                        name="organization_id"
                        :blank_value="0"
                    blank_text="Access to ALL Organizations">
                    </ui-select-pick-one>
                </std-form-group>
            </div>
        </div>





        <div class="form-group mt-4">
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" :disabled="processing">
                        <span v-if="this.form_data.id">Change Users</span>
                        <span v-else="this.form_data.id">Add Users</span>
                    </button>
                </div>
                <div class="col-md-6 text-md-right mt-2 mt-md-0">
                    <a href="/user" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios';
    import UiSelectPickOne from "../SS/UiSelectPickOne";

    export default {
        name: "user-form",
        components: { UiSelectPickOne },
        props: {
            record: {
                type: [Boolean, Object],
                default: false,
            },
            csrf_token: {
                type: String,
                default: ''
            },
            roles: {
                type: [Array],
                default() {
                    return [];
                }
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
                    email: '',
                    selected_roles: ""
                },
                form_errors: {
                    id: false,
                    organization_id: false,
                    name: false,
                    email: false,
                    active: false,
                    email_verified_at: false,
                    password: false,
                    remember_token: false,
                    selected_roles: false
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

            this.form_data["selected_roles"] = this.roles;

        },
        methods: {
            async handleSubmit() {

                this.server_message = false;
                this.processing = true;
                let url = '';
                let amethod = '';
                if (this.form_data.id) {
                    url = '/user/' + this.form_data.id;
                    amethod = 'put';
                } else {
                    url = '/user';
                    amethod = 'post';
                }
                await axios({
                    method: amethod,
                    url: url,
                    data: this.form_data
                })
                    .then((res) => {
                        if (res.status === 200) {
                            window.location = '/user';
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
                                window.location = '/user';
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

