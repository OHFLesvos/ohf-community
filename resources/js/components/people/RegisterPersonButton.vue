<template>
    <p>
        <a
            :href="url"
            class="btn btn-primary"
        >
            <font-awesome-icon icon="plus-circle"/>
            {{ $t('people.register_a_new_person') }}
        </a>
    </p>
</template>

<script>
export default {
    props: {
        url: {
            required: true,
            type: String
        }
    }
}
</script>