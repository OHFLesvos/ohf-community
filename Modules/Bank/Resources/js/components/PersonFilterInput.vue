<template>
    <div class="form-row">
        <div class="col">
            <div class="input-group">
                <input
                    ref="input"
                    v-model="filter"
                    type="text"
                    class="form-control"
                    :placeholder="lang['people::people.bank_search_text']"
                    :disabled="busy"
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
                            :disabled="!filter"
                            @click="submit"
                        >
                            <font-awesome-icon icon="search"/>
                        </button>
                        <button
                            class="btn btn-secondary"
                            type="button"
                            :disabled="!filter"
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
                :disabled="busy"
                @click="scanCard"
            >
                <font-awesome-icon icon="qrcode"/>
                <span class="d-none d-sm-inline">
                    {{ lang['people::people.scan_card'] }}
                </span>
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
    }
}
</script>