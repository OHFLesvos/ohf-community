<template>
    <type-ahead
        v-model="input"
        :src="url"
        :fetch="fetch"
        :getResponse="getResponse"
        :limit="10"
        :onHit="onHit"
        :render="render"
        :placeholder="$t('library.search_title_author_isbn')"
    />
</template>

<script>
import axios from '@/plugins/axios'
// Documentation: https://www.npmjs.com/package/vue2-typeahead
import TypeAhead from 'vue2-typeahead'
export default {
    components: {
        TypeAhead
    },
    data () {
        return {
            input: '',
            selectedItem: null,
            url: `${this.route('api.library.books.filter')}?query=:keyword`
        }
    },
    watch: {
        input (val) {
            if (this.selectedItem && this.selectedItem.value != val) {
                this.selectedItem = null
            }
        },
        selectedItem (val) {
            this.$emit('select', val ? val.data : null)
        }
    },
    methods: {
        onHit: function (item, vue, index) {
            if (index >= 0) {
                this.selectedItem = vue.data[index]
            }
            vue.query = item
        },
        render: function (items) {
            return items.map(e => e.value)
        },
        getResponse: function (res) {
            return res.data.suggestions
        },
        fetch: function (url) {
            return axios.get(url)
        }
    }
}
</script>
