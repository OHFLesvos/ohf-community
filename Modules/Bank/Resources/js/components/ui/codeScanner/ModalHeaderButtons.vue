<template>
    <span>
        <button
            type="button"
            class="close"
            aria-label="Close"
            @click="close"
        >
            <span aria-hidden="true">&times;</span>
        </button>
        <button
            v-if="isCameraMode"
            type="button"
            class="close"
            @click="setKeyboardMode"
        >
            <font-awesome-icon icon="keyboard"/>
        </button>
        <button
            v-if="isKeyboardMode"
            type="button"
            class="close"
            @click="setCameraMode"
        >
            <font-awesome-icon icon="qrcode"/>
        </button>
    </span>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            required: true,
            validator: value => {
                return ['camera', 'keyboard'].indexOf(value) !== -1
            }
        }
    },
    data() {
        return {
            mode: this.value
        }
    },
    computed: {
        isKeyboardMode() {
            return this.mode == 'keyboard'
        },
        isCameraMode() {
            return this.mode == 'camera'
        }
    },
    watch: {
        mode(val) {
            this.$emit('input', val)
        }
    },
    methods: {
        setKeyboardMode() {
            this.mode = 'keyboard'
        },
        setCameraMode() {
            this.mode = 'camera'
        },
        close() {
            this.$emit('close')
        }
    }
}
</script>