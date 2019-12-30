<template>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <span>
                {{ lang['shop::shop.card'] }}
                {{ handout.code_short }}
            </span>
            <span>
                <span class="d-none d-sm-inline">{{ lang['shop::shop.registered'] }}</span> {{ handout.date }}
            </span>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col mb-4 mb-sm-0">
                    <div class="row align-items-center">
                        <div class="col-auto text-center">
                            <span class="display-4">
                                <icon name="female" v-if="handout.person.gender == 'f'"></icon>
                                <icon name="male" v-if="handout.person.gender == 'm'"></icon>
                            </span>
                        </div>
                        <div class="col">
                            {{ handout.person.fullName }}
                            <a :href="handout.person.url" target="_blank">
                                <icon name="search"></icon>
                            </a>
                            <br>
                            <template v-if="handout.person.date_of_birth">
                                {{ handout.person.date_of_birth }} ({{ handout.person.age_formatted }})
                            </template>
                            <br>
                            <template v-if="handout.person.nationality != null">
                                {{ handout.person.nationality }}
                            </template>
                            <template v-if="handout.person.children.length > 0">
                                <span v-for="child in handout.person.children" :key="child.fullName">
                                    <br>
                                    <icon name="child"></icon> {{ child.fullName }} ({{ child.age_formatted }})
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="col-sm-auto text-center">
                    <strong v-if="handout.code_redeemed != null" class="text-warning">
                        <icon name="exclamation-triangle"></icon> {{ lang['shop::shop.card_already_redeemed'] }}<br>
                        <small>{{ handout.updated_diff_formatted }}</small>
                    </strong>
                    <strong v-else-if="handout.expired" class="text-warning">
                        <icon name="exclamation-triangle"></icon> {{ lang['shop::shop.card_expired'] }}<br>
                        <small>{{ handout.validity_formatted }}</small>
                    </strong>
                    <template v-else>
                        <button type="submit" class="btn btn-lg btn-block btn-success" @click="redeemCard" :disabled="busy">
                            <icon :name="redeemButtonIcon" :spin="this.redeeming"></icon> {{ lang['shop::shop.redeem'] }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-block btn-outline-danger mt-3" @click="cancelCard" :disabled="busy">
                            <icon :name="cancelButtonIcon" :spin="this.cancelling"></icon> {{ lang['shop::shop.cancel_card'] }}
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Icon from './Icon'
    export default {
        props: {
            handout: {
                type: Object,
                required: true
            },
            lang: {
                type: Object,
                required: true
            },
            busy: {
                type: Boolean,
                required: false,
                default: false,
            }
        },
        components: {
            Icon
        },
        data() {
            return {
                redeeming: false,
                cancelling: false,
            }
        },
        computed: {
            redeemButtonIcon() {
                if (this.redeeming) {
                    return 'spinner'
                }
                return 'check'
            },
            cancelButtonIcon() {
                if (this.cancelling) {
                    return 'spinner'
                }
                return 'undo'
            }
        },
        methods: {
            redeemCard() {
                this.redeeming = true
                this.$emit('redeem')
            },
            cancelCard() {
                this.cancelling = true
                this.$emit('cancel')
            }
        },
        watch: {
            busy(val, oldVal) {
                if (val == false && oldVal == true) {
                    this.redeeming = false
                    this.cancelling = false
                }
            }
        }
    }
</script>