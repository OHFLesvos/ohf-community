<template>
    <div>

        <info-alert
            v-if="person.is_active_helper"
            :message="$t('people.person_registered_as_helper')"
        />

        <info-alert
            v-if="person.remarks"
            :message="`${$t('people.remarks')}: ${person.remarks}`"
        />

        <div class="row mb-3">
            <div class="col-md">

                <person-properties :person="person" />

            </div>
            <div class="col-md">

                <person-cards-list :person="person" />

                <person-coupons-list
                    :person="person"
                    :show-handout-limit="showHandoutLimit"
                />

            </div>
        </div>

        <hr>
        <p class="text-right">
            <small>{{ $t('app.created') }}: {{ person.created_at_dfh }}
                <small class="text-muted">{{ person.created_at }}</small>
            </small>
            <br>
            <small>{{ $t('app.last_updated')}}: {{ person.updated_at_dfh }}
                <small class="text-muted">{{ person.updated_at }}</small>
            </small>
        </p>

    </div>
</template>

<script>
import PersonProperties from '@/components/people/PersonProperties'
import PersonCardsList from '@/components/people/PersonCardsList'
import PersonCouponsList from '@/components/people/PersonCouponsList'
import InfoAlert from '@/components/alerts/InfoAlert'
export default {
    components: {
        PersonProperties,
        PersonCardsList,
        PersonCouponsList,
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