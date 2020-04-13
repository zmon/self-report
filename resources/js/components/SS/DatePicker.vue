<template>
    <div >
        <input type="text" class="maskdate" size="10" maxlength="10" :placeholder="placeholder"
               :value="value"
               @input="input"
               @click="click"
               @dblclick="dblClick"
               @focus.prevent=""
               :disabled="disabled"
        />
       <calendar-pop-up
           ref="calendarPopUp"
           v-if="doubleClicked"
           :minDate="minDate"
           :maxDate="maxDate"
           @input="input"
           :format="format"
       ></calendar-pop-up>
    </div>


</template>

<script>

    import CalendarPopUp from "./CalendarPopUp";
    export default {
        name: "DatePicker",
        components: {CalendarPopUp},
        props: {
            value: {
                default: null
            },
            placeholder: {
                default: '',
                type: String,
            },
            disabled: {
                default: false,
                type: Boolean
            },
            minDate: {
                type: String,
                default: null
            },
            maxDate: {
                type: String,
                default: null
            },
            identifier: {
                type: String,
                default: Math.random().toString(Math.floor(Math.random()*40)).substring(2, 15) +
                            Math.random().toString(Math.floor(Math.random()*40)).substring(2, 15),
            },
            format: {
                default: 'mm/dd/yy',
                type: String,
            }
        },
        data() {
          return {
              doubleClicked: false,
              showCalendar: false,
          }
        },
        created() {

        },
        methods: {
            input(v) {
                this.doubleClicked = false
                this.$emit('input', v)
            },
            click(e) {
                this.$emit('click', e)
            },
            dblClick(e) {
                this.doubleClicked = true
                this.$emit('dblClick', e)
            }
        },
        // watch: {
        //
        //     value(newVal) {
        //         $(this.el).datepicker('setDate', newVal);
        //     }
        // }
    }
</script>

<style scoped>


</style>



