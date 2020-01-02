<template>
    <span>
        <template v-if="canUpdate">
            <icon name="id-card"></icon>
            <a href="javascript:;"
                @click="registerCard"
                >
                <strong v-if="value">{{ valueShort }}</strong>
                <template v-else>{{ lang['app.register'] }}</template>
            </a>
        </template>
        <template v-else-if="value">
            <icon name="id-card"></icon>
            <strong>{{ valueShort }}</strong>
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
                defaul: null
            },
            canUpdate: Boolean,
            lang: {
                type: Object,
                required: true
            }
        },
        computed: {
            valueShort() {
                return this.value != null ? this.value.substr(0,7) : null
            }
        },
        methods: {
            registerCard() {
                if (this.value && !confirm('Do you really want to replace the card ' + this.valueShort + ' with a new one?')) {
                    return;
                }
                scanQR((content) => {
                    // TODO input validation of code
                    axios.patch(this.apiUrl, {
                            "card_no": content,
                        })
                        .then(response => {
                            this.value= content
                            showSnackbar(response.data.message);
                            // TODO document.location = '/bank/withdrawal/cards/' + content;
                        })
                        .catch(handleAjaxError);
                });
            }
        }
    }
</script>