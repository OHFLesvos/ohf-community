<template>
  <div>
    <b-table striped hover small bordered responsive :items="items" :fields="fields" :primary-key="'id'" :busy="isBusy">
        <!--:fields="fields"-->
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
// :fields="fields"
  export default {
    props: [
        'items',
        'fields',
    ],
    mounted () {
    },      
    data() {
      return {
        isBusy: false,
        // fields: {
        //     email: {
        //         label: 'E-Mail',
        //     }
        // }
        // // Note 'isActive' is left out and will not appear in the rendered table
        // fields: [
        //   {
        //     key: 'last_name',
        //     sortable: true
        //   },
        //   {
        //     key: 'first_name',
        //     sortable: false
        //   },
        //   {
        //     key: 'age',
        //     label: 'Person age',
        //     sortable: true,
        //   }
        // ],          
        // items: [
        //   { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
        //   { age: 21, first_name: 'Larsen', last_name: 'Shaw' },
        //   { age: 89, first_name: 'Geneva', last_name: 'Wilson' },
        //   { age: 38, first_name: 'Jami', last_name: 'Carney' }
        // ]
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