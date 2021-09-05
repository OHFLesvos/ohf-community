<template>
    <div>
        <b-form-row v-if="pictures.length > 0">
            <b-col
                cols="auto"
                v-for="picture in pictures"
                :key="picture.url"
                class="mb-2"
            >
                <a
                    :href="picture.url"
                    :target="picture.type == 'file' ? '_blank' : null"
                    :title="picture.mime_type"
                    @click="picture.type == 'image' ? openLightbox($event, picture.url) : undefined"
                >
                    <ThumbnailImage
                        v-if="picture.thumbnail_url"
                        :url="picture.thumbnail_url"
                        :size="picture.thumbnail_size"
                    />
                    <span v-else class="display-4" :title="picture.mime_type">
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
            :source="source"
            :sources="actualImages.map(i => i.url)"
            :key="this.actualImages.length"
        />
        <template v-if="transaction.can_update && allowUpload">
            <b-button @click="$refs.fileInput.click()" :disabled="isUploading">
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
    </div>
</template>

<script>
import transactionsApi from "@/api/accounting/transactions";
import FsLightbox from "fslightbox-vue";
import ThumbnailImage from "@/components/ThumbnailImage";
export default {
    components: {
        ThumbnailImage,
        FsLightbox
    },
    props: {
        transaction: {
            required: true
        },
        allowUpload: Boolean
    },
    data() {
        return {
            pictures: this.transaction.receipt_pictures,
            toggler: false,
            source: undefined,
            isUploading: false
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
    methods: {
        async addReceiptPicture(event) {
            const files = event.target.files;
            if (files.length == 0) {
                return;
            }
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
        },
        openLightbox(evt, source) {
            evt.preventDefault();
            this.source = source;
            this.toggler = !this.toggler;
        }
    }
};
</script>
