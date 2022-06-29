export const active = (propertyId, active = false) => {
  return reaxios.patch(
    window.reroute("jp_realestate.api.property.active_or_inactive", [
      propertyId,
    ]),
    {
      active,
    },
  );
};

export default {
  active,
};
