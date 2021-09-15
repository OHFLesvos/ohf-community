<template>
    <span>
        <b-button :variant="variant" @click="handleClick()" :disabled="isBusy">
            <font-awesome-icon :icon="icon" :spin="iconSpin" />
        </b-button>
        <FsLightbox
            v-if="this.actualImages.length > 0"
            :toggler="toggler"
            :sources="actualImages.map(i => i.url)"
        />
        <input
            v-if="this.pictures.length == 0"
            ref="fileInput"
            type="file"
            accept="image/*,application/pdf"
            multiple
            @change="handleUpload"
            v-show="false"
        />
    </span>
</template>

<script>
import FsLightbox from "fslightbox-vue";
import transactionsApi from "@/api/accounting/transactions";
export default {
    components: { FsLightbox },
    props: {
        transaction: {
            required: true
        },
        value: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            pictures: this.value,
            toggler: false,
            isBusy: false
        };
    },
    computed: {
        actualImages() {
            return this.pictures.filter(i => i.type == "image");
        },
        icon() {
            if (this.isBusy) {
                return "spinner";
            }
            if (this.pictures.length == 0) {
                return "upload";
            }
            const numImages = this.actualImages.length;
            if (numImages > 1) {
                return "images";
            }
            if (numImages == 1) {
                return "image";
            }
            if (this.pictures.length > 1) {
                return "copy";
            }
            return "file";
        },
        iconSpin() {
            return this.isBusy == true;
        },
        variant() {
            if (this.isBusy) {
                return "info";
            }
            if (this.pictures.length > 0) {
                return "success";
            }
            return "warning";
        }
    },
    methods: {
        handleClick() {
            if (this.actualImages.length > 0) {
                this.toggler = !this.toggler;
                return;
            }
            if (this.pictures.length > 0) {
                window.open(this.pictures[0].url, "_blank");
                return;
            }
            this.$refs.fileInput.click();
        },
        async handleUpload(event) {
            const files = event.target.files;
            if (files.length == 0) {
                return;
            }
            this.isBusy = true;
            try {
                let data = await transactionsApi.updateReceipt(
                    this.transaction,
                    event.target.files
                );
                this.pictures = data;
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        }
    }
};
</script>
