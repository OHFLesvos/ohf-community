<template>
    <header class="d-flex justify-content-between mb-2">
        <h1 class="display-4">
            {{ title }}
            <small v-if="subtitle">{{ subtitle }}</small>
        </h1>
        <span class="text-right pt-2">
            <span
                v-for="(button, idx) in availableButtons"
                :key="idx"
            >
                <b-button
                    :key="button.text"
                    :to="button.to"
                    :href="button.href"
                    :variant="button.variant ? button.variant : 'secondary'"
                    class="mb-1"
                    @click="handleClick(button)"
                >
                    <font-awesome-icon :icon="button.icon" />
                    <span class="d-none d-md-inline">{{ button.text }}</span>
                </b-button>
                {{ idx < buttons.length - 1 ? ' ': '' }}
            </span>
        </span>
    </header>
</template>

<script>
export default {
    props: {
        title: {
            required: true,
            type: String
        },
        subtitle: {
            required: false,
            type: String
        },
        // Button props: [(to, href, click), variant, icon, text]
        buttons: {
            required: false,
            type: Array,
            default: function() {
                return []
            }
        }
    },
    computed: {
        availableButtons () {
            return this.buttons.filter(i => {
                if (i.show != undefined) {
                    if (typeof i.show === 'function') {
                        return i.show()
                    }
                    return i.show
                }
                return true
            })
        }
    },
    methods: {
        handleClick (button) {
            if (typeof button.click == 'function') {
                button.click()
            }
        }
    }
}
</script>
