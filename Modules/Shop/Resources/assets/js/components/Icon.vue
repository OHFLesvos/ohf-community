<template>
    <i class="fa" :class="classes"></i>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true
            },
            spin: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        computed: {
            classes() {
                let classes = [`fa-${this.name}`]
                if (this.spin) {
                    classes.push('fa-spin')
                }
                return classes
            }
        }
    }
</script>