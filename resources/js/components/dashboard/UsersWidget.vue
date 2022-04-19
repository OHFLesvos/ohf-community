<template>
    <BaseWidget :title="$t('Users')" icon="user-friends" :to="{ name: 'users.index' }">
        <ValueTable :items="items"/>
            <b-list-group flush>
                <b-list-group-item :href="route('users.show', data.latest_user.id)">
                    {{ $t("The newest user is {name}, registered {registered}.", {
                        name: data.latest_user.name,
                        registered: timeFromNow(data.latest_user.created_at) })
                    }}
                </b-list-group-item>
            </b-list-group>
    </BaseWidget>
</template>
<script>
import BaseWidget from "@/components/dashboard/BaseWidget"
import ValueTable from "@/components/dashboard/ValueTable"
import { timeFromNow } from "@/utils/date";
export default {
    components: {
        BaseWidget,
        ValueTable
    },
    props: {
        data: { required: true, type: Object },
    },
    data() {
        return {
            items: [
                { key: this.$t('Users in the database'), value: this.data.num_users },
            ]
        }
    },
    methods: {
        timeFromNow
    }
};
</script>
