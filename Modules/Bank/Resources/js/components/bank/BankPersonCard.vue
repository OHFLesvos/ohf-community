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
                        :url="person.show_helper_url"
                        :canView="person.can_view_helper"
                        :lang="lang"
                    />
                    <name-label
                        :url="person.show_url"
                        :can-view="person.can_view"
                        :value="person.full_name"
                        :highlight-terms="highlightTerms"
                    />
                    <gender-selector
                        :api-url="person.gender_update_url"
                        :value="person.gender"
                        :can-update="person.can_update"
                        :disabled="disabled"
                    />
                    <date-of-birth-selector
                        :api-url="person.date_of_birth_update_url"
                        :value="person.date_of_birth"
                        :can-update="person.can_update"
                        :disabled="disabled"
                        @setAge="$emit('change')"
                    />
                    <nationality-selector
                        :api-url="person.nationality_update_url"
                        :value="person.nationality"
                        :can-update="person.can_update"
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
                        :api-url="person.register_card_url"
                        :value="person.card_no"
                        :can-update="person.can_update"
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
                :api-url="person.remarks_update_url"
                :can-update="person.can_update"
                :lang="lang"
            />
            <overdue-book-lendings-label
                v-if="person.has_overdue_book_lendings"
                :canOperateLibrary="person.can_operate_library"
                :url="person.library_lending_person_url"
                :lang="lang"
            />
        </div>

        <!-- Card footer -->
        <div class="card-body p-0 px-2 pt-2">
            <coupon-handout-buttons
                :couponTypes="person.coupon_types"
                :disabled="disabled"
                :lang="lang"
            />
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
import CouponHandoutButtons from './CouponHandoutButtons'

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
        CouponHandoutButtons
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