<template>
    <form @submit.prevent="handleSubmit">
        <div
            v-if="server_message !== false"
            class="alert alert-danger"
            role="alert"
        >
            {{ this.server_message }}
        </div>

        <std-form-group
            class="row"
            label="Email"
            label-for="email"
            :errors="form_errors.email"
            additionalLabelClasses="col-lg-4 col-form-label text-lg-right"
            :required="true"
        >
            <div class="col-lg-6">
                <fld-input
                    type="email"
                    name="email"
                    v-model="form_data.email"
                    required
                    autocomplete="off"
                    data-lpignore="true"
                />
            </div>
        </std-form-group>

        <std-form-group
            class="row"
            label="Password"
            label-for="password"
            :errors="form_errors.password"
            additionalLabelClasses="col-lg-4 col-form-label text-lg-right"
            :required="true"
        >
            <div class="col-lg-6">
                <vue-password
                    classes="form-control"
                    :disable-toggle="false"
                    name="password"
                    style="width:20em;"
                    :score="score"
                    v-model="form_data.password"
                    @input="updateScore"
                    :user-inputs="password_user_inputs"
                    :required="true"
                    autocomplete="off"
                >

                    <!--The following two slots were added since I could not get the JS score to be the same as the PHP API's -->
                    <!--https://github.com/skegel13/vue-password/blob/master/src/components/VuePasswordCustom.vue-->
                    <template v-slot:strength-meter>
                        <svg
                            viewBox="0 0 123 2"
                            class="VuePassword__Meter"
                            preserveAspectRatio="none"
                        >
                            <path
                                d="M0 1 L30 1"
                                :class="getStrengthClass(0)"
                            ></path>
                            <path
                                d="M31 1 L61 1"
                                :class="getStrengthClass(1)"
                            ></path>
                            <path
                                d="M62 1 L92 1"
                                :class="getStrengthClass(2)"
                            ></path>
                            <path
                                d="M93 1 L123 1"
                                :class="getStrengthClass(3)"
                            ></path>
                        </svg>
                    </template>

                    <template v-slot:strength-message>
                        <div
                            class="VuePassword__Message"
                            :class="strengthClass"
                        >
                            {{ message() }}
                        </div>
                    </template>
                </vue-password>
            </div>
        </std-form-group>

        <std-form-group
            class="row"
            label="Confirm Password"
            label-for="password_confirmation"
            :errors="form_errors.password_confirmation"
            additionalLabelClasses="col-lg-4 col-form-label text-lg-right"
            :required="true"
        >
            <div class="col-lg-6">
                <input
                    type="password"
                    class="form-control"
                    name="password_confirmation"
                    @input="validPassword"
                    v-model="form_data.password_confirmation"
                    autocomplete="off"
                    :required="true"
                />
            </div>
        </std-form-group>

        <div class="form-group row mb-lg-0 mt-lg-0 mb-1 mt-4">
            <div class="col-lg-4 offset-lg-4">
                <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="processing"
                >
                    Reset Password
                </button>
            </div>
            <!--<div class="col-md-3 text-md-right mt-2 mt-md-0">
                <a href="/user" class="btn btn-default">Cancel</a>
            </div> -->
        </div>
    </form>
</template>

