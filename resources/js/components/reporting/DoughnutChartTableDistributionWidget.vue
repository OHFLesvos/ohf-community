<template>
    <b-card :header="title" class="mb-4" no-body>
        <template v-if="myData">
            <b-card-body>
                <doughnut-chart
                    :key="JSON.stringify(myData)"
                    :title="title"
                    :data="chartData"
                    :height="300"
                    class="mb-2"
                >
                </doughnut-chart>
            </b-card-body>
            <b-table-simple
                v-if="myData.length"
                responsive
                small
                class="my-0"
            >
                <b-tr v-for="(e) in myData" :key="e.label">
                    <b-td class="fit">
                        {{ formatLabel(e.label) }}
                    </b-td>
                    <b-td class="align-middle d-none d-sm-table-cell">
                        <b-progress
                            :value="e.value"
                            :max="total"
                            :show-value="false"
                            variant="secondary"
                        />
                    </b-td>
                    <b-td class="fit text-right">
                        {{ percentValue(e.value, total) }}%
                    </b-td>
                    <b-td class="fit text-right d-none d-sm-table-cell">
                        {{ e.value | numberFormat }}
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
            type: [Function, Object, Array],
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
            return this.myData.reduce((a, b) => a + b.value, 0);
        },
        chartData() {
            let chartData = {}
            this.myData.forEach(v => chartData[this.formatLabel(v.label)] = v.value)
            return chartData;
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
            let resData
            if (typeof this.data === "function") {
                resData = await this.data();
            } else {
                resData = this.data;
            }
            if (Array.isArray(resData))  {
                this.myData = resData
            } else {
                this.myData = Object.entries(resData).map(e => ({label: e[0], value: e[1]}))
            }
        },
        formatLabel(v) {
            return v && v.length ? v : this.$t('Unspecified')
        },
        copyToClipboard() {
            const separator = '\t';
            const csvText = `${this.title}${separator}${this.$t("Percentage")}${separator}${this.$t("Amount")}\n`
                + this.myData.map(e => `${this.formatLabel(e.label)}${separator}${this.percentValue(e.value, this.total)}${separator}${e.value}`).join("\n")

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
