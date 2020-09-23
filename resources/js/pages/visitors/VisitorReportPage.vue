<template>
    <b-container>
        <p class="text-right">
            <b-button
                variant="secondary"
                :to="{ name: 'visitors.listCurrent' }"
            >
                <font-awesome-icon icon="arrow-left"/>
                {{ $t('app.back') }}
            </b-button>
        </p>
        <h3>Visitors by day</h3>
        <b-table
            :items="itemProvider"
            :fields="fields"
            striped
            hover
            small
            bordered
            responsive
            :show-empty="true"
            :empty-text="$t('app.no_data_registered')"
        >
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ $t('app.loading') }}</strong>
            </div>
        </b-table>
    </b-container>
</template>

<script>
import moment from 'moment'
import visitorsApi from '@/api/visitors'
export default {
    components: {
    },
    data () {
        return {
            data: [],
            fields: [
                {
                    key: 'day',
                    label: this.$t('app.date')
                },
                {
                    key: 'amount',
                    label: this.$t('app.amount')
                },
            ]
        }
    },
    methods: {
        async itemProvider(ctx) {
            let data = await visitorsApi.dailyVisitors()
            return data || []
        }
    }
}
</script>
