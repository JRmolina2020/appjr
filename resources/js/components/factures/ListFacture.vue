<template>
    <div>
        <div class="card-body" v-if="tabletype">
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
                                <button
                                    type="button"
                                    @click="typelist(row)"
                                    class="btn btn-tool clickable"
                                >
                                    <i class="fi fi-calculator"></i>
                                </button>
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
        <!-- tabla detalle -->
        <div v-else>
            <div class="alert alert-primary" role="alert">Detalle de venta</div>
            <div id="facturepdf">
                <div class="table-responsive">
                    <p>
                        <strong>VENTA #{{ facid }}</strong>
                    </p>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Nit.</th>
                                <th>Cliente</th>
                                <th>Tel</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cli in clientFac" :key="cli.id">
                                <td>
                                    {{ cli.nit }}
                                </td>
                                <td>
                                    {{ cli.name_client }}
                                </td>
                                <td>
                                    {{ cli.tel }}
                                </td>
                                <td>
                                    {{ cli.date_facture }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- table detail -->
                <div class="table-responsive">
                    <VTable :data="facd" class="table table-striped">
                        <template #head>
                            <tr>
                                <th>Produto</th>
                                <th>Precio</th>
                                <th>Cant</th>
                                <th>sub</th>
                            </tr>
                        </template>
                        <template #body="{ rows }">
                            <tr v-for="row in rows" :key="row.id">
                                <th scope="row">
                                    {{ row.name_product }}
                                </th>
                                <td>${{ row.price | currency }}</td>
                                <td>{{ row.cant }}</td>
                                <td>${{ row.sub | currency }}</td>
                            </tr>
                        </template>
                    </VTable>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total</td>
                                    <td>Efectivo</td>
                                    <td>Tranfe</td>
                                    <td>Cant.</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{ this.totd | currency }}</th>
                                    <th>{{ this.efectyd | currency }}</th>
                                    <th>{{ this.otherd | currency }}</th>
                                    <th>{{ sumProduct }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end -->
            <div class="row">
                <button
                    type="button"
                    @click="typelist2()"
                    class="btn btn-tool clickable"
                >
                    <i class="fi fi-close-a"></i>
                </button>

                <button
                    type="button"
                    @click="print()"
                    class="btn btn-tool clickable"
                >
                    <i class="fi fi-print"></i>
                </button>
            </div>
        </div>
        <!-- end -->
        <div class="alert alert-primary mt-3" role="alert">
            Revisa tus ventas
        </div>
        <div class="row">
            <div class="col-lg-4">
                <table class="table table-bordered table-dark">
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
            facid: 0,
            totd: 0,
            efectyd: 0,
            otherd: 0,
            tabletype: 1,
            totalPages: 1,
            currentPage: 1,
            totalPages2: 1,
            currentPage2: 1,
            filters: {
                name: { value: "", keys: ["name"] },
            },
            date: "",
            date2: "",
            clientFac: [],
        };
    },
    components: {
        DataExample,
    },
    computed: {
        ...mapState(["status", "urlfac", "fac", "facd", "fact", "facg"]),
        sumProduct() {
            let tot = 0;
            this.facd.map((data) => {
                tot = tot + data.cant;
            });
            return tot;
        },
    },
    created() {
        this.getList();
    },
    methods: {
        print() {
            this.$htmlToPaper("facturepdf");
        },
        typelist(row) {
            this.facid = row.id;
            this.totd = row.tot;
            this.efectyd = row.efecty;
            this.otherd = row.other;

            this.tabletype = 0;
            this.clientFac.push({
                name_client: row.name_client,
                nit: row.nit,
                tel: row.tel,
                efecty: row.efecty,
                other: row.other,
                date_facture: row.date_facture,
                tot: row.tot,
            });
            this.getlistDetail(row.id);
        },
        getlistDetail(id) {
            let obj = {
                prop1: id,
            };
            this.$store.dispatch("Facdactions", obj);
        },
        typelist2() {
            this.tabletype = 1;
            this.clientFac = [];
            console.log(this.clientFac);
        },
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
