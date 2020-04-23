<template>
    <div class="datepicker"
         @input="input"
         @select="input"
    ></div>
</template>

<script>
    export default {
        name: "CalendarPopUp",
        props: {
            minDate: {
                type: String,
                default: null
            },
            maxDate: {
                type: String,
                default: null
            },
            format: {
                default: 'mm/dd/yy',
                type: String,
            }
        },
        mounted() {
            let $this = this;
            $(this.$el).datepicker({
                dateFormat: $this.format,
                onClose: this.onClose,
                minDate: this.minDate ? this.moment(this.minDate).toDate() : null,
                maxDate: this.maxDate ? this.moment(this.maxDate).toDate() : null,
                onSelect: function (date) {
                    $this.input(date)
                }

            })
        },
        methods: {
            input(v) {
                this.$emit('input', v)
            },
            onClose(date) {

                this.$emit('input', date)
            },
        },
    }
</script>

<style scoped>
    .datepicker {
        z-index: 1;
        position: absolute;
    }
</style>
