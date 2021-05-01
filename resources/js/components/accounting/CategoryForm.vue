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
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Parent -->
                <b-col md>
                    <validation-provider
                        :name="$t('Parent category')"
                        vid="parent_id"
                        :rules="{}"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Parent category')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.parent_id"
                                :options="tree"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Description -->
                <b-col md>
                    <validation-provider
                        :name="$t('Description')"
                        vid="description"
                        :rules="{}"
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
                </b-col>
            </b-form-row>

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled || !loaded"
                    >
                        <font-awesome-icon icon="check" />
                        {{ category ? $t("Update") : $t("Add") }}
                    </b-button>

                    <!-- Cancel -->
                    <b-button
                        variant="link"
                        :disabled="disabled || !loaded"
                        @click="$emit('cancel')"
                    >
                        {{ $t("Cancel") }}
                    </b-button>
                </span>

                <!-- Delete -->
                <b-button
                    v-if="category && category.can_delete"
                    variant="link"
                    :disabled="disabled || !loaded"
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
import categoriesApi from "@/api/accounting/categories";
export default {
    props: {
        category: {
            type: Object,
            required: false
        },
        disabled: Boolean
    },
    data() {
        return {
            form: this.category
                ? {
                      name: this.category.name,
                      parent_id: this.category.parent_id,
                      description: this.category.description
                  }
                : {
                      name: null,
                      parent_id: null,
                      description: null
                  },
            tree: [],
            loaded: false
        };
    },
    async created() {
        await this.fetchTree();
        this.loaded = true;
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit() {
            this.$emit("submit", this.form);
        },
        onDelete() {
            if (confirm(this.$t("Really delete this category?"))) {
                this.$emit("delete");
            }
        },
        async fetchTree() {
            let data = await categoriesApi.tree({exclude: this.category.id});
            this.tree = [
                {
                    text: " ",
                    value: null
                }
            ];
            for (let elem of data) {
                this.fillTree(this.tree, elem)
            }
        },
        fillTree(tree, elem, level = 0) {
            let text = '';
            if (level > 0) {
                text += "&nbsp;".repeat(level * 5);
            }
            text += elem.name;
            tree.push({
                html: text,
                value: elem.id
            });
            for (let child of elem.children) {
                this.fillTree(tree, child, level + 1)
            }
        }
    }
};
</script>
