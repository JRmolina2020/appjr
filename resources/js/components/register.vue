<template>
    <form method="POST" @submit.enter.prevent="route()" autocomplete="off">
        <div class="form-group">
            <div class="form-group">
                <input
                    type="text"
                    v-validate="'required|max:30|min:3'"
                    class="form-control"
                    :class="{
                        'is-invalid': submitted && errors.has('name'),
                    }"
                    v-model="form.name"
                    name="name"
                    placeholder="Nombre y apellido"
                />
                <div
                    v-if="submitted && errors.has('name')"
                    class="invalid-feedback"
                >
                    {{ errors.first("name") }}
                </div>
            </div>

            <input
                type="text"
                v-validate="'required|max:40|min:4|alpha_dash'"
                class="form-control"
                :class="{
                    'is-invalid': submitted && errors.has('negocio'),
                }"
                placeholder="Nombre de tu negocio"
                v-model="form.email"
                name="negocio"
            />
            <div
                v-if="submitted && errors.has('negocio')"
                class="invalid-feedback"
            >
                {{ errors.first("negocio") }}
            </div>
        </div>
        <div class="form-group">
            <input
                type="password"
                v-model="form.password"
                name="password"
                placeholder="Clave de ingreso"
                v-validate="'required|max:15|min:3'"
                class="form-control"
                :class="{
                    'is-invalid': submitted && errors.has('password'),
                }"
            />
            <div
                v-if="submitted && errors.has('password')"
                class="invalid-feedback"
            >
                {{ errors.first("password") }}
            </div>
        </div>
        <div class="row">
            <div class="col-4" v-if="status">
                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                    :disabled="errors.any()"
                >
                    Guardar
                </button>
            </div>

            <div class="col-12" v-else>
                <div class="alert alert-success" role="alert">Registrando</div>
            </div>
        </div>
        <p class="mt-3">
            Ya tienes cuenta?
            <strong><a href="/">Ingresa</a></strong>
        </p>
    </form>
</template>
<script>
import { mapState } from "vuex";
export default {
    $_veeValidate: {
        validator: "new",
    },
    computed: {
        ...mapState(["urlusers"]),
    },
    name: "login",
    data() {
        return {
            send: true,
            submitted: true,
            status: true,
            url: "login",
            form: {
                id: null,
                name: "",
                email: null,
                password: null,
                rol: "visitante",
            },
        };
    },

    methods: {
        add() {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.route();
                }
            });
        },
        async route() {
            this.form.email = this.form.email.toLowerCase();
            let response = await axios.post("users", this.form);

            try {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Validando...",
                    text: "redireccionando al panel",
                });
                setTimeout(() => {
                    this.login();
                }, 2000);
            } catch (error) {
                console.log(error.response);
            }
        },
        login() {
            axios.post("login", this.form).then((response) => {
                window.location.replace("/factures");
            });
        },
        clear() {
            this.form.name = null;
            this.form.email = null;
            this.form.password = null;
            this.$validator.reset();
            this.send = true;
        },
        minuscula(e) {
            e.value = e.value.toUpperCase();
        },
    },
};
</script>
