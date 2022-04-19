<template>
    <div class="img-overlay-wrap">
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
        <button
            type="button"
            @click="$emit('close')"
        >
            &times;
        </button>
    </div>
</template>

<script>
export default {
    props: {
        size: {
            required: false,
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

<style scoped>
    .img-overlay-wrap {
        position: relative;
        display: inline-block; /* <= shrinks container to image size */
    }

    .img-overlay-wrap svg { /* <= optional, for responsiveness */
        display: block;
        max-width: 100%;
        height: auto;
    }

    .img-overlay-wrap button {
        position: absolute;
        top: 0;
        right: 0;
        background: rgba(127, 127, 127, 0.5);
        border: 0;
        border-left: lightgray 1px solid;
        border-bottom: lightgray 1px solid;
        color: lightgray;
        padding: 0;
        width: 30px;
        height: 30px;
        line-height: 100%;
        text-align: center;
        vertical-align: middle;
        font-weight: bold;
        font-size: 20px;
    }

    .img-overlay-wrap button:hover {
        background: rgba(0, 0, 0, 0.5);;
        color: white;
        border-color: white;
    }
</style>
