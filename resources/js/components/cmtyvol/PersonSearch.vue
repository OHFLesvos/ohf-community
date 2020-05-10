<template>
    <div>
        <!-- Alert -->
        <b-alert variant="danger" :show="errorText != null">
            <i class="fa fa-times-circle"></i> Error: {{ errorText }}
        </b-alert>

        <!-- Input group -->
        <b-input-group class="mb-3">
            <b-form-input
                v-model="filterText"
                debounce="400"
                :trim="true"
                type="search"
                :placeholder="$t('people.search_existing_person')"
                @keydown.enter.prevent="applyFilter"
                @keydown.esc="clearFilter"
                autocomplete="off"
                autofocus
                :state="invalidMessage != null ? false : null"
                ref="person_search"
            ></b-form-input>
            <b-input-group-append>
                <b-button :disabled="filterText.length == 0 || processing" @click="clearFilter">
                    <i class="fa fa-times"></i>
                </b-button>
            </b-input-group-append>
            <b-form-invalid-feedback v-if="invalidMessage != null">{{ invalidMessage }}</b-form-invalid-feedback>
        </b-input-group>

        <p v-if="processing">
            <i class="fa fa-spinner fa-spin"></i> {{ $t('app.searching') }}
        </p>

        <template v-else>

            <!-- Results -->
            <template v-if="suggestions.length > 0">
                <b-form-group :label="$t('people.select_existing_person_or_register_new_one')">
                    <b-form-radio v-model="selected" v-for="sug in suggestions" :key="sug.data" name="some-radios" :value="sug.data">{{ sug.value }}</b-form-radio>
                </b-form-group>
                <input type="hidden" :name="name" :id="name" v-model="selected">
                <slot name="found" v-if="selected != ''"></slot>
                <slot name="not-found"></slot>
            </template>

            <!-- No results found -->
            <template v-else-if="searched">
                <b-alert variant="warning" :show="true">
                    <i class="fa fa-exclamation-circle"></i> {{ $t('app.not_found') }}
                </b-alert>
                <slot name="not-found"></slot>
            </template>

        </template>

    </div>
</template>
<script>
import { getAjaxErrorMessage } from '@/utils'
import axios from '@/plugins/axios'
export default {
    props: {
        name: {
            type: String,
            required: true
        },
        invalid: {
            type: String,
            required: false,
            default: null
        }
    },
    data() {
        return {
            filterText: '',
            errorText: null,
            suggestions: [],
            searched: false,
            selected: '',
            processing: false,
            invalidMessage: this.invalid
        }
    },
    methods: {
        applyFilter() {
            this.invalidMessage = null
            if (this.filterText.length == '') {
                this.clearFilter()
                return
            }
            this.errorText = null
            this.selected = ''
            this.processing = true
            const apiUrl = this.route('api.people.filterPersons')
            axios.get(`${apiUrl}?query=${this.filterText}`)
                .then(data => {
                    this.suggestions = data.data.suggestions;
                    this.searched = true
                })
                .catch(this.handleAjaxError)
                .then(() => {
                    this.processing = false
                })
        },
        clearFilter() {
            this.filterText = ''
            this.suggestions = []
            this.searched = false
            this.selected = ''
            this.$refs.person_search.focus();
        },
        handleAjaxError(err){
            this.errorText = getAjaxErrorMessage(err);
            return [];
        }
    },
    watch: {
        selected(value) {
            this.$emit('selected', value)
        },
        filterText() {
            this.applyFilter()
        }
    }
}
</script>
