<template>
    <div>
        <h4 class="mb-3">
            {{ lang['shop.redeemed_cards'] }}
            <template v-if="handouts.length > 0">
                ({{ handouts.length }})
            </template>
        </h4>
        <p v-if="loading">
            <font-awesome-icon
                icon="spinner"
                spin
            />
            {{ lang['app.loading'] }}
        </p>
        <table
            v-else-if="handouts.length > 0"
            class="table table-sm table-striped mb-4"
        >
            <tbody>
                <tr
                    v-for="handout in handouts"
                    :key="handout.id"
                >
                    <td>
                        <a
                            :href="handout.person.url"
                            target="_blank"
                        >
                            <person-label :person="handout.person"></person-label>
                        </a>
                    </td>
                    <td>
                        {{ handout.code_short }}
                    </td>
                    <td>{{ handout.updated_diff_formatted }}</td>
                </tr>
            </tbody>
        </table>
        <p v-else>
            <em>{{ lang['shop.no_cards_redeemed_so_far_today'] }}</em>
        </p>
    </div>
</template>

<script>
import PersonLabel from '@/components/PersonLabel'
export default {
    components: {
        PersonLabel
    },
    props: {
        handouts: {
            type: Array,
            required: true
        },
        lang: {
            type: Object,
            required: true
        },
        loading: {
            type: Boolean,
            required: false,
            default: false
        }
    }
}
</script>