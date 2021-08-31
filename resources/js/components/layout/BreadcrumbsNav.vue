<template>
    <b-breadcrumb v-if="items.length > 0" :items="items"></b-breadcrumb>
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
