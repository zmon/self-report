/** For text area =====================

<dsp-dollar v-model="PostData.amount" />

*/

<template>
    <span> ${{ formatNumber(value, 2) }} </span>
</template>

<script>
export default {
    name: "dsp-dollar",
    props: {
        value: {
            default: 0
        }
    }
};
</script>
