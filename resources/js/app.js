/*import Vue from "vue";
// Defaults bootstrap
import "@/bootstrap";
// Register components
import "@/register_components";
// Regiter prototypes
import "@/regiter_prototypes";
// Regiter globals
import "@/register_globals";

new Vue({
  el: "#app",
  data() {
    return {
      toast: [],
      teste: null,
    };
  },
});*/
import(/* webpackChunkName: "main" */ "@/main.js");
//require(/* webpackChunkName: "main" */ "./main");
