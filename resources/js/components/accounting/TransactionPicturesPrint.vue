<template>
    <div class="d-none d-print-block">
        <template
                v-for="picture in pictures"
        >
            <div style="page-break-before: always;"></div>
            <img
                :key="picture.url"
                :src="`${picture.url}?${picture.hash}`"
                :title="picture.mime_type"
                style="width: 100%; height: 1000px; object-fit: contain;"
            />
        </template>
    </div>
</template>

<script>
export default {
    props: {
        transaction: {
            required: true
        }
    },
    data() {
        return {
            pictures: this.transaction.receipt_pictures,
        };
    }
};
</script>
