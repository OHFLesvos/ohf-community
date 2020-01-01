<template>
    <div class="alert alert-danger" role="alert">
        <icon name="exclamation-triangle"></icon> {{ message }}
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