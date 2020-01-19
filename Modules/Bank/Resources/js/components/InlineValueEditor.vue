<template>
    <div class="input-group">
        <input
            ref="input"
            v-model="modelValue"
            type="text"
            :placeholder="placeholder"
            class="form-control form-control-sm"
            :disabled="disabled || busy"
            @keydown.enter="saveEdit"
            @keydown.esc="cancelEdit"
        />
        <div class="input-group-append">
            <span
                v-if="busy"
                class="input-group-text"
            >
                <font-awesome-icon
                    icon="spinner"
                    :spin="true"
                />
            </span>
            <template v-else>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    :disabled="disabled || busy"
                    @click="saveEdit"
                >
                    <font-awesome-icon icon="check"/>
                </button>
                <button
                    type="button"
                    class="btn btn-secondary btn-sm"
                    :disabled="disabled || busy"
                    @click="cancelEdit"
                >
                    <font-awesome-icon icon="times"/>
                </button>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            required: true
        },
        placeholder: {
            type: String,
            required: false,
            default: ''
        },
        disabled: Boolean,
        busy: Boolean
    },
    data() {
        return {
            modelValue: this.value
        }
    },
    watch: {
        busy(val, oldVal) {
            if (!val && oldVal) {
                this.$nextTick(() => {
                    this.$refs.input.focus()
                })
            }
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.$refs.input.focus()
        })
    },
    methods: {
        saveEdit() {
            this.$emit('submit', this.modelValue)
        },
        cancelEdit() {
            this.modelValue = this.value
            this.$emit('cancel')
        }
    }
}
</script>