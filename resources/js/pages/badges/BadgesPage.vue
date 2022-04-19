<template>
    <b-container>
        <b-form @submit.prevent="submit">
            <b-card :header="$t('Data source')" class="shadow-sm mb-4" footer-class="d-flex justify-content-between">
                <b-form-group :label="$t('Source')">
                    <b-radio-group v-model="source" :options="sources"/>
                </b-form-group>
                <template v-if="source == 'list'">
                    <b-form-row v-for="(element, index) in elements" :key="index" class="mb-3">
                        <b-col>
                            <b-input
                                v-model="element.name"
                                :key="`name.${index}`"
                                :placeholder="$t('Name')"
                                autocomplete="off"

                                autofocus/>
                        </b-col>
                        <b-col :cols="4">
                            <b-input
                                v-model="element.position"
                                :key="`position.${index}`"
                                :placeholder="$t('Position')"
                                autocomplete="off"/>
                        </b-col>
                        <b-col :cols="4">
                            <b-file
                                v-model="element.picture"
                                :key="`picture.${index}`"
                                :placeholder="$t('Picture')"
                                accept="image/*"/>
                        </b-col>
                        <b-col cols="auto">
                            <b-button
                                variant="danger"
                                :disabled="elements.length == 1"
                                @click="removeElement(index)">
                                <font-awesome-icon icon="minus-circle"/>
                            </b-button>
                        </b-col>
                    </b-form-row>
                    <b-button @click="addElement" variant="success">
                        <font-awesome-icon icon="plus-circle"/> {{ $t('Add row') }}
                    </b-button>
                </template>
                <template v-else-if="source == 'file'">
                    <b-form-group :description="$t('File must be in Excel or CSV format and contain the columns \'Name\', \'Position\'.')">
                        <b-file
                            v-model="importFile"
                            :placeholder="$t('Choose file...')"
                            accept=".xlsx,.xls,.csv"/>
                    </b-form-group>
                    <b-button
                        :disabled="!importFile || isBusy"
                        variant="success"
                        @click="uploadFile"
                    >
                        <font-awesome-icon icon="upload"/> {{ $t('Upload') }}
                    </b-button>
                </template>
                <template v-else-if="source == 'cmtyvol'">
                    <b-button
                        :disabled="isBusy"
                        variant="success"
                        @click="fetchCommunityVolunteers"
                    >
                        <font-awesome-icon icon="download"/> {{ $t('Load data') }}
                    </b-button>
                </template>
                <template #footer>
                    <b-button
                        type="button"
                        variant="secondary"
                        :disabled="isBusy"
                        @click="reset"
                    >
                        {{ $t('Reset') }}
                    </b-button>
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="isBusy || !isReady"
                    >
                        <font-awesome-icon
                            :icon="isSubmitting ? 'spinner' : 'check'"
                            :spin="isSubmitting"
                        />
                        {{ $t('Create') }}
                    </b-button>
                </template>
            </b-card>
        </b-form>
    </b-container>
</template>

<script>
import badgesApi from "@/api/badges";
export default {
    data() {
        const sources = [
            { value: 'list', text: this.$t('List') },
            { value: 'file', text: this.$t('File') },
        ]
        if (this.can("view-community-volunteers")) {
            sources.push({ value: 'cmtyvol', text: this.$t('Community Volunteers') })
        }
        return {
            source: 'list',
            sources: sources,
            elements: [this.createElement()],
            importFile: null,
            isBusy: false,
            isSubmitting: false
        }
    },
    computed: {
        isReady() {
            return this.elements.filter(e => e.name).length > 0;
        }
    },
    methods: {
        async submit() {
            this.isBusy = true;
            this.isSubmitting = true;
            try {
                await badgesApi.make({
                    elements: this.elements,
                })
            } catch (err) {
                alert(err);
                console.error(err);
            }
            this.isBusy = false;
            this.isSubmitting = false;
        },
        createElement: () => ({ name: '', position: '', file: null }),
        addElement() {
            this.elements.push(this.createElement());
        },
        removeElement(index) {
            this.elements.splice(index, 1)
        },
        async uploadFile() {
            this.isBusy = true;
            try {
                let data = await badgesApi.parseSpreadsheet(this.importFile);
                this.elements = this.elements.filter(e => e.name)
                this.elements.push(...data);
                this.importFile = null;
                this.source = 'list'
            } catch (err) {
                alert(err);
                console.error(err);
            }
            this.isBusy = false;
        },
        async fetchCommunityVolunteers() {
            this.isBusy = true;
            try {
                let data = await badgesApi.fetchCommunityVolunteers();
                this.elements = this.elements.filter(e => e.name)
                this.elements.push(...data);
                this.source = 'list'
            } catch (err) {
                alert(err);
                console.error(err);
            }
            this.isBusy = false;
        },
        reset() {
            this.importFile = null;
            this.source = 'list'
            this.elements = [this.createElement()]
        }
    }
}
</script>
