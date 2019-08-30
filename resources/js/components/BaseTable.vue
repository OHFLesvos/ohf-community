<template>
  <div>
    <b-table 
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
    >
        <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope"><slot :name="slot" v-bind="scope"/></template>
        <div slot="table-busy" class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
        </div>
    </b-table>
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
      }
    },
    methods: {
        mailtoHref(val) {
            return `mailto:${val}`;
        },
        telHref(val) {
            return `tel:${val}`;
        },
    }
  }
</script>