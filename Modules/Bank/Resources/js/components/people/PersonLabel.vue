<template>
    <span>
        <font-awesome-icon
            v-if="person.gender == 'f'"
            icon="female"
        />
        <font-awesome-icon
            v-if="person.gender == 'm'"
            icon="male"
        />
        <a
            v-if="person.url"
            :href="person.url"
            target="_blank"
        >{{ person.name }}</a>
        <template v-else>
            {{ person.name }}
        </template>
        <template v-if="person.date_of_birth">
            {{ person.date_of_birth }}
            ({{ person.age }})
        </template>
        <template v-if="person.nationality">
            <font-awesome-icon icon="globe"/>
            {{ person.nationality }}
        </template>
    </span>
</template>

<script>
export default {
    props: {
        person: {
            type: Object,
            required: true
        }
    }
}
</script>