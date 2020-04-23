<template>
    <div
        class="input-group mb-0"
        v-bind:class="{ 'has-error': this.has_errors }"
    >
        <label
            :class="'mb-2 mr-2 pt-2 pt-sm-0 ' + this.additionalLabelClasses"
            :for="this.labelFor != '' ? 'field_' + this.labelFor : false"
        >{{ label }}</label
        >
        <div>
            <div class="help-block" v-if="this.errors !== false">
                <strong>{{ this.errors[0] }}</strong>
            </div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "search-form-group",
        props: {
            label: {
                type: String,
                default: ""
            },
            labelFor: {
                type: String,
                default: ""
            },
            errors: {
                type: [Array, Boolean],
                default: false
            },
            additionalLabelClasses: {
                type: String,
                default: ""
            }
        },
        data() {
            return {
                has_errors: false
            };
        }
    };
</script>
