<template>
    <div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="filters.name.value"
                        placeholder="Buscar inversión"
                    />
                </div>
            </div>
            <div class="col-8">
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

        <div class="table-responsive">
            <VTable
                :data="investments"
                :filters="filters"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table table-dark"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="name">Concepto</VTh>
                        <th>Precio</th>
                        <th>Cant</th>
                        <th>Total</th>
                        <th>Op</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.name }}</td>
                        <td>{{ row.quantity }}</td>
                        <td>{{ row.price | currency }}</td>
                        <td>{{ (row.price * row.quantity) | currency }}</td>

                        <td>
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="destroy(row.id)"
                                class="btn bg-danger btn-sm"
                            >
                                <i class="fi fi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </VTable>
        </div>

        <div class="text-xs-center">
            <VTPagination
                :currentPage.sync="currentPage"
                :total-pages="totalPages"
                :boundary-links="true"
            />
        </div>

        <div
            class="alert alert-dark"
            v-for="(item, index) in investmentstot"
            :key="'b' + index"
            role="alert"
        >
            <p>TOT ${{ item.tot | currency }}</p>
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
