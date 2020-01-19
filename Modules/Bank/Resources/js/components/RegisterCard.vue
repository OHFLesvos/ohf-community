<template>
    <span>
        <template v-if="canUpdate && !disabled">
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
    </span>
</template>

<script>
import scanQR from '@app/qr'
import { showSnackbar, handleAjaxError } from '@app/utils'
export default {
    props: {
        apiUrl: {
            type: String,
            required: true
        },
        value: {
            type: String,
            required: false,
            default: null
        },
        canUpdate: Boolean,
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
            scanQR(content => {
                this.busy = true
                axios.patch(this.apiUrl, {
                        "card_no": content,
                    })
                    .then(response => {
                        this.cardNo = content
                        showSnackbar(response.data.message);
                    })
                    .catch(handleAjaxError)
                    .then(() => this.busy = false);
            });
        }
    }
}
</script>