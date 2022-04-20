<template>
    <b-avatar
        size="2em"
        :text="text"
        :style="{ backgroundColor: color }"
    >
        <template v-slot:badge v-if="badgeIcon"><font-awesome-icon :icon="badgeIcon"/></template>
    </b-avatar>
</template>

<script>
import '@/utils/string'
import stc from 'string-to-color'
export default {
    props: {
        value: {
            required: true,
            type: String
        },
        badgeIcon: {
            required: false,
            type: String
        }
    },
    computed: {
        text () {
            return this.value.capitalize().getInitials()
        },
        color () {
            return stc(this.value)
        }
    }
}
</script>
