<template>
    <b-container v-if="transaction" class="px-0">
        Edit
        <p>
            <router-link
                :to="{
                    name: 'accounting.transactions.show',
                    params: { id: id }
                }"
                class="btn btn-secondary"
            >
                <font-awesome-icon icon="times-circle" />
                {{ $t("Cancel") }}
            </router-link>
        </p>
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
export default {
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            transaction: null,
            isBusy: false
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
                let data = await transactionsApi.find(this.id);
                this.transaction = data.data;
            } catch (err) {
                alert(err);
                console.error(err);
            }
        }
    }
};
</script>
