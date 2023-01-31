import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import axios from "axios";

require('/public/assets/plugins/global/plugins.bundle.css');
require('/public/assets/css/style.bundle.css');
require("@/store/subscriber");

import 'sweetalert2/dist/sweetalert2.min.css';
import '@vuepic/vue-datepicker/dist/main.css';
import 'vue-multiselect/dist/vue-multiselect.css';

import VueSweetalert2 from 'vue-sweetalert2';
import Multiselect from 'vue-multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import VueScrollingTable from 'vue-scrolling-table';
import HighchartsVue from 'highcharts-vue';

import Header from '@/components/partials/Header.vue';
import Loading from '@/components/modules/Loading.vue';
import Modal from '@/components/modules/Modal.vue';
import BaseInput from '@/components/modules/form/BaseInput.vue';
import BaseSelect from '@/components/modules/form/BaseSelect.vue';
import BaseButton from '@/components/modules/form/BaseButton.vue';
import BaseEditor from '@/components/modules/form/BaseEditor.vue';
import BaseDatePicker from '@/components/modules/form/BaseDatePicker.vue';

import CustomRow from '@/components/modules/content/CustomRow.vue';

axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`

const app = createApp(App)
    app.use(store)
    app.use(router)
    app.use(VueSweetalert2)
    app.use(HighchartsVue)
    app.component('Header', Header)
    app.component('BaseInput', BaseInput)
    app.component('BaseSelect', BaseSelect)
    app.component('base-editor', BaseEditor)
    app.component('base-button', BaseButton)
    app.component('BaseDatePicker', BaseDatePicker)
    app.component('loading', Loading)
    app.component('modal', Modal)
    app.component('custom-row', CustomRow)
    app.component('date-picker', Datepicker);
    app.component('multiselect', Multiselect);
    app.component(VueScrollingTable.name, VueScrollingTable);
    app.mount('#app')

import "bootstrap"
