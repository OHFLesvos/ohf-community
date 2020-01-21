<template>
    <div>
        <p>
            <b-button
                :variant="buttonVariant"
                block
                :disabled="busy"
                @click="isEnabled = !isEnabled"
            >
                <font-awesome-icon :icon="buttonIcon"/>
                {{ buttonLabel }}
            </b-button>
        </p>
        <div
            v-if="isEnabled"
            class="mb-2"
        >
            <qrcode-stream
                style="width: 100%; height: 100%"
                :camera="busy ? 'off' : camera"
                @init="onInit"
                @decode="onDecode"
            >
                <div
                    v-if="errorMessage"
                    class="error-message"
                >
                    {{ errorMessage }}
                </div>

                <div
                    v-if="loading"
                    class="loading-indicator"
                >
                    <font-awesome-icon icon="cog" spin />
                </div>

                <div
                    v-if="validationSuccess"
                    class="validation-success"
                >
                    <font-awesome-icon icon="check" />
                </div>

                <div
                    v-if="validationFailure"
                    class="validation-failure"
                >
                    {{ validatorMessage }}
                </div>

            </qrcode-stream>
        </div>
    </div>
</template>

<script>
import { BButton } from 'bootstrap-vue'
import { QrcodeStream } from 'vue-qrcode-reader'
export default {
    components: {
        QrcodeStream,
        BButton
    },
    props: {
        busy: Boolean,
        enabled: Boolean,
        validator: {
            type: Function,
            required: false,
        },
        validatorMessage: {
            type: String,
            required: false,
            default: 'Invalid input.'
        }
    },
    data() {
        return {
            errorMessage: undefined,
            loading: false,
            isEnabled: this.enabled,
            camera: 'auto',
            foo: null,
            isValid: undefined
        }
    },
    computed: {
        buttonVariant() {
            return this.isEnabled ? 'warning' : 'primary'
        },
        buttonIcon() {
            return this.isEnabled ? 'times' : 'qrcode'
        },
        buttonLabel() {
            return this.isEnabled ? 'Stop scanner' : 'Enable scanner'
        },
        validationSuccess() {
            return this.isValid === true
        },
        validationFailure() {
            return this.isValid === false
        },

    },
    watch: {
        isEnabled(val, oldVal) {
            if (val && !oldVal) {
                this.$emit('enable')
            }
            if (!val && oldVal) {
                this.$emit('disable')
            }
        }
    },
    methods: {
        onInit(promise) {
            this.loading = true
            this.errorMessage = undefined
            this.resetValidationState()
            promise
                .catch(error => this.errorMessage = error.message)
                .then(() => this.loading = false)
        },
        async onDecode(value) {
            this.turnCameraOff()
            await this.timeout(500)
            this.isValid = !this.validator || this.validator(value)
            if (this.isValid) {
                this.$emit('decode', value)
            }
            await this.timeout(this.isValid ? 1000 : 2500)
            this.turnCameraOn()
        },
        resetValidationState () {
            this.isValid = undefined
        },
        turnCameraOn() {
            this.camera = 'auto'
        },
        turnCameraOff() {
            this.camera = 'off'
        },
        timeout(ms) {
            return new Promise(resolve => window.setTimeout(resolve, ms))
        }
    }
}
</script>

<style scoped>
.error-message {
    color: red;
}
.loading-indicator {
    color: grey;
}
.loading-indicator,
.validation-success,
.validation-failure,
.error-message {
    position: absolute;
    width: 100%;
    height: 100%;

    background-color: rgba(255, 255, 255, .8);
    text-align: center;
    font-weight: bold;
    font-size: 1.4rem;
    padding: 10px;

    display: flex;
    flex-flow: column nowrap;
    justify-content: center;
}
.validation-success {
    color: green;
}
.validation-failure {
    color: red;
}
</style>