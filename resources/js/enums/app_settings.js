export const WATTERMARK_IMAGE_PROPERTY = "wattermark_image_property";
export const WATTERMARK_IMAGE_PROJECT = "wattermark_image_project";

export const resolveSettingsWattermark = (entity) => {
  switch (entity) {
    case "projects":
      return WATTERMARK_IMAGE_PROJECT;
    case "projects":
    default:
      return WATTERMARK_IMAGE_PROPERTY;
  }
};
