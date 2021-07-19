<template>
    <b-container v-if="transaction" class="px-0">
        <b-list-group flush class="shawow-sm mb-4">
            <!-- Receipt -->
            <two-col-list-group-item :title="$t('Receipt')">
                {{ transaction.receipt_no }}
            </two-col-list-group-item>

            <!-- Date -->
            <two-col-list-group-item :title="$t('Date')">
                {{ transaction.date }}
            </two-col-list-group-item>

            <!-- Amount -->
            <two-col-list-group-item :title="$t('Amount')">
                <span
                    :class="{
                        'text-success': transaction.type == 'income',
                        'text-danger': transaction.type == 'spending'
                    }"
                    >{{ numberFormat(transaction.amount) }}</span
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
                {{ numberFormat(transaction.fees) }}
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
                <a
                    v-if="transaction.supplier.can_view"
                    :href="
                        route(
                            'accounting.suppliers.show',
                            transaction.supplier.slug
                        )
                    "
                >
                    {{ transaction.supplier.name }}
                </a>
                <template v-else>
                    {{ transaction.supplier.name }}
                </template>
                <template v-if="transaction.supplier.category">
                    <br /><small>{{ transaction.supplier.category }}</small>
                </template>
            </two-col-list-group-item>

            <!-- Attendee -->
            <two-col-list-group-item :title="$t('Attendee')">
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
                {{ dateTimeFormat(transaction.created_at) }}
                <small v-if="transaction.creating_user"
                    >({{ transaction.creating_user }})</small
                >
            </two-col-list-group-item>

            <!-- Controlled -->
            <two-col-list-group-item :title="$t('Controlled')">
                <template v-if="transaction.controlled_at">
                    {{ dateTimeFormat(transaction.controlled_at) }}
                    <template v-if="transaction.controlled_by">
                        ({{ transaction.controller_name }})
                        <button
                            v-if="transaction.can_undo_controlling"
                            class="btn btn-secondary btn-sm undo-controlled"
                            :disabled="isBusy"
                            @click="undoControlled()"
                        >
                            {{ $t("Undo") }}
                        </button>
                    </template>
                </template>
                <button
                    v-else-if="transaction.can_update"
                    class="btn btn-primary btn-sm mark-controlled"
                    :disabled="isBusy"
                    @click="markControlled()"
                >
                    {{ $t("Mark as controlled") }}
                </button>
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
                        transaction.can_book_externally &&
                            transaction.external_id
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
                        :disabled="isBusy"
                        @click="undoBooking()"
                    >
                        <font-awesome-icon icon="undo" />
                        {{ $t("Undo booking") }}
                    </button>
                </p>
            </two-col-list-group-item>
        </b-list-group>

        <!-- Pictures -->
        <template v-if="transaction.receipt_pictures.length > 0">
            <hr class="mt-0" />
            <div class="form-row mx-3 mb-2">
                <div
                    v-for="picture in transaction.receipt_pictures"
                    :key="picture.url"
                    class="col-auto mb-2"
                >
                    <a
                        :href="picture.url"
                        :data-fslightbox="
                            picture.type == 'image' ? 'gallery' : null
                        "
                        :target="picture.type == 'file' ? '_blank' : null"
                        :title="picture.mime_type"
                    >
                        <ThumbnailImage
                            v-if="picture.thumbnail_url"
                            :url="picture.thumbnail_url"
                            :size="picture.thumbnail_size"
                        />
                        <span
                            v-else
                            class="display-4"
                            :title="picture.mime_type"
                        >
                            <font-awesome-icon icon="file" />
                        </span>
                    </a>
                    <template v-if="!picture.thumbnail_url">
                        {{ picture.mime_type }} ({{ picture.file_size }})
                    </template>
                </div>
            </div>
        </template>
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import "fslightbox";
import transactionsApi from "@/api/accounting/transactions";
import numeral from "numeral";
import moment from "moment";
import TwoColListGroupItem from "@/components/ui/TwoColListGroupItem";
import ThumbnailImage from "@/components/ThumbnailImage";
export default {
    components: {
        TwoColListGroupItem,
        ThumbnailImage
    },
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
                this.$nextTick(() => refreshFsLightbox());
            } catch (err) {
                alert(err);
                console.error(err);
            }
        },
        numberFormat(val) {
            return numeral(val).format("0,0.00");
        },
        dateTimeFormat(value) {
            return moment(value).format("LLL");
        },
        async markControlled() {
            this.isBusy = true;
            try {
                await transactionsApi.markControlled(this.transaction);
                this.fetch();
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async undoControlled() {
            this.isBusy = true;
            try {
                await transactionsApi.undoControlled(this.transaction);
                this.fetch();
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
                this.fetch();
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        }
    }
};
</script>
