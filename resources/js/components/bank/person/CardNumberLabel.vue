<template>
    <span>
        <template v-if="allowUpdate && !disabled">
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
                <template v-else>{{ $t('app.register') }}</template>
            </a>
        </template>
        <template v-else-if="cardNo">
            <font-awesome-icon icon="id-card"/>
            <strong>{{ cardNoShort }}</strong>
        </template>

        <code-scanner-modal
            ref="codeScanner"
            :title="$t('people.qr_code_scanner')"
            :validator="validateCode"
            :validator-message="$t('app.only_letters_and_numbers_allowed')"
            @decode="submitScannedCard"
        />
    </span>
</template>

<script>
import { showSnackbar } from '@/utils'
import { isAlphaNumeric } from '@/utils'
import CodeScannerModal from '@/components/ui/CodeScannerModal'
import peopleApi from '@/api/people'
export default {
    components: {
        CodeScannerModal
    },
    props: {
        person: {
            required: true
        },
        allowUpdate: Boolean,
        disabled: Boolean
    },
    data () {
        return {
            busy: false,
            cardNo: this.person.card_no
        }
    },
    computed: {
        cardNoShort () {
            return this.cardNo != null ? this.cardNo.substr(0,7) : null
        }
    },
    methods: {
        registerCard () {
            if (this.cardNo && !confirm(this.$t('people.really_replace_card_with_new_one', { cardNo: this.cardNoShort }))) {
                return
            }
            this.$refs.codeScanner.open()
        },
        validateCode (val) {
            return isAlphaNumeric(val)
        },
        async submitScannedCard (value) {
            this.busy = true
            try {
                let data = await peopleApi.updateCardNo(this.person.id, value)
                this.cardNo = value
                showSnackbar(data.message)
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        }
    }
}
</script>
