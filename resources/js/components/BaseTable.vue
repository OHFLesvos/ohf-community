<template>
  <div>
    <b-table
        id="my-table"
        striped
        hover
        small
        bordered
        responsive
        :items="myProvider"
        :fields="fields"
        :primary-key="'id'"
        :busy.sync="isBusy"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :per-page="perPage"
        :current-page="currentPage"
        :api-url="apiUrl"
    >
        <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope"><slot :name="slot" v-bind="scope"/></template>
        <div slot="table-busy" class="text-center my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
        </div>
    </b-table>
    <b-pagination
        size="sm"
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
    ></b-pagination>    
  </div>
</template>

<script>
  export default {
    props: {
        items: {
            required: true,
        },
        fields: {
            required: true,
        },
        sortby: {
            required: false,
        },
        sortdesc: {
            required: false,
            default: false,
        },
        apiurl: {
            required: false,
        }
    },
    data() {
      return {
        isBusy: false,
        sortBy: this.sortby,
        sortDesc: this.sortdesc,
        apiUrl: this.apiurl,
        perPage: 3,
        currentPage: 1,
      }
    },
    methods: {
        mailtoHref(val) {
            return `mailto:${val}`;
        },
        telHref(val) {
            return `tel:${val}`;
        },
        myProvider(ctx, callback) {
            this.isBusy = true
            const promise = axios.get(this.apiUrl + '?page=' + ctx.currentPage + '&pageSize=' + ctx.perPage + '&sortBy=' + ctx.sortBy  + '&sortDirection=' + (ctx.sortDesc ? 'desc' : 'asc'))
            return promise.then(data => {
                this.isBusy = false
                const items = data.data
                return items || []
            })
        }        
    },
    computed: {
      rows() {
        return this.items.length
      }
    }    
  }
</script>