<template>
    <b-container class="mb-3">
        <h1 class="display-4">{{ $t("Settings") }}</h1>
        <template v-if="loaded">
            <template v-if="Object.keys(fields).length">
                <b-tabs content-class="">
                    <b-tab
                        v-for="(sectionLabel, sectionKey) in sections"
                        :key="sectionKey"
                        :title="sectionLabel"
                        class="bg-white pt-3 px-3 pb-1"
                    >
                        <div
                            v-for="(field, fieldKey) in fields"
                            :key="fieldKey"
                        >
                            <SettingsField
                                v-if="field.section == sectionKey"
                                v-model="formData[fieldKey]"
                                :field="field"
                                :disabled="isBusy"
                            />
                        </div>
                    </b-tab>
                </b-tabs>
                <div class="mt-3 d-flex justify-content-between">
                    <b-button
                        variant="outline-danger"
                        type="submit"
                        @click="resetSettings"
                    >
                        <font-awesome-icon icon="arrow-rotate-left" />
                        {{ $t("Reset to default settings") }}
                    </b-button>
                    <b-button variant="primary" @click="updateSettings">
                        <font-awesome-icon icon="check" /> {{ $t("Save") }}
                    </b-button>
                </div>
            </template>
            <b-alert v-else variant="info" show>
                {{
                    $t("There is currently no content available for you here.")
                }}
            </b-alert>
        </template>
        <div v-else>
            {{ $t("Loading...") }}
        </div>
    </b-container>
</template>

<script>
import settingsApi from "@/api/settings";
import SettingsField from "@/components/settings/SettingsField.vue";
import { showSnackbar } from "@/utils";
export default {
    components: {
        SettingsField,
    },
    data() {
        return {
            loaded: false,
            fields: {},
            sections: {},
            isBusy: false,
            formData: {},
        };
    },
    async created() {
        this.fetchSettings();
    },
    methods: {
        async fetchSettings() {
            let data = await settingsApi.fields();
            this.sections = data.sections;
            this.fields = data.fields;
            this.formData = Object.fromEntries(
                Object.entries(this.fields).map(([key, { value }]) => [
                    key,
                    value,
                ])
            );
            this.loaded = true;
        },
        async updateSettings() {
            this.isBusy = true;
            try {
                let data = await settingsApi.update(this.formData);
                showSnackbar(data.message);
                await this.fetchSettings();
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async resetSettings() {
            if (!confirm(this.$t("Really reset to default settings?"))) {
                return;
            }
            this.isBusy = true;
            try {
                let data = await settingsApi.reset();
                await this.fetchSettings();
                showSnackbar(data.message);
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
    },
};
</script>
