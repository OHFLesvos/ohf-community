<template>
        <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5">
                <strong>
                    {{ title }}
                </strong>
            </div>
            <div class="col-sm">
                <slot></slot>
            </div>
        </div>
    </li>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        }
    }
}
</script>