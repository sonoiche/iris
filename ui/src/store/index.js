import { createStore } from 'vuex'
import auth from "./auth";
import adminauth from "./adminauth";

export default createStore({
    state: {
    },
    getters: {
    },
    mutations: {
    },
    actions: {
        
    },
    modules: {
        auth,
        adminauth
    }
})