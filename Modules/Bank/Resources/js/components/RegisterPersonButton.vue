<template>
    <p>
        <a :href="url" class="btn btn-primary">
            <icon name="plus-circle"></icon>
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