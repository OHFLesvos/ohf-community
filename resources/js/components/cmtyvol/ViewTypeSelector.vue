<template>
    <b-button-group size="sm">
        <b-button
            v-for="(type, key) in viewTypes"
            :key="key"
            :variant="value == key ? 'dark' : 'secondary'"
            @click="$emit('input', key)"
        >
            <font-awesome-icon :icon="type.icon" />
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
            viewTypes: {
                list: {
                    label: this.$t('app.list'),
                    icon: 'table'
                },
                grid: {
                    label: this.$t('app.grid'),
                    icon: 'grip-horizontal'
                }
            }
        }
    }
}
</script>