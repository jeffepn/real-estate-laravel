(self.webpackChunk=self.webpackChunk||[]).push([[305],{7757:(t,e,r)=>{t.exports=r(3076)},1162:(t,e,r)=>{"use strict";r.r(e),r.d(e,{default:()=>i});var n=r(5879),a=r.n(n)()((function(t){return t[1]}));a.push([t.id,".block-select-per-page[data-v-4d92fcd5]{display:flex;align-items:center}.block-select-per-page select[data-v-4d92fcd5]{max-width:70px}.block-select-per-page span[data-v-4d92fcd5]{white-space:nowrap}",""]);const i=a},1264:(t,e,r)=>{"use strict";r.r(e),r.d(e,{default:()=>i});var n=r(5879),a=r.n(n)()((function(t){return t[1]}));a.push([t.id,".content-table[data-v-dd8a1e22]{max-width:100%;overflow-x:auto}.actions[data-v-dd8a1e22]{display:flex;flex-wrap:nowrap}.actions a[data-v-dd8a1e22]{margin:0 .25rem}.actions span[data-v-dd8a1e22]{white-space:nowrap}.input-search[data-v-dd8a1e22]{max-width:250px}",""]);const i=a},3076:t=>{var e=function(t){"use strict";var e,r=Object.prototype,n=r.hasOwnProperty,a="function"==typeof Symbol?Symbol:{},i=a.iterator||"@@iterator",s=a.asyncIterator||"@@asyncIterator",o=a.toStringTag||"@@toStringTag";function c(t,e,r){return Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}),t[e]}try{c({},"")}catch(t){c=function(t,e,r){return t[e]=r}}function u(t,e,r,n){var a=e&&e.prototype instanceof v?e:v,i=Object.create(a.prototype),s=new j(n||[]);return i._invoke=function(t,e,r){var n=d;return function(a,i){if(n===h)throw new Error("Generator is already running");if(n===f){if("throw"===a)throw i;return N()}for(r.method=a,r.arg=i;;){var s=r.delegate;if(s){var o=E(s,r);if(o){if(o===g)continue;return o}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(n===d)throw n=f,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n=h;var c=l(t,e,r);if("normal"===c.type){if(n=r.done?f:p,c.arg===g)continue;return{value:c.arg,done:r.done}}"throw"===c.type&&(n=f,r.method="throw",r.arg=c.arg)}}}(t,r,s),i}function l(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}t.wrap=u;var d="suspendedStart",p="suspendedYield",h="executing",f="completed",g={};function v(){}function m(){}function y(){}var b={};b[i]=function(){return this};var _=Object.getPrototypeOf,x=_&&_(_($([])));x&&x!==r&&n.call(x,i)&&(b=x);var P=y.prototype=v.prototype=Object.create(b);function w(t){["next","throw","return"].forEach((function(e){c(t,e,(function(t){return this._invoke(e,t)}))}))}function C(t,e){function r(a,i,s,o){var c=l(t[a],t,i);if("throw"!==c.type){var u=c.arg,d=u.value;return d&&"object"==typeof d&&n.call(d,"__await")?e.resolve(d.__await).then((function(t){r("next",t,s,o)}),(function(t){r("throw",t,s,o)})):e.resolve(d).then((function(t){u.value=t,s(u)}),(function(t){return r("throw",t,s,o)}))}o(c.arg)}var a;this._invoke=function(t,n){function i(){return new e((function(e,a){r(t,n,e,a)}))}return a=a?a.then(i,i):i()}}function E(t,r){var n=t.iterator[r.method];if(n===e){if(r.delegate=null,"throw"===r.method){if(t.iterator.return&&(r.method="return",r.arg=e,E(t,r),"throw"===r.method))return g;r.method="throw",r.arg=new TypeError("The iterator does not provide a 'throw' method")}return g}var a=l(n,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,g;var i=a.arg;return i?i.done?(r[t.resultName]=i.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,g):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,g)}function k(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function L(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function j(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(k,this),this.reset(!0)}function $(t){if(t){var r=t[i];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var a=-1,s=function r(){for(;++a<t.length;)if(n.call(t,a))return r.value=t[a],r.done=!1,r;return r.value=e,r.done=!0,r};return s.next=s}}return{next:N}}function N(){return{value:e,done:!0}}return m.prototype=P.constructor=y,y.constructor=m,m.displayName=c(y,o,"GeneratorFunction"),t.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===m||"GeneratorFunction"===(e.displayName||e.name))},t.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,y):(t.__proto__=y,c(t,o,"GeneratorFunction")),t.prototype=Object.create(P),t},t.awrap=function(t){return{__await:t}},w(C.prototype),C.prototype[s]=function(){return this},t.AsyncIterator=C,t.async=function(e,r,n,a,i){void 0===i&&(i=Promise);var s=new C(u(e,r,n,a),i);return t.isGeneratorFunction(r)?s:s.next().then((function(t){return t.done?t.value:s.next()}))},w(P),c(P,o,"Generator"),P[i]=function(){return this},P.toString=function(){return"[object Generator]"},t.keys=function(t){var e=[];for(var r in t)e.push(r);return e.reverse(),function r(){for(;e.length;){var n=e.pop();if(n in t)return r.value=n,r.done=!1,r}return r.done=!0,r}},t.values=$,j.prototype={constructor:j,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(L),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function a(n,a){return o.type="throw",o.arg=t,r.next=n,a&&(r.method="next",r.arg=e),!!a}for(var i=this.tryEntries.length-1;i>=0;--i){var s=this.tryEntries[i],o=s.completion;if("root"===s.tryLoc)return a("end");if(s.tryLoc<=this.prev){var c=n.call(s,"catchLoc"),u=n.call(s,"finallyLoc");if(c&&u){if(this.prev<s.catchLoc)return a(s.catchLoc,!0);if(this.prev<s.finallyLoc)return a(s.finallyLoc)}else if(c){if(this.prev<s.catchLoc)return a(s.catchLoc,!0)}else{if(!u)throw new Error("try statement without catch or finally");if(this.prev<s.finallyLoc)return a(s.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var a=this.tryEntries[r];if(a.tryLoc<=this.prev&&n.call(a,"finallyLoc")&&this.prev<a.finallyLoc){var i=a;break}}i&&("break"===t||"continue"===t)&&i.tryLoc<=e&&e<=i.finallyLoc&&(i=null);var s=i?i.completion:{};return s.type=t,s.arg=e,i?(this.method="next",this.next=i.finallyLoc,g):this.complete(s)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),g},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),L(r),g}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var a=n.arg;L(r)}return a}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:$(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),g}},t}(t.exports);try{regeneratorRuntime=e}catch(t){Function("r","regeneratorRuntime = r")(e)}},3527:(t,e,r)=>{"use strict";r.d(e,{Z:()=>a});const n={props:{loading:{type:Boolean,default:!1},classes:{type:String,default:"btn btn-primary"},type:{type:String,default:"button"}},methods:{handleClick:function(){this.$emit("click")}}};const a=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("button",{class:t.classes,attrs:{type:t.type},on:{click:t.handleClick}},[t.loading?t._e():t._t("default"),t._v(" "),r("i",{directives:[{name:"show",rawName:"v-show",value:t.loading,expression:"loading"}],staticClass:"fas fa-circle-notch fa-spin"})],2)}),[],!1,null,null,null).exports},3668:(t,e,r)=>{"use strict";r.r(e),r.d(e,{default:()=>u});var n=r(7757),a=r.n(n);const i={props:{perPage:{type:Number,require:!0},total:{type:Number,require:!0},page:{type:Number,require:!0}},data:function(){return{selectPerPage:this.perPage,options:[{id:10,value:10},{id:20,value:20},{id:25,value:25},{id:50,value:50}]}},computed:{disabledPrevious:function(){return this.page<=1},disabledNext:function(){return this.page>=this.pages},pages:function(){return Math.ceil(this.total/this.selectPerPage)},minFromPage:function(){var t=(this.page-1)*this.selectPerPage+1;return this.total<1?0:t},maxFromPage:function(){var t=this.page*this.selectPerPage;return t<=0?0:t<=this.total?t:this.total},paginationLabel:function(){var t=this.total>=0?this.total:0;return"Mostrando ".concat(this.minFromPage," - ").concat(this.maxFromPage," de ").concat(t)}},watch:{selectPerPage:function(t){this.$emit("changePage",1),this.$emit("changePerPage",parseInt(t))}},methods:{updatePage:function(t){this.$emit("changePage",parseInt(t))},prev:function(){this.disabledPrevious||this.$emit("changePage",parseInt(this.page-1))},next:function(){this.disabledNext||this.$emit("changePage",parseInt(this.page+1))}},beforeMount:function(){var t=this;this.options.find((function(e){return e.id===t.perPage}))||(this.options.push({id:this.perPage,value:this.perPage}),this.options.sort((function(t,e){return t.id-e.id})))}};r(5325);var s=r(1900);function o(t,e,r,n,a,i,s){try{var o=t[i](s),c=o.value}catch(t){return void r(t)}o.done?e(c):Promise.resolve(c).then(n,a)}const c={components:{RePagination:(0,s.Z)(i,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("p",{staticClass:"g-pagination-label"},[t._v("\n    "+t._s(t.paginationLabel)+"\n  ")]),t._v(" "),r("div",{staticClass:"d-flex flex-wrap"},[r("nav",{staticClass:" me-3"},[r("ul",{staticClass:"pagination flex-wrap mb-0 mt-2"},[r("li",{staticClass:"page-item",class:{disabled:t.disabledPrevious}},[r("a",{staticClass:"page-link",attrs:{href:"#"},on:{click:function(e){return e.preventDefault(),t.prev.apply(null,arguments)}}},[r("span",{attrs:{"aria-hidden":"true"}},[t._v("«")])])]),t._v(" "),t._l(parseInt(t.pages),(function(e){return r("li",{key:e,staticClass:"page-item",class:{active:t.page===e}},[r("a",{staticClass:"page-link",attrs:{href:"#"},domProps:{textContent:t._s(e)},on:{click:function(r){return r.preventDefault(),t.updatePage(e)}}})])})),t._v(" "),r("li",{staticClass:"page-item",class:{disabled:t.disabledNext}},[r("a",{staticClass:"page-link",attrs:{href:"#"},on:{click:function(e){return e.preventDefault(),t.next.apply(null,arguments)}}},[r("span",{attrs:{"aria-hidden":"true"}},[t._v("»")])])])],2)]),t._v(" "),r("div",{staticClass:"block-select-per-page mt-2"},[r("select",{directives:[{name:"model",rawName:"v-model",value:t.selectPerPage,expression:"selectPerPage"}],staticClass:"form-select me-3",attrs:{"aria-label":"Selecione uma quantidade de imóveis por página"},on:{change:function(e){var r=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.selectPerPage=e.target.multiple?r:r[0]}}},t._l(t.options,(function(e){return r("option",{key:e.id,domProps:{value:e.id,textContent:t._s(e.value)}})})),0),t._v(" "),r("span",[t._v("\n        Itens por página\n      ")])])])])}),[],!1,null,"4d92fcd5",null).exports,ReButton:r(3527).Z},data:function(){return{search:null,data:[],included:[],pagination:{perPage:10,page:1}}},computed:{properties:function(){var t=(this.pagination.page-1)*this.pagination.perPage,e=t+this.pagination.perPage;return this.filteredProperties.slice(t,e)},filteredProperties:function(){var t=this;if(this.searchIsEmpty)return this.originalProperties;var e=this.originalProperties.filter((function(e){return-1!==e.type.name.search(new RegExp(t.search.replaceAll(" ",".*"),"i"))||-1!==e.sub_type.name.search(new RegExp(t.search.replaceAll(" ",".*"),"i"))||-1!==e.slug.search(new RegExp(t.search.replaceAll(" ",".*"),"i"))}));return this.resetPage(),e},originalProperties:function(){var t=this;return this.data.reduce((function(e,r){var n=r.id,a=r.attributes,i=r.relationships,s=Object.assign({},{id:n},a);return s.businesses=t.extractBusiness(i),s.sub_type=t.extractSubType(i),s.type=t.extractType(i),s.address=t.extractAdress(i),e.push(s),e}),[])},total:function(){return this.filteredProperties.length},searchIsEmpty:function(){return!(this.search&&this.search.trim().length)}},methods:{active:function(t){var e=this,r=arguments.length>1&&void 0!==arguments[1]&&arguments[1];this.$axios.patch(this.$route("jp_realestate.property.update",[t.id]),{active:r}).then((function(n){e.data=e.data.map((function(e){return e.id===t.id&&(e.attributes.active=r),e}))})).catch((function(t){var r=t.response;r&&e.$toast.message({type:"danger",message:r.data.message})}))},getProperties:function(){var t=this;this.$axios.get(this.$route("jp_realestate.property.index")).then((function(e){t.data=e.data.data,t.included=e.data.included})).catch((function(e){t.$toast.message(e.message,!0)}))},updatePage:function(t){this.pagination.page=t},updatePerPage:function(t){this.pagination.perPage=t},resetPage:function(){this.updatePage(1)},formateAddress:function(t){return"".concat(t.address.neighborhood,", ").concat(t.address.city," - ").concat(t.address.initials)},businessesOfProperty:function(t){return t.businesses.reduce((function(t,e){return t+=0===t.trim().length?" ".concat(e.name," "):" | ".concat(e.name," ")}),"")},extractBusiness:function(t){var e=t.businesses,r=this.included;return e.map((function(t){var e=r.find((function(e){return"business_property"===e.type&&e.id===t.data.id})),n=r.find((function(t){return"business"===t.type&&t.id===e.attributes.business_id}));return{id:n.id,name:n.attributes.name}}))},extractSubType:function(t){var e=this.included.find((function(e){return"sub_type"===e.type&&e.id===t.sub_type.data.id}));return Object.assign({},{id:e.id},e.attributes)},extractType:function(t){var e=this.included.find((function(e){return"sub_type"===e.type&&e.id===t.sub_type.data.id})),r=this.included.find((function(t){return"type"===t.type&&t.id===e.relationships.type.data.id}));return Object.assign({},{id:r.id},r.attributes)},extractAdress:function(t){var e=this.included.find((function(e){return"address"===e.type&&e.id===t.address.data.id})),r=this.included.find((function(t){return"neighborhood"===t.type&&t.id===e.relationships.neighborhood.data.id})),n=this.included.find((function(t){return"city"===t.type&&t.id===r.relationships.city.data.id})),a=this.included.find((function(t){return"state"===t.type&&t.id===n.relationships.state.data.id})),i=Object.assign({},{id:e.id},e.attributes);return i.neighborhood=r.attributes.name,i.city=n.attributes.name,i.state=a.attributes.name,i.initials=a.attributes.initials,i}},beforeMount:function(){var t,e=this;return(t=a().mark((function t(){return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.getProperties();case 2:case"end":return t.stop()}}),t)})),function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){o(i,n,a,s,c,"next",t)}function c(t){o(i,n,a,s,c,"throw",t)}s(void 0)}))})()},mounted:function(){window.tooltip()}};r(6505);const u=(0,s.Z)(c,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"container"},[r("div",{staticClass:"card my-5"},[r("div",{staticClass:"\n        card-header\n        d-flex\n        flex-wrap\n        justify-content-between\n        align-items-center\n      "},[r("h2",[t._v("Imóveis")]),t._v(" "),r("div",{staticClass:"d-flex flex-wrap"},[r("div",{staticClass:"input-search input-group"},[r("input",{directives:[{name:"model",rawName:"v-model",value:t.search,expression:"search"}],staticClass:"form-control",attrs:{type:"text",placeholder:"Procurar","aria-label":"Procurar","aria-describedby":"basic-addon2"},domProps:{value:t.search},on:{input:function(e){e.target.composing||(t.search=e.target.value)}}}),t._v(" "),t._m(0)]),t._v(" "),r("a",{staticClass:"btn btn-outline-primary ms-2",attrs:{href:t.$route("jp_realestate.property.create")}},[r("i",{staticClass:"fas fa-plus"}),t._v(" Novo\n        ")])])]),t._v(" "),r("div",{staticClass:"card-body"},[r("div",{staticClass:"content-table"},[r("table",{staticClass:"table table-striped"},[t._m(1),t._v(" "),r("tbody",t._l(t.properties,(function(e){return r("tr",{key:e.id},[r("th",[r("div",{staticClass:"actions"},[r("span",{domProps:{textContent:t._s(e.code+" - ")}}),t._v(" "),e.active?r("re-button",{attrs:{classes:"btn btn-success btn-sm","data-bs-toggle":"tooltip","data-bs-placement":"bottom",title:"Imóvel publicado. Clique para arquivar..."},on:{click:function(r){return t.active(e,!1)}}},[r("i",{staticClass:"fas fa-globe"})]):r("re-button",{attrs:{classes:"btn btn-primary btn-sm","data-bs-toggle":"tooltip","data-bs-placement":"bottom",title:"Imóvel arquivado. Clique para publicar..."},on:{click:function(r){return t.active(e,!0)}}},[r("i",{staticClass:"fas fa-archive"})]),t._v(" "),r("a",{staticClass:"btn btn-primary btn-sm",attrs:{href:t.$route("jp_realestate.property.edit",[e.id]),"data-bs-toggle":"tooltip","data-bs-placement":"bottom",title:"Editar"}},[r("i",{staticClass:"fas fa-edit"})]),t._v(" "),t._m(2,!0)],1)]),t._v(" "),r("td",{domProps:{textContent:t._s(t.formateAddress(e))}}),t._v(" "),r("td",{domProps:{textContent:t._s(t.businessesOfProperty(e))}}),t._v(" "),r("td",{domProps:{textContent:t._s(e.type.name+" - "+e.sub_type.name)}})])})),0)])])]),t._v(" "),r("div",{staticClass:"card-footer"},[r("re-pagination",{attrs:{"per-page":t.pagination.perPage,total:t.total,page:t.pagination.page},on:{changePage:t.updatePage,changePerPage:t.updatePerPage}})],1)])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("span",{staticClass:"input-group-text",attrs:{id:"basic-addon2"}},[e("i",{staticClass:"fas fa-search"})])},function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("thead",[r("tr",[r("th",{attrs:{scope:"col"}},[t._v("Código")]),t._v(" "),r("th",{attrs:{scope:"col"}},[t._v("Endereço")]),t._v(" "),r("th",{attrs:{scope:"col"}},[t._v("Negócio")]),t._v(" "),r("th",{attrs:{scope:"col"}},[t._v("Tipo - Subtipo")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("a",{staticClass:"btn btn-danger btn-sm",attrs:{href:"#","data-bs-toggle":"tooltip","data-bs-placement":"bottom",title:"Excluir"}},[e("i",{staticClass:"fas fa-trash"})])}],!1,null,"dd8a1e22",null).exports},5325:(t,e,r)=>{var n=r(1162);n.__esModule&&(n=n.default),"string"==typeof n&&(n=[[t.id,n,""]]),n.locals&&(t.exports=n.locals);(0,r(5346).Z)("5d96df1c",n,!0,{})},6505:(t,e,r)=>{var n=r(1264);n.__esModule&&(n=n.default),"string"==typeof n&&(n=[[t.id,n,""]]),n.locals&&(t.exports=n.locals);(0,r(5346).Z)("45cf1293",n,!0,{})}}]);