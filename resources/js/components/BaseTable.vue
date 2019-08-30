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
                    @click="$root.$emit('bv::refresh::table', tableId)"
                    class="float-right"
                >
                    <i class="fa fa-redo"></i> Reload
                </b-button>
            </b-col>
        </b-row>
    </b-alert>
    <b-table
        :id="tableId"
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
    <div class="float-right">
        <small>Total: {{ totalRows }}</small>
    </div>
    <b-pagination
        v-if="totalRows > 0"
        size="sm"
        v-model="currentPage"
        :total-rows="totalRows"
        :per-page="perPage"
        :aria-controls="tableId"
    ></b-pagination>    
  </div>
</template>

<script>
  export default {
    props: {
        id: {
            required: true,
            type: String,
        },
        fields: {
            required: true,
            type: Object,
        },
        apiurl: {
            required: true,
            type: String
        },  
        sortby: {
            required: true,
            type: String
        },
        sortdesc: {
            required: false,
            type: Boolean,
            default: false,
        },
        emptyText: {
            required: false,
            type: String
        }
    },
    data() {
      return {
        tableId: this.id,
        isBusy: false,
        sortBy: this.sortby,
        sortDesc: this.sortdesc,
        apiUrl: this.apiurl,
        perPage: 25,
        currentPage: 1,
        totalRows: 0,
        errorText: null,
      }
    },
    methods: {
        mailtoHref(val) {
            return `mailto:${val}`;
        },
        telHref(val) {
            return `tel:${val}`;
        },
        itemProvider(ctx, callback) {
            this.isBusy = true
            this.errorText = null
            this.totalRows = 0
            const promise = axios.get(this.apiUrl + '?page=' + ctx.currentPage + '&pageSize=' + ctx.perPage + '&sortBy=' + ctx.sortBy  + '&sortDirection=' + (ctx.sortDesc ? 'desc' : 'asc'))
            return promise.then(data => {
                this.isBusy = false
                const items = data.data.data
                this.totalRows = data.data.meta.total
                return items || []
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
        }

    }    
  }
</script>