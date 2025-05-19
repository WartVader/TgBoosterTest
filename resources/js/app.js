import "./bootstrap.js";
import "normalize.css"
import { createApp } from 'vue';
import App from './App.vue';
import router from "./router.js";

createApp(App).use(router).mount("#app");
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
