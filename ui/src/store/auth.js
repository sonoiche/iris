import axios from "axios";
export default {
    namespaced: true,

    state: {
        token: null,
        user: null
    },

    getters: {
        authenticated() {
            let authuser = JSON.parse(localStorage.getItem("authuser"));
            if(authuser != null && authuser.two_factor_confirmed_at != null) {
                if(authuser.two_factor_until != null) {
                    return localStorage.getItem("token");
                }
                return '';
            }
            if(authuser != null) {
                return localStorage.getItem("token");
            }
        },
        user() {
            return localStorage.getItem("authuser");
        }
    },

    mutations: {
            SET_TOKEN(state, token) {
            state.token = token;
        },
            SET_USER(state, data) {
            state.user = data;
        }
    },

    actions: {
        async attempt({ commit, state }, token) {
            if (token) {
                commit("SET_TOKEN", token);
            }

            if (!state.token) {
                return;
            }

            try {
                let response = await axios.get("/client/authuser");
                commit("SET_USER", response.data);
                let user = response.data;
                localStorage.setItem("authuser", JSON.stringify(response.data));
                if((user.two_factor_secret || user.sms_authentication == 1) && user.two_factor_until == null) {
                    localStorage.removeItem("authuser");
                }
                return response.data;
            } catch (e) {
                console.log(e);
                commit("SET_TOKEN", null);
                commit("SET_USER", null);
            }
        },

        async signUp({ dispatch }, request) {
            let response = await axios.post("/client/auth/register", request);
            return dispatch("attempt", response.data.token);
        },

        async signIn({ dispatch }, request) {
            let response = await axios.post("/client/auth/login", request);
            if(response.data.qrcode && response.data.two_factor_until) {
                localStorage.setItem('auth-qrcode', response.data.qrcode);
            }
            return dispatch("attempt", response.data.token);
        },

        signOut() {
            axios.defaults.headers.common["Authorization"] = null;
            localStorage.removeItem("token");
            localStorage.removeItem("authuser");
        }
    }
};
