<template>
    <div>
        <div class="card-body">
            <div v-for="item in bills" :key="item.id">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">{{ item.name }}</h5>
                        <div class="card-tools">
                            <a class="btn btn-tool"
                                >${{ item.price | currency }}</a
                            >
                            <a
                                href="#"
                                class="btn btn-tool"
                                @click="$emit('show', item)"
                            >
                                <i class="fi fi-check"></i>
                            </a>
                            <a
                                href="#"
                                class="btn btn-tool"
                                @click="destroy(item.id)"
                            >
                                <i class="fi fi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="alert alert-dark"
            v-for="(item, index) in billstot"
            :key="'b' + index"
            role="alert"
        >
            <p>TOTAL ${{ item.price | currency }}</p>
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
        ...mapState(["user", "bills", "billstot", "status", "urlbills"]),
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
            this.$store.dispatch("Billactions", obj);
            this.$store.dispatch("Billtotactions", obj);
        },
        getDate() {
            let obj = {
                prop1: this.date,
                prop2: this.date2,
            };
            this.$store.dispatch("Billactions", obj);
            this.$store.dispatch("Billtotactions", obj);
        },

        async destroy(id) {
            let url = this.urlbills + "/" + id;
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
