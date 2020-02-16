<template>
    <ul class="list-group list-group-flush">

        <two-col-list-group-item :title="$t('people.name')">
            {{ person.name }}
        </two-col-list-group-item>

        <two-col-list-group-item :title="$t('people.family_name')">
            {{ person.family_name }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="person.gender"
            :title="$t('people.gender')"
        >
            <gender-label :value="person.gender"/>
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="person.date_of_birth"
            :title="$t('people.date_of_birth')"
        >
            {{ person.date_of_birth }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="person.age"
            :title="$t('people.age')"
        >
            {{ $t('people.age_n', { age: person.age }) }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="person.police_no"
            :title="$t('people.police_number')"
        >
            {{ person.police_no_formatted }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="person.nationality"
            :title="$t('people.nationality')"
        >
            {{ person.nationality }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="person.languages"
            :title="$t('people.languages')"
        >
            {{ person.languages.join(', ') }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="person.portrait_picture"
            :title="$t('people.portrait_picture')"
        >
            <img :src="person.portrait_picture_url" class="img-fluid img-thumbnail">
        </two-col-list-group-item>

    </ul>
</template>

<script>
import TwoColListGroupItem from '@/components/ui/TwoColListGroupItem'
import GenderLabel from '@/components/people/GenderLabel'
export default {
    components: {
        TwoColListGroupItem,
        GenderLabel
    },
    props: {
        person: {
            type: Object,
            required: true
        }
    }
}
</script>