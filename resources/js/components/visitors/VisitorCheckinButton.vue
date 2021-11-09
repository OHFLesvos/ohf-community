<template>
    <div v-if="open && !visitor.checked_in_today">
        <b-form-group :label="$t('Purpose of visit')">
            <b-form-select
                v-model="formData.purpose_of_visit"
                autocomplete="off"
                :disabled="isBusy"
                :options="purposesOfVisit"
            />
        </b-form-group>
        <b-button
            variant="success"
            :disabled="isBusy"
            @click="checkin(visitor)"
        >
            <font-awesome-icon icon="calendar-check" />
            {{ $t("Check-in") }}</b-button
        >
        <b-button variant="link" :disabled="isBusy" @click="open = false">{{
            $t("Cancel")
        }}</b-button>
    </div>
    <div v-else class="d-flex justify-content-between">
        <b-button
            v-if="!visitor.checked_in_today"
            variant="primary"
            @click="open = true"
        >
            <font-awesome-icon icon="calendar-check" />
            {{ $t("Check-in") }}</b-button
        >
        <b-button v-else variant="secondary" disabled>{{
            $t("Checked-in today")
        }}</b-button>
        <slot name="append"></slot>
    </div>
</template>

<script>
import visitorsApi from "@/api/visitors";
import { mapState } from "vuex";
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
            isBusy: false,
            open: false,
            formData: {
                purpose_of_visit: ""
            }
        };
    },
    computed: {
        ...mapState(["settings"]),
        purposesOfVisit() {
            return ["", ...this.settings["visitors.purposes_of_visit"]];
        }
    },
    methods: {
        async checkin(visitor) {
            this.isBusy = true;
            try {
                let data = await visitorsApi.checkin(visitor.id, this.formData);
                this.$emit("checkin", data.checked_in_today);
                visitor.checked_in_today = true;
                showSnackbar(`Checked in ${visitor.name}.`);
                this.open = false;
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        }
    }
};
</script>
