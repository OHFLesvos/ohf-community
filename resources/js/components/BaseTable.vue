<template>
  <div>
    <b-alert variant="danger" :show="errorText != null">
        <b-row align-v="center">
            <b-col>
                <i class="fa fa-times-circle"></i> Error: {{ errorText }}
            </b-col>
            <b-col sm="auto">
                <b-button
                    variant="danger"
                    size="sm"
                    @click="$root.$emit('bv::refresh::table', id)"
                    class="float-right"
                >
                    <i class="fa fa-redo"></i> Reload
                </b-button>
            </b-col>
        </b-row>
    </b-alert>

    <p v-if="Object.keys(tags).length > 0" class="mb-3">
        Tags:
        <tag-select-button
            :label="tag_name"
            :value="tag_key"
            :toggled="tagSelected(tag_key)"
            @toggled="toggleTag"
            v-for="(tag_name, tag_key) in tags" :key="tag_key"
        ></tag-select-button>
    </p>

    <b-input-group size="sm" class="mb-3">
        <b-form-input
            v-model="filterText"
            debounce="400"
            :trim="true"
            type="search"
            :placeholder="filterPlaceholder"
            autocomplete="off"
            @keyup.enter="applyFilter"
            @keyup.esc="clearFilter"
        ></b-form-input>
        <b-input-group-append class="d-none d-sm-block">
            <b-input-group-text v-if="isBusy">
                ...
            </b-input-group-text>
            <b-input-group-text v-else>
                {{ $t('app.n_results', { num: totalRows }) }}
            </b-input-group-text>
        </b-input-group-append>
    </b-input-group>

    <b-table
        :id="id"
        striped
        hover
        small
        bordered
        responsive
        :items="itemProvider"
        :fields="fields"
        :primary-key="'id'"
        :busy.sync="isBusy"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :per-page="perPage"
        :current-page="currentPage"
        :api-url="apiUrl"
        :show-empty="true"
        :empty-text="emptyText"
        :empty-filtered-text="$t('app.no_records_matching_your_request')"
        :no-sort-reset="true"
        :filter="filter"
    >
        <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope"><slot :name="slot" v-bind="scope"/></template>
        <div slot="table-busy" class="text-center my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>{{ loadingLabel }}</strong>
        </div>
        <template slot="empty" slot-scope="scope">
            <em>{{ scope.emptyText }}</em>
        </template>
        <template slot="emptyfiltered" slot-scope="scope">
            <em>{{ scope.emptyFilteredText }}</em>
        </template>
    </b-table>

    <b-row align-v="center" class="mb-2">
        <b-col>
            <b-pagination
                v-if="totalRows > 0"
                size="sm"
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
                :aria-controls="id"
                class="mb-0"
            ></b-pagination>
        </b-col>
        <b-col sm="auto" class="text-right">
            <small>
                {{ $t('app.x_out_of_y', {
                    x: `${((currentPage - 1) * perPage) + 1} - ${Math.min(currentPage * perPage, totalRows)}`,
                    y: totalRows
                }) }}
            </small>
        </b-col>
    </b-row>

  </div>
</template>

<script>
import axios from '@/plugins/axios'
import TagSelectButton from '@/components/tags/TagSelectButton'
import { getAjaxErrorMessage } from '@/utils'
export default {
    components: {
        'tag-select-button': TagSelectButton,
    },
    props: {
        id: {
            required: true,
            type: String,
        },
        fields: {
            required: false,
            type: Array,
        },
        apiUrl: {
            required: true,
            type: String
        },
        defaultSortBy: {
            required: true,
            type: String
        },
        defaultSortDesc: {
            required: false,
            type: Boolean,
            default: false,
        },
        emptyText: {
            required: false,
            type: String
        },
        itemsPerPage: {
            required: false,
            type: Number,
            default: 25
        },
        filterPlaceholder: {
            require: false,
            type: String,
            default: function() {
                return this.$t('app.type_to_search')
            }
        },
        tags: {
            require: false,
            type: Object,
            default: () => {
                return {}
            }
        },
        tag: {
            require: false,
            type: String,
            default: null
        },
        loadingLabel: {
            type: String,
            required: false,
            default: function() {
                return this.$t('app.loading')
            }
        },
    },
    data() {
      return {
        isBusy: false,
        sortBy: sessionStorage.getItem(this.id + '.sortBy')
            ? sessionStorage.getItem(this.id + '.sortBy')
            : this.defaultSortBy,
        sortDesc: sessionStorage.getItem(this.id + '.sortDesc')
            ? sessionStorage.getItem(this.id + '.sortDesc') == 'true'
            : this.defaultSortDesc,
        currentPage: sessionStorage.getItem(this.id + '.currentPage')
            ? sessionStorage.getItem(this.id + '.currentPage')
            : 1,
        perPage: this.itemsPerPage,
        totalRows: 0,
        errorText: null,
        filter: sessionStorage.getItem(this.id + '.filter')
            ? sessionStorage.getItem(this.id + '.filter')
            : '',
        filterText: sessionStorage.getItem(this.id + '.filter')
            ? sessionStorage.getItem(this.id + '.filter')
            : '',
        selectedTags: this.getSelectedTags()
      }
    },
    methods: {
        getSelectedTags () {
            let tags
            if (this.tag) {
                tags = [this.tag]
                sessionStorage.setItem(this.id + '.selectedTags', JSON.stringify(tags));
            } else if (sessionStorage.getItem(this.id + '.selectedTags')) {
                tags = JSON.parse(sessionStorage.getItem(this.id + '.selectedTags'))
            } else {
                tags = []
            }
            return tags.filter(e => Object.keys(this.tags).indexOf(e) >= 0)
        },
        applyFilter() {
            this.filter = this.filterText
            this.currentPage = 1
        },
        clearFilter() {
            this.filterText = ''
            this.filter = ''
            this.currentPage = 1
        },
        itemProvider(ctx, callback) {
            this.isBusy = true
            this.errorText = null
            let url = this.apiUrl + '?filter=' + ctx.filter + '&page=' + ctx.currentPage + '&pageSize=' + ctx.perPage + '&sortBy=' + ctx.sortBy  + '&sortDirection=' + (ctx.sortDesc ? 'desc' : 'asc')
            for (let i = 0; i < this.selectedTags.length; i++) {
                url += '&tags[]=' + this.selectedTags[i]
            }
            const promise = axios.get(url)
            return promise.then(data => {
                this.isBusy = false
                this.totalRows = data.data.meta.total
                sessionStorage.setItem(this.id + '.sortBy', ctx.sortBy)
                sessionStorage.setItem(this.id + '.sortDesc', ctx.sortDesc)
                sessionStorage.setItem(this.id + '.currentPage', ctx.currentPage)
                return data.data.data || []
            }).catch((err) => {
                this.handleAjaxError(err)
                this.totalRows = 0
            })
        },
        handleAjaxError(err){
            this.errorText = getAjaxErrorMessage(err);
            return [];
        },
        toggleTag(value, toggled) {
            this.selectedTags = this.selectedTags.filter((v) => v != value)
            if (toggled) {
                this.selectedTags.push(value)
            }
            sessionStorage.setItem(this.id + '.selectedTags', JSON.stringify(this.selectedTags))
            this.$root.$emit('bv::refresh::table', this.id)
        },
        tagSelected(key) {
            return this.selectedTags.indexOf(key) >= 0
        }
    },
    watch: {
        filter(val, oldVal) {
            sessionStorage.setItem(this.id + '.filter', val)
        },
        filterText(val, oldVal) {
            this.applyFilter()
        }
    }
  }
</script>
