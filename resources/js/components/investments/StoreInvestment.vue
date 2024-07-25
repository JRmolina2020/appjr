<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de inversión"
            sone="modal-dialog modal-sm"
        >
            <section slot="titlebutton">Registrar inversión</section>
            <section v-if="!form.id" slot="title">
                Registro de inversión
            </section>
            <section v-else slot="title">Editar inversión</section>
            <section slot="closebtn">
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </section>
            <section slot="body">
                <form
                    method="POST"
                    @submit.enter.prevent="add(form.id)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Producto</label>
                                <v-select
                                    :options="products"
                                    v-model="form.product_id"
                                    :reduce="(products) => products.id"
                                    label="name"
                                >
                                    <template #search="{ attributes, events }">
                                        <input
                                            class="vs__search"
                                            :required="!form.product_id"
                                            v-bind="attributes"
                                            v-on="events"
                                        />
                                    </template>
                                </v-select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">cant.</span>
                                </div>
                                <input
                                    class="form-control"
                                    v-validate="'|required|min_value:0'"
                                    type="number"
                                    v-model="form.quantity"
                                    placeholder="Cantidad"
                                    required
                                />
                            </div>
                        </div>
                    </div>
                    <button
                        v-if="!send"
                        class="btn btn-primary mt-3"
                        type="button"
                        disabled
                    >
                        <span
                            class="spinner-border spinner-border-sm"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Loading...
                    </button>
                    <button
                        v-if="send"
                        :hidden="errors.any()"
                        type="submit"
                        v-bind:class="{
                            'btn btn-outline-primary mt-3 ': !this.form.id,
                            'btn btn-outline-danger mt-3 ': this.form.id,
                        }"
                    >
                        <i
                            v-bind:class="{
                                'fi fi-wink': !this.form.id,
                                'fi fi-like': this.form.id,
                            }"
                            aria-hidden="true"
                        ></i>
                    </button>
                </form>
            </section>
        </Modal-Resource>
    </div>
</template>
<script>
import { mapState } from "vuex";
import ModalResource from "../utilities/modal.vue";
import date_now from "../../mixins/date";

export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {
        ModalResource,
    },
    computed: {
        ...mapState(["urlinvestments", "products"]),
    },
    data() {
        return {
            submitted: true,
            send: true,
            form: {
                id: null,
                product_id: null,
                quantity: 1,
            },
        };
    },

    methods: {
        getList() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
            };
            $("#model").modal("hide");
            this.clear();
            this.$store.dispatch("Investmentactions", obj);
            this.$store.dispatch("Investmenttotactions", obj);
        },
        add(id) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.addInvestment(id);
                }
            });
        },
        async addInvestment(id) {
            this.send = false;
            if (id) {
                let response = await axios.put(
                    this.urlinvestments + "/" + id,
                    this.form
                );
                try {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    this.getList();
                } catch (error) {
                    console.log(error.response);
                }
            } else {
                let response = await axios.post(this.urlinvestments, this.form);
                try {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1600,
                    });

                    this.getList();
                } catch (error) {
                    console.log(error.response);
                }
            }
        },

        clear() {
            this.form.id = null;
            this.form.quantity = 1;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
