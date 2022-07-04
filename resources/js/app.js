import Vue from "vue";

window.eventBus = new Vue();
//Vue.prototype.$eventBus = window.eventBus;

// Template
import "bootstrap/dist/css/bootstrap.min.css";

// require("bootstrap/dist/js/bootstrap.min.js");
window.bootstrap = require("bootstrap");
// Defaults bootstrap
import "@/bootstrap";
// Register components
import "@/register_components";
// Regiter prototypes
import "@/regiter_prototypes";
// Regiter globals
import "@/register_globals";

new Vue({
  el: "#content-realestate",
  data() {
    return {
      toast: [],
    };
  },
});
