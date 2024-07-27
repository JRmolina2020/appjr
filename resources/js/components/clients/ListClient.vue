<template>
    <div>
        <div class="form-group mt-3">
            <input
                type="text"
                class="form-control"
                v-model="filters.name.value"
                placeholder="Buscar cliente"
            />
        </div>
        <div class="table-responsive mt-3">
            <VTable
                :data="clients"
                :filters="filters"
                :page-size="10"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="nit">Nit</VTh>
                        <th>Nombre</th>
                        <th>Tel</th>
                        <th>Op</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.nit }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.tel }}</td>
                        <td>
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
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

export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["nit"] },
            },
        };
    },
    computed: {
        ...mapState(["clients", "status"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Clientsactions");
        },
    },
};
</script>
