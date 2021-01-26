<template>
    <b-container>
        <b-form v-if="loaded" @submit.stop.prevent="onSubmit">
            <b-card
                :header="$t('app.export')"
                class="shadow-sm mb-4"
                footer-class="text-right"
            >
                <!-- File format -->
                <b-form-group :label="$t('app.file_format')">
                    <b-form-radio-group
                        v-model="form.format"
                        :options="formats"
                        stacked
                    />
                </b-form-group>

                <!-- Selection -->
                <b-form-group :label="$t('app.selection')">
                    <b-form-radio-group
                        v-model="form.selection"
                        :options="selections"
                        stacked
                    />
                </b-form-group>
                <template #footer>
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="isBusy"
                    >
                        <font-awesome-icon :icon="isBusy ? 'spinner' : 'download'" :spin="isBusy" />
                        {{ $t("app.export") }}
                    </b-button>
                </template>
            </b-card>
        </b-form>
        <p v-else>
            {{ $t("app.loading") }}
        </p>
    </b-container>
</template>

<script>
import libraryApi from "@/api/library";
import { postRequest } from "@/utils/form";
export default {
    data() {
        return {
            loaded: false,
            form: {
                format: null,
                selection: null
            },
            isBusy: false,
            formats: null,
            selections: null
        };
    },
    async created() {
        try {
            let data = await libraryApi.fetchExportData();
            this.form.format = data.format;
            this.form.selection = data.selection;
            this.formats = Object.entries(data.formats).map(e => ({
                value: e[0],
                text: e[1]
            }));
            this.selections = Object.entries(data.selections).map(e => ({
                value: e[0],
                text: e[1]
            }));
            this.loaded = true;
        } catch (err) {
            alert(err);
        }
    },
    methods: {
        async onSubmit() {
            this.isBusy = true;
            try {
                postRequest(this.route("api.library.doExport"), this.form);
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        }
    }
};
</script>
