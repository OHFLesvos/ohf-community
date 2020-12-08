<template>
    <b-card
        :header="$t('app.cards')"
        no-body
        class="shadow-sm mb-4">
        <b-table-simple
            v-if="person.card_no"
            hover
            responsive
            class="m-0">
            <b-thead>
                <b-tr>
                    <b-th class="fit">{{ $t('app.date') }}</b-th>
                    <b-th>{{ $t('app.card_number') }}</b-th>
                </b-tr>
            </b-thead>
            <b-tbody>
                <b-tr>
                    <b-td class="fit">
                        <template v-if="person.card_issued">
                            {{ person.card_issued }}
                            <small>{{ person.card_issued_dfh }}</small>
                        </template>
                    </b-td>
                    <b-td>
                        <strong>{{ person.card_no.substr(0, 7) }}</strong>{{ person.card_no.substr(7) }}
                    </b-td>
                </b-tr>
                <b-tr v-for="card in person.revoked_cards" :key="card.card_no">
                    <b-td class="fit">
                        <span class="text-danger">{{ $t('app.revoked') }}</span> {{ card.date }}
                        <small>{{ card.date_dfh }}</small>
                    </b-td>
                    <b-td>
                        <del>
                            <strong>{{ card.card_no.substr(0, 7) }}</strong>{{ card.card_no.substr(7) }}
                        </del>
                    </b-td>
                </b-tr>
            </b-tbody>
        </b-table-simple>
        <info-alert
            v-else
            :message="$t('app.no_cards_registered')"
            class="m-0"
        />
    </b-card>
</template>

<script>
import InfoAlert from '@/components/alerts/InfoAlert'
import { BCard, BTableSimple, BThead, BTbody, BTr, BTh, BTd } from 'bootstrap-vue'
export default {
    components: {
        InfoAlert,
        BCard,
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
        }
    }
}
</script>
