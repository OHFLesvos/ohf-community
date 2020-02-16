<template>
    <div>
        <h4>{{ $t('coupons.coupons') }}</h4>
        <template v-if="person.handouts.length > 0">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>{{ $t('app.date') }}</th>
                            <th>{{ $t('app.type') }}</th>
                            <th>{{ $t('app.registered') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="handout in person.handouts" :key="handout.id">
                            <td>{{ handout.date }}</td>
                            <td>{{ handout.amount }} {{ handout.coupon_name }}</td>
                            <td>
                                {{ handout.created_at_dfh }}
                                <small class="text-muted">{{ handout.created_at }}</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p><small>
                <template v-if="showHandoutLimit < person.num_handouts">
                    {{ $t('app.last_n_transactions_shown', { num: showHandoutLimit }) }}
                </template>
                {{ $t('coupons.n_coupons_received_total_since_date', {
                    num: person.num_handouts,
                    date: person.first_handout_date,
                    date_diff: person.first_handout_date_diff,
                }) }}
            </small></p>
        </template>
        <info-alert
            v-else
            :message="$t('coupons.no_coupons_received_so_far')"
        />
    </div>
</template>

<script>
import InfoAlert from '@/components/alerts/InfoAlert'
export default {
    components: {
        InfoAlert
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