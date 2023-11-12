<template>
    <b-card :header="title" class="mb-4" no-body>
        <template v-if="myData">
            <b-card-body>
                <doughnut-chart
                    :key="JSON.stringify(myData)"
                    :title="title"
                    :data="myData"
                    :height="300"
                    class="mb-2"
                >
                </doughnut-chart>
            </b-card-body>
            <b-table-simple
                v-if="Object.keys(myData).length > 0"
                responsive
                small
                class="my-0"
            >
                <b-tr v-for="(value, label) in myData" :key="label">
                    <b-td class="fit">
                        {{ label && label.length ? label : $t('Unspecified') }}
                    </b-td>
                    <b-td class="align-middle d-none d-sm-table-cell">
                        <b-progress
                            :value="value"
                            :max="total"
                            :show-value="false"
                            variant="secondary"
                        />
                    </b-td>
                    <b-td class="fit text-right">
                        {{ percentValue(value, total) }}%
                    </b-td>
                    <b-td class="fit text-right d-none d-sm-table-cell">
                        {{ value | numberFormat }}
                    </b-td>
                </b-tr>
                <b-tfoot>
                    <b-tr>
                        <b-th colspan="4" class="text-right">
                            <b-button size="sm" @click="copyToClipboard" variant="outline-secondary">
                                <template v-if="copied"><font-awesome-icon icon="check"/> {{ $t('Copied') }}</template>
                                <template v-else>{{ $t('Copy to clipboard') }}</template>
                            </b-button>
                        </b-th>
                    </b-tr>
                </b-tfoot>
            </b-table-simple>
        </template>
        <b-card-body v-else>
            {{ $t("Loading...") }}
        </b-card-body>
    </b-card>
</template>

<script>
import DoughnutChart from "@/components/charts/DoughnutChart.vue";
import copy from 'copy-to-clipboard';

export default {
    components: {
        DoughnutChart
    },
    props: {
        title: {
            required: true,
            type: String
        },
        data: {
            type: [Function, Object],
            required: true
        }
    },
    data() {
        return {
            myData: null,
            copied: false,
        };
    },
    computed: {
        total() {
            return Object.values(this.myData).reduce((a, b) => a + b, 0);
        }
    },
    watch: {
        data() {
            this.fetchData()
        }
    },
    async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            let myData
            if (typeof this.data === "function") {
                myData = await this.data();
            } else {
                myData = this.data;
            }
            this.myData = Array.isArray(myData) && myData.length == 0 ? {} : myData
        },
        copyToClipboard() {
            const separator = '\t';
            const csvText = `${this.title}${separator}${this.$t("Percentage")}${separator}${this.$t("Amount")}\n`
                + Object.entries(this.myData).map(e => `${e[0] && e[0].length ? e[0] : this.$t('Unspecified') }${separator}${this.percentValue(e[1], this.total)}${separator}${e[1]}`).join("\n")

            copy(csvText, {
                format: 'text/plain',
                message: 'Press #{key} to copy',
            });
            this.copied = true
            setTimeout(() => this.copied = false, 3000)
        }
    }
};
</script>
