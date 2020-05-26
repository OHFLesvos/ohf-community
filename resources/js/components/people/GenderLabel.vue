<template>
    <span v-if="value == 'm'">
        <font-awesome-icon
            icon="male"
        />
        <span v-if="!iconOnly">{{ $t('app.male') }}</span>
    </span>
    <span v-else-if="value == 'f'">
        <font-awesome-icon
            icon="female"
        />
        <span v-if="!iconOnly">{{ $t('app.female') }}</span>
    </span>
    <span v-else>
        <font-awesome-icon
            icon="genderless"
        />
        <span v-if="!iconOnly">{{ $t('app.unknown') }}</span>
    </span>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            required: false
        },
        iconOnly: Boolean
    }
}
</script>
