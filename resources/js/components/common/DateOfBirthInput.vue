<template>
    <div class="d-inline-flex align-items-center">
        <b-form-input
            v-model="day"
            trim
            :reqired="required"
            autocomplete="off"
            :disabled="disabled"
            placeholder="DD"
            maxlength="2"
            class="text-center"
            style="width: 3.5em"
        />
        <span class="mx-1">/</span>
        <b-form-input
            v-model="month"
            ref="month"
            trim
            :reqired="required"
            autocomplete="off"
            :disabled="disabled"
            placeholder="MM"
            maxlength="2"
            class="text-center"
            style="width: 3.5em"
        />
        <span class="mx-1">/</span>
        <b-form-input
            v-model="year"
            ref="year"
            trim
            :reqired="required"
            autocomplete="off"
            :disabled="disabled"
            placeholder="YYYY"
            maxlength="4"
            class="text-center"
            style="width: 4.5em"
        />
        <span v-if="age" class="ml-2">{{ $t("Age {age}", { age: age }) }}</span>
    </div>
</template>

<script>
import moment from "moment";
import { calculateAge } from "@/utils";
export default {
    props: {
        value: {},
        disabled: Boolean,
        required: Boolean,
    },
    data() {
        const date = moment(this.value, moment.HTML5_FMT.DATE, true);
        return {
            day: date.isValid() ? date.format("DD") : '',
            month: date.isValid() ? date.format("MM") : '',
            year: date.isValid() ? date.format("YYYY") : '',
        };
    },
    computed: {
        age() {
            let age = calculateAge(this.value);
            if (age >= 0)
                return "" + age;
            return undefined;
        },
    },
    watch: {
        day(val) {
            this.emitChange();
            if (val.length == 2 && this.month.length == 0) {
                this.$refs.month.focus()
            }
        },
        month(val) {
            this.emitChange();
            if (val.length == 2 && this.year.length == 0) {
                this.$refs.year.focus()
            }
        },
        year() {
            this.emitChange();
        },
    },
    methods: {
        emitChange() {
            const date = moment(
                `${this.year}-${this.month}-${this.day}`,
                moment.HTML5_FMT.DATE,
                true
            );
            this.$emit(
                "input",
                date.isValid() ? date.format(moment.HTML5_FMT.DATE) : null
            );
        },
    },
};
</script>
