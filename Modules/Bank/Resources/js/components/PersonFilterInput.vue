<template>
    <div class="form-row">
        <div class="col">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    v-model="filter"
                    :placeholder="lang['people::people.bank_search_text']"
                    @keydown.enter="submit"
                    @keydown.esc="reset"
                    @focus="$refs.input.select()"
                    ref="input"
                    :disabled="busy"
                />
                <div class="input-group-append">
                    <span class="input-group-text" v-if="busy">
                        <icon name="spinner" :spin="true"></icon>
                    </span>
                    <template v-else>
                        <button class="btn btn-primary" type="button" :disabled="!filter" @click="submit">
                            <icon name="search"></icon>
                        </button>
                        <button class="btn btn-secondary" type="button" :disabled="!filter" @click="reset">
                            <icon name="eraser"></icon>
                        </button>
                    </template>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <button
                class="btn btn-primary"
                type="button"
                @click="scanCard"
                :disabled="busy"
            >
                <icon name="qrcode"></icon>
                <span class="d-none d-sm-inline"> {{ lang['people::people.scan_card'] }}</span>
            </button>
        </div>
    </div>
</template>

<script>
import scanQR from '@app/qr'
import { EventBus } from '@app/event-bus.js';
export default {
    props: {
        lang: {
            type: Object,
            required: true
        },
        busy: Boolean,
        value: {
            type: String,
            required: false,
            default: ''
        }
    },
    data() {
        return {
            filter: this.value
        }
    },
    methods: {
        submit() {
            this.$emit('input', this.filter)
        },
        reset() {
            this.filter = ''
            this.$emit('input', this.filter)
            this.$refs.input.focus()
        },
        scanCard() {
            scanQR((content) => {
                this.filter = ''
                this.$emit('scan', content)
            });
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.$refs.input.focus()
        })
        EventBus.$on('zero-results', () => {
            this.$refs.input.select()
        });
    }
}
</script>