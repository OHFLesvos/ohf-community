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
                        :person="person"
                        :allow-update="person.can_update"
                        :disabled="disabled"
                    />
                    <date-of-birth-selector
                        :person="person"
                        :allow-update="person.can_update"
                        :disabled="disabled"
                        @changed="$emit('change')"
                    />
                    <nationality-selector
                        :person="person"
                        :allow-update="person.can_update"
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
                        :person="person"
                        :allow-update="person.can_update"
                        :disabled="disabled"
                    />
                </div>
            </div>
        </div>

        <!-- Card body -->
        <div class="card-body p-2">
            <police-no-label
                :person="person"
                :highlight-terms="highlightTerms"
                :allow-update="person.can_update"
            />
            <remarks-label
                :person="person"
                :allow-update="person.can_update"
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
                        :person-id="person.id"
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
import GenderSelector from '@/components/bank/person/GenderSelector'
import DateOfBirthSelector from '@/components/bank/person/DateOfBirthSelector'
import NationalitySelector from '@/components/bank/person/NationalitySelector'
import FrequentVisitorMarker from '@/components/bank/FrequentVisitorMarker'
import EditLink from '@/components/people/EditLink'
import CardNumberLabel from '@/components/bank/person/CardNumberLabel'
import PoliceNoLabel from '@/components/bank/person/PoliceNoLabel'
import RemarksLabel from '@/components/bank/person/RemarksLabel'
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
