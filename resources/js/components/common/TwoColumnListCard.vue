<template>
    <b-card
        no-body
        class="mb-4"
    >
        <b-card-header header-class="d-flex justify-content-between">
            <span>{{ header }}</span>
            <a
                v-if="items.length > this.limit"
                href="javascript:;"
                @click="topTen = !topTen"
            >
                {{ topTen ? $t('app.show_all') : $t('app.shop_top_x', { num: limit }) }}
            </a>
        </b-card-header>
        <b-list-group flush>
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
            <b-list-group-item
                v-if="topTen && items.length > limit"
                class="d-flex justify-content-between align-items-center"
            >
                <em>{{ $t('app.others') }}</em>
                <span>
                    {{ unselectedItemsAmount }} &nbsp;
                    <small class="text-muted">{{ roundWithDecimals(unselectedItemsAmount / totalAmount * 100, 1) }}%</small>
                </span>
            </b-list-group-item>
        </b-list-group>
    </b-card>

</template>

<script>
import { roundWithDecimals } from '@/utils'
export default {
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
    },
    methods: {
        roundWithDecimals
    }
}
</script>
