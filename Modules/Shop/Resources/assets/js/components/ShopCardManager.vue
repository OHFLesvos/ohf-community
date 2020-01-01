<template>
    <div>
        <!-- Error message -->
        <error-alert :message="error" v-if="error"></error-alert>

        <table class="table table-sm table-striped mb-4" v-if="items.length > 0">
            <thead>
                <tr>
                    <th>{{ lang['app.date'] }}</th>
                    <th>{{ lang['shop::shop.non_redeemed_cards'] }}</th>
                    <th>{{ lang['shop::shop.expired'] }}</th>
                    <th class="fit">{{ lang['app.actions'] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items" :key="item.date" :class="{'table-warning': item.expired}">
                    <td class="align-middle">{{ item.date }}</td>
                    <td class="align-middle">{{ item.total }}</td>
                    <td class="align-middle"><icon :name="item.expired ? 'check' : 'times'"></icon></td>
                    <td class="fit">
                        <button class="btn btn-danger btn-sm" @click="deleteCards(item.date)" :disabled="busy">
                            {{ lang['shop::shop.delete_cards'] }}
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <p v-else-if="!busy">
            <em>{{ lang['shop::shop.no_suitable_cards_found'] }}</em>
        </p>
        <p v-else>
            <icon name="spinner" :spin="true"></icon> {{ lang['app.loading'] }}
        </p>
    </div>
</template>

<script>
    import { getAjaxErrorMessage } from '../../../../../../resources/js/utils'
    import showSnackbar from '../../../../../../resources/js/snackbar'
    import ErrorAlert from './ErrorAlert'
    import Icon from './Icon'
    export default {
        props: {
            lang: {
                type: Object,
                required: true
            },
            summaryUrl: {
                type: String,
                required: true
            },
            deleteUrl: {
                type: String,
                required: true
            }
        },
        components: {
            ErrorAlert,
            Icon
        },
        data() {
            return {
                items: [],
                error: null,
                busy: false,
            }
        },
        methods: {
            loadSummary() {
                this.busy = true
                this.error = false
                axios.get(this.summaryUrl)
                    .then(res => {
                        this.items = res.data
                    })
                    .catch(err => {
                        this.error = getAjaxErrorMessage(err)
                    })
                    .then(() => {
                        this.busy = false
                    })
            },
            deleteCards(date) {
                if (window.confirm(`Really delete all non-redeemed cards from ${date}?`)) {
                    this.busy = true
                    axios.post(this.deleteUrl, {
                            date: date
                        })
                        .then(res => {
                            this.items = this.items.filter(i => i.date != date)
                            this.loadSummary()
                            showSnackbar(res.data.message)
                        })
                        .catch(err => {
                            this.error = getAjaxErrorMessage(err)
                            console.error(err)
                        })
                        .then(() => {
                            this.busy = false
                        });
                }
            }
        },
        mounted() {
            this.loadSummary()
        }
    }
</script>