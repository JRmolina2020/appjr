<template>
    <div>
        <section class="section">
            <store-investment ref="form_investment"></store-investment>
            <list-investment v-on:show="show" class="mt-3"></list-investment>
        </section>
    </div>
</template>
<script>
import StoreInvestment from "./StoreInvestment.vue";
import ListInvestment from "./ListInvestment.vue";

export default {
    components: {
        StoreInvestment,
        ListInvestment,
    },
    methods: {
        show(row) {
            this.$refs.form_investment.show(row);
        },
    },
};
</script>
