<template>
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
                {{ genderLabel }}
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
                {{ dateOfBirthLabel }}
            </dd>
        </template>
        <template v-if="visitor.living_situation">
            <dt class="col-sm-4">
                {{ $t("Living situation") }}
            </dt>
            <dd class="col-sm-8">
                {{ visitor.living_situation }}
            </dd>
        </template>
        <template v-if="!visitor.liability_form_signed">
            <dt class="col-sm-4">
                {{ $t("Liability form") }}
            </dt>
            <dd class="col-sm-8">
                <span class="text-danger"><font-awesome-icon icon="times"/> {{ $t('Not signed!') }}</span>
                <b-button size="sm" @click="signLiabilityForm()" :disabled="isBusy">{{ $t('Mark as signed') }}</b-button>
            </dd>
        </template>
    </dl>
</template>

<script>
import visitorsApi from "@/api/visitors";
import moment from "moment";
import { showSnackbar } from "@/utils";
export default {
    props: {
        value: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            visitor: this.value,
            isBusy: false,
        };
    },
    computed: {
        genderLabel() {
            if (this.visitor.gender == "male") {
                return this.$t("male");
            }
            if (this.visitor.gender == "female") {
                return this.$t("female");
            }
            if (this.visitor.gender == "other") {
                return this.$t("other");
            }
            return this.visitor.gender;
        },
        dateOfBirthLabel() {
            if (this.visitor.date_of_birth) {
                return moment(
                    this.visitor.date_of_birth,
                    moment.HTML5_FMT.DATE,
                    true
                ).format('DD/MM/YYYY');
            }
            return null;
        },
    },
    methods: {
        async signLiabilityForm() {
            this.isBusy = true;
            try {
                let data = await visitorsApi.signLiabilityForm(this.visitor.id);
                this.visitor = data.data;
                showSnackbar(this.$t("Visitor updated."));
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        }
    }
};
</script>
