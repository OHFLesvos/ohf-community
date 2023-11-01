<template>
    <p v-if="items.filter(i => typeof i.show === 'undefined' || i.show).length">
        <template v-for="item in items">
            <b-button :key="item.text" v-if="typeof item.show === 'undefined' || item.show" :to="item.to" :href="item.href" :variant="item.variant" class="mr-2">
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
