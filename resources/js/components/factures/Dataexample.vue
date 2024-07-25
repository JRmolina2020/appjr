<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn btn-tool"
            data-toggle="modal"
            :data-target="'#modelId' + item.id"
            @click="getListd(item.id)"
        >
            <i class="fi fi-calculator"></i>
        </button>

        <!-- Modal -->
        <div
            class="modal fade"
            :id="'modelId' + item.id"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>
                            #Cotización <strong>{{ item.id }}</strong>
                        </p>
                        <div class="form-group">
                            <input
                                type="text"
                                class="form-control"
                                v-model="filters.name.value"
                                placeholder="Buscar producto"
                            />
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nit.</th>
                                        <th>Nombre</th>
                                        <th>Telefono</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ item.nit }}</td>
                                        <td>{{ item.name_client }}</td>
                                        <td>{{ item.phone }}</td>
                                        <td>{{ item.date_facture }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <VTable
                                    :data="facd"
                                    :filters="filters"
                                    :page-size="5"
                                    :currentPage.sync="currentPage"
                                    @totalPagesChanged="totalPages = $event"
                                    class="table table-striped"
                                >
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
                                            <td>{{ row.price | currency }}</td>
                                            <td>{{ row.cant }}</td>
                                            <td>{{ row.sub | currency }}</td>
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
                            TOTAL ${{ item.tot | currency }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    computed: {
        ...mapState(["status", "urlfacd", "facd"]),
    },
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["name_product"] },
            },
        };
    },
    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    methods: {
        getListd(id) {
            let obj = {
                prop1: id,
            };
            this.$store.dispatch("Facdactions", obj);
        },
    },
};
</script>
