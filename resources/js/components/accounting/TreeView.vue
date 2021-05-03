<template>
    <b-list-group :class="root ? 'list-group-root well' : null">
        <template v-for="item in items">
            <b-list-group-item
                :key="item.id"
                :style="{ 'padding-left': 20 + level * 25 + 'px' }"
                :to="{
                    name: 'accounting.categories.edit',
                    params: { id: item.id }
                }"
                >{{ item.name }}
            </b-list-group-item>
            <tree-view
                :key="`${item.id}-children`"
                v-if="item.children.length > 0"
                :items="item.children"
                :root="false"
                :level="level + 1"
            />
        </template>
    </b-list-group>
</template>

<script>
export default {
    name: "tree-view",
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
