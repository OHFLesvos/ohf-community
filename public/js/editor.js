!function(e){var n={};function r(t){if(n[t])return n[t].exports;var o=n[t]={i:t,l:!1,exports:{}};return e[t].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=n,r.d=function(e,n,t){r.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:t})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,n){if(1&n&&(e=r(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(r.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var o in e)r.d(t,o,function(n){return e[n]}.bind(null,o));return t},r.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(n,"a",n),n},r.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},r.p="/",r(r.s=188)}({188:function(e,n,r){e.exports=r(189)},189:function(e,n){$(document).ready(function(){$("#editor").summernote({toolbar:[["style",["bold","italic","underline","clear"]],["color",["forecolor"]],["para",["ul","ol"]],["insert",["link","lfm","video","table"]],["misc",["undo","redo","fullscreen","codeview"]]],buttons:{lfm:function(e){return $.summernote.ui.button({contents:'<i class="note-icon-picture"></i> ',tooltip:"Insert image with filemanager",click:function(){var n,r,t;r=function(n,r){Array.isArray(n)?n.forEach(function(n){e.invoke("insertImage",n.url)}):e.invoke("insertImage",n)},t=(n={type:"image",prefix:"/laravel-filemanager"})&&n.prefix?n.prefix:"/laravel-filemanager",window.open(t+"?type="+n.type||"file","FileManager","width=900,height=600"),window.SetUrl=r}}).render()}},dialogsInBody:!0})})}});
//# sourceMappingURL=editor.js.map