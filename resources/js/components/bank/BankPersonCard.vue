<template>
    <div class="card mb-4 bg-light">

        <!-- Card header -->
        <div
            class="card-header p-2"
            :style="headerStyle"
        >
            <div class="form-row">
                <div class="col">
                    <community-volunteer-marker
                        v-if="person.is_community_volunteer"
                        :url="person.can_view_community_volunteer ? person.show_community_volunteer_url : null"
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
                        :countries="countries"
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
                    />
                </div>
            </div>
        </div>

        <!-- Card body -->
        <div class="card-body p-2">
            <police-no-label
                :value="person.police_no_formatted"
                :highlight-terms="highlightTerms"
                :api-url="person.can_update ? person.police_no_update_url : null"
            />
            <remarks-label
                :value="person.remarks"
                :api-url="person.can_update ? person.remarks_update_url : null"
            />
            <overdue-book-lendings-label
                v-if="person.has_overdue_book_lendings"
                :url="person.can_operate_library ? person.library_lending_person_url : null"
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
                        :personId="person.id"
                        :coupon="coupon"
                        :disabled="disabled"
                    />
                </div>
            </div>
            <div
                v-else
                class="pb-2 px-2"
            >
                <em>{{ $t('people.no_coupons_defined') }}</em>
            </div>
        </div>

    </div>
</template>

<script>
import CommunityVolunteerMarker from '@/components/people/CommunityVolunteerMarker'
import NameLabel from '@/components/people/NameLabel'
import GenderSelector from '@/components/people/GenderSelector'
import DateOfBirthSelector from '@/components/people/DateOfBirthSelector'
import NationalitySelector from '@/components/people/NationalitySelector'
import FrequentVisitorMarker from '@/components/bank/FrequentVisitorMarker'
import EditLink from '@/components/people/EditLink'
import CardNumberLabel from '@/components/people/CardNumberLabel'
import PoliceNoLabel from '@/components/people/PoliceNoLabel'
import RemarksLabel from '@/components/people/RemarksLabel'
import OverdueBookLendingsLabel from '@/components/people/OverdueBookLendingsLabel'
import CouponHandoutButton from '@/components/bank/CouponHandoutButton'

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
            default: () => []
        },
        countries: {
            type: Array,
            required: true,
        }
    },
    components: {
        CommunityVolunteerMarker,
        NameLabel,
        GenderSelector,
        DateOfBirthSelector,
        NationalitySelector,
        FrequentVisitorMarker,
        EditLink,
        CardNumberLabel,
        PoliceNoLabel,
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
