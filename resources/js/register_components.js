import Vue from "vue";

// Components
import ReHeader from "@/components/Layout/Header.vue";
import ReLoading from "@/components/Loading.vue";
import ReContainerToast from "@/components/ContainerToast.vue";
import ReList from "@/views/Properties/List.vue";
import ReCreateOrEdit from "@/views/Properties/CreateOrEdit.vue";
import ReListBanners from "@/views/Banners/List.vue";
import ReListProjects from "@/views/Projects/List.vue";
import ReListTypes from "@/views/Types/List.vue";
import ReCreateOrEditProjects from "@/views/Projects/CreateOrEdit.vue";

Vue.component("re-header", ReHeader);
Vue.component("re-loading", ReLoading);
Vue.component("re-container-toast", ReContainerToast);

// Views
// Propetites
Vue.component("re-list-properties", ReList);
Vue.component("re-create-or-edit-properties", ReCreateOrEdit);
Vue.component("re-list-banners", ReListBanners);
// Projects
Vue.component("re-list-projects", ReListProjects);
Vue.component("re-create-or-edit-projects", ReCreateOrEditProjects);
// Types
Vue.component("re-list-types", ReListTypes);
