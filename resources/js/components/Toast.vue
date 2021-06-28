<template>
  <div
    :id="idToast"
    class="toast"
    :data-bs-delay="delay"
    :data-bs-autohide="hide"
  >
    <div class="toast-header">
      <svg
        class="bd-placeholder-img rounded me-2"
        width="20"
        height="20"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
        preserveAspectRatio="xMidYMid slice"
        focusable="false"
      >
        <rect width="100%" height="100%" :fill="fill"></rect>
      </svg>
      <strong class="me-auto" v-text="title"></strong>
      <small v-text="startTime.format('DD/MM/YYYY HH:mm:ss')"></small>
    </div>
    <div class="toast-body" v-html="message"></div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      default: null,
    },
    message: {
      type: String,
      default: "",
    },
    type: {
      type: String,
      default: "info",
    },
    hide: {
      type: Boolean,
      default: true,
    },
    delay: {
      type: Number,
      default: 5000,
    },
  },
  data() {
    return {
      startTime: this.now(),
      idToast: null,
      toast: null,
      fill: null,
      title: null,
    };
  },
  methods: {
    now() {
      return window.moment();
    },
    initialise() {
      this.idToast = this.id ? this.id : `id-toast-master-${this._uid}`;
      switch (this.type) {
        case "danger":
          this.title = "Erro!";
          this.fill = "#dc3545";
          break;
        case "warning":
          this.title = "Atenção!";
          this.fill = "#ffc107";
          break;
        case "success":
          this.title = "Mensagem!";
          this.fill = "#198754";
          break;

        default:
          this.title = "Informação!";
          this.fill = "#0dcaf0";
          break;
      }
    },
  },
  beforeMount() {
    this.initialise();
  },
  mounted() {
    const toastElList = document.getElementById(this.idToast);
    this.toast = new bootstrap.Toast(toastElList);
    this.toast.show();
  },
};
</script>

<style></style>
