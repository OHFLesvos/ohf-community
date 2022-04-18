<template>
    <table class="table mb-0">
        <tbody>
            <tr v-for="item in items" :key="item.key">
                <td>{{ item.key }}</td>
                <td v-if="typeof item.value == 'number'" class="text-right">
                    {{ item.value | numberFormat }}
                </td>
                <td v-else>{{ item.value }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        items: { required: true, type: Array},
    }
};
</script>
