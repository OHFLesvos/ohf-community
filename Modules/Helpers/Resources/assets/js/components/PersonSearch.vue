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
                :trim="true"
                type="search"
                :placeholder="placeholder"
                @keydown.enter.prevent="applyFilter"
                @keydown.esc="clearFilter"
                autocomplete="off"
                autofocus
                :state="invalidMessage != null ? false : null"
            ></b-form-input>
            <b-input-group-append v-if="processing">
                <b-button variant="primary" :disabled="true">
                    <i class="fa fa-spinner fa-spin"></i>
                </b-button>
            </b-input-group-append>
            <b-input-group-append v-if="!processing">
                <b-button :disabled="!filterText" variant="primary" @click="applyFilter">
                    <i class="fa fa-search"></i>
                </b-button>
            </b-input-group-append>
            <b-input-group-append>
                <b-button :disabled="!filterText || processing" @click="clearFilter">
                    <i class="fa fa-times"></i>
                </b-button>
            </b-input-group-append>
            <b-form-invalid-feedback v-if="invalidMessage != null">{{ invalidMessage }}</b-form-invalid-feedback>
        </b-input-group>

        <!-- Results -->
        <template v-if="suggestions.length > 0">
            <b-form-group :label="foundLabel">
                <b-form-radio v-model="selected" v-for="sug in suggestions" :key="sug.data" name="some-radios" :value="sug.data">{{ sug.value }}</b-form-radio>
            </b-form-group>
            <input type="hidden" :name="name" :id="name" v-model="selected">
            <slot v-if="selected != ''"></slot>
            <slot name="not-found"></slot>
        </template>

        <!-- No results found -->
        <template v-else-if="searched">
            <b-alert variant="warning" :show="true">
                <i class="fa fa-exclamation-circle"></i> {{ notFoundLabel }}
            </b-alert>
            <slot name="not-found"></slot>
        </template>
    </div>
</template>
<script>
import { getAjaxErrorMessage } from '../../../../../../resources/js/utils'

export default {
    props: {
        name: {
            type: String,
            required: true
        },        
        apiUrl: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            required: false,
            default: 'Search person'
        },
        foundLabel: {
            type: String,
            required: false,
            default: 'Select an existing person or register a new one:'
        },
        notFoundLabel: {
            type: String,
            required: false,
            default: 'No person found.'
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
            let url = this.apiUrl + '?query=' + this.filterText;
            axios.get(url)
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
        },
        handleAjaxError(err){
            this.errorText = getAjaxErrorMessage(err);
            return [];
        }
    },
    watch: {
        selected(value, oldValue) {
            this.$emit('selected', value)
        }
    }    
}
</script>