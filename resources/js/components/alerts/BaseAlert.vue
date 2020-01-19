<template>
    <div class="alert" :class="`alert-${variant}`" role="alert">
        <font-awesome-icon :icon="icon"/>
        {{ message }}
    </div>
</template>

<script>
import FontAwesomeIcon from '@app/components/common/FontAwesomeIcon'
export default {
    components: {
        FontAwesomeIcon
    },
    props: {
        message: {
            required: true
        }
    }
}
</script>