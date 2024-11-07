import './bootstrap';


import {createApp} from 'vue';


import app from './companents/app.vue';
import router from './companents/router/index.js';
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';


createApp(app).use(router).use(ToastPlugin).mount("#app")
