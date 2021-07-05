import Vue from "vue";

// Components
import ReHeader from "@/components/Layout/Header.vue";
import ReLoading from "@/components/Loading.vue";
import ReContainerToast from "@/components/ContainerToast.vue";
Vue.component("re-header", ReHeader);
Vue.component("re-loading", ReLoading);
Vue.component("re-container-toast", ReContainerToast);

// Views
// List propetites
Vue.component("re-list-properties", () =>
  import(
    /* webpackChunkName: "list-properites" */ "@/views/Properties/List.vue"
  ),
);
Vue.component("re-create-or-edit-properties", () =>
  import(
    /* webpackChunkName: "create-or-edit-properites" */ "@/views/Properties/CreateOrEdit.vue"
  ),
);
