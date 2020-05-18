<template>
    <b-input-group size="sm" class="mb-3">
        <b-form-input
            v-model="filterText"
            debounce="400"
            :trim="true"
            type="search"
            :placeholder="placeholder"
            autocomplete="off"
            @keyup.enter="$emit('apply')"
            @keyup.esc="$emit('clear')"
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
</template>

<script>
export default {
    props: {
        value: {
            required: true,
        },
        placeholder: {
            required: false,
            type: String,
               default: function() {
                return this.$t('app.type_to_search')
            }
        },
        totalRows: {
            type: Number
        },
        isBusy: Boolean,
    },
    computed: {
        filterText: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    }
}
</script>