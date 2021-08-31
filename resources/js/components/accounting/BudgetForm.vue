<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>
                <!-- Name -->
                <b-col md>
                    <validation-provider
                        :name="$t('Name')"
                        vid="name"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Name')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.name"
                                autocomplete="off"
                                :autofocus="!budget"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>
            </b-form-row>

            <!-- <p>
                <b-form-checkbox
                    v-model="form.is_default"
                >
                    {{ $t('Default') }}
                </b-form-checkbox>
            </p> -->

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled"
                    >
                        <font-awesome-icon icon="check" />
                        {{ budget ? $t("Update") : $t("Add") }}
                    </b-button>

                    <!-- Cancel -->
                    <b-button
                        variant="link"
                        :disabled="disabled"
                        @click="$emit('cancel')"
                    >
                        {{ $t("Cancel") }}
                    </b-button>
                </span>

                <!-- Delete -->
                <b-button
                    v-if="budget && budget.can_delete"
                    variant="link"
                    :disabled="disabled"
                    class="text-danger"
                    @click="onDelete"
                >
                    {{ $t("Delete") }}
                </b-button>
            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import rolesApi from "@/api/user_management/roles";
export default {
    props: {
        budget: {
            type: Object,
            required: false
        },
        disabled: Boolean
    },
    data() {
        return {
            form: this.budget
                ? {
                      name: this.budget.name
                  }
                : {
                      name: null
                  },
            roles: []
        };
    },
    created() {
        this.fetchRoles();
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit() {
            this.$emit("submit", this.form);
        },
        onDelete() {
            if (confirm(this.$t("Really delete this budget?"))) {
                this.$emit("delete");
            }
        },
        async fetchRoles() {
            let data = await rolesApi.list();
            this.roles = data.data.map(r => {
                return {
                    text: r.name,
                    value: r.id
                };
            });
        }
    }
};
</script>
