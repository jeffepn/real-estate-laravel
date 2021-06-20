<template>
  <div :id="idModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div v-if="header" class="modal-header">
          <h5 class="modal-title" v-text="title"></h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div v-if="footer" class="modal-footer">
          <slot v-if="buttonCancel" name="button-cancel">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              v-text="textButtonCancel"
              @click="$emit('cancel')"
            ></button>
          </slot>
          <slot v-if="buttonOk" name="button-ok">
            <button
              type="button"
              class="btn btn-primary"
              v-text="textButtonOk"
              @click="$emit('ok')"
            ></button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      default: null,
    },
    show: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: "Modal",
    },
    header: {
      type: Boolean,
      default: true,
    },
    footer: {
      type: Boolean,
      default: true,
    },
    buttonCancel: {
      type: Boolean,
      default: true,
    },
    textButtonCancel: {
      type: String,
      default: "Fechar",
    },
    buttonOk: {
      type: Boolean,
      default: true,
    },
    textButtonOk: {
      type: String,
      default: "Ok",
    },
  },
  data() {
    return {
      idModal: null,
      modal: null,
    };
  },
  watch: {
    show() {
      this.toggle();
    },
  },
  beforeMount() {
    this.idModal = this.id ? this.id : `modal-master-${this._uid}`;
  },
  mounted() {
    this.initialise();
  },
  methods: {
    initialise() {
      let elementModal = document.getElementById(this.idModal);
      this.modal = new bootstrap.Modal(elementModal);
      elementModal.addEventListener("hide.bs.modal", this.close);
      this.toggle();
    },
    toggle() {
      this.show ? this.modal.show() : this.modal.hide();
    },
    close() {
      this.$emit("close");
    },
  },
};
</script>

<style></style>
