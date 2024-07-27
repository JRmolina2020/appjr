<template>
    <div>
        <div class="card-body">
            <div class="table-responsive">
                <VTable
                    :data="fac"
                    :filters="filters"
                    :page-size="5"
                    :currentPage.sync="currentPage"
                    @totalPagesChanged="totalPages = $event"
                    class="table table-striped table-dark table-hover"
                >
                    <template #head>
                        <tr>
                            <th>Nit</th>
                            <th>Total</th>
                            <th>Efectivo</th>
                            <th>Tranfe</th>
                            <th>Fecha</th>
                            <th>Ver</th>
                            <th></th>
                        </tr>
                    </template>
                    <template #body="{ rows }">
                        <tr v-for="row in rows" :key="row.id">
                            <td>{{ row.nit }}</td>
                            <td>${{ row.tot | currency }}</td>
                            <td>${{ row.efecty | currency }}</td>
                            <td class="bg-warning" v-if="row.other > 0">
                                ${{ row.other | currency }}
                            </td>
                            <td v-else>${{ row.other | currency }}</td>
                            <th>{{ row.date_facture }}</th>
                            <th>
                                <data-example v-bind:item="row"></data-example>
                            </th>
                            <th>
                                <a
                                    href="#"
                                    class="btn btn-tool"
                                    @click="destroy(row.id)"
                                >
                                    <i class="fi fi-trash"></i>
                                </a>
                            </th>
                        </tr>
                    </template>
                </VTable>
                <div class="text-xs-center">
                    <VTPagination
                        :currentPage.sync="currentPage"
                        :total-pages="totalPages"
                        :boundary-links="true"
                    />
                </div>
            </div>
        </div>
        <div class="alert alert-primary" role="alert">Revisa tus ventas</div>
        <div class="row">
            <div class="col-lg-4">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Monto</td>
                            <td>Efectivo</td>
                            <td>Tranfe</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr v-for="(item, index) in fact" :key="index">
                            <th>{{ item.tot | currency }}</th>
                            <th>{{ item.efecty | currency }}</th>
                            <th>{{ item.other | currency }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p><strong>Filtra tus ventas por rango de fecha</strong></p>
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
        <div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cant</th>
                            <th>Venta</th>
                            <th>Costo</th>
                            <th>Ganacia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in facg" :key="item.id">
                            <td scope="row">{{ item.name }}</td>
                            <td>{{ item.quantity }}</td>
                            <td>${{ item.tot | currency }}</td>
                            <td>${{ item.cost | currency }}</td>
                            <td>${{ item.gain | currency }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_now from "../../mixins/date";
import DataExample from "./Dataexample.vue";

export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["name"] },
            },
            date: "",
            date2: "",
        };
    },
    components: {
        DataExample,
    },
    computed: {
        ...mapState(["status", "urlfac", "fac", "fact", "facg"]),
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
            this.$store.dispatch("Facactions", obj);
            this.$store.dispatch("Facgactions", obj);
            this.$store.dispatch("Factactions", obj);
        },
        getDate() {
            let obj = {
                prop1: this.date,
                prop2: this.date2,
            };
            this.$store.dispatch("Facactions", obj);
            this.$store.dispatch("Facgactions", obj);
            this.$store.dispatch("Factactions", obj);
        },

        async destroy(id) {
            let url = this.urlfac + "/" + id;
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
