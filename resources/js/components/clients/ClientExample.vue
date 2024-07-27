<template>
    <div>
        <section class="section">
            <store ref="form"></store>
            <list v-on:show="show"></list>
        </section>
    </div>
</template>
<script>
import Store from "./StoreClient.vue";
import List from "./ListClient.vue";

export default {
    components: {
        Store,
        List,
    },
    methods: {
        show(row) {
            this.$refs.form.show(row);
        },
    },
};
</script>
