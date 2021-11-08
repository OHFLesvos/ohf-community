<template>
    <b-container>
        <h2 class="display-4 mb-3">New visitor registration</h2>
        <VisitorForm />
        {{ visitor }}
    </b-container>
</template>

<script>
import VisitorForm from "@/components/visitors/VisitorForm";
export default {
    components: {
        VisitorForm,
    },
    data() {
        return {
            visitor: {},
        };
    },
};
</script>
