import Vue from "vue";

import "@/bootstrap";
import ReHeader from "@/components/layout/Header.vue";
import ReLoading from "@/components/Loading.vue";
Vue.component("re-header", ReHeader);
Vue.component("re-loading", ReLoading);
Vue.prototype.$eventBus = new Vue();

// import { ZiggyVue } from "z";
// import { Ziggy } from "@/routes";

// Vue.use(ZiggyVue, Ziggy);
import RouteZiggy from "ziggy";
import { Ziggy } from "@/routes";
Vue.prototype.$route = (name, params) => RouteZiggy(name, params, false, Ziggy);

new Vue({
  el: "#app",
});
