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
export default {
    props: {
        value: {},
        disabled: Boolean,
        required: Boolean,
    },
    data() {
        const date = moment(this.value, moment.HTML5_FMT.DATE, true);
        return {
            day: date.isValid() ? date.format("DD") : null,
            month: date.isValid() ? date.format("MM") : null,
            year: date.isValid() ? date.format("YYYY") : null,
        };
    },
    computed: {
        age() {
            if (this.value) {
                let date = moment(this.value, moment.HTML5_FMT.DATE, true);
                if (date.isValid()) {
                    return "" + moment().diff(date, "years");
                }
            }
            return undefined;
        },
    },
    watch: {
        day() {
            this.emitChange();
        },
        month() {
            this.emitChange();
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
