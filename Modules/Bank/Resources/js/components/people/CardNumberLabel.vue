<template>
    <span>
        <template v-if="apiUrl != null && !disabled">
            <font-awesome-icon icon="id-card"/>
            <font-awesome-icon
                v-if="busy"
                icon="spinner"
                spin
            />
            <a
                v-else
                href="javascript:;"
                @click="registerCard"
            >
                <strong v-if="cardNo">{{ cardNoShort }}</strong>
                <template v-else>{{ lang['app.register'] }}</template>
            </a>
        </template>
        <template v-else-if="cardNo">
            <font-awesome-icon icon="id-card"/>
            <strong>{{ cardNoShort }}</strong>
        </template>

        <code-scanner-modal
            ref="codeScanner"
            :title="lang['people::people.qr_code_scanner']"
            :validator="validateCode"
            :validator-message="lang['app.only_letters_and_numbers_allowed']"
            @decode="submitScannedCard"
        />
    </span>
</template>

<script>
import { showSnackbar, handleAjaxError } from '@app/utils'
import { isAlphaNumeric } from '@app/utils'
import CodeScannerModal from '../ui/CodeScannerModal'
export default {
    components: {
        CodeScannerModal
    },
    props: {
        apiUrl: {
            type: String,
            required: false,
            default: null
        },
        value: {
            type: String,
            required: false,
            default: null
        },
        disabled: Boolean,
        lang: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            busy: false,
            cardNo: this.value
        }
    },
    computed: {
        cardNoShort() {
            return this.cardNo != null ? this.cardNo.substr(0,7) : null
        }
    },
    methods: {
        registerCard() {
            if (this.cardNo && !confirm('Do you really want to replace the card ' + this.cardNoShort + ' with a new one?')) {
                return;
            }
            this.$refs.codeScanner.open()
        },
        validateCode(val) {
            return isAlphaNumeric(val)
        },
        submitScannedCard(value) {
            this.busy = true
            axios.patch(this.apiUrl, {
                    "card_no": value,
                })
                .then(response => {
                    this.cardNo = value
                    showSnackbar(response.data.message);
                })
                .catch(handleAjaxError)
                .then(() => this.busy = false)
        }
    }
}
</script>