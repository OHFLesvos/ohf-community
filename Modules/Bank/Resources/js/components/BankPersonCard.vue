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
            <span class="d-block d-sm-inline" v-if="currentPerson.police_no">
                <small class="text-muted">{{ lang['people::people.police_number'] }}:</small>
                <span class="pr-2 mark-text">{{ currentPerson.police_no_formatted }}</span>
            </span>
            <span class="d-block d-sm-inline" v-if="currentPerson.case_no_hash">
                <small class="text-muted">{{lang['people::people.case_number'] }}:</small>
                <span class="pr-2">{{ lang['app.yes'] }}</span>
            </span>
            <div v-if="currentPerson.remarks">
                <em class="text-info">{{ currentPerson.remarks }}</em>
            </div>
            <div v-if="currentPerson.has_overdue_book_lendings">
                <em class="text-danger">Needs to bring back book(s) to the
                    <a :href="currentPerson.library_lending_person_url" v-if="currentPerson.can_operate_library">
                        {{ lang['library::library.library'] }}
                    </a>
                    <template v-else>
                        {{ lang['library::library.library'] }}
                    </template>
                </em>
            </div>
        </div>

        <!-- Card footer -->
        <div class="card-body p-0 px-2 pt-2">
            <div class="form-row">
                <template v-if="currentPerson.coupon_types.length > 0">
                    <div class="col-sm-auto mb-2" v-for="coupon in currentPerson.coupon_types.filter(c => c.person_eligible_for_coupon)" :key="coupon.id">
                        <button
                            type="button"
                            class="btn btn-secondary btn-sm btn-block"
                            disabled
                            :data-url="coupon.handout_url"
                            v-if="coupon.last_handout"
                        >
                            {{ coupon.daily_amount }}
                            <icon :name="coupon.icon"></icon>
                            {{ coupon.name }}
                            ({{ coupon.last_handout }})
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm btn-block give-coupon"
                            :data-url="coupon.handout_url"
                            :data-amount="coupon.daily_amount"
                            :data-min_age="coupon.min_age"
                            :data-max_age="coupon.max_age"
                            :data-qr-code-enabled="coupon.qr_code_enabled"
                            v-else
                        >
                            {{ coupon.daily_amount }}
                            <icon :name="coupon.icon"></icon>
                            {{ coupon.name }}
                            <icon name="qrcode" v-if="coupon.qr_code_enabled"></icon>
                        </button>
                    </div>
                </template>
                <em class="pb-2 px-2" v-else>
                    {{ lang['people::people.no_coupons_defined'] }}
                </em>
            </div>
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
            PersonName
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