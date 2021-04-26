<template>
    <b-card
        :no-body="!loading && items.length > 0"
        :header="loading || items.length == 0 ? header : null"
        class="mb-4"
    >
        <!-- Error -->
        <b-card-text v-if="error">
            <em v-if="error" class="text-danger">{{ error }}</em>
        </b-card-text>

        <!-- Loading -->
        <b-card-text v-else-if="loading">
            <em>{{ $t('Loading...') }}</em>
        </b-card-text>

        <!-- No items -->
        <b-card-text v-else-if="items.length == 0">
            <em>{{ $t('No data registered.') }}</em>
        </b-card-text>

        <template v-else>
            <!-- Interactive card header -->
            <b-card-header header-class="d-flex justify-content-between">
                <span>{{ header }}</span>
                <a
                    v-if="items.length > this.limit"
                    href="javascript:;"
                    @click="topTen = !topTen"
                >
                    {{ topTen ? $t('Show all :num', { num: items.length }) : $t('Show Top :num', { num: limit }) }}
                </a>
            </b-card-header>
            <b-list-group flush>

                <!-- Items -->
                <b-list-group-item
                    v-for="item in selectedItems"
                    :key="item.name"
                    class="d-flex justify-content-between align-items-center"
                >
                    <span>{{ item.name }}</span>
                    <span>
                        {{ item.amount }} &nbsp;
                        <small class="text-muted">{{ roundWithDecimals(item.amount / totalAmount * 100, 1) }}%</small>
                    </span>
                </b-list-group-item>

                <!-- Show other itmes -->
                <b-list-group-item
                    v-if="topTen && items.length > limit"
                    class="d-flex justify-content-between align-items-center"
                    href="javascript:;"
                    @click="topTen = !topTen"
                >
                    <em>{{ $t('Others') }}</em>
                    <span>
                        {{ unselectedItemsAmount }} &nbsp;
                        <small class="text-muted">{{ roundWithDecimals(unselectedItemsAmount / totalAmount * 100, 1) }}%</small>
                    </span>
                </b-list-group-item>

            </b-list-group>
        </template>
    </b-card>
</template>

<script>
import numberFormatMixin from '@/mixins/numberFormatMixin'
export default {
    mixins: [
        numberFormatMixin
    ],
    props: {
        header: {
            required: true,
            type: String
        },
        items: {
            required: true,
            type: Array
        },
        limit: {
            requireD: false,
            type: Number,
            default: 10
        },
        loading: Boolean,
        error: {
            required: false,
            type: String
        }
    },
    data () {
        return {
            topTen: true
        }
    },
    computed: {
        totalAmount () {
            return this.items.reduce((a, item) => a + item.amount, 0)
        },
        selectedItems () {
            if (this.topTen) {
                return this.items.slice(0, this.limit)
            }
            return this.items
        },
        unselectedItemsAmount () {
            if (this.topTen) {
                return this.items.slice(this.limit).reduce((a, item) => a + item.amount, 0)
            }
            return 0
        }
    }
}
</script>
