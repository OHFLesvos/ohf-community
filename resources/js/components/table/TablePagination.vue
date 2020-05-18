<template>
    <b-row align-v="center" class="mb-3">
        <b-col>
            <b-pagination
                v-if="totalRows > 0"
                size="sm"
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
                class="mb-0"
            ></b-pagination>
        </b-col>
        <b-col sm="auto" class="text-right">
            <small>
                {{ $t('app.x_out_of_y', {
                    x: `${itemsStart} - ${itemsEnd}`,
                    y: totalRows
                }) }}
            </small>
        </b-col>
    </b-row>
</template>

<script>
export default {
    props: {
        value: Number,
        totalRows: Number,
        perPage: Number
    },
    computed: {
        currentPage: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        },
        itemsStart () {
            return ((this.currentPage - 1) * this.perPage) + 1
        },
        itemsEnd () {
            return Math.min(this.currentPage * this.perPage, this.totalRows)
        }
    }
}
</script>