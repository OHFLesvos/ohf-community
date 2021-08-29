<template>
    <form @submit.prevent="submit" class="form-row align-items-center">
        <div class="col">
            <tag-input
                v-model="value"
                :suggestions="suggestions"
                :suggestions-provider="suggestionsProvider"
                :preload="preload"
                :disabled="disabled"
            />
        </div>
        <div class="col-auto">
            <b-button
                variant="primary"
                size="sm"
                type="submit"
                key="submit"
                :disabled="disabled"
            >
                <font-awesome-icon icon="check" />
            </b-button>
            <b-button
                variant="secondary"
                size="sm"
                :disabled="disabled"
                key="cancel"
                @click="$emit('cancel')"
            >
                <font-awesome-icon icon="times" />
            </b-button>
        </div>
    </form>
</template>

<script>
import TagInput from '@/components/tags/TagInput'
export default {
    components: {
        TagInput,
        BButton
    },
    props: {
        tags: {
            required: true,
            type: Array
        },
        suggestions: {
            required: false,
            type: Array,
            default: () => []
        },
        suggestionsProvider: {
            required: false,
            type: Function
        },
        preload: Boolean,
        disabled: Boolean,
    },
    data () {
        return {
            value: [...this.tags]
        }
    },
    methods: {
        submit () {
            this.$emit('submit', this.value)
        }
    }
}
</script>
