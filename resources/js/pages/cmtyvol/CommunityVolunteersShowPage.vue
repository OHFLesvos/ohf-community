<template>
  <div>
    <CmtyvolComments :id="id" />
  </div>
</template>

<script>
import CmtyvolComments from "@/components/cmtyvol/CmtyvolComments";
export default {
  components: {
    CmtyvolComments,
  },
  props: {
    id: {
      required: true,
    },
  },
};
</script>
