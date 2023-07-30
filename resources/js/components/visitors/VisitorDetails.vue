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
                ({{ $t("Age {age}", { age: age }) }})
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
        <template v-if="visitor.remarks">
            <dt class="col-sm-4">
                {{ $t("Remarks") }}
            </dt>
            <dd class="col-sm-8">
                {{ visitor.remarks }}
            </dd>
        </template>
        <dt class="col-sm-4">
            {{ $t("Liability form signed") }}
        </dt>
        <dd class="col-sm-8">
            <template v-if="visitor.liability_form_signed">
                {{ visitor.liability_form_signed | dateFormat }}
            </template>
            <template v-else>
                <span class="text-danger"><font-awesome-icon icon="times"/> {{ $t('Not signed!') }}</span>
                <b-button size="sm" @click="signLiabilityForm()" :disabled="isBusy">{{ $t('Mark as signed') }}</b-button>
            </template>
        </dd>
        <template v-if="visitor.date_of_birth && age < 18">
            <dt class="col-sm-4">
                {{ $t("Parental consent") }}
            </dt>
            <dd class="col-sm-8">
                <template v-if="visitor.parental_consent_given">
                    {{ $t('Yes') }}
                </template>
                <template v-else>
                    <span class="text-danger"><font-awesome-icon icon="times"/> {{ $t('Not given!') }}</span>
                    <b-button size="sm" @click="giveParentalConsent()" :disabled="isBusy">{{ $t('Mark as given') }}</b-button>
                </template>
            </dd>
        </template>
    </dl>
</template>

<script>
import visitorsApi from "@/api/visitors";
import moment from "moment";
import { showSnackbar, calculateAge } from "@/utils";
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
            switch (this.visitor.gender) {
                case "male":
                    return this.$t("male");
                case "female":
                    return this.$t("female");
                case "other":
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
        age() {
            return calculateAge(this.visitor.date_of_birth);
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
        },
        async giveParentalConsent() {
            this.isBusy = true;
            try {
                let data = await visitorsApi.giveParentalConsent(this.visitor.id);
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
