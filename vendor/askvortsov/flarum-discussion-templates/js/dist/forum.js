module.exports=function(e){var t={};function o(n){if(t[n])return t[n].exports;var r=t[n]={i:n,l:!1,exports:{}};return e[n].call(r.exports,r,r.exports,o),r.l=!0,r.exports}return o.m=e,o.c=t,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)o.d(n,r,function(t){return e[t]}.bind(null,r));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=9)}([function(e,t){e.exports=flarum.core.compat.extend},function(e,t){e.exports=flarum.core.compat.Model},function(e,t){e.exports=flarum.core.compat["tags/models/Tag"]},,function(e,t){e.exports=flarum.core.compat["components/IndexPage"]},function(e,t){e.exports=flarum.core.compat["tags/components/TagDiscussionModal"]},,,,function(e,t,o){"use strict";o.r(t);var n=o(0),r=o(4),a=o.n(r),p=o(1),c=o.n(p),u=o(2),i=o.n(u),s=o(5),l=o.n(s);function f(){if(!app.composer.component.content()){var e={};app.composer.component.tags.forEach((function(t){null!==t.position()&&t.template()&&(e[t.id()]=t.template())}));var t=Object.keys(e);if(2===t.length){var o=app.store.getById("tags",t[0]),n=app.store.getById("tags",t[1]);o.parent()===n&&delete e[t[1]],n.parent()===o&&delete e[t[0]]}if(1===Object.keys(e).length){var r=Object.values(e)[0];app.composer.component.editor.value(r)}}}app.initializers.add("askvortsov/flarum-discussion-templates",(function(){i.a.prototype.template=c.a.attribute("template"),Object(n.extend)(a.a.prototype,"newDiscussionAction",(function(e){e.then((function(e){e.tags.length>0&&f()}))})),Object(n.extend)(l.a.prototype,"onhide",(function(){app.composer.component&&app.composer.component.tags.length>0&&f()}))}))}]);
//# sourceMappingURL=forum.js.map