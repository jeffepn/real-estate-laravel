const Ziggy = {
  url: "http://localhost",
  port: null,
  defaults: {},
  routes: {
    "jp_realestate.business.index": {
      uri: "minha-api/business",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.business.store": {
      uri: "minha-api/business",
      methods: ["POST"],
    },
    "jp_realestate.business.show": {
      uri: "minha-api/business/{business}",
      methods: ["GET", "HEAD"],
      bindings: { business: "id" },
    },
    "jp_realestate.business.update": {
      uri: "minha-api/business/{business}",
      methods: ["PUT", "PATCH"],
      bindings: { business: "id" },
    },
    "jp_realestate.business.destroy": {
      uri: "minha-api/business/{business}",
      methods: ["DELETE"],
      bindings: { business: "id" },
    },
    "jp_realestate.type.index": {
      uri: "minha-api/type",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.type.store": { uri: "minha-api/type", methods: ["POST"] },
    "jp_realestate.type.show": {
      uri: "minha-api/type/{type}",
      methods: ["GET", "HEAD"],
      bindings: { type: "id" },
    },
    "jp_realestate.type.update": {
      uri: "minha-api/type/{type}",
      methods: ["PUT", "PATCH"],
      bindings: { type: "id" },
    },
    "jp_realestate.type.destroy": {
      uri: "minha-api/type/{type}",
      methods: ["DELETE"],
      bindings: { type: "id" },
    },
    "jp_realestate.sub-type.index": {
      uri: "minha-api/sub-type",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.sub-type.store": {
      uri: "minha-api/sub-type",
      methods: ["POST"],
    },
    "jp_realestate.sub-type.show": {
      uri: "minha-api/sub-type/{sub_type}",
      methods: ["GET", "HEAD"],
      bindings: { subType: "id" },
    },
    "jp_realestate.sub-type.update": {
      uri: "minha-api/sub-type/{sub_type}",
      methods: ["PUT", "PATCH"],
      bindings: { subType: "id" },
    },
    "jp_realestate.sub-type.destroy": {
      uri: "minha-api/sub-type/{sub_type}",
      methods: ["DELETE"],
      bindings: { subType: "id" },
    },
    "jp_realestate.property.index": {
      uri: "minha-api/property",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.property.store": {
      uri: "minha-api/property",
      methods: ["POST"],
    },
    "jp_realestate.property.show": {
      uri: "minha-api/property/{property}",
      methods: ["GET", "HEAD"],
      bindings: { property: "id" },
    },
    "jp_realestate.property.update": {
      uri: "minha-api/property/{property}",
      methods: ["PUT", "PATCH"],
      bindings: { property: "id" },
    },
    "jp_realestate.property.destroy": {
      uri: "minha-api/property/{property}",
      methods: ["DELETE"],
      bindings: { property: "id" },
    },
    "jp_realestate.dashboard": {
      uri: "panel-imobiliaria/dasboard",
      methods: ["GET", "HEAD"],
    },
    "jp_realestate.property.list": {
      uri: "panel-imobiliaria/imoveis",
      methods: ["GET", "HEAD"],
    },
  },
};

if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
  for (let name in window.Ziggy.routes) {
    Ziggy.routes[name] = window.Ziggy.routes[name];
  }
}

export { Ziggy };
