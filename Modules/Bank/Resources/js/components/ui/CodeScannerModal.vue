<template>
    <b-modal
        ref="modal"
        :title="title"
        no-fade
        hide-footer
    >
        <qrcode-stream
            style="width: 100%; height: 100%;"
            :camera="camera"
            @init="onInit"
            @decode="onDecode"
        >
            <div class="loading-indicator" v-if="errorMessage">
                {{ errorMessage }}
            </div>

            <div class="loading-indicator" v-if="loading">
                <font-awesome-icon icon="cog" spin/>
            </div>

            <div v-if="validationSuccess" class="validation-success">
                <font-awesome-icon icon="check"/>
            </div>

            <div v-if="validationFailure" class="validation-failure">
                {{ validatorMessage }}
            </div>

            <div v-if="validationPending" class="validation-pending">
                <font-awesome-icon icon="cog" spin/>
            </div>
        </qrcode-stream>
    </b-modal>
</template>
<script>
import { BModal } from 'bootstrap-vue'
import { QrcodeStream } from 'vue-qrcode-reader'
export default {
    components: {
        BModal,
        QrcodeStream
    },
    props: {
        title: {
            type: String,
            required: false,
            default: 'QR Code Scanner'
        },
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
            loading: false,
            isValid: undefined,
            camera: 'auto',
            errorMessage: undefined
        }
    },
    computed: {
        validationPending() {
            return this.isValid === undefined && this.camera === 'off'
        },
        validationSuccess() {
            return this.isValid === true
        },
        validationFailure() {
            return this.isValid === false
        }
    },
    methods: {
        open() {
            this.$refs.modal.show()
            this.turnCameraOn()
        },
        onInit (promise) {
            this.loading = true
            this.errorMessage = undefined
            promise
                .catch(error => this.errorMessage = error.message)
                .then(this.resetValidationState)
                .then(() => this.loading = false)
        },
        resetValidationState () {
            this.isValid = undefined
        },
        async onDecode (content) {
            this.turnCameraOff()

            // Show waiting message
            await this.timeout(250)

            this.isValid = !this.validator || this.validator(content)

            if (this.isValid) {
                await this.timeout(1000)
                this.$emit('decode', content)
                this.resetValidationState()
                this.$refs.modal.hide()
                return
            }

            // some more delay, so users have time to read the message
            await this.timeout(2500)

            this.turnCameraOn()
        },
        turnCameraOn () {
            this.camera = 'auto'
        },
        turnCameraOff () {
            this.camera = 'off'
        },
        timeout (ms) {
            return new Promise(resolve => {
                window.setTimeout(resolve, ms)
            })
        }
    }
}
</script>

<style scoped>
.loading-indicator,
.validation-success,
.validation-failure,
.validation-pending {
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