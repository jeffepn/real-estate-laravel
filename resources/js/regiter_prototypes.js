import Vue from "vue";

import axios from "axios";
axios.defaults.headers.common = {
  Accept: "application/json",
  "Content-Type": "application/json;charset=UTF-8",
};
window.reaxios = axios;

import RouteZiggy from "ziggy";
import { Ziggy } from "@/routes";
window.reroute = (name, params) => RouteZiggy(name, params, false, Ziggy);

import toast from "@/toast";
Vue.prototype.$toast = toast;

import VueDND from "awe-dnd";

Vue.use(VueDND);
