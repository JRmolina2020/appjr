<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de cliente"
            sone="modal-dialog modal-md"
        >
            <section slot="titlebutton">Registrar cliente</section>
            <section v-if="!form.id" slot="title">Registro de cliente</section>
            <section v-else slot="title">Editar cliente</section>
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
                                <label for>Nit</label>
                                <input
                                    type="number"
                                    v-validate="'required|numeric|max:13|min:1'"
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('nit'),
                                    }"
                                    v-model="form.nit"
                                    name="nit"
                                />
                                <div
                                    v-if="submitted && errors.has('nit')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("nit") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Nombre</label>
                                <input
                                    type="text"
                                    v-validate="'required|max:30|min:3'"
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('nombre'),
                                    }"
                                    v-model="form.name"
                                    name="nombre"
                                />
                                <div
                                    v-if="submitted && errors.has('nombre')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("nombre") }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <label for>Tel</label>
                                <input
                                    type="number"
                                    v-validate="
                                        'required|between:1,10000000000'
                                    "
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('tel'),
                                    }"
                                    v-model="form.tel"
                                    name="tel"
                                />
                                <div
                                    v-if="submitted && errors.has('tel')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("tel") }}
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
        ...mapState(["urlclient"]),
    },
    data() {
        return {
            submitted: true,
            send: true,
            form: {
                id: null,
                nit: 0,
                name: "",
                tel: "",
            },
        };
    },

    methods: {
        getList() {
            $("#model").modal("hide");
            this.clear();
            this.$store.dispatch("Clientsactions");
        },
        add(id) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.addClient(id);
                }
            });
        },
        async addClient(id) {
            this.send = false;
            if (id) {
                let response = await axios.put(
                    this.urlclient + "/" + id,
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
                let response = await axios.post(this.urlclient, this.form);
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
            this.form.nit = row.nit;
            this.form.name = row.name;
            this.form.tel = row.tel;

            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = null;
            this.form.nit = null;
            this.form.name = null;
            this.form.tel = null;

            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
