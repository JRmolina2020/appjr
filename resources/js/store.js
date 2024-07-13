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
       
        status: false,
        urlusers: "/api/users",
        urluser: "/api/user",
        urlroles: "/api/roles",
        urlpermissions: "/api/permissions",
        urlbills: "/api/bills",
        urlbillstot: "/api/billsTot",
        urlinvestments: "/api/investments",
        urlinvestmentstot: "/api/investmentsTot",

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
     
      
    },
});
