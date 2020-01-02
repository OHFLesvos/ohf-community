<template>
    <!-- TODO <div class="card @if(isset($bottom_margin))mb-{{ $bottom_margin }}@else mb-4 @endif bg-light @isset($border)border-{{ $border }}@endisset"> -->
    <div class="card mb-4 bg-light">

        <!-- Card header -->
        <div class="card-header p-2" :style="headerStyle">
            <div class="form-row">
                <div class="col">
                    <helper-marker
                        v-if="currentPerson.is_helper"
                        :lang="lang"
                        :url="currentPerson.show_helper_url"
                        :canView="currentPerson.can_view_helper"
                    ></helper-marker>
                    <person-name
                        :url="currentPerson.show_url"
                        :can-view="currentPerson.can_view"
                        :name="currentPerson.full_name"
                    ></person-name>
                    <gender-selector
                        :api-url="currentPerson.gender_update_url"
                        :value="currentPerson.gender"
                        :can-update="currentPerson.can_update"
                    ></gender-selector>
                    <date-of-birth-selector
                        :api-url="currentPerson.date_of_birth_update_url"
                        :value="currentPerson.date_of_birth"
                        :can-update="currentPerson.can_update"
                    ></date-of-birth-selector>
                    <nationality-selector
                        :api-url="currentPerson.nationality_update_url"
                        :value="currentPerson.nationality"
                        :can-update="currentPerson.can_update"
                    ></nationality-selector>
                    <frequent-visitor-marker v-if="currentPerson.frequent_visitor"></frequent-visitor-marker>
                    <a :href="currentPerson.edit_url" title="Edit" v-if="currentPerson.can_update">
                        <icon name="edit"></icon>
                    </a>
                </div>
                <div class="col-auto">
                    <register-card
                        :api-url="currentPerson.register_card_url"
                        :value="currentPerson.card_no"
                        :can-update="currentPerson.can_update"
                        :lang="lang"
                    ></register-card>
                </div>
            </div>
        </div>

        <!-- Card body -->
        <div class="card-body p-2" v-if="currentPerson.police_no || currentPerson.case_no_hash || currentPerson.remarks || currentPerson.has_overdue_book_lendings">
            <police-no-label
                v-if="currentPerson.police_no"
                :value="currentPerson.police_no_formatted"
                :lang="lang"
            ></police-no-label>
            <case-no-label
                v-if="currentPerson.case_no_hash"
                :value="currentPerson.case_no_hash"
                :lang="lang"
            ></case-no-label>
            <person-remarks
                v-if="currentPerson.remarks"
                :value="currentPerson.remarks"
            ></person-remarks>
            <overdue-book-lendings
                v-if="currentPerson.has_overdue_book_lendings"
                :canOperateLibrary="currentPerson.can_operate_library"
                :url="currentPerson.library_lending_person_url"
                :lang="lang"
            ></overdue-book-lendings>
        </div>

        <!-- Card footer -->
        <div class="card-body p-0 px-2 pt-2">
            <coupon-handout-buttons
                :couponTypes="currentPerson.coupon_types"
                :lang="lang"
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
    export default {
        props: {
            person: {
                type: Object,
                required: true
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
            CouponHandoutButtons
        },
        data() {
            return {
                currentPerson:  JSON.parse(JSON.stringify(this.person))
            }
        },
        computed: {
            headerStyle() {
                if (this.currentPerson.frequent_visitor) {
                    return 'background: lightgoldenrodyellow;'
                }
                return null
            }
        },
        methods: {
        }
    }
</script>