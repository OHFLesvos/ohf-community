<template>
    <div>
        <h4>{{ $t('app.cards') }}</h4>
        <div
            v-if="person.card_no"
            class="table-responsive"
        >
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th class="fit">{{ $t('app.date') }}</th>
                        <th>{{ $t('app.card_number') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fit">
                            <template v-if="person.card_issued">
                                {{ person.card_issued }}
                                <small>{{ person.card_issued_dfh }}</small>
                            </template>
                        </td>
                        <td>
                            <strong>{{ person.card_no.substr(0, 7) }}</strong>{{ person.card_no.substr(7) }}
                        </td>
                    </tr>
                    <tr v-for="card in person.revoked_cards" :key="card.card_no">
                        <td class="fit">
                            <span class="text-danger">{{ $t('app.revoked') }}</span> {{ card.date }}
                            <small>{{ card.date_dfh }}</small>
                        </td>
                        <td>
                            <del>
                                <strong>{{ card.card_no.substr(0, 7) }}</strong>{{ card.card_no.substr(7) }}
                            </del>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <info-alert
            v-else
            :message="$t('app.no_cards_registered')"
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
        }
    }
}
</script>