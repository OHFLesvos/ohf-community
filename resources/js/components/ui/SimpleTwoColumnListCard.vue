<template>
    <b-card
        :no-body="!loading"
        :header="loading ? header : null"
        class="mb-4"
    >
        <b-card-header
            v-if="!loading && header"
             header-class="d-flex justify-content-between align-items-center"
        >
            <span>{{ header }}</span>
            <small v-if="headerAddon">{{ headerAddon }}</small>
        </b-card-header>

        <b-card-text v-if="error">
            <em v-if="error" class="text-danger">{{ error }}</em>
        </b-card-text>
        <b-card-text v-else-if="loading">
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
            type: String
        },
        headerAddon: {
            required: false,
            type: String
        },
        items: {
            required: true,
            type: Array
        },
        loading: Boolean,
        error: {
            required: false,
            type: String
        }
    },
}
</script>
