<template>
    <span>
        <template v-if="prefix.length > 0">{{ prefix }}:</template>
        <font-awesome-icon
            v-if="person.gender == 'f'"
            icon="female"
        />
        <font-awesome-icon
            v-if="person.gender == 'm'"
            icon="male"
        />
        {{ person.fullName }}<template v-if="person.date_of_birth">, {{ person.date_of_birth }}
        ({{ person.age_formatted }})</template><template v-if="person.nationality">, {{ person.nationality }}</template>
        <template v-if="suffix.length > 0"><small class="text-muted">{{ $suffix }}</small></template>
    </span>
</template>

<script>
import FontAwesomeIcon from './common/FontAwesomeIcon'
export default {
    components: {
        FontAwesomeIcon
    },
    props: {
        person: {
            type: Object,
            required: true
        },
        prefix: {
            type: String,
            required: false,
            default: ''
        },
        suffix: {
            type: String,
            required: false,
            default: ''
        }
    }
}
</script>