<template>
    <div>
        <div>
            <img v-if="picture_url" :src="picture_url" class="img-fluid" :style="{ 'max-width': width }">
            <div v-else :style="{ width: width }"><em>{{ $t('No picture has been added.') }}</em></div>
        </div>
        <div v-if="picture_url && cmtyvol.can_update && !replace" class="mt-3">
            <b-button size="sm" @click="replace = true">
                <font-awesome-icon icon="sync"/> {{ $t('Replace') }}
            </b-button>
            <b-button size="sm" variant="danger" @click="removePicture">
                <font-awesome-icon icon="trash"/>{{ $t('Remove') }}
            </b-button>
        </div>
        <div v-else-if="!picture_url && cmtyvol.can_update && !replace" class="mt-3">
            <b-button size="sm" @click="replace = true"><font-awesome-icon icon="sync"/> {{ $t('Upload') }}</b-button>
        </div>
        <div v-if="replace" class="mt-3">
            <b-form-group
                :description="$t('Image will be cropped/resized to 2:3 aspect ratio if necessary.')"
                class="mb-0 bt-3"
            >
                <b-file
                    v-model="new_picture_url"
                    :placeholder="$t('Portrait Picture')"
                    accept="image/*"
                />
            </b-form-group>
            <div class="mt-3">
                <b-button
                    size="sm"
                    variant="success"
                    :disabled="isBusy || !new_picture_url"
                    @click="updatePicture"
                >
                    <font-awesome-icon icon="check"/> {{ $t('Save') }}
                </b-button>
                <b-button
                    size="sm"
                    variant="secondary"
                    :disabled="isBusy"
                    @click="replace = false"
                >
                    <font-awesome-icon icon="times"/>{{ $t('Cancel') }}
                </b-button>
            </div>
        </div>
    </div>
</template>

<script>
import cmtyvolApi from '@/api/cmtyvol/cmtyvol'
import { showSnackbar } from "@/utils";

export default {
    props: {
        cmtyvol: {
            type: Object,
            required: false
        },
        width: {
            type: String,
            required: false,
            default: () => '300px'
        }
    },
    data () {
        return {
            picture_url: this.cmtyvol.portrait_picture_url,
            new_picture_url: null,
            isBusy: false,
            replace: false,
        }
    },
    methods: {
        async updatePicture() {
            this.isBusy = true;
            try {
                let res = await cmtyvolApi.updatePortraitPicture(this.cmtyvol.id, this.new_picture_url);
                showSnackbar(this.$t("Community volunteer updated."));
                this.picture_url = res.url
                this.new_picture_url = null
                this.replace = false
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async removePicture() {
            this.isBusy = true;
            try {
                await cmtyvolApi.removePortraitPicture(this.cmtyvol.id);
                showSnackbar(this.$t("Community volunteer updated."));
                this.picture_url = null
                this.new_picture_url = null
                this.replace = false
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
    }
}
</script>
