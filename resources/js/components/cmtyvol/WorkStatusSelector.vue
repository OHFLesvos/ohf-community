<template>
    <b-button-group size="sm">
        <b-button
            v-for="(label, key) in workStatuses"
            :key="key"
            :variant="value == key ? 'dark' : 'secondary'"
            @click="$emit('input', key)"
        >
            {{ label }}
        </b-button>
    </b-button-group>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            required: true
        }
    },
    data () {
        return {
            workStatuses: {
                active: this.$t('app.active'),
                future: this.$t('app.future'),
                alumni: this.$t('app.alumni'),
            }
        }
    }
}
</script>
