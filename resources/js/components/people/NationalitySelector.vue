<template>
    <span class="form-inline d-inline">
        <font-awesome-icon
            v-if="busy"
            icon="spinner"
            spin
        />
        <template v-else-if="nationality != null">
            {{ nationality }}
        </template>
        <template v-else-if="apiUrl != null">
            <template v-if="form">
                <b-form-input
                    ref="input"
                    v-model="newNationality"
                    size="sm"
                    :placeholder="$t('people.choose_nationality')"
                    list="country-list"
                    @keydown.enter="setNationality"
                    @keydown.esc="form = false"
                />
                <b-form-datalist id="country-list" :options="countries"/>
                <b-button
                    variant="primary"
                    size="sm"
                    @click="setNationality"
                >
                    <font-awesome-icon icon="check"/>
                </b-button>
                <b-button
                    variant="secondary"
                    size="sm"
                    @click="form = false"
                >
                    <font-awesome-icon icon="times"/>
                </b-button>
            </template>
            <b-button
                v-else
                variant="warning"
                size="sm"
                title="Set nationality"
                :disabled="disabled"
                @click="form = true"
            >
                <font-awesome-icon icon="globe"/>
            </b-button>
        </template>
    </span>
</template>

<script>
import showSnackbar from '@/snackbar'
import { handleAjaxError } from '@/utils'
import { BButton, BFormInput, BFormDatalist } from 'bootstrap-vue'
export default {
    components: {
        BButton,
        BFormInput,
        BFormDatalist
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
        countries: {
            type: Array,
            required: true,
        },
        disabled: Boolean
    },
    data() {
        return {
            busy: false,
            form: false,
            nationality: this.value != null && this.value.length > 0 ? this.value : null,
            newNationality: null
        }
    },
    watch: {
        form(val, oldVal) {
            if (val && !oldVal) {
                this.$nextTick(() => {
                    this.$refs.input.focus();
                })
            }
            if (!val && oldVal) {
                this.newNationality = null
            }
        }
    },
    methods: {
        setNationality() {
            if (this.newNationality == null || this.newNationality.length == 0) {
                this.$nextTick(() => {
                    this.$refs.input.select();
                })
                return
            }
            this.busy = true
            axios.patch(this.apiUrl, {
                    'nationality': this.newNationality
                })
                .then(response => {
                    var data = response.data
                    this.nationality = this.newNationality
                    this.form = false
                    showSnackbar(data.message);
                })
                .catch(err => {
                    handleAjaxError(err);
                    this.busy = false
                    this.$nextTick(() => {
                        this.$refs.input.select();
                    })
                })
                .then(() => {
                    this.busy = false
                })
        }
    }
}
</script>