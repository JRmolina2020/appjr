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
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Producto</label>
                                <input
                                    type="text"
                                    v-validate="'required|max:30|min:3'"
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('concepto'),
                                    }"
                                    v-model="form.name"
                                    name="concepto"
                                />
                                <div
                                    v-if="submitted && errors.has('concerpto')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("concepto") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Precio</label>
                                <currency-input
                                    v-validate="'|required|min_value:0'"
                                    class="form-control form-control-sm"
                                    v-currency="{
                                        currency: 'USD',
                                        precision: 0,
                                        locale: 'en',
                                    }"
                                    :class="{
                                        'is-invalid':
                                            submitted &&
                                            errors.has('monto total'),
                                    }"
                                    v-model.number="form.price"
                                    name="monto total"
                                />
                                <div
                                    v-if="
                                        submitted && errors.has('monto total')
                                    "
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("monto total") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-12">
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
        ...mapState(["urlinvestments"]),
    },
    data() {
        return {
            submitted: true,
            send: true,
            form: {
                id: null,
                name: null,
                price: 0,
                quantity: 0,
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
        show(row) {
            this.form.id = row.id;
            this.form.name = row.name;
            this.form.quantity = row.quantity;
            this.form.price = parseFloat(row.price);
            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = null;
            this.form.name = null;
            this.form.price = 0;
            this.form.quantity = 0;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
