<template>
    <dl class="row">
        <dt class="col-sm-4">{{ $t("Name") }}</dt>
        <dd class="col-sm-8">{{ budget.name }}</dd>
        <dt class="col-sm-4">{{ $t("Agreed amount") }}</dt>
        <dd class="col-sm-8">
            {{ budget.agreed_amount_formatted }}
        </dd>
        <dt class="col-sm-4">{{ $t("Balance") }}</dt>
        <dd class="col-sm-8">
            {{ budget.balance_formatted }}
        </dd>
    </dl>
</template>

<script>
export default {
    props: {
        budget: {
            required: true
        }
    }
};
</script>
