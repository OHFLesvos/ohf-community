<template>
    <b-container v-if="transaction" class="px-0">
        <b-list-group flush class="shawow-sm mb-4">
            <!-- Wallet -->
            <two-col-list-group-item :title="$t('Wallet')">
                <router-link
                    :to="{
                        name: 'accounting.transactions.index',
                        params: {
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
            <b-list-group-item
                v-if="pictures.length > 0 || transaction.can_update"
            >
                <b-form-row>
                    <b-col
                        cols="auto"
                        v-for="(picture, idx) in pictures"
                        :key="picture.url"
                        class="mb-2"
                    >
                        <a
                            :href="picture.url"
                            :target="picture.type == 'file' ? '_blank' : null"
                            :title="picture.mime_type"
                            @click="openLightbox($event, idx)"
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
                    </b-col>
                </b-form-row>
                <FsLightbox
                    v-if="this.actualImages.length > 0"
                    :toggler="toggler"
                    :sourceIndex="sourceIndex"
                    :sources="actualImages.map(i => i.url)"
                    :key="this.actualImages.length"
                />
                <template v-if="transaction.can_update">
                    <b-button
                        @click="$refs.fileInput.click()"
                        :disabled="isBusy"
                    >
                        <font-awesome-icon :icon="icon" :spin="iconSpin" />
                        {{ $t("Add picture") }}
                    </b-button>
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/*,application/pdf"
                        multiple
                        @change="addReceiptPicture"
                        v-show="false"
                    />
                </template>
            </b-list-group-item>
        </b-list-group>

        <p>
            <router-link
                v-if="transaction.can_update"
                :to="{
                    name: 'accounting.transactions.edit',
                    params: { id: id }
                }"
                class="btn btn-primary"
            >
                <font-awesome-icon icon="edit" /> {{ $t("Edit") }}</router-link
            >
            <router-link
                :to="{
                    name: 'accounting.transactions.index',
                    params: { wallet: transaction.wallet_id }
                }"
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
import FsLightbox from "fslightbox-vue";
import transactionsApi from "@/api/accounting/transactions";
import numeral from "numeral";
import moment from "moment";
import TwoColListGroupItem from "@/components/ui/TwoColListGroupItem";
import ThumbnailImage from "@/components/ThumbnailImage";
export default {
    components: {
        TwoColListGroupItem,
        ThumbnailImage,
        FsLightbox
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            transaction: null,
            isBusy: false,
            pictures: [],
            isUploading: false,
            toggler: false,
            sourceIndex: 0
        };
    },
    computed: {
        actualImages() {
            return this.pictures.filter(i => i.type == "image");
        },
        icon() {
            if (this.isUploading) {
                return "spinner";
            }
            return "upload";
        },
        iconSpin() {
            return this.isUploading == true;
        }
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
                this.pictures = this.transaction.receipt_pictures;
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
        },
        async addReceiptPicture(event) {
            const files = event.target.files;
            if (files.length == 0) {
                return;
            }
            this.isBusy = true;
            this.isUploading = true;
            try {
                let data = await transactionsApi.updateReceipt(
                    this.transaction,
                    event.target.files
                );
                this.pictures = data;
            } catch (err) {
                alert(err);
            }
            this.isUploading = false;
            this.isBusy = false;
        },
        openLightbox(evt, idx)
        {
            evt.preventDefault();
            this.sourceIndex = idx;
            this.toggler = !this.toggler;
        }
    }
};
</script>
