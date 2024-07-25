<template>
    <div>
        <section class="section">
            <store-Facture ref="form_facture"></store-Facture>
            <list-Facture v-on:show="show"></list-Facture>
        </section>
    </div>
</template>
<script>
import StoreFacture from "./StoreFacture.vue";
import ListFacture from "./ListFacture.vue";

export default {
    name: "facture",
    components: {
        StoreFacture,
        ListFacture,
    },
    methods: {
        show(row) {
            this.$refs.form_facture.show(row);
        },
    },
};
</script>
