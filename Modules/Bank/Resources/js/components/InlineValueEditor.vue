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
            :disabled="disabled"
        />
        <div class="input-group-append">
            <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="saveEdit"
                :disabled="disabled"
            >
                <icon name="check"></icon>
            </button>
            <button
                type="button"
                class="btn btn-secondary btn-sm"
                @click="cancelEdit"
                :disabled="disabled"
            >
                <icon name="times"></icon>
            </button>
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
        disabled: Boolean
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
    mounted() {
        this.$nextTick(() => {
            this.$refs.input.focus()
        })
    }
}
</script>