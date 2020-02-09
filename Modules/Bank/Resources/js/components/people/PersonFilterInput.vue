<template>
    <div class="form-row">
        <div class="col">
            <div class="input-group">
                <input
                    ref="input"
                    v-model="filter"
                    type="text"
                    class="form-control"
                    :placeholder="placeholder"
                    :disabled="busy || disabled"
                    @keydown.enter="submit"
                    @keydown.esc="reset"
                    @focus="$refs.input.select()"
                />
                <div class="input-group-append">
                    <span
                        v-if="busy"
                        class="input-group-text"
                    >
                        <font-awesome-icon
                            icon="spinner"
                            spin
                        />
                    </span>
                    <template v-else>
                        <button
                            class="btn btn-primary"
                            type="button"
                            :disabled="!filter || disabled"
                            @click="submit"
                        >
                            <font-awesome-icon icon="search"/>
                        </button>
                        <button
                            class="btn btn-secondary"
                            type="button"
                            :disabled="!filter || disabled"
                            @click="reset"
                        >
                            <font-awesome-icon icon="eraser"/>
                        </button>
                    </template>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <button
                class="btn btn-primary"
                type="button"
                :disabled="busy || disabled"
                @click="scanCard"
            >
                <font-awesome-icon icon="qrcode"/>
                <span class="d-none d-sm-inline">
                    {{ lang['people.scan_card'] }}
                </span>
            </button>
        </div>

        <code-scanner-modal
            ref="codeScanner"
            :title="lang['people.qr_code_scanner']"
            :validator="validateCode"
            :validator-message="lang['app.only_letters_and_numbers_allowed']"
            @decode="submitScannedCode"
        />
    </div>
</template>

<script>
import { EventBus } from '@app/event-bus';
import { isAlphaNumeric } from '@app/utils'
import CodeScannerModal from '../ui/CodeScannerModal'
export default {
    components: {
        CodeScannerModal
    },
    props: {
        value: {
            type: String,
            required: false,
            default: ''
        },
        placeholder: {
            type: String,
            required: false,
            default: 'Search...'
        },
        busy: Boolean,
        lang: {
            type: Object,
            required: true
        },
        disabled: Boolean
    },
    data() {
        return {
            filter: this.value
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.$refs.input.focus()
        })
        EventBus.$on('zero-results', () => {
            this.$refs.input.select()
        });
    },
    methods: {
        submit() {
            this.$emit('submit', this.filter)
        },
        reset() {
            this.filter = ''
            this.$emit('reset')
            this.$refs.input.focus()
        },
        scanCard() {
            this.$refs.codeScanner.open()
        },
        submitScannedCode(value) {
            this.filter = value
            this.submit()
        },
        validateCode(val) {
            return isAlphaNumeric(val)
        }
    }
}
</script>