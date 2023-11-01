<template>
    <b-container v-if="category">
        <b-card :title="`${$t('Category')} ${category.name}`" body-class="pb-0">
            <b-card-text v-if="category.description" class="pre-formatted">{{ category.description }}</b-card-text>

            <h5>{{ $t('Transactions') }}</h5>
            <p v-if="category.num_transactions">Registered <strong>{{ category.num_transactions }}</strong> transactions in category.</p>
            <p v-else>{{ $t('No transactions found.') }}</p>
        </b-card>
        <p class="mt-3">
            <router-link
                v-if="category.can_update"
                :to="{ name: 'accounting.categories.edit', params: { id: id } }"
                class="btn btn-primary"
            >
                <font-awesome-icon icon="edit" /> {{ $t("Edit") }}</router-link
            >
        </p>
    </b-container>
    <b-container v-else>
        {{ $t("Loading...") }}
    </b-container>
</template>

<script>
import categoriesApi from "@/api/accounting/categories";
export default {
    title() {
        return this.$t("View category");
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            category: null
        };
    },
    watch: {
        $route() {
            this.fetch();
        }
    },
    async created() {
        this.fetch();
    },
    methods: {
        async fetch() {
            try {
                let data = await categoriesApi.find(this.id);
                this.category = data.data;
            } catch (err) {
                alert(err);
            }
        }
    }
};
</script>
