<template>
    <b-breadcrumb v-if="items.length > 0" class="bg-white shadow-sm">
        <b-breadcrumb-item :to="{name: 'dashboard'}">
            <font-awesome-icon icon="home" :title="$t('Dashboard')"/>
        </b-breadcrumb-item>
        <template v-for="(item,index) in items" >
            <b-breadcrumb-item v-if="typeof item.show === 'undefined' || item.show" :key="item.text" :to="item.to" :active="index == items.length - 1">
                <font-awesome-icon v-if="item.icon" :icon="item.icon"/>
                {{ item.text }}
            </b-breadcrumb-item>
        </template>
    </b-breadcrumb>
</template>

<script>
export default {
    props: {
        items: {
            required: false,
            type: Array,
            default: function() {
                return [];
            }
        }
    }
};
</script>
