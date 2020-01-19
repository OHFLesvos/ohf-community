<template>
    <i class="fa" :class="classes"></i>
</template>

<script>
    export default {
        props: {
            icon: {
                type: String,
                required: true
            },
            spin: {
                type: Boolean,
                required: false,
                default: false
            },
            pulse: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        computed: {
            classes() {
                let classes = [`fa-${this.icon}`]
                if (this.spin) {
                    classes.push('fa-spin')
                }
                if (this.pulse) {
                    classes.push('fa-pulse')
                }
                return classes
            }
        }
    }
</script>