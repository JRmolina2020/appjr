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
                :data="products"
                :filters="filters"
                :page-size="10"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="name">Nombre</VTh>
                        <th>stock</th>
                        <th>Costo</th>
                        <th>Precio M</th>
                        <th>Precio D</th>
                        <th>Op</th>
                        <th></th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.name }}</td>
                        <td>{{ row.stock }}</td>
                        <td>${{ row.cost | currency }}</td>

                        <td>${{ row.price_two | currency }}</td>
                        <td>${{ row.price | currency }}</td>
                        <td>
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
                            </button>
                        </td>
                        <td>
                            <button
                                type="button"
                                @click="thestatus(row, urlproduct, prefijo)"
                                v-bind:class="{
                                    'btn btn-sm btn-flat': true,
                                    'btn-success': row.status,
                                    'btn-danger': row.status == 0,
                                }"
                            >
                                <i
                                    :class="
                                        row.status ? 'fi fi-fire' : 'fi fi-ban'
                                    "
                                    aria-hidden="true"
                                ></i>
                            </button>
                        </td>
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
    </div>
</template>
<script>
import { mapState } from "vuex";
import status from "../../mixins/status";

export default {
    data() {
        return {
            prefijo: "El producto",
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    mixins: [status],
    computed: {
        ...mapState(["products", "status", "urlproduct"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Productsactions");
        },
    },
};
</script>
