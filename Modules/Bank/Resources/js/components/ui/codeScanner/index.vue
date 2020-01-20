<template>
    <b-modal
        :id="id"
        no-fade
        hide-footer
        @hide="stopCamera"
        @hidden="value = ''"
    >
        <template v-slot:modal-header="{ close }">
            <modal-header
                :title="lang['people::people.qr_code_scanner']"
                v-model="mode"
                @close="close()"
            />
        </template>
        <template v-if="isCameraMode">
            <canvas
                ref="canvas"
                hidden
                style="width: 100%; height: 100%; background: red"
            ></canvas>
            <span v-if="!videoLoaded">
                {{ lang['app.please_wait'] }}
            </span>
        </template>
        <keyboard-input
            v-if="isKeyboardMode"
            v-model="value"
            :validator="validator"
            :validator-message="validatorMessage"
            @submit="submit"
        />
    </b-modal>
</template>
<script>
import LocalVariable from '@app/LocalVariable'
const rememberMode = new LocalVariable('scanner-dialog.mode')

import { BModal } from 'bootstrap-vue'
import ModalHeader from './ModalHeader'
import KeyboardInput from './KeyboardInput'

import jsQR from 'jsqr'
import { readQRcodeFromImage } from '@app/utils/media'
import CanvasCamera from '@app/utils/canvasCamera'

export default {
    components: {
        BModal,
        ModalHeader,
        KeyboardInput
    },
    props: {
        validator: {
            type: Function,
            required: false
        },
        validatorMessage: {
            type: String,
            required: false,
            default: 'Invalid input.'
        },
        lang: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            value: '',
            isInvalidValue: false,
            mode: this.defaultMode(),
            videoLoaded: false,
            id: 'scanner-modal'
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
            if (val == 'keyboard') {
                this.enableKeyboard()
            } else if (val == 'camera') {
                this.enableCamera()
            }
            rememberMode.set(val)
        }
    },
    methods: {
        open() {
            this.$bvModal.show(this.id)
            if (this.mode == 'camera') {
                this.enableCamera()
            }
        },
        defaultMode() {
            return rememberMode.get('camera')
        },
        submit() {
            this.isInvalidValue = false
            if (this.validator && !this.validator(this.value)) {
                this.isInvalidValue = true
                return
            }
            this.$emit('submit', this.value)
            this.$bvModal.hide(this.id)
        },
        enableKeyboard() {
            this.stopCamera()
            this.value = ''
        },
        enableCamera() {
            this.value = ''
            this.$nextTick(() => {
                this.camera = new CanvasCamera(() => this.$refs.canvas)
                this.camera.setImageDataCallback(this.handleImageData)
                this.camera.setVideoLoadedCallback(() => this.videoLoaded = true)
                this.camera.setVideoStoppedCallback(() => this.videoLoaded = false)
                this.camera.startCamera()
            })
        },
        handleImageData(imageData) {
            const code = readQRcodeFromImage(imageData)
            if (code != null) {
                this.value = code
                this.submit()
                return
            }
        },
        stopCamera() {
            if (this.camera) {
                this.camera.stopCamera()
            }
        }
    }
}
</script>
