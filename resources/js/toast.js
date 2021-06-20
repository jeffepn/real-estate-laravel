export const message = (data) => {
  window.eventBus.$emit("add-toast-master", {
    data,
  });
};

export default {
  message,
};
