<template>
    <b-row class="align-items-center">
        <b-col>
            <dl class="row mb-0">
                <template v-if="visitor.name">
                    <dt class="col-sm-4">
                        {{ $t("Name") }}
                    </dt>
                    <dd class="col-sm-8">
                        {{ visitor.name }}
                    </dd>
                </template>
                <template v-if="visitor.id_number">
                    <dt class="col-sm-4">
                        {{ $t("ID Number") }}
                    </dt>
                    <dd class="col-sm-8">
                        {{ visitor.id_number }}
                    </dd>
                </template>
                <template v-if="visitor.gender">
                    <dt class="col-sm-4">
                        {{ $t("Gender") }}
                    </dt>
                    <dd class="col-sm-8">
                        {{ visitor.gender }}
                    </dd>
                </template>
                <template v-if="visitor.nationality">
                    <dt class="col-sm-4">
                        {{ $t("Nationality") }}
                    </dt>
                    <dd class="col-sm-8">
                        {{ visitor.nationality }}
                    </dd>
                </template>
                <template v-if="visitor.date_of_birth">
                    <dt class="col-sm-4">
                        {{ $t("Date of birth") }}
                    </dt>
                    <dd class="col-sm-8">
                        {{ visitor.date_of_birth }}
                    </dd>
                </template>
                <template v-if="visitor.date_of_birth">
                    <dt class="col-sm-4">
                        {{ $t("Living situation") }}
                    </dt>
                    <dd class="col-sm-8">
                        {{ visitor.living_situation }}
                    </dd>
                </template>
            </dl>
        </b-col>
        <b-col cols="auto">
            <b-button
                :variant="visitor.checked_in_today ? 'secondary' : 'primary'"
                :disabled="visitor.checked_in_today || isBusy"
                @click="checkin(visitor)"
                >Check-in</b-button
            >
        </b-col>
    </b-row>
</template>

<script>
import visitorsApi from "@/api/visitors";
import { showSnackbar } from "@/utils";
export default {
    props: {
        value: {
            required: true,
        },
    },
    data() {
        return {
            visitor: this.value,
            isBusy: false,
        };
    },
    methods: {
        async checkin(visitor) {
            this.isBusy = true;
            try {
                let data = await visitorsApi.checkin(visitor.id);
                visitor.checked_in_today = true;
                this.$emit("checkedIn", data.checked_in_today);
                showSnackbar("Checked in " + visitor.name);
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        },
    },
};
</script>
