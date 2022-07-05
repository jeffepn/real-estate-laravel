import Vue from "vue";

window.eventBus = new Vue();

window.bootstrap = require("bootstrap/dist/js/bootstrap.bundle.min");
// Defaults bootstrap
import "@/bootstrap";
// Register components
import "@/register_components";
// Regiter prototypes
import "@/regiter_prototypes";

new Vue({
  el: "#content-realestate",
  data() {
    return {
      toast: [],
    };
  },
});
