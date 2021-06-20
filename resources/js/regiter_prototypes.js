import Vue from "vue";
window.eventBus = new Vue();
Vue.prototype.$eventBus = window.eventBus;

import axios from "axios";
Vue.prototype.$axios = axios;

import RouteZiggy from "ziggy";
import { Ziggy } from "@/routes";
Vue.prototype.$route = (name, params) => RouteZiggy(name, params, false, Ziggy);

import toast from "@/toast";
Vue.prototype.$toast = toast;
