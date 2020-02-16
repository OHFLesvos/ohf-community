<template>
    <span v-if="value == 'm'">
        <font-awesome-icon
            icon="male"
        />
        {{ $t('app.male') }}
    </span>
    <span v-else-if="value == 'f'">
        <font-awesome-icon
            icon="female"
        />
        {{ $t('app.female') }}
    </span>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            required: true
        }
    }
}
</script>