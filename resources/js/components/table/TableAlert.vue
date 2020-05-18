<template>
    <b-alert variant="danger" :show="value != null">
        <b-row align-v="center">
            <b-col>
                <font-awesome-icon icon="times-circle" />
                {{ $t('app.error_err', { err: value }) }}
            </b-col>
            <b-col sm="auto">
                <b-button
                    variant="danger"
                    size="sm"
                    @click="$emit('retry')"
                    class="float-right"
                >
                    <font-awesome-icon icon="redo" />
                    {{ $t('app.retry') }}
                </b-button>
            </b-col>
        </b-row>
    </b-alert>
</template>

<script>
export default {
    props: ['value']
}
</script>