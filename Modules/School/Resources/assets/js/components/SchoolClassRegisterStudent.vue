<template>
    <div>
        <p>
            <b-button v-b-modal.search-person-modal variant="primary">
                <i class="fa fa-plus-circle"></i> Add student
            </b-button>
        </p>
        <b-modal id="search-person-modal" title="Search person" hide-footer @hidden="resetModal">
            <b-input-group>
                <b-form-input
                    v-model.trim="search.name"
                    type="text"
                    required
                    autofocus
                    :placeholder="placeholderText"
                    @keyup.enter="searchPersons"
                ></b-form-input>
                <b-input-group-append>
                    <b-button variant="primary" @click="searchPersons"><i class="fa fa-search"></i></b-button>
                </b-input-group-append>
            </b-input-group>                
            <b-list-group v-if="suggestions.length > 0" class="mt-3">
                <b-list-group-item button v-for="suggestion in suggestions" :key="suggestion.data" @click="addPersonToClass(suggestion.data)">
                    {{ suggestion.value }}
                </b-list-group-item>
            </b-list-group>
            <b-alert show variant="warning" v-if="notFound" class="mt-4">No persons found.</b-alert>
            <b-alert show variant="info" v-if="searching" class="mt-4">Searching...</b-alert>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: {
        filterPersonsUrl: {
            required: true
        },
        addStudentUrl: {
            required: true
        },
        redirectUrl: {
            required: true
        },
        placeholderText: {
            required: false,
            default: 'Person name',
        }
    },
    data () {
        return {
            showForm: false,
            search: {
                name: '',
            },
            suggestions: [],
            notFound: false,
            searching: false,
        }
    },
    methods: {
        searchPersons() {
            this.notFound = false
            this.searching = true
            axios.get(this.filterPersonsUrl + '?query=' + this.search.name)
                .then(response => {
                    this.searching = false
                    this.suggestions = response.data.suggestions
                    if (this.suggestions.length == 0) {
                        this.notFound = true
                    }
                })
                .catch(error => {
                    this.searching = false
                    this.$bvModal.msgBoxOk(error.response.data.message, {
                        okVariant: 'danger',
                    })                    
                })
        },
        addPersonToClass(personKey) {
            axios.post(this.addStudentUrl, {
                    person: personKey
                })
                .then(response => {
                    this.$bvModal.hide('search-person-modal')
                    this.$emit('added')
                    window.location = this.redirectUrl
                })
                .catch(error => {
                    this.$bvModal.msgBoxOk(error.response.data.message, {
                        okVariant: 'danger',
                    })
                })
        },
        resetModal() {
            this.search.name = ''
            this.suggestions = []
            this.notFound = false
            this.searching = false
        }
    }
}
</script>