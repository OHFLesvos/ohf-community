<template>
    <div class="card mb-4 bg-light">

        <!-- Card header -->
        <div
            class="card-header p-2"
            :style="headerStyle"
        >
            <div class="form-row">
                <div class="col">
                    <helper-marker
                        v-if="person.is_helper"
                        :url="person.can_view_helper ? person.show_helper_url : null"
                        :lang="lang"
                    />
                    <name-label
                        :url="person.can_view ? person.show_url : null"
                        :value="person.full_name"
                        :highlight-terms="highlightTerms"
                    />
                    <gender-selector
                        :api-url="person.can_update ? person.gender_update_url : null"
                        :value="person.gender"
                        :disabled="disabled"
                    />
                    <date-of-birth-selector
                        :api-url="person.can_update ? person.date_of_birth_update_url : null"
                        :value="person.date_of_birth"
                        :disabled="disabled"
                        @setAge="$emit('change')"
                    />
                    <nationality-selector
                        :api-url="person.can_update ? person.nationality_update_url : null"
                        :value="person.nationality"
                        :disabled="disabled"
                    />
                    <frequent-visitor-marker
                        v-if="person.frequent_visitor"
                    />
                    <edit-link
                        v-if="person.can_update"
                        :url="person.edit_url"
                    />
                </div>
                <div class="col-auto">
                    <card-number-label
                        :api-url="person.can_update ? person.register_card_url : null"
                        :value="person.card_no"
                        :disabled="disabled"
                        :lang="lang"
                    />
                </div>
            </div>
        </div>

        <!-- Card body -->
        <div class="card-body p-2">
            <police-no-label
                v-if="person.police_no"
                :value="person.police_no_formatted"
                :highlight-terms="highlightTerms"
                :lang="lang"
            />
            <case-no-label
                v-if="person.case_no_hash"
                :value="person.case_no_hash"
                :lang="lang"
            />
            <remarks-label
                :value="person.remarks"
                :api-url="person.can_update ? person.remarks_update_url : null"
                :lang="lang"
            />
            <overdue-book-lendings-label
                v-if="person.has_overdue_book_lendings"
                :url="person.can_operate_library ? person.library_lending_person_url : null"
                :lang="lang"
            />
        </div>

        <!-- Card footer -->
        <div class="card-body p-0 px-2 pt-2">
            <div class="form-row" v-if="person.coupon_types.length > 0">
                <div
                    v-for="coupon in person.coupon_types"
                    :key="coupon.id"
                    class="col-sm-auto mb-2"
                >
                    <coupon-handout-button
                        :coupon="coupon"
                        :disabled="disabled"
                        :lang="lang"
                    />
                </div>
            </div>
            <div
                v-else
                class="pb-2 px-2"
            >
                <em>{{ lang['people::people.no_coupons_defined'] }}</em>
            </div>
        </div>

    </div>
</template>

<script>
import HelperMarker from '../people/HelperMarker'
import NameLabel from '../people/NameLabel'
import GenderSelector from '../people/GenderSelector'
import DateOfBirthSelector from '../people/DateOfBirthSelector'
import NationalitySelector from '../people/NationalitySelector'
import FrequentVisitorMarker from './FrequentVisitorMarker'
import EditLink from '../people/EditLink'
import CardNumberLabel from '../people/CardNumberLabel'
import PoliceNoLabel from '../people/PoliceNoLabel'
import CaseNoLabel from '../people/CaseNoLabel'
import RemarksLabel from '../people/RemarksLabel'
import OverdueBookLendingsLabel from '../people/OverdueBookLendingsLabel'
import CouponHandoutButton from './CouponHandoutButton'

export default {
    props: {
        person: {
            type: Object,
            required: true
        },
        disabled: Boolean,
        highlightTerms: {
            type: Array,
            required: false,
            default: []
        },
        lang: {
            type: Object,
            required: true
        }
    },
    components: {
        HelperMarker,
        NameLabel,
        GenderSelector,
        DateOfBirthSelector,
        NationalitySelector,
        FrequentVisitorMarker,
        EditLink,
        CardNumberLabel,
        PoliceNoLabel,
        CaseNoLabel,
        RemarksLabel,
        OverdueBookLendingsLabel,
        CouponHandoutButton
    },
    computed: {
        headerStyle() {
            if (this.person.frequent_visitor) {
                return 'background: lightgoldenrodyellow;'
            }
            return null
        }
    }
}
</script>