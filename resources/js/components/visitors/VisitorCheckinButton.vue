<template>
    <b-button
        v-if="!visitor.checked_in_today"
        variant="primary"
        :disabled="isBusy"
        @click="checkin(visitor)"
    >
        <font-awesome-icon icon="calendar-check" />
        {{ $t("Check-in") }}</b-button
    >
    <b-button v-else variant="secondary" disabled>{{
        $t("Checked-in today")
    }}</b-button>
</template>

<script>
import visitorsApi from "@/api/visitors";
import { showSnackbar } from "@/utils";
export default {
    props: {
        value: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            visitor: this.value,
            isBusy: false
        };
    },
    methods: {
        async checkin(visitor) {
            this.isBusy = true;
            try {
                let data = await visitorsApi.checkin(visitor.id);
                this.$emit("checkin", data.checked_in_today);
                visitor.checked_in_today = true;
                showSnackbar(`Checked in ${visitor.name}.`);
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        }
    }
};
</script>
