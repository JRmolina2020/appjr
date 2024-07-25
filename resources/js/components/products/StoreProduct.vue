<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de producto"
            sone="modal-dialog modal-md"
        >
            <section slot="titlebutton">Registrar producto</section>
            <section v-if="!form.id" slot="title">Registro de producto</section>
            <section v-else slot="title">Editar producto</section>
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
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Nombre</label>
                                <input
                                    type="text"
                                    v-validate="'required|max:30|min:3'"
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('producto'),
                                    }"
                                    v-model="form.name"
                                    name="producto"
                                />
                                <div
                                    v-if="submitted && errors.has('producto')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("producto") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Costo</label>
                                <currency-input
                                    v-validate="'|required|min_value:0'"
                                    class="form-control"
                                    v-currency="{
                                        currency: 'USD',
                                        precision: 0,
                                        locale: 'en',
                                    }"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('costo'),
                                    }"
                                    v-model.number="form.cost"
                                    name="cosrto"
                                />
                                <div
                                    v-if="submitted && errors.has('costo')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("costo") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Precio mayorista</label>
                                <currency-input
                                    v-validate="{
                                        min_value: 0,
                                        required: true,
                                        min_value: form.cost,
                                    }"
                                    class="form-control"
                                    v-currency="{
                                        currency: 'USD',
                                        precision: 0,
                                        locale: 'en',
                                    }"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('precio m'),
                                    }"
                                    v-model.number="form.price_two"
                                    name="precio m"
                                />
                                <div
                                    v-if="submitted && errors.has('precio m')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("precio m") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Precio detal</label>
                                <currency-input
                                    v-validate="{
                                        min_value: 0,
                                        required: true,
                                        min_value: form.price_two,
                                    }"
                                    class="form-control"
                                    v-currency="{
                                        currency: 'USD',
                                        precision: 0,
                                        locale: 'en',
                                    }"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('precio d'),
                                    }"
                                    v-model.number="form.price"
                                    name="precio d"
                                />
                                <div
                                    v-if="submitted && errors.has('precio d')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("precio d") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <button
                        v-if="!send"
                        class="btn btn-primary"
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
                            'btn btn-outline-primary ': !this.form.id,
                            'btn btn-outline-danger ': this.form.id,
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

export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {
        ModalResource,
    },
    computed: {
        ...mapState(["urlproduct"]),
    },
    data() {
        return {
            submitted: true,
            send: true,
            form: {
                id: null,
                cost: 0,
                name: null,
                price: 0,
                price_two: 0,
            },
        };
    },

    methods: {
        getList() {
            $("#model").modal("hide");
            this.clear();
            this.$store.dispatch("Productsactions");
        },
        add(id) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.addProduct(id);
                }
            });
        },
        async addProduct(id) {
            this.send = false;
            if (id) {
                let response = await axios.put(
                    this.urlproduct + "/" + id,
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
                let response = await axios.post(this.urlproduct, this.form);
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
        show(row) {
            this.form.id = row.id;
            this.form.name = row.name;
            this.form.cost = parseFloat(row.cost);
            this.form.price = parseFloat(row.price);
            this.form.price_two = parseFloat(row.price_two);
            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = null;
            this.form.name = null;
            this.form.cost = 0;
            this.form.price = 0;
            this.form.price_two = 0;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
