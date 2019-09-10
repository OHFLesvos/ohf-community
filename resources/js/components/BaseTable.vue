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

    <p v-if="tags" class="mb-3">
        Tags:
        <tag-select-button 
            :label="tag_name" 
            :value="tag_key"
            :toggled="selectedTags.indexOf(tag_key) > 0"
            @toggled="toggleTag"
            v-for="(tag_name, tag_key) in tags" :key="tag_key"
        ></tag-select-button>
    </p>

    <b-input-group size="sm" class="mb-3">
        <b-form-input
            v-model="filterText"
            :trim="true"
            type="search"
            :placeholder="filterPlaceholder"
            @keyup.enter="applyFilter"
            @keyup.esc="clearFilter"
        ></b-form-input>
        <b-input-group-append>
            <b-button :disabled="!filterText" variant="primary" @click="applyFilter">
                <i class="fa fa-search"></i>
            </b-button>
        </b-input-group-append>
        <b-input-group-append>
            <b-button :disabled="!filterText" @click="clearFilter">
                <i class="fa fa-times"></i>
            </b-button>
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
        :no-sort-reset="true"
        :filter="filter"
    >
        <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope"><slot :name="slot" v-bind="scope"/></template>
        <div slot="table-busy" class="text-center my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
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
            <small>{{ ((currentPage - 1) * perPage) + 1 }} - {{ Math.min(currentPage * perPage, totalRows) }}, Total: {{ totalRows }}</small>
        </b-col>
    </b-row>

  </div>
</template>

<script>
  import TagSelectButton from './TagSelectButton'
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
            required: true,
            type: Object,
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
            default: 'Type to Search'
        },
        tags: {
            require: false,
            type: Object,
            default: { }
        }
    },
    data() {
      return {
        isBusy: false,
        sortBy: localStorage.getItem(this.id + '.sortBy') ? localStorage.getItem(this.id + '.sortBy') : this.defaultSortBy,
        sortDesc: localStorage.getItem(this.id + '.sortDesc') ? localStorage.getItem(this.id + '.sortDesc') == 'true' : this.defaultSortDesc,
        currentPage: localStorage.getItem(this.id + '.currentPage') ? localStorage.getItem(this.id + '.currentPage') : 1,
        perPage: this.itemsPerPage,
        totalRows: 0,
        errorText: null,
        filter: '',
        filterText: '',
        selectedTags: [], 
      }
    },
    methods: {
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
            this.totalRows = 0
            let url = this.apiUrl + '?filter=' + ctx.filter + '&page=' + ctx.currentPage + '&pageSize=' + ctx.perPage + '&sortBy=' + ctx.sortBy  + '&sortDirection=' + (ctx.sortDesc ? 'desc' : 'asc')
            for (let i = 0; i < this.selectedTags.length; i++) {
                url += '&tags[]=' + this.selectedTags[i]
            }
            const promise = axios.get(url)
            return promise.then(data => {
                this.isBusy = false
                this.totalRows = data.data.meta.total
                localStorage.setItem(this.id + '.sortBy', ctx.sortBy)
                localStorage.setItem(this.id + '.sortDesc', ctx.sortDesc)
                localStorage.setItem(this.id + '.currentPage', ctx.currentPage)
                return data.data.data || []
            }).catch(this.handleAjaxError)
        },
        handleAjaxError(err){
            var msg;
            if (err.response.data.message) {
                msg = err.response.data.message;
            }
            if (err.response.data.errors) {
                msg += "\n" + Object.entries(err.response.data.errors).map(([k, v]) => {
                    return v.join('. ');
                });
            } else if (err.response.data.error) {
                msg = err.response.data.error;
            }
            this.errorText = msg;
            return [];
        },
        toggleTag(value, toggled) {
            this.selectedTags = this.selectedTags.filter((v) => v != value)
            if (toggled) {
                this.selectedTags.push(value)
            }
            console.log(this.selectedTags)
            this.$root.$emit('bv::refresh::table', this.id)
        }
    }    
  }
</script>