<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card :title="title" body-class="pb-2" footer-class="d-flex justify-content-between align-items-start" class="mb-3">

                <!-- Name -->
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
                            required
                            autocomplete="off"
                            :autofocus="responsibility == null"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <!-- Description -->
                <validation-provider
                    :name="$t('Description')"
                    vid="description"
                    :rules="{ }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('Description')"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                    >
                        <b-form-textarea
                            v-model="form.description"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <!-- Capacity -->
                <validation-provider
                    :name="$t('Capacity')"
                    vid="capacity"
                    :rules="{ }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('Capacity')"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                    >
                        <b-form-input
                            v-model="form.capacity"
                            type="number"
                            min="1"
                            autocomplete="off"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <div class="mb-2">
                    <b-form-checkbox v-model="form.available">
                        {{ $t("Available") }}
                    </b-form-checkbox>
                </div>

                <template #footer>
                    <span>
                        <!-- Submit -->
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="disabled"
                        >
                            <font-awesome-icon icon="check" />
                            {{ responsibility ? $t('Update') : $t('Add') }}
                        </b-button>

                        <!-- Cancel -->
                        <b-button
                            variant="link"
                            :disabled="disabled"
                            @click="$emit('cancel')"
                        >
                            {{ $t('Cancel') }}
                        </b-button>
                    </span>

                    <!-- Delete -->
                    <b-button
                        v-if="responsibility && responsibility.can_delete"
                        variant="link"
                        :disabled="disabled"
                        class="text-danger"
                        @click="onDelete"
                    >
                        {{ $t('Delete') }}
                    </b-button>

                </template>
            </b-card>
        </b-form>
    </validation-observer>
</template>

<script>
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    props: {
        responsibility: {
            type: Object,
            required: false
        },
        title: {
            required: false,
            default: undefined
        },
        disabled: Boolean
    },
    data () {
        return {
            form: this.responsibility ? {
                name: this.responsibility.name,
                description: this.responsibility.description,
                capacity: this.responsibility.capacity,
                available: this.responsibility.available,

            } : {
                name: null,
                description: null,
                capacity: null,
                available: true,
            },
        }
    },
    methods: {
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('Really delete this responsibility?'))) {
                this.$emit('delete')
            }
        },
    }
}
</script>
