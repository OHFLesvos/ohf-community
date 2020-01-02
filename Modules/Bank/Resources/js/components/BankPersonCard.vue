<template>
    <!-- TODO <div class="card @if(isset($bottom_margin))mb-{{ $bottom_margin }}@else mb-4 @endif bg-light @isset($border)border-{{ $border }}@endisset"> -->
    <div class="card mb-4 bg-light">

        <!-- Card header -->
        <div class="card-header p-2" :style="headerStyle">
            <div class="form-row">
                <div class="col">
                    <template v-if="person.is_helper">
                        <strong v-if="person.can_view_helper">
                            <a :href="person.show_helper_url" class="text-warning">{{ lang['helpers::helpers.helper'].toUpperCase() }}</a>
                        </strong>
                        <strong class="text-warning" v-else>
                            {{ lang['helpers::helpers.helper'].toUpperCase() }}
                        </strong>
                    </template>
                    <a :href="person.show_url" alt="View" v-if="person.can_view"><strong class="mark-text">{{ person.full_name }}</strong></a>
                    <strong class="mark-text" v-else>{{ person.full_name }}</strong>
                    <gender-selector
                        :update-url="person.gender_update_url"
                        :value="person.gender"
                        :can-update="person.can_update"
                    ></gender-selector>
                    <date-of-birth-selector
                        :update-url="person.date_of_birth_update_url"
                        :value="person.date_of_birth"
                        :can-update="person.can_update"
                    ></date-of-birth-selector>
                    <nationality-selector
                        :update-url="person.nationality_update_url"
                        :value="person.nationality"
                        :can-update="person.can_update"
                    ></nationality-selector>
                    <span class="text-warning" title="Frequent visitor" v-if="person.frequent_visitor">
                        <icon name="star"></icon>
                    </span>
                    <a :href="person.edit_url" title="Edit" v-if="person.can_update">
                        <icon name="edit"></icon>
                    </a>
                </div>
                <div class="col-auto">
                    <template v-if="person.can_update">
                        <icon name="id-card"></icon>
                        <a href="javascript:;" class="register-card" :data-url="person.register_card_url" :data-card="person.card_no">
                            <strong v-if="person.card_no">{{ person.card_no_short }}</strong>
                            <template v-else>{{ lang['app.register'] }}</template>
                        </a>
                    </template>
                    <template v-else-if="person.card_no">
                        <icon name="id-card"></icon>
                        <strong>{{ person.card_no_short}}</strong>
                    </template>
                </div>
            </div>
        </div>

        <!-- Card body -->
        <div class="card-body p-2" v-if="person.police_no || person.case_no_hash || person.remarks || person.has_overdue_book_lendings">
            <span class="d-block d-sm-inline" v-if="person.police_no">
                <small class="text-muted">{{ lang['people::people.police_number'] }}:</small>
                <span class="pr-2 mark-text">{{ person.police_no_formatted }}</span>
            </span>
            <span class="d-block d-sm-inline" v-if="person.case_no_hash">
                <small class="text-muted">{{lang['people::people.case_number'] }}:</small>
                <span class="pr-2">{{ lang['app.yes'] }}</span>
            </span>
            <div v-if="person.remarks">
                <em class="text-info">{{ person.remarks }}</em>
            </div>
            <div v-if="person.has_overdue_book_lendings">
                <em class="text-danger">Needs to bring back book(s) to the
                    <a :href="person.library_lending_person_url" v-if="person.can_operate_library">
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
                <template v-if="person.coupon_types.length > 0">
                    <div class="col-sm-auto mb-2" v-for="coupon in person.coupon_types.filter(c => c.person_eligible_for_coupon)" :key="coupon.id">
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
    import Icon from '@app/components/Icon'
    import GenderSelector from './GenderSelector'
    import NationalitySelector from './NationalitySelector'
    import DateOfBirthSelector from './DateOfBirthSelector'
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
            Icon,
            GenderSelector,
            NationalitySelector,
            DateOfBirthSelector
        },
        data() {
            return {

            }
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