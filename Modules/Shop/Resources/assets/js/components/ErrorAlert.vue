<template>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <icon name="exclamation-triangle"></icon> {{ message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    import Icon from './Icon'
    export default {
        props: {
            message: {
                type: String,
                required: true
            }
        },
        components: {
            Icon
        }
    }
</script>