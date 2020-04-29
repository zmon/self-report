/** For text area =====================

<dsp-date v-model="PostData.created_on" />

*/

<template>
    <span
        :class="'form-text ' + this.additionalClasses"
        v-html="moment(String(value)).format('MM/DD/YYYY')"
    >
    </span>
</template>

<script>
export default {
    name: "dsp-date",
    props: {
        value: {
            default: null
        },
        additionalClasses: {
            type: String,
            default: ""
        }
    }
};
</script>
