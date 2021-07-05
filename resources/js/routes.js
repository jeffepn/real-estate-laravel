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
    "jp_realestate.business.index": {
      uri: "api/business",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.business.store": { uri: "api/business", methods: ["POST"] },
    "jp_realestate.business.show": {
      uri: "api/business/{business}",
      methods: ["GET", "HEAD"],
      bindings: { business: "id" },
    },
    "jp_realestate.business.update": {
      uri: "api/business/{business}",
      methods: ["PUT", "PATCH"],
      bindings: { business: "id" },
    },
    "jp_realestate.business.destroy": {
      uri: "api/business/{business}",
      methods: ["DELETE"],
      bindings: { business: "id" },
    },
    "jp_realestate.type.index": { uri: "api/type", methods: ["GET", "HEAD"] },
    "jp_realestate.type.store": { uri: "api/type", methods: ["POST"] },
    "jp_realestate.type.show": {
      uri: "api/type/{type}",
      methods: ["GET", "HEAD"],
      bindings: { type: "id" },
    },
    "jp_realestate.type.update": {
      uri: "api/type/{type}",
      methods: ["PUT", "PATCH"],
      bindings: { type: "id" },
    },
    "jp_realestate.type.destroy": {
      uri: "api/type/{type}",
      methods: ["DELETE"],
      bindings: { type: "id" },
    },
    "jp_realestate.sub-type.index": {
      uri: "api/sub-type",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.sub-type.store": { uri: "api/sub-type", methods: ["POST"] },
    "jp_realestate.sub-type.show": {
      uri: "api/sub-type/{sub_type}",
      methods: ["GET", "HEAD"],
      bindings: { subType: "id" },
    },
    "jp_realestate.sub-type.update": {
      uri: "api/sub-type/{sub_type}",
      methods: ["PUT", "PATCH"],
      bindings: { subType: "id" },
    },
    "jp_realestate.sub-type.destroy": {
      uri: "api/sub-type/{sub_type}",
      methods: ["DELETE"],
      bindings: { subType: "id" },
    },
    "jp_realestate.property.index": {
      uri: "api/property",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.property.store": { uri: "api/property", methods: ["POST"] },
    "jp_realestate.property.show": {
      uri: "api/property/{property}",
      methods: ["GET", "HEAD"],
      bindings: { property: "id" },
    },
    "jp_realestate.property.update": {
      uri: "api/property/{property}",
      methods: ["PUT", "PATCH"],
      bindings: { property: "id" },
    },
    "jp_realestate.property.destroy": {
      uri: "api/property/{property}",
      methods: ["DELETE"],
      bindings: { property: "id" },
    },
    "jp_realestate.property.index-image_property": {
      uri: "api/property/{property}/image-property",
      methods: ["GET", "HEAD"],
      bindings: { property: "id" },
    },
    "jp_realestate.image_property.store": {
      uri: "api/image-property",
      methods: ["POST"],
    },
    "jp_realestate.image_property.destroy": {
      uri: "api/image-property/{imageProperty}",
      methods: ["DELETE"],
      bindings: { imageProperty: "id" },
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
  },
};

if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
