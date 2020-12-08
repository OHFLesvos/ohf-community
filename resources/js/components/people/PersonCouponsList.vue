<template>
    <b-card
        :header="$t('bank.coupons')"
        no-body
        class="shadow-sm mb-4">
        <template v-if="person.handouts.length > 0">
            <b-table-simple
                hover
                responsive
                class="m-0">
                <b-thead>
                    <b-tr>
                        <b-th>{{ $t('app.date') }}</b-th>
                        <b-th>{{ $t('app.type') }}</b-th>
                        <b-th>{{ $t('app.registered') }}</b-th>
                    </b-tr>
                </b-thead>
                <b-tbody>
                    <b-tr v-for="handout in person.handouts" :key="handout.id">
                        <b-td>{{ handout.date }}</b-td>
                        <b-td>{{ handout.amount }} {{ handout.coupon_name }}</b-td>
                        <b-td>
                            {{ handout.created_at_dfh }}
                            <small class="text-muted">{{ handout.created_at }}</small>
                        </b-td>
                    </b-tr>
                </b-tbody>
            </b-table-simple>
            <b-card-footer>
                <small>
                    <template v-if="showHandoutLimit < person.num_handouts">
                        {{ $t('app.last_n_transactions_shown', { num: showHandoutLimit }) }}
                    </template>
                    {{ $t('bank.n_coupons_received_total_since_date', {
                        num: person.num_handouts,
                        date: person.first_handout_date,
                        date_diff: person.first_handout_date_diff,
                    }) }}
                </small>
            </b-card-footer>
        </template>
        <info-alert
            v-else
            :message="$t('bank.no_coupons_received_so_far')"
            class="m-0"
        />
    </b-card>
</template>

<script>
import InfoAlert from '@/components/alerts/InfoAlert'
import { BCard, BCardFooter, BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
export default {
    components: {
        InfoAlert,
        BCard,
        BCardFooter,
        BTableSimple,
        BThead,
        BTbody,
        BTr,
        BTh,
        BTd
    },
    props: {
        person: {
            type: Object,
            required: true
        },
        showHandoutLimit: {
            type: Number,
            required: false,
            default: 15
        }
    }
}
</script>
