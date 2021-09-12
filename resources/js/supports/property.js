export const active = (propertyId, active = false) => {
  return axios.patch(
    window.route("jp_realestate.property.active_or_inactive", [propertyId]),
    {
      active,
    },
  );
};

export default {
  active,
};
