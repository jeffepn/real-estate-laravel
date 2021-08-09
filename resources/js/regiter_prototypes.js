import Vue from "vue";
window.eventBus = new Vue();
Vue.prototype.$eventBus = window.eventBus;

import axios from "axios";
axios.defaults.headers.common = {
  Accept: "application/json",
  "Content-Type": "application/json;charset=UTF-8",
};
Vue.prototype.$axios = axios;

import RouteZiggy from "ziggy";
import { Ziggy } from "@/routes";
Vue.prototype.$route = (name, params) => RouteZiggy(name, params, false, Ziggy);

import toast from "@/toast";
Vue.prototype.$toast = toast;

import VueDND from "awe-dnd";

Vue.use(VueDND);
