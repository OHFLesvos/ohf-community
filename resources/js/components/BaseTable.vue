<template>
  <div>
    <b-table
        id="my-table"
        striped
        hover
        small
        bordered
        responsive
        :items="items"
        :fields="fields"
        :primary-key="'id'"
        :busy="isBusy"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :per-page="perPage"
        :current-page="currentPage"
    >
        <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope"><slot :name="slot" v-bind="scope"/></template>
        <div slot="table-busy" class="text-center text-danger my-2">
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
        }
    },
    data() {
      return {
        isBusy: false,
        sortBy: this.sortby,
        sortDesc: this.sortdesc,
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
    },
    computed: {
      rows() {
        return this.items.length
      }
    }    
  }
</script>