<template>
    <b-list-group :class="root ? 'list-group-root' : null">
        <template v-for="item in items">
            <b-list-group-item
                :button="item.can_update"
                :key="item.id"
                :style="{ 'padding-left': 20 + level * itemPaddingLeft + 'px' }"
                @click="handleClick(item)"
            >
                <del v-if="!item.enabled">{{ item.name }}</del>
                <template v-else>{{ item.name }}</template>
                <small v-if="item.description" class="text-muted d-block">
                    {{ item.description }}</small
                >
            </b-list-group-item>
            <nested-list-group
                :key="`${item.id}-children`"
                v-if="item.children.length > 0"
                :items="item.children"
                :root="false"
                :level="level + 1"
                @itemClick="$emit('itemClick', $event)"
            />
        </template>
    </b-list-group>
</template>

<script>
export default {
    name: "nested-list-group",
    props: {
        items: {
            required: true
        },
        root: {
            type: Boolean,
            default: true
        },
        level: {
            type: Number,
            default: 0
        },
        itemPaddingLeft: {
            type: Number,
            default: 25
        }
    },
    methods: {
        handleClick(item) {
            if (item.can_update) {
                this.$emit("itemClick", item.id);
            }
        }
    }
};
</script>

<style scoped>
.list-group.list-group-root {
    padding: 0;
    overflow: hidden;
}

.list-group.list-group-root .list-group {
    margin-bottom: 0;
}

.list-group.list-group-root .list-group-item {
    border-radius: 0;
    border-width: 1px 0 0 0;
}

.list-group.list-group-root > .list-group-item:first-child {
    border-top-width: 0;
}
</style>
