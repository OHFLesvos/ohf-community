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
                        <button type="submit" class="btn btn-lg btn-block btn-success" @click="$emit('redeem')" :disabled="busy">
                            <icon name="check"></icon> {{ lang['shop::shop.redeem'] }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-block btn-outline-danger mt-3" @click="$emit('cancel')" :disabled="busy">
                            <icon name="undo"></icon> {{ lang['shop::shop.cancel_card'] }}
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Icon from './icon'
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
        }
    }
</script>