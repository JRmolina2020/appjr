<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de cotización"
            sone="modal-dialog modal-lg"
        >
            <section @click="normalite()" slot="titlebutton">
                Registrar cotización
            </section>

            <section v-if="!form.id" slot="title">
                Registro de cotización
            </section>

            <section v-else slot="title">Editar cotización</section>

            <section slot="closebtn">
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                    @click="normalite()"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </section>
            <section slot="body">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            @click.prevent="setActive('home')"
                            :class="{ active: isActive('home') }"
                            href="#home"
                            >Home</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            @click.prevent="setActive('view')"
                            :class="{ active: isActive('view') }"
                            href="#view"
                            >Detalle</a
                        >
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            @click.prevent="setActive('profile')"
                            :class="{ active: isActive('profile') }"
                            href="#profile"
                            @click="calculateEfecty()"
                            >Cliente</a
                        >
                    </li>
                </ul>
                <div class="tab-content py-3" id="myTabContent">
                    <div
                        class="tab-pane fade"
                        :class="{ 'active show': isActive('home') }"
                        id="home"
                    >
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="filters"
                                        placeholder="Buscar productos"
                                    />
                                </div>
                            </div>

                            <div
                                v-for="(item, index) in filteredList"
                                :key="'t' + index"
                                class="col-4"
                            >
                                <div
                                    v-if="item.stock > 0"
                                    class="card text-white bg-dark mb-3"
                                    style="max-width"
                                    @click="addItem(item, index)"
                                >
                                    <div class="card-body">
                                        <p>{{ item.name }}</p>
                                        <p v-if="typesale == 1">
                                            ${{ item.price | currency }}
                                        </p>
                                        <p v-else>
                                            ${{ item.price_two | currency }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div></div>
                        </div>
                    </div>
                    <div
                        class="tab-pane fade"
                        :class="{ 'active show': isActive('view') }"
                        id="view"
                    >
                        <div v-if="viewtabledetail" class="table-responsive">
                            T:{{ sumProducts }}
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Cant</th>
                                        <th>%Descuent</th>
                                        <th>T</th>
                                        <th>Sub</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in form.product"
                                        :key="'t' + index"
                                    >
                                        <td>{{ item.namep }}</td>
                                        <td>{{ item.price | currency }}</td>
                                        <td>
                                            <button
                                                type="button"
                                                @click="decrementDetail(index)"
                                                class="btn bg-danger btn-xs"
                                            >
                                                <i class="fi fi-angle-down"></i>
                                            </button>
                                            {{ item.cant }}

                                            <button
                                                v-if="item.cant < item.stock"
                                                type="button"
                                                @click="incrementDetail(index)"
                                                class="btn bg-success btn-xs"
                                            >
                                                <i class="fi fi-angle-up"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                @click="
                                                    decrementdiscount(index)
                                                "
                                                class="btn bg-danger btn-xs"
                                            >
                                                <i class="fi fi-angle-down"></i>
                                            </button>
                                            {{ item.discount }}%

                                            <button
                                                type="button"
                                                @click="
                                                    incrementdiscount(index)
                                                "
                                                class="btn bg-info btn-xs"
                                            >
                                                <i class="fi fi-angle-up"></i>
                                            </button>
                                        </td>
                                        <td>
                                            {{
                                                (((item.price * item.cant) /
                                                    100) *
                                                    form.product[index]
                                                        .discount)
                                                    | currency
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                (form.product[index].sub =
                                                    item.price * item.cant -
                                                    ((item.price * item.cant) /
                                                        100) *
                                                        form.product[index]
                                                            .discount)
                                                    | currency
                                            }}
                                        </td>

                                        <td>
                                            <button
                                                type="button"
                                                @click="removeDetail(index)"
                                                class="btn bg-danger"
                                            >
                                                <i class="fi fi-close-a"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                @click="
                                                    viewdiscount(item, index)
                                                "
                                                class="btn bg-info"
                                            >
                                                <i class="fi fi-dollar"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ this.viewref }}</td>
                                        <td>{{ this.viewprice }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model.number="price[indexproduct]"
                                    @change="updateprice(indexproduct)"
                                />
                                <small id="helpId" class="form-text text-muted"
                                    >Cambia el precio</small
                                >
                                <strong>{{ price[indexproduct] }}</strong>
                            </div>
                            <button
                                class="btn btn-tool"
                                @click="viewtabledetail = 1"
                            >
                                <i class="fi fi-dollar"></i>
                            </button>
                        </div>
                    </div>
                    <div
                        class="tab-pane fade"
                        :class="{ 'active show': isActive('profile') }"
                        id="profile"
                    >
                        <form
                            method="POST"
                            @submit.enter.prevent="add(form.id)"
                            autocomplete="off"
                            onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                        >
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <v-select
                                            :options="clients"
                                            v-model="form.client_id"
                                            :reduce="(clients) => clients.id"
                                            label="name"
                                        >
                                            <template
                                                #search="{ attributes, events }"
                                            >
                                                <input
                                                    class="vs__search"
                                                    :required="!form.client_id"
                                                    v-bind="attributes"
                                                    v-on="events"
                                                />
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-6">
                                    <small i class="form-text text-muted"
                                        >TOTAL VENTA ${{
                                            onViewTot | currency
                                        }}</small
                                    >
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <currency-input
                                            required
                                            v-validate="{
                                                required: true,
                                                min_value: 0,
                                                max_value: onViewTot,
                                            }"
                                            :class="{
                                                'is-invalid':
                                                    submitted &&
                                                    errors.has('efectivo'),
                                            }"
                                            v-model="form.efecty"
                                            @keyup="equalsTot()"
                                            class="form-control"
                                            v-currency="{
                                                currency: 'USD',
                                                precision: 0,
                                                locale: 'en',
                                            }"
                                        />
                                        <small i class="form-text text-muted"
                                            >total efectivo
                                            {{ form.efecty | currency }}</small
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.other"
                                            disabled
                                        />
                                        <small i class="form-text text-muted"
                                            >total tranferencia
                                            {{ form.other | currency }}</small
                                        >
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
                            <div v-if="form.product.length > 0">
                                <button
                                    v-if="send"
                                    :hidden="errors.any()"
                                    type="submit"
                                    v-bind:class="{
                                        'btn btn-outline-primary ':
                                            !this.form.id,
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
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </Modal-Resource>
        <div class="form-group mt-3">
            <select
                @change="typesaleData()"
                v-model="typesale"
                class="form-control"
            >
                <option value="1">Detal</option>
                <option value="0">Mayorista</option>
            </select>
        </div>
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

    created() {
        this.getListProduct();
        this.getlistClient();
    },
    computed: {
        ...mapState(["urlfac", "products", "clients", "facd"]),
        onViewTot() {
            let subtot = 0;
            this.form.product.map((data) => {
                subtot = parseInt(subtot) + parseInt(data.sub);
            });
            return subtot;
        },

        filteredList() {
            return this.products.filter((products) => {
                return products.name
                    .toLowerCase()
                    .includes(this.filters.toLowerCase());
            });
        },
        sumProducts() {
            let tot = 0;
            this.form.product.map((data) => {
                tot = parseInt(tot) + parseInt(data.cant);
            });
            return tot;
        },
    },
    data() {
        return {
            submitted: true,
            send: true,
            activeItem: "home",
            typesale: "1",
            filters: "",
            viewtabledetail: 1,
            viewprice: "",
            viewref: "",
            price: [],
            indexproduct: 0,
            form: {
                id: null,
                client_id: null,
                tot: 1,
                efecty: null,
                other: 0,
                discount: 0,
                product: [],
            },
        };
    },

    methods: {
        priceModified(product, index) {
            let price = 0;
            product.map((data) => {
                price = data.price;
                this.product.price = price;
            });

            console.log(this.product.price);
        },
        isActive(menuItem) {
            return this.activeItem === menuItem;
        },
        setActive(menuItem) {
            this.activeItem = menuItem;
        },
        getListProduct() {
            this.$store.dispatch("Productsactions");
        },
        getlistClient() {
            this.$store.dispatch("Clientsactions");
        },
        getList() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
            };
            $("#model").modal("hide");

            this.$store.dispatch("Facactions", obj);
            this.$store.dispatch("Facgactions", obj);
            this.$store.dispatch("Factactions", obj);
            this.$store.dispatch("Productsactions");
        },
        add(id) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.addFacture(id);
                }
            });
        },

        async addFacture(id) {
            this.send = false;
            if (id) {
                let response = await axios.put(
                    this.urlfac + "/" + id,
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
                    console.log(this.form);
                    this.getList();
                } catch (error) {
                    console.log(error.response);
                }
            } else {
                this.form.tot = this.onViewTot;
                let response = await axios.post(this.urlfac, this.form);
                console.log(response);
                try {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1600,
                    });

                    this.getList();
                    this.form.product = [];
                    console.log(this.form);
                } catch (error) {
                    console.log(error.response);
                }
            }
        },
        addItem(item, index) {
            let priceD = 0;
            if (this.typesale == 1) {
                priceD = item.price;
            } else {
                priceD = item.price_two;
            }
            console.log(priceD);
            this.form.product.push({
                product_id: item.id,
                cant: 1,
                sub: 0,
                price: priceD,
                pricef: priceD,
                discount: 0,
                namep: item.name,
                stock: item.stock,
            });
            this.calculateEfecty();

            Swal.fire({
                position: "center",
                icon: "success",
                title: "producto agregado",
                showConfirmButton: false,
                timer: 500,
            });

            this.ProductRowUnique();
        },

        clear() {
            this.form.id = null;
            this.form.client_id = null;
            this.form.efecty = null;
            this.form.other = 0;
            this.$validator.reset();
            this.send = true;
            this.activeItem = "home";
        },
        typesaleData() {
            if (this.typesale == 0) {
                this.typesale = 0;
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Usted ha escogido venta mayorista",
                    showConfirmButton: true,
                });
            } else {
                this.typesale = 1;
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Usted ha escogido venta al detal",
                    showConfirmButton: true,
                });
            }
        },
        normalite() {
            this.activeItem = "home";
            this.form.product = [];
        },

        incrementDetail(index, stock) {
            this.form.product[index].cant++;
            this.calculateEfecty();
        },
        decrementDetail(index) {
            let num = this.form.product[index].cant;
            if (num != 1) {
                this.form.product[index].cant--;
            }
            this.calculateEfecty();
        },
        incrementdiscount(index) {
            let num = this.form.product[index].discount;
            if (num <= 95) {
                this.form.product[index].discount =
                    this.form.product[index].discount + 5;
                let discount = 0;
                discount =
                    (parseInt(this.form.product[index].price) / 100) *
                    this.form.product[index].discount;
            }
            this.calculateEfecty();
        },
        decrementdiscount(index) {
            let num = this.form.product[index].discount;
            if (num >= 5) {
                let discount = 0;
                this.form.product[index].discount =
                    this.form.product[index].discount - 5;
                discount =
                    (parseInt(this.form.product[index].price) / 100) *
                    this.form.product[index].discount;
            }
            this.calculateEfecty();
        },

        removeDetail(index) {
            this.form.product.splice(index, 1);
            this.calculateEfecty();
        },
        ProductRowUnique() {
            const cartProduct = this.form.product.reduce(
                (acum, valueActuality) => {
                    const elementTrue = acum.find(
                        (element) =>
                            element.product_id === valueActuality.product_id
                    );
                    if (elementTrue) {
                        return acum.map((element) => {
                            if (
                                element.product_id === valueActuality.product_id
                            ) {
                                return {
                                    ...element,
                                    cant: element.cant + valueActuality.cant,
                                };
                            }

                            return element;
                        });
                    }

                    return [...acum, valueActuality];
                },
                []
            );
            this.form.product = cartProduct;
        },
        equalsTot() {
            this.form.other = this.onViewTot - this.form.efecty;
        },

        calculateEfecty() {
            this.form.efecty = this.onViewTot;
            this.form.other = 0;
            this.filters = "";
        },
        viewdiscount(item, index) {
            console.log(item);
            this.viewtabledetail = 0;
            this.viewref = item.namep;
            this.viewprice = item.price;
            this.indexproduct = index;
            this.price = [];
        },
        updateprice(index) {
            this.form.product[index].price = this.price[index];

            console.log(index);
        },
    },
};
</script>
<style>
body {
    padding: 20px 0;
}
</style>
