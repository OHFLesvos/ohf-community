<template>
    <b-list-group flush class="shawow-sm mb-4">
        <!-- Wallet -->
        <two-col-list-group-item :title="$t('Wallet')">
            <router-link
                :to="{
                    name: 'accounting.transactions.index',
                    query: {
                        wallet: transaction.wallet_id
                    }
                }"
            >
                {{ transaction.wallet_name }}
            </router-link>
        </two-col-list-group-item>

        <!-- Receipt -->
        <two-col-list-group-item :title="$t('Receipt')">
            {{ transaction.receipt_no }}
        </two-col-list-group-item>

        <!-- Date -->
        <two-col-list-group-item :title="$t('Date')">
            {{ transaction.date | dateFormat }}
        </two-col-list-group-item>

        <!-- Budget -->
        <two-col-list-group-item
            v-if="transaction.budget_id"
            :title="$t('Budget')"
        >
            <router-link
                v-if="can('view-budgets')"
                :to="{
                    name: 'accounting.budgets.show',
                    params: { id: transaction.budget_id }
                }"
                >{{ transaction.budget_name }}</router-link
            >
            <template v-else>
                {{ transaction.budget_name }}
            </template>
        </two-col-list-group-item>

        <!-- Amount -->
        <two-col-list-group-item :title="$t('Amount')">
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
        </two-col-list-group-item>

        <!-- Fees -->
        <two-col-list-group-item
            v-if="transaction.fees"
            :title="$t('Transaction fees')"
        >
            {{ transaction.fees_formatted }}
        </two-col-list-group-item>

        <!-- Category -->
        <two-col-list-group-item :title="$t('Category')">
            {{ transaction.category_full_name }}
        </two-col-list-group-item>

        <!-- Secondary Category -->
        <two-col-list-group-item
            v-if="transaction.secondary_category"
            :title="$t('Secondary Category')"
        >
            {{ transaction.secondary_category }}
        </two-col-list-group-item>

        <!-- Project -->
        <two-col-list-group-item
            v-if="transaction.project_id"
            :title="$t('Project')"
        >
            {{ transaction.project_full_name }}
        </two-col-list-group-item>

        <!-- Location -->
        <two-col-list-group-item
            v-if="transaction.location"
            :title="$t('Location')"
        >
            {{ transaction.location }}
        </two-col-list-group-item>

        <!-- Cost Center -->
        <two-col-list-group-item
            v-if="transaction.cost_center"
            :title="$t('Cost Center')"
        >
            {{ transaction.cost_center }}
        </two-col-list-group-item>

        <!-- Description -->
        <two-col-list-group-item :title="$t('Description')">
            {{ transaction.description }}
        </two-col-list-group-item>

        <!-- Supplier -->
        <two-col-list-group-item
            v-if="transaction.supplier"
            :title="$t('Supplier')"
        >
            <router-link
                v-if="transaction.supplier.can_view"
                :to="{
                    name: 'accounting.suppliers.show',
                    params: { id: transaction.supplier.slug }
                }"
            >
                {{ transaction.supplier.name }}
            </router-link>
            <template v-else>
                {{ transaction.supplier.name }}
            </template>
            <template v-if="transaction.supplier.category">
                <br /><small>{{ transaction.supplier.category }}</small>
            </template>
        </two-col-list-group-item>

        <!-- Attendee -->
        <two-col-list-group-item
            v-if="transaction.attendee"
            :title="$t('Attendee')"
        >
            {{ transaction.attendee }}
        </two-col-list-group-item>

        <!-- Remarks -->
        <two-col-list-group-item
            v-if="transaction.remarks"
            :title="$t('Remarks')"
        >
            {{ transaction.remarks }}
        </two-col-list-group-item>

        <!-- Registered -->
        <two-col-list-group-item :title="$t('Registered')">
            {{ transaction.created_at | dateTimeFormat }}
        </two-col-list-group-item>

        <!-- Last updated -->
        <two-col-list-group-item :title="$t('Last updated')">
            {{ transaction.updated_at | dateTimeFormat }}
        </two-col-list-group-item>

        <!-- Controlled -->
        <two-col-list-group-item :title="$t('Controlled')">
            <template v-if="transaction.controlled_at">
                {{ transaction.controlled_at | dateTimeFormat }}
                <template v-if="transaction.controlled_by">
                    <small>({{ transaction.controller_name }})</small>
                    <b-button
                        v-if="transaction.can_undo_controlling"
                        variant="secondary"
                        size="sm"
                        :disabled="isBusy || disabled"
                        @click="undoControlled()"
                    >
                        {{ $t("Undo") }}
                    </b-button>
                </template>
            </template>
            <b-button
                v-else-if="transaction.can_control"
                variant="primary"
                size="sm"
                :disabled="isBusy || disabled"
                @click="markControlled()"
            >
                {{ $t("Mark as controlled") }}
            </b-button>
            <template v-else>
                {{ $t("No") }}
            </template>
        </two-col-list-group-item>

        <!-- Booked -->
        <two-col-list-group-item
            v-if="transaction.booked"
            :title="$t('Booked')"
        >
            <template
                v-if="
                    transaction.can_book_externally && transaction.external_id
                "
            >
                Webling:
                <template v-if="transaction.external_url">
                    <a :href="transaction.external_url" target="_blank">{{
                        transaction.external_id
                    }}</a>
                </template>
                <template v-else>
                    {{ transaction.external_id }}
                </template>
            </template>
            <template v-else>
                {{ $t("Yes") }}
            </template>
            <p v-if="transaction.can_undo_booking" class="mb-0 mt-2">
                <button
                    type="submit"
                    class="btn btn-sm btn-outline-danger"
                    :disabled="isBusy || disabled"
                    @click="undoBooking()"
                >
                    <font-awesome-icon icon="undo" />
                    {{ $t("Undo booking") }}
                </button>
            </p>
        </two-col-list-group-item>
        <b-list-group-item
            v-if="
                this.transaction.receipt_pictures.length > 0 ||
                    transaction.can_update
            "
        >
            <TransactionPictures :transaction="transaction" allowUpload />
        </b-list-group-item>
    </b-list-group>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
import TwoColListGroupItem from "@/components/ui/TwoColListGroupItem";
import TransactionPictures from "@/components/accounting/TransactionPictures";
export default {
    components: {
        TwoColListGroupItem,
        TransactionPictures
    },
    props: {
        transaction: {
            required: true
        },
        disabled: Boolean
    },
    data() {
        return {
            isBusy: false
        };
    },
    methods: {
        async markControlled() {
            this.isBusy = true;
            try {
                await transactionsApi.markControlled(this.transaction);
                this.$emit("update");
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async undoControlled() {
            this.isBusy = true;
            try {
                await transactionsApi.undoControlled(this.transaction);
                this.$emit("update");
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async undoBooking() {
            if (!confirm(this.$t("Really undo booking?"))) {
                return;
            }

            this.isBusy = true;
            try {
                await transactionsApi.undoBooking(this.transaction);
                this.$emit("update");
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        }
    }
};
</script>
