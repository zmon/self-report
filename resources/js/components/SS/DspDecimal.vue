/** For text area =====================

<dsp-decimal v-model="PostData.length_in_feet" />

*/

<template>
    <span> {{ formatNumber(value, precision) }} </span>
</template>

<script>
export default {
    name: "dsp-decimal",
    props: {
        value: {
            default: 0
        },
        precision: {
            default: 2
        }
    }
};
</script>
