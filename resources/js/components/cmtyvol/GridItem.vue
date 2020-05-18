<template>
    <b-card
        class="mb-4"
        no-body
        header-class="p-2"
    >

        <!-- Header -->
        <template v-slot:header>
            <font-awesome-icon
                v-if="item.gender == 'f'"
                icon="female"
            />
            <font-awesome-icon
                v-if="item.gender == 'm'"
                icon="male"
            />
            <template v-if="item.nickname">
                {{ item.nickname }}
            </template>
            <template v-else>
                {{ item.first_name }}
            </template>
        </template>

        <!-- Picture -->
        <b-card-body class="p-0">
            <a :href="item.url">
                <img
                    v-if="item.portrait_picture_url"
                    :src="item.portrait_picture_url"
                    class="img-fluid"
                >
                <img
                    v-else
                    :src="placeholderPicture"
                    class="img-fluid"
                >
            </a>
        </b-card-body>

        <!-- Body -->
        <b-card-body class="py-1 px-2">
            <small>
                {{ item.full_name }}<br>
                {{ item.age }}<template v-if="item.nationality && item.age">,</template>
                {{ item.nationality }}<br>
                <!-- <template v-if="item.responsibilities">
                    {{ arrayToString(item.responsibilities) }}
                </template> -->
            </small>
        </b-card-body>

    </b-card>
</template>

<script>
export default {
    props: {
        item: {
            reuqired: true
        }
    },
    data () {
        return {
            placeholderPicture: '/img/portrait_placeholder.png'
        }
    },
    methods: {
        arrayToString (value) {
            if (Array.isArray(value)) {
                return value.join(', ')
            }
            return value
        }
    }
}
</script>
