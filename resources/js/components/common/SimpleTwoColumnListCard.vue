<template>
    <b-card
        :no-body="!loading"
        :header="header"
        class="mb-4"
    >
        <b-card-text v-if="loading">
            <em>{{ $t('app.loading') }}</em>
        </b-card-text>
        <template v-else>
            <b-list-group flush>
                <b-list-group-item
                    v-for="item in items"
                    :key="item.name"
                    class="d-flex justify-content-between"
                >
                    <span>{{ item.name }}</span>
                    <span>{{ item.value }}</span>
                </b-list-group-item>
            </b-list-group>
        </template>
    </b-card>
</template>

<script>
export default {
    props: {
        header: {
            required: true,
            type: String
        },
        items: {
            required: true,
            type: Array
        },
        loading: Boolean
    },
}
</script>
