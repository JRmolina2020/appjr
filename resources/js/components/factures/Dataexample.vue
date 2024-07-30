<template>
    <div>
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
        <div class="table" style="overflow: auto">
            <table class="table">
                <thead>
                    <tr>
                        <th style="color: black">Nit.</th>
                        <th style="color: black">Nombre</th>
                        <th style="color: black">Telefono</th>
                        <th style="color: black">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="color: black">
                            {{ item.nit }}
                        </td>
                        <td style="color: black">
                            {{ item.name_client }}
                        </td>
                        <td style="color: black">
                            {{ item.tel }}
                        </td>
                        <td style="color: black">
                            {{ item.date_facture }}
                        </td>
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
                        <tr style="color: black">
                            <th>Produto</th>
                            <th>Precio</th>
                            <th>Cant</th>
                            <th>sub</th>
                        </tr>
                    </template>
                    <template #body="{ rows }">
                        <tr
                            style="color: black"
                            v-for="row in rows"
                            :key="row.id"
                        >
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

<style>
.clickable {
    cursor: pointer;
}
</style>
