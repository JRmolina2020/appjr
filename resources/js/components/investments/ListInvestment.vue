<template>
    <div>
        <div class="form-group mt-3">
            <input
                type="text"
                class="form-control"
                v-model="filters.name.value"
                placeholder="Buscar productos"
            />
        </div>
        <div class="table-responsive mt-3">
            <VTable
                :data="investments"
                :filters="filters"
                :page-size="10"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="name">Producto</VTh>
                        <th>Cantidad</th>
                        <th>Precio prod</th>
                        <th>Total i.</th>
                        <th></th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.product }}</td>
                        <td>{{ row.quantity }}</td>
                        <td>${{ row.cost | currency }}</td>
                        <td>${{ (row.cost * row.quantity) | currency }}</td>
                        <td></td>
                    </tr>
                </template>
            </VTable>
            <div class="text-xs-center">
                <VTPagination
                    :currentPage.sync="currentPage"
                    :total-pages="totalPages"
                    :boundary-links="true"
                    :maxPageLinks="4"
                />
            </div>
        </div>
        <div
            class="alert alert-dark"
            v-for="(item, index) in investmentstot"
            :key="'b' + index"
            role="alert"
        >
            <p>TOTAL ${{ item.tot | currency }}</p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="input-group">
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="date"
                        placeholder=".form-control-sm"
                    />
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="date2"
                        placeholder=".form-control-sm"
                    />
                    <div class="input-group-append">
                        <button
                            class="btn btn-outline-secondary btn-sm"
                            @click="getDate()"
                            type="button"
                        >
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_now from "../../mixins/date";

export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            date: "",
            date2: "",
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    computed: {
        ...mapState([
            "user",
            "investments",
            "investmentstot",
            "status",
            "urlinvestments",
        ]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
            };
            this.$store.dispatch("Investmentactions", obj);
            this.$store.dispatch("Investmenttotactions", obj);
            this.$store.dispatch("Productsactions");
        },
        getDate() {
            let obj = {
                prop1: this.date,
                prop2: this.date2,
            };
            this.$store.dispatch("Investmentactions", obj);
            this.$store.dispatch("Investmenttotactions", obj);
        },

        async destroy(id) {
            let url = this.urlinvestments + "/" + id;
            let response = await axios.delete(url);
            try {
                this.getList();
                Swal.fire({
                    title: `${response.data.message}`,
                    icon: "success",
                });
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>
