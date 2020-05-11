<template>
    <i :class="classes"></i>
</template>

<script>
    export default {
        props: {
            icon: {
                type: String,
                required: true
            },
            variant: {
                type: String,
                required: false,
                default: 'fa'
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
            },
        },
        computed: {
            classes() {
                let classes = [
                    this.variant,
                    `fa-${this.icon}`
                ]
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
