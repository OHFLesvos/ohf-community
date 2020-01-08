<template>
    <div class="alert alert-info" role="alert">
        <icon name="info-circle"></icon> {{ message }}
    </div>
</template>

<script>
    import Icon from './Icon'
    export default {
        props: {
            message: {
                required: true
            }
        },
        components: {
            Icon
        }
    }
</script>