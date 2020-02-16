<template>
    <div>
        <p class="mb-2">{{ $t('people.code_card') }}</p>
        <b-button
            :variant="card_no ? 'secondary' : 'primary'"
            @click="$refs.codeScanner.open()"
        >
            <font-awesome-icon icon="qrcode"/>
        </b-button>
        <template v-if="card_no != null && card_no.length > 0">
            {{ card_no }}
            <b-button
                variant="warning"
                @click="card_no = ''"
            >
                <font-awesome-icon icon="trash"/>
            </b-button>
        </template>
        <span
            v-else
            class="text-muted"
        >
            {{ $t('app.no_cards_registered') }}
        </span>

        <code-scanner-modal
            ref="codeScanner"
            :title="$t('people.qr_code_scanner')"
            :validator="validateCode"
            :validator-message="$t('app.only_letters_and_numbers_allowed')"
            @decode="assignCard"
        />
    </div>
</template>

<script>
import { BButton } from 'bootstrap-vue'
import CodeScannerModal from '@/components/ui/CodeScannerModal'
import { isAlphaNumeric } from '@/utils'
export default {
    components: {
        BButton,
        CodeScannerModal
    },
    props: {
        value: {
            type: String,
        }
    },
    data() {
        return {
            card_no: this.value
        }
    },
    watch: {
        card_no(v) {
            this.$emit('input', this.card_no)
        }
    },
    methods: {
        validateCode(val) {
            return isAlphaNumeric(val)
        },
        assignCard(val) {
            this.card_no = val
        }
    }
}
</script>