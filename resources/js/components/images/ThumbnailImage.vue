<template>
    <svg
        viewBox="0 0 1 1"
        :width="size"
        :height="size"
        style="max-width: 100%; height: auto;"
    >
        <image
            :xlink:href="url"
            width="100%"
            height="100%"
            preserveAspectRatio="xMidYMid slice"
        />
    </svg>
</template>

<script>
export default {
    props: {
        size: {
            required: true,
            type: Number,
            default: 200
        },
        url: {
            required: true,
            type: String
        }
    }
};
</script>