<script>
    import axios from "axios";
    import { VuePassword, VuePasswordAuto } from "vue-password";

    export default {
        name: "password-reset-form",
        components: {
            VuePassword,
            VuePasswordAuto
        },
        props: {
            record: {
                type: [Boolean, Object],
                default: false
            },
            csrf_token: {
                type: String,
                default: ""
            },
            token: {
                type: String,
                default: ""
            },
            password_user_inputs: {
                type: [Array, Object],
                default() {
                    return [];
                }
            },
            /**
             * The following properties were added from the vue-password code since the score set by the JavaScript was
             * different from the score set from the PHP API
             **/
            strengthClasses: {
                type: Array,
                default() {
                    return [
                        "VuePassword--very-weak",
                        "VuePassword--weak",
                        "VuePassword--medium",
                        "VuePassword--good",
                        "VuePassword--great"
                    ];
                }
            },
            strengthMessages: {
                type: Array,
                default() {
                    return ["Very Weak", "Weak", "Medium", "Strong", "Very Strong"];
                }
            }
        },
        data() {
            return {
                form_data: {
                    // _method: 'patch',
                    _token: this.csrf_token,
                    token: this.token,
                    email: "",
                    password: "",
                    password_confirmation: ""
                },
                form_errors: {
                    email: false,
                    password: false,
                    password_confirmation: false
                },
                server_message: false,
                processing: false,

                score: 0, // Javascript password strength score
                password_strength: 0, // PHP password strength score
                changePasswordBtnDisable: true
            };
        },
        mounted() {
            if (this.record !== false) {
                // this.form_data._method = 'patch';
                Object.keys(this.record).forEach(
                    i => (this.form_data[i] = this.record[i])
                );
            } else {
                // this.form_data._method = 'post';
            }

            this.form_data["role_name"] = this.role_name;

            // THese will never need data from db:
            this.form_data.password = "";
            this.form_data.password_confirmation = "";
        },
        methods: {
            async handleSubmit() {
                this.server_message = false;
                this.processing = true;

                let url = "/password/reset";
                let amethod = "post";

                console.log(url);

                await axios({
                    method: amethod,
                    url: url,
                    data: this.form_data
                })
                    .then(res => {
                        if (res.status === 200) {
                            window.location = "/home";
                        } else {
                            this.server_message = res.status;
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                // Clear errors out
                                Object.keys(this.form_errors).forEach(
                                    i => (this.form_errors[i] = false)
                                );
                                // Set current errors
                                Object.keys(error.response.data.errors).forEach(
                                    i =>
                                        (this.form_errors[i] =
                                            error.response.data.errors[i])
                                );
                                this.server_message =
                                    "The given data was invalid. Please correct the fields in red below.";
                            } else if (error.response.status === 404) {
                                // Record not found
                                this.server_message = "Record not found";
                                window.location = "/user";
                            } else if (error.response.status === 419) {
                                // Unknown status
                                this.server_message =
                                    "Unknown Status, please try to ";
                            } else if (error.response.status === 500) {
                                // Unknown status
                                this.server_message =
                                    "Server Error, please try to ";
                            } else {
                                this.server_message = error.response.data.message;
                            }
                        } else {
                            console.log(error.response);
                            this.server_message = error;
                        }
                        this.scrollToTop();
                        this.processing = false;
                    });
            },
            /**
             * Go out to the server and get it's score and save in out password_strength field.
             **/
            async updateScore(password, userInputs) {
                this.server_message = false;
                let url = "";
                let amethod = "";
                this.changePasswordBtnDisable = false;

                url = "/password-strength";
                amethod = "post";

                var user_inputs = [];
                user_inputs.push(this.form_data.email);

                await axios({
                    method: amethod,
                    url: url,
                    data: {
                        password: password,
                        password_user_inputs: this.password_user_inputs.concat(
                            user_inputs
                        )
                    }
                })
                    .then(res => {
                        if (res.status === 200) {
                            this.password_strength = res.data;
                            this.score = this.password_strength; // Trying to force JS to be the same as API
                            this.validPassword();
                        } else {
                            this.server_message = res.status;
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                // Clear errors out
                                Object.keys(this.form_errors).forEach(
                                    i => (this.form_errors[i] = false)
                                );
                                // Set current errors
                                Object.keys(error.response.data.errors).forEach(
                                    i =>
                                        (this.form_errors[i] =
                                            error.response.data.errors[i])
                                );
                                this.server_message =
                                    "The given data was invalid. Please correct the fields in red below.";
                            } else if (error.response.status === 404) {
                                // Record not found
                                this.server_message = "Record not found";
                            } else if (error.response.status === 419) {
                                // Unknown status
                                this.server_message =
                                    "Unknown Status, please try to ";
                            } else if (error.response.status === 500) {
                                // Unknown status
                                this.server_message =
                                    "Server Error, please try to ";
                            } else {
                                this.server_message = error.response.data.message;
                            }
                        } else {
                            this.server_message = error;
                        }
                    });
                this.validPassword();
            },
            /**
             * Verify that passwords match and that the strength is good
             * Set the changePasswordBtnDisable flag
             **/
            validPassword() {
                if (
                    this.form_data.password !== this.form_data.password_confirmation
                ) {
                    this.changePasswordBtnDisable = true;
                }
                this.changePasswordBtnDisable = !(this.password_strength > 2);
            },
            /**
             * The following functions were added from the vue-password code since the score set by the JavaScript was
             * different from the score set from the PHP API
             * https://github.com/skegel13/vue-password/blob/master/src/components/VuePasswordCustom.vue
             **/
            getStrengthClass(strength) {
                if (this.password_strength > strength) {
                    return this.strengthClass();
                }
                return "";
            },
            /**
             * Get the current password strength class.
             *
             * @return {string}
             */
            strengthClass() {
                if (this.password_strength >= 0) {
                    if (this.strengthClasses[this.password_strength]) {
                        return this.strengthClasses[this.password_strength];
                    }
                }
            },
            /**
             * Get the current password strength message.
             *
             * @return {string}
             */
            message() {
                if (this.password_strength >= 0) {
                    if (this.strengthMessages[this.password_strength]) {
                        return this.strengthMessages[this.password_strength];
                    }
                }
            }
        }
    };
</script>
