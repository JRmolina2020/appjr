<template>
    <form method="POST" @submit.prevent="login" autocomplete="off">
        <div class="form-group">
            <input
                type="text"
                v-validate="'required|max:40|min:4'"
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
                placeholder="Clave"
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
                    Ingresar
                </button>
            </div>

            <div class="col-12" v-else>
                <div class="alert alert-success" role="alert">Ingresando</div>
            </div>
        </div>
        <p class="mt-3">
            No tienes cuenta?
            <strong><a href="/register">Registrate</a></strong>
        </p>
    </form>
</template>
<script>
export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "login",
    data() {
        return {
            url: "login",
            submitted: true,
            status: true,
            form: {
                email: null,
                password: null,
            },
        };
    },
    methods: {
        login() {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    axios
                        .post(this.url, this.form)
                        .then((response) => {
                            window.location.replace("/factures");
                            this.status = false;
                        })
                        .catch((error) => {
                            this.form.password = null;
                            Swal.fire({
                                icon: "error",
                                title: "Oopss...",
                                text: "Verifique las credenciales de ingreso",
                            });
                        });
                }
            });
        },
    },
};
</script>
