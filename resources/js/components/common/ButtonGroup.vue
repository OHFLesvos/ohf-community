<template>
    <p v-if="items.filter(i => i.show).length">
        <template v-for="item in items">
            <b-button :key="item.text" v-if="item.show" :to="item.to" :variant="item.variant">
                <font-awesome-icon v-if="item.icon" :icon="item.icon"/>
                {{ item.text }}
            </b-button>
        </template>
    </p>
</template>

<script>
export default {
    props: {
        items: {
            required: true,
            type: Array
        }
    }
}
</script>
