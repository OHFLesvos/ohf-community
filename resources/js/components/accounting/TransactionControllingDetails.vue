<template>
    <div>
        <dl class="row mb-0">
            <dt class="col-sm-4">{{ $t("Receipt") }}</dt>
            <dd class="col-sm-8">{{ transaction.receipt_no }}</dd>

            <dt class="col-sm-4">{{ $t("Date") }}</dt>
            <dd class="col-sm-8">{{ transaction.date | dateFormat }}</dd>

            <dt class="col-sm-4">{{ $t("Amount") }}</dt>
            <dd class="col-sm-8">
                <span
                    :class="{
                        'text-success': transaction.type == 'income',
                        'text-danger': transaction.type == 'spending'
                    }"
                    >{{ transaction.amount_formatted }}</span
                >
                <small v-if="transaction.type == 'income'"
                    >({{ $t("Income") }})</small
                >
                <small v-if="transaction.type == 'spending'"
                    >({{ $t("Spending") }})</small
                >
            </dd>

            <template v-if="transaction.budget_id">
                <dt class="col-sm-4">{{ $t("Budget") }}</dt>
                <dd class="col-sm-8">{{ transaction.budget_name }}</dd>
            </template>

            <template v-if="transaction.fees">
                <dt class="col-sm-4">{{ $t("Transaction fees") }}</dt>
                <dd class="col-sm-8">{{ transaction.fees_formatted }}</dd>
            </template>

            <dt class="col-sm-4">{{ $t("Category") }}</dt>
            <dd class="col-sm-8">{{ transaction.category_full_name }}</dd>

            <template v-if="transaction.secondary_category">
                <dt class="col-sm-4">{{ $t("Secondary Category") }}</dt>
                <dd class="col-sm-8">{{ transaction.secondary_category }}</dd>
            </template>

            <template v-if="transaction.project_id">
                <dt class="col-sm-4">{{ $t("Project") }}</dt>
                <dd class="col-sm-8">{{ transaction.project_full_name }}</dd>
            </template>

            <template v-if="transaction.location">
                <dt class="col-sm-4">{{ $t("Location") }}</dt>
                <dd class="col-sm-8">{{ transaction.location }}</dd>
            </template>

            <template v-if="transaction.cost_center">
                <dt class="col-sm-4">{{ $t("Cost Center") }}</dt>
                <dd class="col-sm-8">{{ transaction.cost_center }}</dd>
            </template>

            <dt class="col-sm-4">{{ $t("Description") }}</dt>
            <dd class="col-sm-8">{{ transaction.description }}</dd>

            <template v-if="transaction.supplier">
                <dt class="col-sm-4">{{ $t("Supplier") }}</dt>
                <dd class="col-sm-8">
                    {{ transaction.supplier.name }}
                    <template v-if="transaction.supplier.category">
                        <br /><small>{{ transaction.supplier.category }}</small>
                    </template>
                </dd>
            </template>

            <template v-if="transaction.attendee">
                <dt class="col-sm-4">{{ $t("Attendee") }}</dt>
                <dd class="col-sm-8">{{ transaction.attendee }}</dd>
            </template>

            <template v-if="transaction.remarks">
                <dt class="col-sm-4">{{ $t("Remarks") }}</dt>
                <dd class="col-sm-8">{{ transaction.remarks }}</dd>
            </template>

            <dt class="col-sm-4">{{ $t("Registered") }}</dt>
            <dd class="col-sm-8">
                {{ transaction.created_at | dateTimeFormat }}
            </dd>

            <dt class="col-sm-4">{{ $t("Last updated") }}</dt>
            <dd class="col-sm-8">
                {{ transaction.updated_at | dateTimeFormat }}
            </dd>
        </dl>
        <TransactionPictures :transaction="transaction" />
        <b-button
            :to="{
                name: 'accounting.transactions.show',
                params: { id: transaction.id }
            }"
        >
            <font-awesome-icon icon="search" />
            {{ $t("Details") }}
        </b-button>
        <template v-if="controlled">
            <b-button
                v-if="transaction.can_undo_controlling"
                variant="warning"
                :disabled="isBusy"
                @click="undoControlled()"
            >
            <font-awesome-icon icon="undo" />
                {{ $t("Undo") }}
            </b-button>
        </template>
        <b-button
            v-else-if="transaction.can_control"
            variant="primary"
            :disabled="isBusy"
            @click="markControlled()"
        >
        <font-awesome-icon icon="check" />
            {{ $t("Mark as controlled") }}
        </b-button>
    </div>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
import TransactionPictures from "@/components/accounting/TransactionPictures";
export default {
    components: {
        TransactionPictures
    },
    props: {
        transaction: {
            required: true
        }
    },
    data() {
        return {
            isBusy: false,
            controlled: false
        };
    },
    methods: {
        async markControlled() {
            this.isBusy = true;
            try {
                await transactionsApi.markControlled(this.transaction);
                this.controlled = true;
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async undoControlled() {
            this.isBusy = true;
            try {
                await transactionsApi.undoControlled(this.transaction);
                this.controlled = false;
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        }
    }
};
</script>
