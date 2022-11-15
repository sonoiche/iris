import axios from "axios";
export default {
    namespaced: true,

    state: {
        token: null,
        user: null
    },

    getters: {
        authenticated() {
            return localStorage.getItem("token");
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
                let response = await axios.get("/admin/authuser");
                commit("SET_USER", response.data);
                localStorage.setItem("authuser", JSON.stringify(response.data));
                return response.data;
            } catch (e) {
                console.log(e);
                commit("SET_TOKEN", null);
                commit("SET_USER", null);
            }
        },

        async signIn({ dispatch }, request) {
            let response = await axios.post("/admin/auth/login", request);
            return dispatch("attempt", response.data.token);
        },

        signOut() {
            axios.defaults.headers.common["Authorization"] = null;
            localStorage.removeItem("token");
            localStorage.removeItem("authuser");
        }
    }
};
