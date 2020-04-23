<template>
    <form @submit.prevent="handleSubmit" class="form-horizontal">
        <div class="card">
            <div class="card-header align-middle">
                <h1>
                    <span v-if="form_data.id"> Edit {{ record.name }} </span>
                    <span v-else> Add role_has_permissions </span>
                </h1>
            </div>
            <div class="card-body">
                <div
                    v-if="server_message !== false"
                    class="alert alert-danger"
                    role="alert"
                >
                    {{ this.server_message }}
                    <a v-if="try_logging_in" href="/login">Login</a>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <std-form-group
                            label="Permission Id"
                            label-for="permission_id"
                            :errors="form_errors.permission_id"
                        >
                            <fld-input
                                name="permission_id"
                                v-model="form_data.permission_id"
                            />
                        </std-form-group>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <std-form-group
                            label="Role Id"
                            label-for="role_id"
                            :errors="form_errors.role_id"
                        >
                            <fld-input
                                name="role_id"
                                v-model="form_data.role_id"
                            />
                        </std-form-group>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <std-form-group
                            label="Deleted At"
                            label-for="deleted_at"
                            :errors="form_errors.deleted_at"
                        >
                            <fld-input
                                name="deleted_at"
                                v-model="form_data.deleted_at"
                            />
                        </std-form-group>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="processing"
                        >
                            <span v-if="this.form_data.id"
                            >Change role_has_permissions</span
                            >
                            <span v-else="this.form_data.id"
                            >Add role_has_permissions</span
                            >
                        </button>
                    </div>
                    <div class="col-md-6 text-md-right mt-2 mt-md-0">
                        <a :href="this.cancel_url" class="btn btn-default"
                        >Cancel</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from "axios";

    export default {
        name: "role-has-permission-form",
        props: {
            record: {
                type: [Boolean, Object],
                default: false
            },
            cancel_url: {
                type: [String],
                default: "/role_has_permission"
            },
            csrf_token: {
                type: String,
                default: ""
            }
        },
        data() {
            return {
                form_data: {
                    // _method: 'patch',
                    _token: this.csrf_token,
                    permission_id: 0,
                    role_id: 0,
                    deleted_at: ""
                },
                form_errors: {
                    permission_id: false,
                    role_id: false,
                    deleted_at: false
                },
                server_message: false,
                try_logging_in: false,
                processing: false
            };
        },
        mounted() {
            if (this.record !== false) {
                // this.form_data._method = 'patch';
                Object.keys(this.record).forEach(i =>
                    this.$set(this.form_data, i, this.record[i])
                );
            } else {
                // this.form_data._method = 'post';
            }
        },
        methods: {
            async handleSubmit() {
                this.server_message = false;
                this.processing = true;
                let url = "";
                let amethod = "";
                if (this.form_data.id) {
                    url = "/role-has-permission/" + this.form_data.id;
                    amethod = "put";
                } else {
                    url = "/role-has-permission";
                    amethod = "post";
                }
                await axios({
                    method: amethod,
                    url: url,
                    data: this.form_data
                })
                    .then(res => {
                        if (res.status === 200) {
                            window.location = "/role-has-permission";
                        } else {
                            this.server_message = res.status;
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                // Clear errors out
                                Object.keys(this.form_errors).forEach(i =>
                                    this.$set(this.form_errors, i, false)
                                );
                                // Set current errors
                                Object.keys(error.response.data.errors).forEach(i =>
                                    this.$set(
                                        this.form_errors,
                                        i,
                                        error.response.data.errors[i]
                                    )
                                );
                            } else if (error.response.status === 404) {
                                // Record not found
                                this.server_message = "Record not found";
                                window.location = "/role-has-permission";
                            } else if (error.response.status === 419) {
                                // Unknown status
                                this.server_message =
                                    "Unknown Status, please try to ";
                                this.try_logging_in = true;
                            } else if (error.response.status === 500) {
                                // Unknown status
                                this.server_message =
                                    "Server Error, please try to ";
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
        }
    };
</script>
