<template>
    <b-container v-if="category" class="px-0">
        <h5>{{ category.name }}</h5>
        <nl2br
            v-if="category.description"
            tag="p"
            :text="category.description"
        />

        <h6>{{ $t('Transactions') }}</h6>
        <p v-if="category.num_transactions">Registered <strong>{{ category.num_transactions }}</strong> transactions in category.</p>
        <p v-else>{{ $t('No transactions found.') }}</p>

        <p>
            <router-link
                v-if="category.can_update"
                :to="{ name: 'accounting.categories.edit', params: { id: id } }"
                class="btn btn-primary"
            >
                <font-awesome-icon icon="edit" /> {{ $t("Edit") }}</router-link
            >
            <router-link
                :to="{ name: 'accounting.categories.index' }"
                class="btn btn-secondary"
            >
                <font-awesome-icon icon="times-circle" />
                {{ $t("Overview") }}
            </router-link>
        </p>
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import categoriesApi from "@/api/accounting/categories";
import Nl2br from "vue-nl2br";
export default {
    components: {
        Nl2br
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
