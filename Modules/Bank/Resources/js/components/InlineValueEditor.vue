<template>
    <div class="input-group">
        <input
            type="text"
            :placeholder="placeholder"
            v-model="modelValue"
            class="form-control form-control-sm"
            ref="input"
            @keydown.enter="saveEdit"
            @keydown.esc="cancelEdit"
            :disabled="disabled || busy"
        />
        <div class="input-group-append">
            <span class="input-group-text" v-if="busy">
                <icon name="spinner" :spin="true"></icon>
            </span>
            <template v-else>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="saveEdit"
                    :disabled="disabled || busy"
                >
                    <icon name="check"></icon>
                </button>
                <button
                    type="button"
                    class="btn btn-secondary btn-sm"
                    @click="cancelEdit"
                    :disabled="disabled || busy"
                >
                    <icon name="times"></icon>
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
    methods: {
        saveEdit() {
            this.$emit('submit', this.modelValue)
        },
        cancelEdit() {
            this.modelValue = this.value
            this.$emit('cancel')
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
    }
}
</script>