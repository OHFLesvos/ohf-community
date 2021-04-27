<template>
    <div class="alert" :class="`alert-${variant}`" role="alert">
        <font-awesome-icon :icon="icon"/>
        {{ message }}
    </div>
</template>

<script>
export default {
    props: {
        message: {
            required: true
        }
    }
}
</script>

