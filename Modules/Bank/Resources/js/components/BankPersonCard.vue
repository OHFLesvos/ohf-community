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
                    <person-name
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
                    <person-edit-link
                        v-if="person.can_update"
                        :url="person.edit_url"
                    />
                </div>
                <div class="col-auto">
                    <register-card
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
            <person-remarks
                :value="person.remarks"
                :api-url="person.remarks_update_url"
                :can-update="person.can_update"
                :lang="lang"
            />
            <overdue-book-lendings
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
import GenderSelector from './GenderSelector'
import NationalitySelector from './NationalitySelector'
import DateOfBirthSelector from './DateOfBirthSelector'
import RegisterCard from './RegisterCard'
import FrequentVisitorMarker from './FrequentVisitorMarker'
import HelperMarker from './HelperMarker'
import PersonName from './PersonName'
import PoliceNoLabel from './PoliceNoLabel'
import CaseNoLabel from './CaseNoLabel'
import PersonRemarks from './PersonRemarks'
import OverdueBookLendings from './OverdueBookLendings'
import CouponHandoutButtons from './CouponHandoutButtons'
import PersonEditLink from './PersonEditLink'
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
        GenderSelector,
        NationalitySelector,
        DateOfBirthSelector,
        RegisterCard,
        FrequentVisitorMarker,
        HelperMarker,
        PersonName,
        PoliceNoLabel,
        CaseNoLabel,
        PersonRemarks,
        OverdueBookLendings,
        CouponHandoutButtons,
        PersonEditLink
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