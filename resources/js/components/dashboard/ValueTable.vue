<template>
    <b-table-simple class="mb-0">
        <tbody>
            <tr v-for="item in items" :key="item.key">
                <td>{{ item.key }}</td>
                <td v-if="typeof item.value == 'number'" class="text-right">
                    {{ item.value | numberFormat }}
                </td>
                <td v-else-if="alignAllItemsRight" class="text-right" v-html="item.value"></td>
                <td v-else v-html="item.value"></td>
            </tr>
        </tbody>
    </b-table-simple>
</template>

<script>
export default {
    props: {
        items: { required: true, type: Array},
        alignAllItemsRight: Boolean
    }
};
</script>
