<template>
    <span>
        <icon
            v-if="person.gender == 'f'"
            name="female"
        />
        <icon
            v-if="person.gender == 'm'"
            name="male"
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
            <icon name="globe"/>
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