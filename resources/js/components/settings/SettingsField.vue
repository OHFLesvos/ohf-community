<template>
    <div class="mb-3">
        <p v-if="field.type == 'checkbox'">
            <b-form-checkbox
                v-model="modelValue"
                v-bind="field.args"
                :disabled="disabled"
                >{{ field.label }}</b-form-checkbox
            >
        </p>
        <b-form-group v-else :label="field.label" :description="field.help">
            <b-form-textarea
                v-if="field.type == 'textarea'"
                v-model="modelValue"
                v-bind="field.args"
                :placeholder="field.placeholder"
                :disabled="disabled"
            />
            <b-form-input
                v-else-if="field.type == 'number'"
                v-model="modelValue"
                v-bind="field.args"
                type="number"
                :placeholder="field.placeholder"
                :disabled="disabled"
            />
            <b-form-input
                v-else-if="field.type == 'text'"
                v-model="modelValue"
                v-bind="field.args"
                :placeholder="field.placeholder"
                :disabled="disabled"
            />
            <b-form-select
                v-else-if="field.type == 'select'"
                v-model="modelValue"
                v-bind="field.args"
                :options="{ null: field.placeholder, ...field.list }"
                :disabled="disabled"
            />
            <b-form-file
                v-else-if="field.type == 'file'"
                v-model="modelValue"
                v-bind="field.args"
                :placeholder="field.placeholder ?? $t('Choose file...')"
                :disabled="disabled"
            />
        </b-form-group>
        <template v-if="field.type == 'file' && field.file_url">
                <a :href="field.file_url" target="_blank">
                    <img
                        v-if="field.file_is_image"
                        :src="field.file_url"
                        class="img-fluid img-thumbnail mb-2"
                        style="max-height: 200px"
                    />
                    <template v-else>{{ $t("View file") }}</template>
                </a>
                <br>
                <b-button
                    :disabled="disabled"
                    variant="outline-danger"
                    size="sm"
                    @click="$emit('reset', fieldKey)">{{ $t("Remove file") }}
                </b-button>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        field: {
            required: true,
            type: Object,
        },
        disabled: Boolean,
        value: {
            required: true
        },
        fieldKey: {
            required: true,
            type: String
        }
    },
    computed: {
        modelValue: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    }
};
</script>
