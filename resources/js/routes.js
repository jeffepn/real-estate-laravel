const Ziggy = {
  url: "http://0.0.0.0:9092",
  port: 9092,
  defaults: {},
  routes: {
    "country.index": { uri: "api/country", methods: ["GET", "HEAD"] },
    "country.store": { uri: "api/country", methods: ["POST"] },
    "country.show": {
      uri: "api/country/{country}",
      methods: ["GET", "HEAD"],
      bindings: { country: "id" },
    },
    "country.update": {
      uri: "api/country/{country}",
      methods: ["PUT", "PATCH"],
      bindings: { country: "id" },
    },
    "country.destroy": {
      uri: "api/country/{country}",
      methods: ["DELETE"],
      bindings: { country: "id" },
    },
    "state.index": { uri: "api/state", methods: ["GET", "HEAD"] },
    "state.store": { uri: "api/state", methods: ["POST"] },
    "state.show": {
      uri: "api/state/{state}",
      methods: ["GET", "HEAD"],
      bindings: { state: "id" },
    },
    "state.update": {
      uri: "api/state/{state}",
      methods: ["PUT", "PATCH"],
      bindings: { state: "id" },
    },
    "state.destroy": {
      uri: "api/state/{state}",
      methods: ["DELETE"],
      bindings: { state: "id" },
    },
    "city.index": { uri: "api/city", methods: ["GET", "HEAD"] },
    "city.store": { uri: "api/city", methods: ["POST"] },
    "city.show": {
      uri: "api/city/{city}",
      methods: ["GET", "HEAD"],
      bindings: { city: "id" },
    },
    "city.update": {
      uri: "api/city/{city}",
      methods: ["PUT", "PATCH"],
      bindings: { city: "id" },
    },
    "city.destroy": {
      uri: "api/city/{city}",
      methods: ["DELETE"],
      bindings: { city: "id" },
    },
    "neighborhood.index": { uri: "api/neighborhood", methods: ["GET", "HEAD"] },
    "neighborhood.store": { uri: "api/neighborhood", methods: ["POST"] },
    "neighborhood.show": {
      uri: "api/neighborhood/{neighborhood}",
      methods: ["GET", "HEAD"],
      bindings: { neighborhood: "id" },
    },
    "neighborhood.update": {
      uri: "api/neighborhood/{neighborhood}",
      methods: ["PUT", "PATCH"],
      bindings: { neighborhood: "id" },
    },
    "neighborhood.destroy": {
      uri: "api/neighborhood/{neighborhood}",
      methods: ["DELETE"],
      bindings: { neighborhood: "id" },
    },
    "address.index": { uri: "api/address", methods: ["GET", "HEAD"] },
    "address.store": { uri: "api/address", methods: ["POST"] },
    "address.show": {
      uri: "api/address/{address}",
      methods: ["GET", "HEAD"],
      bindings: { address: "id" },
    },
    "address.update": {
      uri: "api/address/{address}",
      methods: ["PUT", "PATCH"],
      bindings: { address: "id" },
    },
    "address.destroy": {
      uri: "api/address/{address}",
      methods: ["DELETE"],
      bindings: { address: "id" },
    },
    "post.index": { uri: "post", methods: ["GET", "HEAD"] },
    "post.store": { uri: "post", methods: ["POST"] },
    "post.show": {
      uri: "post/{post}",
      methods: ["GET", "HEAD"],
      bindings: { post: "id" },
    },
    "post.update": {
      uri: "post/{post}",
      methods: ["PUT", "PATCH"],
      bindings: { post: "id" },
    },
    "post.destroy": {
      uri: "post/{post}",
      methods: ["DELETE"],
      bindings: { post: "id" },
    },
    "jp_realestate.api.business.index": {
      uri: "api/business",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.business.store": {
      uri: "api/business",
      methods: ["POST"],
    },
    "jp_realestate.api.business.show": {
      uri: "api/business/{business}",
      methods: ["GET", "HEAD"],
      bindings: { business: "id" },
    },
    "jp_realestate.api.business.update": {
      uri: "api/business/{business}",
      methods: ["PUT", "PATCH"],
      bindings: { business: "id" },
    },
    "jp_realestate.api.business.destroy": {
      uri: "api/business/{business}",
      methods: ["DELETE"],
      bindings: { business: "id" },
    },
    "jp_realestate.api.situation.index": {
      uri: "api/situation",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.situation.store": {
      uri: "api/situation",
      methods: ["POST"],
    },
    "jp_realestate.api.situation.show": {
      uri: "api/situation/{situation}",
      methods: ["GET", "HEAD"],
      bindings: { situation: "id" },
    },
    "jp_realestate.api.situation.update": {
      uri: "api/situation/{situation}",
      methods: ["PUT", "PATCH"],
      bindings: { situation: "id" },
    },
    "jp_realestate.api.situation.destroy": {
      uri: "api/situation/{situation}",
      methods: ["DELETE"],
      bindings: { situation: "id" },
    },
    "jp_realestate.api.type.index": {
      uri: "api/type",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.type.store": { uri: "api/type", methods: ["POST"] },
    "jp_realestate.api.type.show": {
      uri: "api/type/{type}",
      methods: ["GET", "HEAD"],
      bindings: { type: "id" },
    },
    "jp_realestate.api.type.update": {
      uri: "api/type/{type}",
      methods: ["PUT", "PATCH"],
      bindings: { type: "id" },
    },
    "jp_realestate.api.type.destroy": {
      uri: "api/type/{type}",
      methods: ["DELETE"],
      bindings: { type: "id" },
    },
    "jp_realestate.api.sub_type.index": {
      uri: "api/sub-type",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.sub_type.store": {
      uri: "api/sub-type",
      methods: ["POST"],
    },
    "jp_realestate.api.sub_type.show": {
      uri: "api/sub-type/{sub_type}",
      methods: ["GET", "HEAD"],
      bindings: { subType: "id" },
    },
    "jp_realestate.api.sub_type.update": {
      uri: "api/sub-type/{sub_type}",
      methods: ["PUT", "PATCH"],
      bindings: { subType: "id" },
    },
    "jp_realestate.api.sub_type.destroy": {
      uri: "api/sub-type/{sub_type}",
      methods: ["DELETE"],
      bindings: { subType: "id" },
    },
    "jp_realestate.api.property.index": {
      uri: "api/property",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.property.store": {
      uri: "api/property",
      methods: ["POST"],
    },
    "jp_realestate.api.property.show": {
      uri: "api/property/{property}",
      methods: ["GET", "HEAD"],
      bindings: { property: "id" },
    },
    "jp_realestate.api.property.update": {
      uri: "api/property/{property}",
      methods: ["PUT", "PATCH"],
      bindings: { property: "id" },
    },
    "jp_realestate.api.property.destroy": {
      uri: "api/property/{property}",
      methods: ["DELETE"],
      bindings: { property: "id" },
    },
    "jp_realestate.api.image_property.index": {
      uri: "api/image_property",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.image_property.store": {
      uri: "api/image_property",
      methods: ["POST"],
    },
    "jp_realestate.api.image_property.show": {
      uri: "api/image_property/{image_property}",
      methods: ["GET", "HEAD"],
      bindings: { imageProperty: "id" },
    },
    "jp_realestate.api.image_property.update": {
      uri: "api/image_property/{image_property}",
      methods: ["PUT", "PATCH"],
      bindings: { imageProperty: "id" },
    },
    "jp_realestate.api.image_property.destroy": {
      uri: "api/image_property/{image_property}",
      methods: ["DELETE"],
      bindings: { imageProperty: "id" },
    },
    "jp_realestate.api.banner.index": {
      uri: "api/banner",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.banner.store": { uri: "api/banner", methods: ["POST"] },
    "jp_realestate.api.banner.show": {
      uri: "api/banner/{banner}",
      methods: ["GET", "HEAD"],
      bindings: { property: "id" },
    },
    "jp_realestate.api.banner.update": {
      uri: "api/banner/{banner}",
      methods: ["PUT", "PATCH"],
      bindings: { banner: "id" },
    },
    "jp_realestate.api.banner.destroy": {
      uri: "api/banner/{banner}",
      methods: ["DELETE"],
      bindings: { banner: "id" },
    },
    "jp_realestate.api.app_setting.index": {
      uri: "api/app_setting",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.app_setting.store": {
      uri: "api/app_setting",
      methods: ["POST"],
    },
    "jp_realestate.api.app_setting.show": {
      uri: "api/app_setting/{app_setting}",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.api.app_setting.update": {
      uri: "api/app_setting/{app_setting}",
      methods: ["PUT", "PATCH"],
      bindings: { app_setting: "id" },
    },
    "jp_realestate.api.app_setting.destroy": {
      uri: "api/app_setting/{app_setting}",
      methods: ["DELETE"],
      bindings: { app_setting: "id" },
    },
    "jp_realestate.api.property.active_or_inactive": {
      uri: "api/property/{property}/active_or_inactive",
      methods: ["PATCH"],
      bindings: { property: "id" },
    },
    "jp_realestate.api.image_property.update_order": {
      uri: "api/image-property/update-order",
      methods: ["PATCH"],
    },
    "jp_realestate.dashboard": {
      uri: "panel-realestate/dasboard",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.property.list": {
      uri: "panel-realestate/imoveis",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.property.create": {
      uri: "panel-realestate/imoveis/new",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.property.edit": {
      uri: "panel-realestate/imoveis/edit/{property}",
      methods: ["GET", "HEAD"],
      bindings: { property: "id" },
    },
    "jp_realestate.banner.list": {
      uri: "panel-realestate/banners",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.banner.edit": {
      uri: "panel-realestate/banners/edit/{banner}",
      methods: ["GET", "HEAD"],
    },
  },
};

if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
