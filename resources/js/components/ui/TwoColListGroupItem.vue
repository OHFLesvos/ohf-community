<template>
    <b-list-group-item>
        <b-row>
            <b-col sm="5" md="4" lg="3" xl="2">
                <strong>{{ title }}</strong>
            </b-col>
            <b-col sm>
                <slot></slot>
            </b-col>
        </b-row>
    </b-list-group-item>
</template>

<script>
export default {
    components: {
        BListGroupItem,
        BRow,
        BCol
    },
    props: {
        title: {
            type: String,
            required: true
        }
    }
}
</script>
