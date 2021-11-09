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
</template>

<script>
export default {
    props: {
        value: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            visitor: this.value
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
        }
    }
};
</script>
