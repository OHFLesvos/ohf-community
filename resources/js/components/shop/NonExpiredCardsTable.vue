<template>
    <div>
        <!-- Heading -->
        <h4 class="mb-3">
            {{ lang['shop.non_redeemed_cards'] }}
        </h4>

        <!-- Error message -->
        <error-alert
            v-if="error"
            :message="error"
        />

        <!-- Table of items -->
        <table
            v-if="items.length > 0"
            class="table table-sm table-striped mb-4"
        >
            <thead>
                <tr>
                    <th>{{ lang['app.date'] }}</th>
                    <th>{{ lang['shop.cards'] }}</th>
                    <th>{{ lang['shop.expired'] }}</th>
                    <th class="fit">{{ lang['app.actions'] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="item in items"
                    :key="item.date"
                    :class="{'table-warning': item.expired}"
                >
                    <td class="align-middle">{{ item.date }}</td>
                    <td class="align-middle">{{ item.total }}</td>
                    <td class="align-middle">
                        <font-awesome-icon :icon="item.expired ? 'check' : 'times'"/>
                    </td>
                    <td class="fit">
                        <button
                            class="btn btn-danger btn-sm"
                            :disabled="busy"
                            @click="deleteCards(item.date)"
                        >
                            {{ lang['shop.delete_cards'] }}
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Empty results message -->
        <p v-else-if="!busy">
            <em>{{ lang['shop.no_suitable_cards_found'] }}</em>
        </p>

        <!-- Loading indicator -->
        <p v-else>
            <font-awesome-icon
                icon="spinner"
                spin
            />
            {{ lang['app.loading'] }}
        </p>
    </div>
</template>

<script>
    import { getAjaxErrorMessage } from '@/utils'
    import showSnackbar from '@/snackbar'
    import ErrorAlert from '@/components/alerts/ErrorAlert'
    export default {
        components: {
            ErrorAlert
        },
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
        data() {
            return {
                items: [],
                error: null,
                busy: false,
            }
        },
        mounted() {
            this.loadSummary()
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
        }
    }
</script>