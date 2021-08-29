<template>
    <b-card :header="title" class="mb-4" no-body>
        <template v-if="myData">
            <b-card-body>
                <doughnut-chart
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
                        {{ label }}
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
            </b-table-simple>
        </template>
        <b-card-body v-else>
            {{ $t("Loading...") }}
        </b-card-body>
    </b-card>
</template>

<script>
import DoughnutChart from "@/components/charts/DoughnutChart";
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
            myData: null
        };
    },
    computed: {
        total() {
            return Object.values(this.myData).reduce((a, b) => a + b, 0);
        }
    },
    async created() {
        if (typeof this.data === "function") {
            this.myData = await this.data();
        } else {
            this.myData = this.data;
        }
    }
};
</script>
