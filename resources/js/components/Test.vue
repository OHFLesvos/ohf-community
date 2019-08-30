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
        <!-- <template slot="table-caption">There are {{ items.length }} records.</template> -->
        <div slot="table-busy" class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Loading...</strong>
        </div>
        <template slot="[first_name]" slot-scope="data">
            <a :href="data.item.url">{{ data.value }}</a>
        </template>
        <template slot="[last_name]" slot-scope="data">
            <a :href="data.item.url">{{ data.value }}</a>
        </template>
        <template slot="[company]" slot-scope="data">
            <a :href="data.item.url">{{ data.value }}</a>
        </template>
        <template slot="[email]" slot-scope="data">
            <a :href="mailtoHref(data.value)" v-if="data.value != ''">{{ data.value }}</a>
        </template>
        <template slot="[phone]" slot-scope="data">
            <a :href="telHref(data.value)" v-if="data.value != ''">{{ data.value }}</a>
        </template>
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