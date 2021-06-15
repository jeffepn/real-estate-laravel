import Vue from "vue";

// Components
import ReHeader from "@/components/layout/Header.vue";
import ReLoading from "@/components/Loading.vue";
Vue.component("re-header", ReHeader);
Vue.component("re-loading", ReLoading);

// Views
// List propetites
Vue.component("re-list-properties", () =>
  import(
    /* webpackChunkName: "js/list-properites" */ "@/views/properties/List.vue"
  ),
);
