import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        users: [],
        user:[],
        roles: [],
        permissions: [],
        bills: [],
        billstot: [],
        investments: [],
        investmentstot: [],
        fac:[],
        facd:[],
        fact:[],
        facg:[],
        products:[],
        productsL:[],
        clients:[],
        status: false,
        urlusers: "/api/users",
        urluser: "/api/user",
        urlroles: "/api/roles",
        urlpermissions: "/api/permissions",
        urlbills: "/api/bills",
        urlbillstot: "/api/billsTot",
        urlinvestments: "/api/investments",
        urlinvestmentstot: "/api/investmentsTot",
        urlfac:'/api/fac',
        urlfact:'/api/fact',
        urlfacd:'/api/facd',
        urlfacg:'/api/facg',
        urlproduct:'/api/products',
        urlproductL:'/api/productsL',
        urlclient:'/api/clients',

    },
    mutations: {
        Usermutations(state, item) {
            state.users = item;
        },
        UserLoginmutations(state, item) {
            state.user = item;
        },
        Rolemutations(state, item) {
            state.roles = item;
        },
        Permissionmutations(state, item) {
            state.permissions = item;
        },
       
        Billmutations(state, item) {
            state.bills = item;
        },
        Billtotmutations(state, item) {
            state.billstot = item;
        },
        Investmentmutations(state, item) {
            state.investments = item;
        },
        Investmenttotmutations(state, item) {
            state.investmentstot = item;
        },
    
        Facmutations(state, item) {
            state.fac = item;
        },
        Factmutations(state, item) {
            state.fact = item;
        },
        
        Facdmutations(state, item) {
            state.facd = item;
        },
        Facgmutations(state, item) {
            state.facg = item;
        },
          
        Productsmutations(state, item) {
            state.products = item;
        },
        ProductsLmutations(state, item) {
            state.productsL = item;
        },
        Clientsmutations(state, item) {
            state.clients = item;
        },
    },

    actions: {
        async Useractions({ commit, state }) {
            try {
                let response = await axios.get(state.urlusers);
                commit("Usermutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
      
        async Roleactions({ commit, state }) {
            try {
                let response = await axios.get(state.urlroles);
                commit("Rolemutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Permissionactions({ commit, state }) {
            try {
                let response = await axios.get(state.urlpermissions);
                commit("Permissionmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },

       
        async Billactions({ commit, state },obj) {
            try {
                let response = await axios.get(`${state.urlbills}/${obj.prop1}/${obj.prop2}}`);
              
                commit("Billmutations", response.data);
                console.log(response.data)
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Billtotactions({ commit, state },obj
        ) {
            try {
                let response = await axios.get(`${state.urlbillstot}/${obj.prop1}/${obj.prop2}`);
                commit("Billtotmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Investmentactions({ commit, state },obj) {
            try {
                let response = await axios.get(`${state.urlinvestments}/${obj.prop1}/${obj.prop2}}`);
              
                commit("Investmentmutations", response.data);
                console.log(response.data)
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Investmenttotactions({ commit, state },obj
        ) {
            try {
                let response = await axios.get(`${state.urlinvestmentstot}/${obj.prop1}/${obj.prop2}`);
                commit("Investmenttotmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
     
        async Facactions({ commit, state },obj
        ) {
            try {
                let response = await axios.get(`${state.urlfac}/${obj.prop1}/${obj.prop2}`);
                commit("Facmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Factactions({ commit, state },obj
        ) {
            try {
                let response = await axios.get(`${state.urlfact}/${obj.prop1}/${obj.prop2}`);
                commit("Factmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Facdactions({ commit, state },obj
        ) {
            try {
                let response = await axios.get(`${state.urlfacd}/${obj.prop1}`);
                commit("Facdmutations", response.data);
             
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        //ganancia
        async Facgactions({ commit, state },obj
        ) {
            try {
                let response = await axios.get(`${state.urlfacg}/${obj.prop1}/${obj.prop2}`);
                commit("Facgmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Productsactions({ commit, state }
        ) {
            try {
                let response = await axios.get(`${state.urlproduct}`);
                commit("Productsmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async ProductsLactions({ commit, state }
        ) {
            try {
                let response = await axios.get(`${state.urlproductL}`);
                commit("ProductsLmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Clientsactions({ commit, state }
        ) {
            try {
                let response = await axios.get(`${state.urlclient}`);
                commit("Clientsmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
    },
});
