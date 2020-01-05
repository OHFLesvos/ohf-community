<template>
    <!-- TODO <div class="card @if(isset($bottom_margin))mb-{{ $bottom_margin }}@else mb-4 @endif bg-light @isset($border)border-{{ $border }}@endisset"> -->
    <div class="card mb-4 bg-light">

        <!-- Card header -->
        <div class="card-header p-2" :style="headerStyle">
            <div class="form-row">
                <div class="col">
                    <helper-marker
                        v-if="person.is_helper"
                        :lang="lang"
                        :url="person.show_helper_url"
                        :canView="person.can_view_helper"
                    ></helper-marker>
                    <person-name
                        :url="person.show_url"
                        :can-view="person.can_view"
                        :name="person.full_name"
                    ></person-name>
                    <gender-selector
                        :api-url="person.gender_update_url"
                        :value="person.gender"
                        :can-update="person.can_update"
                        :disabled="disabled"
                    ></gender-selector>
                    <date-of-birth-selector
                        :api-url="person.date_of_birth_update_url"
                        :value="person.date_of_birth"
                        :can-update="person.can_update"
                        @setAge="$emit('change')"
                        :disabled="disabled"
                    ></date-of-birth-selector>
                    <nationality-selector
                        :api-url="person.nationality_update_url"
                        :value="person.nationality"
                        :can-update="person.can_update"
                        :disabled="disabled"
                    ></nationality-selector>
                    <frequent-visitor-marker
                        v-if="person.frequent_visitor"
                    ></frequent-visitor-marker>
                    <person-edit-link
                        v-if="person.can_update"
                        :url="person.edit_url"
                    ></person-edit-link>
                </div>
                <div class="col-auto">
                    <register-card
                        :api-url="person.register_card_url"
                        :value="person.card_no"
                        :can-update="person.can_update"
                        :lang="lang"
                        :disabled="disabled"
                    ></register-card>
                </div>
            </div>
        </div>

        <!-- Card body -->
        <div class="card-body p-2" v-if="person.police_no || person.case_no_hash || person.remarks || person.has_overdue_book_lendings">
            <police-no-label
                v-if="person.police_no"
                :value="person.police_no_formatted"
                :lang="lang"
            ></police-no-label>
            <case-no-label
                v-if="person.case_no_hash"
                :value="person.case_no_hash"
                :lang="lang"
            ></case-no-label>
            <person-remarks
                :value="person.remarks"
                :api-url="person.remarks_update_url"
                :can-update="person.can_update"
                :lang="lang"
            ></person-remarks>
            <overdue-book-lendings
                v-if="person.has_overdue_book_lendings"
                :canOperateLibrary="person.can_operate_library"
                :url="person.library_lending_person_url"
                :lang="lang"
            ></overdue-book-lendings>
        </div>

        <!-- Card footer -->
        <div class="card-body p-0 px-2 pt-2">
            <coupon-handout-buttons
                :couponTypes="eligibleCouponTypes"
                :lang="lang"
                :disabled="disabled"
            ></coupon-handout-buttons>
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
            lang: {
                type: Object,
                required: true
            },
            disabled: Boolean
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
            },
            eligibleCouponTypes() {
                return this.person.coupon_types.filter(c => c.person_eligible_for_coupon)
            }
        }
    }
</script>