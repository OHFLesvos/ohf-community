<template>
    <b-card class="shadow-sm mb-4" header-class="d-flex justify-content-between border-bottom-0" no-body>
        <template #header>
            <span>
                <font-awesome-icon v-if="icon" :icon="icon" fixed-width />
                {{ title }}
            </span>
            <router-link v-if="to" :to="to"><font-awesome-icon icon="chevron-circle-right"/></router-link>
            <a v-else-if="href" :href="href"><font-awesome-icon icon="chevron-circle-right"/></a>
        </template>
        <slot></slot>
    </b-card>
</template>
<script>
export default {
    props: {
        title: { required: true, type: String },
        icon: { required: false, type: String },
        to: { required: false, type: Object },
        href: { required: false, type: String }
    }
};
</script>
