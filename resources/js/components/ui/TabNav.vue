<template>
    <b-nav tabs class="mb-3">
        <b-nav-item
            v-for="(item, idx) in availableItems"
            :key="idx"
            :to="item.to"
            :exact="exact"
            active-class="active"
            exact-active-class="active"
        >
            <font-awesome-icon :icon="item.icon" />
            <span class="d-none d-sm-inline">
                {{ item.text }}
            </span>
            <slot :name="item.key ? `after(${item.key})` : 'after'" :item="item"></slot>
        </b-nav-item>
    </b-nav>
</template>

<script>
export default {
    props: {
        items: {
            required: true,
            type: Array
        },
        exact: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        availableItems () {
            return this.items.filter(i => {
                if (i.show != undefined) {
                    if (typeof i.show === 'function') {
                        return i.show()
                    }
                    return i.show
                }
                return true
            })
        }
    }
}
</script>
