/** For text area =====================

<dsp-text v-model="PostData.description" />

*/

<template>
    <span :class="'form-text ' + this.additionalClasses" v-html="value"> </span>
</template>

<script>
export default {
    name: "dsp-text",
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
