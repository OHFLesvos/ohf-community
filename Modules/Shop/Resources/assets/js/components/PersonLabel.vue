<template>
    <span>
        <template v-if="prefix.length > 0">{{ prefix }}:</template>
        <icon name="female" v-if="person.gender == 'f'"></icon>
        <icon name="male" v-if="person.gender == 'm'"></icon>
        {{ person.fullName }}<template v-if="person.date_of_birth">, {{ person.date_of_birth }}
        ({{ person.age_formatted }})</template><template v-if="person.nationality">, {{ person.nationality }}</template>
        <template v-if="suffix.length > 0"><small class="text-muted">{{ $suffix }}</small></template>
    </span>
</template>

<script>
import Icon from './Icon'
export default {
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
    },
    components: {
        Icon
    }
}
</script>