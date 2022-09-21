<template>
    <b-avatar
        :size="size"
        :text="text"
        :src="src"
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
        src: {
            required: false,
            type: String
        },
        badgeIcon: {
            required: false,
            type: String
        },
        size: {
            required: false,
            default: "2em"
        }
    },
    computed: {
        text () {
            if (this.src) {
                return null
            }
            const initials = this.value.capitalize().getInitials()
            if (initials.length > 2) {
                return `${initials[0]}${initials[initials.length-1]}`
            }
            return initials
        },
        color () {
            if (this.src) {
                return 'white';
            }
            return stc(this.value)
        }
    }
}
</script>
