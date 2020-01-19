<template>
    <p>
        <a
            :href="url"
            class="btn btn-primary"
        >
            <font-awesome-icon icon="plus-circle"/>
            {{ lang['people::people.register_a_new_person'] }}
        </a>
    </p>
</template>

<script>
export default {
    props: {
        url: {
            required: true,
            type: String
        },
        lang: {
            type: Object,
            required: true
        }
    }
}
</script>