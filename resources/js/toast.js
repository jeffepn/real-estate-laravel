export const message = (data) => {
 eventBus.$emit("add-toast-master", {
    data,
  });
};

export default {
  message,
};
