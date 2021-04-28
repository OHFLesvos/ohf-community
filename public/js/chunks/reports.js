(self.webpackChunk=self.webpackChunk||[]).push([[992],{892:(t,e,r)=>{"use strict";r.d(e,{hi:()=>g,BC:()=>m});var n=r(7757),a=r.n(n),i=r(9669),s=r.n(i),o=r(4865),u=r.n(o);s().defaults.headers.common["X-Requested-With"]="XMLHttpRequest";var c=document.head.querySelector('meta[name="csrf-token"]');c?s().defaults.headers.common["X-CSRF-TOKEN"]=c.content:console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"),u().configure({showSpinner:!1}),s().interceptors.request.use((function(t){return u().start(),t})),s().interceptors.response.use((function(t){return u().done(),t}),(function(t){return u().done(),Promise.reject(t)}));const l=s();function d(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function f(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){d(i,n,a,s,o,"next",t)}function o(t){d(i,n,a,s,o,"throw",t)}s(void 0)}))}}function p(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=t&&("undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"]);if(null==r)return;var n,a,i=[],s=!0,o=!1;try{for(r=r.call(t);!(s=(n=r.next()).done)&&(i.push(n.value),!e||i.length!==e);s=!0);}catch(t){o=!0,a=t}finally{try{s||null==r.return||r.return()}finally{if(o)throw a}}return i}(t,e)||function(t,e){if(!t)return;if("string"==typeof t)return h(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);"Object"===r&&t.constructor&&(r=t.constructor.name);if("Map"===r||"Set"===r)return Array.from(t);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return h(t,e)}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function h(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}var m=r(9643).Z.methods.route,v=function(t){throw console.error(t),function(t){var e;return t.response?(t.response.data.message&&(e=t.response.data.message),t.response.data.errors?e+="\n"+Object.entries(t.response.data.errors).map((function(t){var e=p(t,2);return e[0],e[1].join(". ")})):t.response.data.error&&(e=t.response.data.error),e||(e="Error ".concat(t.response.status,": ").concat(t.response.statusText))):e=t,e}(t)},g={getNoCatch:function(t){return f(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,l.get(t);case 2:return r=e.sent,e.abrupt("return",r.data);case 4:case"end":return e.stop()}}),e)})))()},get:function(t){return f(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,l.get(t);case 3:return r=e.sent,e.abrupt("return",r.data);case 7:e.prev=7,e.t0=e.catch(0),v(e.t0);case 10:case"end":return e.stop()}}),e,null,[[0,7]])})))()},post:function(t,e){return f(a().mark((function r(){var n;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,r.next=3,l.post(t,e);case 3:return n=r.sent,r.abrupt("return",n.data);case 7:r.prev=7,r.t0=r.catch(0),v(r.t0);case 10:case"end":return r.stop()}}),r,null,[[0,7]])})))()},postFormData:function(t,e){return f(a().mark((function r(){var n;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,r.next=3,l.post(t,e,{headers:{"Content-Type":"multipart/form-data"}});case 3:return n=r.sent,r.abrupt("return",n.data);case 7:r.prev=7,r.t0=r.catch(0),v(r.t0);case 10:case"end":return r.stop()}}),r,null,[[0,7]])})))()},put:function(t,e){return f(a().mark((function r(){var n;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,r.next=3,l.put(t,e);case 3:return n=r.sent,r.abrupt("return",n.data);case 7:r.prev=7,r.t0=r.catch(0),v(r.t0);case 10:case"end":return r.stop()}}),r,null,[[0,7]])})))()},patch:function(t,e){return f(a().mark((function r(){var n;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,r.next=3,l.patch(t,e);case 3:return n=r.sent,r.abrupt("return",n.data);case 7:r.prev=7,r.t0=r.catch(0),v(r.t0);case 10:case"end":return r.stop()}}),r,null,[[0,7]])})))()},delete:function(t){return f(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,l.delete(t);case 3:return r=e.sent,e.abrupt("return",r.data);case 7:e.prev=7,e.t0=e.catch(0),v(e.t0);case 10:case"end":return e.stop()}}),e,null,[[0,7]])})))()}}},6301:(t,e,r)=>{"use strict";r.d(e,{Z:()=>u});var n=r(7757),a=r.n(n),i=r(892);function s(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function o(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function o(t){s(i,n,a,o,u,"next",t)}function u(t){s(i,n,a,o,u,"throw",t)}o(void 0)}))}}const u={listCurrent:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,i.BC)("api.visitors.listCurrent",t),e.next=3,i.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},checkin:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,i.BC)("api.visitors.checkin"),e.next=3,i.hi.post(r,t);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},checkout:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,i.BC)("api.visitors.checkout",t),e.next=3,i.hi.put(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},checkoutAll:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.BC)("api.visitors.checkoutAll"),t.next=3,i.hi.post(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},dailyVisitors:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.BC)("api.visitors.dailyVisitors"),t.next=3,i.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},monthlyVisitors:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.BC)("api.visitors.monthlyVisitors"),t.next=3,i.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()}}},3726:(t,e,r)=>{"use strict";r.d(e,{Z:()=>s});var n=r(2077),a=r.n(n);var i=r(1581);const s={methods:{numberFormat:function(t){return a()(t).format("0,0")},roundWithDecimals:i.Me,percentValue:function(t,e){return(0,i.Me)(t/e*100,1)}}}},1581:(t,e,r)=>{"use strict";r.d(e,{OV:()=>o,Me:()=>u,zf:()=>c,qp:()=>l});var n=r(2085),a=r.n(n),i=r(3447),s=r.n(i);function o(t,e,r,n){var i={text:t,duration:3e3,pos:"bottom-center",actionText:e||null,actionTextColor:null,customClass:r||null};n&&(i.onActionClick=n,i.duration=5e3),a().show(i)}function u(t,e){return Number(Math.round(t+"e"+e)+"e-"+e)}function c(t){return t.replace(/^\w/,(function(t){return t.toUpperCase()}))}function l(t){for(var e,r,n,a,i,o=s()("tol",Math.min(t.length,12)),u=0;u<t.length;u++){var c="#"+o[u%o.length];t[u].backgroundColor=(r=void 0,n=void 0,a=void 0,i=void 0,r=0,n=0,a=0,i=1,5==(e=c+"80").length?(r="0x"+e[1]+e[1],n="0x"+e[2]+e[2],a="0x"+e[3]+e[3],i="0x"+e[4]+e[4]):9==e.length&&(r="0x"+e[1]+e[2],n="0x"+e[3]+e[4],a="0x"+e[5]+e[6],i="0x"+e[7]+e[8]),"rgba("+ +r+","+ +n+","+ +a+","+(i=+(i/255).toFixed(3))+")"),t[u].borderColor=c,t[u].borderWidth=1}}},1376:(t,e,r)=>{"use strict";r.d(e,{Z:()=>m});var n=r(7757),a=r.n(n),i=r(3447),s=r.n(i),o=r(7297),u=r(9523),c=r.n(u),l=r(7432),d=r(3726);function f(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function p(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){f(i,n,a,s,o,"next",t)}function o(t){f(i,n,a,s,o,"throw",t)}s(void 0)}))}}l.Chart.plugins.unregister(c());const h={extends:o.$I,mixins:[d.Z],props:{title:{type:String,required:!0},data:{type:[Function,Object],required:!0},limit:{type:Number,required:!1,default:12},hideLegend:Boolean},mounted:function(){var t=this;return p(a().mark((function e(){return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.addPlugin(c()),t.loadData();case 2:case"end":return e.stop()}}),e)})))()},methods:{loadData:function(){var t=this;return p(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(e.prev=0,"function"!=typeof t.data){e.next=7;break}return e.next=4,t.data();case 4:r=e.sent,e.next=8;break;case 7:r=t.data;case 8:t.renderChart(t.getChartData(r),t.getOptions()),e.next=14;break;case 11:e.prev=11,e.t0=e.catch(0),console.error(e.t0);case 14:case"end":return e.stop()}}),e,null,[[0,11]])})))()},getChartData:function(t){var e=Object.keys(t);if(0==e.length)return this.noDataData();var r=Object.values(t);if(r.length>this.limit){var n=r.slice(this.limit-1).reduce((function(t,e){return t+e}),0);(r=r.slice(0,this.limit-1)).push(n),(e=e.slice(0,this.limit-1)).push(this.$t("Others"))}var a=s()("tol",Math.min(r.length,this.limit));return{labels:e,datasets:[{data:r,backgroundColor:Array(a.length).fill().map((function(t,e){return"#"+a[e%a.length]}))}]}},noDataData:function(){return{labels:[this.$t("No data")],datasets:[{data:[1],datalabels:{color:"#000000"}}]}},getOptions:function(){return{title:{display:!0,text:this.title},legend:{display:!this.hideLegend,position:"bottom"},responsive:!0,maintainAspectRatio:!1,animation:{duration:500},tooltips:{callbacks:{label:this.toolTipLabel,title:this.toolTipTitle}},plugins:{datalabels:{color:"#ffffff",textAlign:"center",formatter:this.dataLabelFormat}}}},toolTipLabel:function(t,e){var r=e.datasets[t.datasetIndex],n=r._meta[Object.keys(r._meta)[0]].total,a=r.data[t.index],i=parseFloat((a/n*100).toFixed(1));return"".concat(this.numberFormat(a)," (").concat(i,"%)")},toolTipTitle:function(t,e){return e.labels[t[0].index]},dataLabelFormat:function(t,e){var r=e.chart.data.datasets[0],n=r._meta[Object.keys(r._meta)[0]].total,a=r.data[e.dataIndex],i=e.chart.data.labels[e.dataIndex],s=parseFloat((a/n*100).toFixed(1));return this.hideLegend?"".concat(i,"\n(").concat(s,"%)"):"".concat(s,"%")}}};const m=(0,r(1900).Z)(h,undefined,undefined,!1,null,null,null).exports},7475:(t,e,r)=>{"use strict";r.r(e),r.d(e,{default:()=>W});var n=r(7757),a=r.n(n);const i={props:{header:{type:String},headerAddon:{required:!1,type:String},items:{required:!0,type:Array},loading:Boolean,error:{required:!1,type:String}}};var s=r(1900);const o=(0,s.Z)(i,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-4",attrs:{"no-body":!t.loading,header:t.loading?t.header:null}},[!t.loading&&t.header?r("b-card-header",{attrs:{"header-class":"d-flex justify-content-between align-items-center"}},[r("span",[t._v(t._s(t.header))]),t._v(" "),t.headerAddon?r("small",[t._v(t._s(t.headerAddon))]):t._e()]):t._e(),t._v(" "),t.error?r("b-card-text",[t.error?r("em",{staticClass:"text-danger"},[t._v(t._s(t.error))]):t._e()]):t.loading?r("b-card-text",[r("em",[t._v(t._s(t.$t("Loading...")))])]):[r("b-list-group",{attrs:{flush:""}},t._l(t.items,(function(e){return r("b-list-group-item",{key:e.name,staticClass:"d-flex justify-content-between"},[r("span",[t._v(t._s(e.name))]),t._v(" "),r("span",[t._v(t._s(e.value))])])})),1)]],2)}),[],!1,null,null,null).exports;var u=r(3726);const c={mixins:[u.Z],props:{header:{required:!0,type:String},items:{required:!0,type:Array},limit:{requireD:!1,type:Number,default:10},loading:Boolean,error:{required:!1,type:String}},data:function(){return{topTen:!0}},computed:{totalAmount:function(){return this.items.reduce((function(t,e){return t+e.amount}),0)},selectedItems:function(){return this.topTen?this.items.slice(0,this.limit):this.items},unselectedItemsAmount:function(){return this.topTen?this.items.slice(this.limit).reduce((function(t,e){return t+e.amount}),0):0}}};const l=(0,s.Z)(c,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-4",attrs:{"no-body":!t.loading&&t.items.length>0,header:t.loading||0==t.items.length?t.header:null}},[t.error?r("b-card-text",[t.error?r("em",{staticClass:"text-danger"},[t._v(t._s(t.error))]):t._e()]):t.loading?r("b-card-text",[r("em",[t._v(t._s(t.$t("Loading...")))])]):0==t.items.length?r("b-card-text",[r("em",[t._v(t._s(t.$t("No data registered.")))])]):[r("b-card-header",{attrs:{"header-class":"d-flex justify-content-between"}},[r("span",[t._v(t._s(t.header))]),t._v(" "),t.items.length>this.limit?r("a",{attrs:{href:"javascript:;"},on:{click:function(e){t.topTen=!t.topTen}}},[t._v("\n                "+t._s(t.topTen?t.$t("Show all :num",{num:t.items.length}):t.$t("Show Top :num",{num:t.limit}))+"\n            ")]):t._e()]),t._v(" "),r("b-list-group",{attrs:{flush:""}},[t._l(t.selectedItems,(function(e){return r("b-list-group-item",{key:e.name,staticClass:"d-flex justify-content-between align-items-center"},[r("span",[t._v(t._s(e.name))]),t._v(" "),r("span",[t._v("\n                    "+t._s(e.amount)+"  \n                    "),r("small",{staticClass:"text-muted"},[t._v(t._s(t.roundWithDecimals(e.amount/t.totalAmount*100,1))+"%")])])])})),t._v(" "),t.topTen&&t.items.length>t.limit?r("b-list-group-item",{staticClass:"d-flex justify-content-between align-items-center",attrs:{href:"javascript:;"},on:{click:function(e){t.topTen=!t.topTen}}},[r("em",[t._v(t._s(t.$t("Others")))]),t._v(" "),r("span",[t._v("\n                    "+t._s(t.unselectedItemsAmount)+"  \n                    "),r("small",{staticClass:"text-muted"},[t._v(t._s(t.roundWithDecimals(t.unselectedItemsAmount/t.totalAmount*100,1))+"%")])])]):t._e()],2)]],2)}),[],!1,null,null,null).exports;var d=r(381),f=r.n(d),p=r(1581),h=r(7297),m=h.tA.reactiveProp;const v={extends:h.$Q,mixins:[m],props:{options:{type:Object,required:!0}},mounted:function(){this.renderChart(this.chartData,this.options)}};const g=(0,s.Z)(v,undefined,undefined,!1,null,null,null).exports;var y=h.tA.reactiveProp;const b={extends:h.x1,mixins:[y],props:{options:{type:Object,required:!0}},mounted:function(){this.renderChart(this.chartData,this.options)}};const x=(0,s.Z)(b,undefined,undefined,!1,null,null,null).exports;var w=r(1304),_=r.n(w);function C(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=t&&("undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"]);if(null==r)return;var n,a,i=[],s=!0,o=!1;try{for(r=r.call(t);!(s=(n=r.next()).done)&&(i.push(n.value),!e||i.length!==e);s=!0);}catch(t){o=!0,a=t}finally{try{s||null==r.return||r.return()}finally{if(o)throw a}}return i}(t,e)||k(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function k(t,e){if(t){if("string"==typeof t)return D(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?D(t,e):void 0}}function D(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}function $(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}const A={components:{ReactiveBarChart:g,ReactiveLineChart:x},mixins:[u.Z],props:{title:{type:String,required:!0},data:{type:[Function,Object],required:!1},error:{type:String,required:!1},dateFrom:{type:String,required:!0},dateTo:{type:String,required:!0},granularity:{type:String,required:!1,value:"days"},height:{type:Number,required:!1,default:350},cumulative:Boolean},data:function(){return{loaded:!1,asyncError:null,chartData:{},units:new Map}},computed:{options:function(){var t,e,r,n=this;switch(this.granularity){case"years":t="year",e="YYYY",r="YYYY";break;case"months":t="month",e="MMMM YYYY",r="YYYY-MM";break;case"weeks":t="week",e="[W]WW GGGG",r=void 0;break;default:t="day",e="dddd, LL",r="YYYY-MM-DD"}return{title:{display:!0,text:this.title},legend:{display:!0,position:"bottom"},scales:{xAxes:[{display:!0,type:"time",time:{tooltipFormat:e,unit:t,parser:r,minUnit:"day",isoWeekday:!0,displayFormats:{day:"ll",week:"[W]WW GGGG"}},ticks:{min:this.dateFrom,max:this.dateTo},gridLines:{display:!0},scaleLabel:{display:!0,labelString:this.$t("Date")}}],yAxes:this.yAxes()},responsive:!0,maintainAspectRatio:!1,animation:{duration:500},tooltips:{callbacks:{label:function(t,e){var r=e.datasets[t.datasetIndex].label||"";return"".concat(r,": ").concat(n.numberFormat(t.yLabel))}}}}}},watch:{granularity:function(){this.loadData()},dateFrom:function(){this.loadData()},dateTo:function(){this.loadData()},data:function(){this.loadData()}},mounted:function(){f().locale(this.$i18n.locale),this.loadData()},methods:{loadData:function(){var t,e=this;return(t=a().mark((function t(){var r;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(e.asyncError=null,e.loaded=!1,!e.data){t.next=18;break}if(t.prev=3,"function"!=typeof e.data){t.next=10;break}return t.next=7,e.data(e.granularity,e.dateFrom,e.dateTo);case 7:r=t.sent,t.next=11;break;case 10:r=e.data;case 11:e.chartData=e.chartDataFromResponse(r),e.loaded=!0,t.next=18;break;case 15:t.prev=15,t.t0=t.catch(3),e.asyncError=t.t0;case 18:case"end":return t.stop()}}),t,null,[[3,15]])})),function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){$(i,n,a,s,o,"next",t)}function o(t){$(i,n,a,s,o,"throw",t)}s(void 0)}))})()},chartDataFromResponse:function(t){var e=this,r={labels:t.labels,datasets:[]},n=new Map;return t.datasets.forEach((function(t){var a=_()(t.unit),i=t.data;if(e.cumulative)for(var s=0,o=0;o<i.length;o++)i[o]&&(i[o]+=s,s=i[o]);r.datasets.push({label:t.label,data:i,yAxisID:a}),n.set(a,t.unit)})),this.units=n,(0,p.qp)(r.datasets),r},yAxes:function(){var t,e=[],r=0,n=function(t,e){var r="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!r){if(Array.isArray(t)||(r=k(t))||e&&t&&"number"==typeof t.length){r&&(t=r);var n=0,a=function(){};return{s:a,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,s=!0,o=!1;return{s:function(){r=r.call(t)},n:function(){var t=r.next();return s=t.done,t},e:function(t){o=!0,i=t},f:function(){try{s||null==r.return||r.return()}finally{if(o)throw i}}}}(this.units);try{for(n.s();!(t=n.n()).done;){var a=C(t.value,2),i=a[0],s=a[1];e.push({display:!0,id:i,position:r++%2==1?"right":"left",gridLines:{display:!0},scaleLabel:{display:!0,labelString:s},ticks:{suggestedMin:0,precision:0,callback:this.numberFormat}})}}catch(t){n.e(t)}finally{n.f()}return e}}};const T=(0,s.Z)(A,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return t.asyncError||t.error||!t.loaded?r("div",{staticClass:"d-flex border",style:"height: "+t.height+"px"},[r("p",{staticClass:"justify-content-center align-self-center text-center w-100"},[t.asyncError||t.error?r("em",{staticClass:"text-danger"},[t._v(t._s(t.asyncError)+" "+t._s(t.error))]):r("em",[t._v(t._s(t.$t("Loading...")))])])]):r(t.cumulative?"reactive-line-chart":"reactive-bar-chart",{tag:"component",staticClass:"border",attrs:{"chart-data":t.chartData,options:t.options,height:t.height}})}),[],!1,null,null,null).exports;function j(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}const S={components:{DoughnutChart:r(1376).Z},mixins:[u.Z],props:{title:{required:!0,type:String},data:{type:[Function,Object],required:!0}},data:function(){return{myData:null}},computed:{total:function(){return Object.values(this.myData).reduce((function(t,e){return t+e}),0)}},created:function(){var t,e=this;return(t=a().mark((function t(){return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if("function"!=typeof e.data){t.next=6;break}return t.next=3,e.data();case 3:e.myData=t.sent,t.next=7;break;case 6:e.myData=e.data;case 7:case"end":return t.stop()}}),t)})),function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){j(i,n,a,s,o,"next",t)}function o(t){j(i,n,a,s,o,"throw",t)}s(void 0)}))})()}};const O=(0,s.Z)(S,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-4",attrs:{header:t.title,"no-body":""}},[t.myData?[r("b-card-body",[r("doughnut-chart",{staticClass:"mb-2",attrs:{title:t.title,data:t.myData,height:300}})],1),t._v(" "),Object.keys(t.myData).length>0?r("b-table-simple",{staticClass:"my-0",attrs:{responsive:"",small:""}},t._l(t.myData,(function(e,n){return r("b-tr",{key:n},[r("b-td",{staticClass:"fit"},[t._v("\n                    "+t._s(n)+"\n                ")]),t._v(" "),r("b-td",{staticClass:"align-middle d-none d-sm-table-cell"},[r("b-progress",{attrs:{value:e,max:t.total,"show-value":!1,variant:"secondary"}})],1),t._v(" "),r("b-td",{staticClass:"fit text-right"},[t._v("\n                    "+t._s(t.percentValue(e,t.total))+"%\n                ")]),t._v(" "),r("b-td",{staticClass:"fit text-right d-none d-sm-table-cell"},[t._v("\n                    "+t._s(t.numberFormat(e))+"\n                ")])],1)})),1):t._e()]:r("b-card-body",[t._v("\n        "+t._s(t.$t("Loading..."))+"\n    ")])],2)}),[],!1,null,null,null).exports;function E(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function R(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?E(Object(r),!0).forEach((function(e){P(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):E(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function P(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}const F={props:{value:{type:Object,required:!0,validator:function(t){return t.from&&t.to}},noGranularity:Boolean,min:{type:String,required:!1,default:null},max:{type:String,required:!1,default:function(){return f()().format(f().HTML5_FMT.DATE)}}},data:function(){return{originalValues:R({},this.value),from:this.value.from,to:this.value.to,granularity:this.value.granularity,granularities:[{value:"days",text:(0,p.zf)(this.$t("days"))},{value:"weeks",text:(0,p.zf)(this.$t("Weeks"))},{value:"months",text:(0,p.zf)(this.$t("Months"))},{value:"years",text:(0,p.zf)(this.$t("Years"))}]}},watch:{from:function(){this.emitChange()},to:function(){this.emitChange()},granularity:function(){this.emitChange()}},methods:{emitChange:function(){this.from&&this.to&&this.$emit("input",{from:this.from,to:this.to,granularity:this.granularity})},reset:function(){this.from=this.originalValues.from,this.to=this.originalValues.to,this.granularity=this.originalValues.granularity}}};const L=(0,s.Z)(F,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"form-row"},[r("div",{class:[t.noGranularity?"col-md":"col-md-8"]},[r("b-input-group",{staticClass:"mb-2",attrs:{prepend:t.$t("Date range")}},[r("b-form-datepicker",{attrs:{placeholder:t.$t("From"),min:t.min,max:t.to,"date-format-options":{year:"numeric",month:"short",day:"numeric",weekday:"short"}},model:{value:t.from,callback:function(e){t.from=e},expression:"from"}}),t._v(" "),r("div",{staticClass:"input-group-prepend input-group-append"},[r("span",{staticClass:"input-group-text"},[t._v(":")])]),t._v(" "),r("b-form-datepicker",{attrs:{placeholder:t.$t("To"),min:t.from,max:t.max,"date-format-options":{year:"numeric",month:"short",day:"numeric",weekday:"short"}},model:{value:t.to,callback:function(e){t.to=e},expression:"to"}})],1)],1),t._v(" "),t.noGranularity?t._e():r("div",{staticClass:"col-md"},[r("b-input-group",{staticClass:"mb-2",attrs:{prepend:t.$t("Granularity")}},[r("b-form-select",{attrs:{options:t.granularities},model:{value:t.granularity,callback:function(e){t.granularity=e},expression:"granularity"}})],1)],1),t._v(" "),r("div",{staticClass:"col-auto"},[r("b-button",{staticClass:"mb-2",attrs:{variant:"secondary"},on:{click:function(e){return t.reset()}}},[r("font-awesome-icon",{attrs:{icon:"undo"}})],1)],1)])}),[],!1,null,null,null).exports;var M=r(892);function q(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function Y(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){q(i,n,a,s,o,"next",t)}function o(t){q(i,n,a,s,o,"throw",t)}s(void 0)}))}}const I={getCount:function(t){return Y(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="".concat((0,M.BC)("api.fundraising.report.donors.count"),"?date=").concat(t),e.next=3,M.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},getCountries:function(t){return Y(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="".concat((0,M.BC)("api.fundraising.report.donors.countries"),"?date=").concat(t),e.next=3,M.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},getLanguages:function(t){return Y(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="".concat((0,M.BC)("api.fundraising.report.donors.languages"),"?date=").concat(t),e.next=3,M.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},fetchDonorRegistrations:function(t,e,r){return Y(a().mark((function n(){var i,s;return a().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return i={granularity:t,from:e,to:r},s=(0,M.BC)("api.fundraising.report.donors.registrations",i),n.next=4,M.hi.get(s);case 4:return n.abrupt("return",n.sent);case 5:case"end":return n.stop()}}),n)})))()},fetchDonationRegistrations:function(t,e,r){return Y(a().mark((function n(){var i,s;return a().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return i={granularity:t,from:e,to:r},s=(0,M.BC)("api.fundraising.report.donations.registrations",i),n.next=4,M.hi.get(s);case 4:return n.abrupt("return",n.sent);case 5:case"end":return n.stop()}}),n)})))()},fechCurrencyDistribution:function(){return Y(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,M.BC)("api.fundraising.report.donations.currencies"),t.next=3,M.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},fetchChannelDistribution:function(){return Y(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,M.BC)("api.fundraising.report.donations.channels"),t.next=3,M.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()}};function B(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function Z(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){B(i,n,a,s,o,"next",t)}function o(t){B(i,n,a,s,o,"throw",t)}s(void 0)}))}}const V={components:{SimpleTwoColumnListCard:o,AdvancedTwoColumnListCard:l,TimeBarChart:T,DoughnutChartTableDistributionWidget:O,DateRangeSelect:L},data:function(){return{firstDonorRegistration:null,count:null,countError:null,countries:null,countriesError:null,languages:null,languagesError:null,donationRegistrations:null,donationRegistrationsError:null,dateRange:{from:f()().subtract(3,"months").format(f().HTML5_FMT.DATE),to:f()().format(f().HTML5_FMT.DATE),granularity:"days"},reportApi:I}},watch:{dateRange:function(){this.loadData()}},created:function(){this.loadData()},methods:{loadData:function(){this.loadCount(),this.loadCountries(),this.loadLanguages()},loadCount:function(){var t=this;return Z(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.countError=null,e.prev=1,e.next=4,I.getCount(t.dateRange.to);case 4:r=e.sent,t.count=t.mapCountData(r),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),t.countError=e.t0;case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()},mapCountData:function(t){return this.firstDonorRegistration=f()(t.first).format("LL"),[{name:this.$t("Total"),value:t.total},{name:this.$t("Individual persons"),value:t.persons},{name:this.$t("Companies"),value:t.companies},{name:this.$t("with registered address"),value:t.with_address},{name:this.$t("with registered email address"),value:t.with_email},{name:this.$t("with registered phone number"),value:t.with_phone}]},loadCountries:function(){var t=this;return Z(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.countriesError=null,e.prev=1,e.next=4,I.getCountries(t.dateRange.to);case 4:r=e.sent,t.countries=r,e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),t.countriesError=e.t0;case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()},loadLanguages:function(){var t=this;return Z(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.languagesError=null,e.prev=1,e.next=4,I.getLanguages(t.dateRange.to);case 4:r=e.sent,t.languages=r,e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),t.languagesError=e.t0;case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()}}};const W=(0,s.Z)(V,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("date-range-select",{model:{value:t.dateRange,callback:function(e){t.dateRange=e},expression:"dateRange"}}),t._v(" "),r("h2",[t._v("\n        "+t._s(t.$t("Donors"))+"\n    ")]),t._v(" "),r("div",{staticClass:"row"},[r("div",{staticClass:"col-md"},[r("simple-two-column-list-card",{attrs:{header:t.$t("Registered donors"),headerAddon:t.$t("since :date",{date:t.firstDonorRegistration}),items:t.count?t.count:[],loading:!t.count,error:t.countError}})],1),t._v(" "),r("div",{staticClass:"col-md"},[r("advanced-two-column-list-card",{attrs:{header:t.$t("Countries"),items:t.countries?t.countries:[],limit:5,loading:!t.countries,error:t.countriesError}})],1),t._v(" "),r("div",{staticClass:"col-md"},[r("advanced-two-column-list-card",{attrs:{header:t.$t("Languages"),items:t.languages?t.languages:[],limit:5,loading:!t.languages,error:t.languagesError}})],1)]),t._v(" "),r("time-bar-chart",{staticClass:"mb-3",attrs:{title:t.$t("New Donors registered"),data:t.reportApi.fetchDonorRegistrations,"date-from":t.dateRange.from,"date-to":t.dateRange.to,granularity:t.dateRange.granularity}}),t._v(" "),r("h2",[t._v("\n        "+t._s(t.$t("Donations"))+"\n    ")]),t._v(" "),r("time-bar-chart",{staticClass:"mb-3",attrs:{title:t.$t("Donations made"),data:t.reportApi.fetchDonationRegistrations,"date-from":t.dateRange.from,"date-to":t.dateRange.to,granularity:t.dateRange.granularity}}),t._v(" "),r("time-bar-chart",{staticClass:"mb-3",attrs:{title:t.$t("Total donations made"),data:t.reportApi.fetchDonationRegistrations,"date-from":t.dateRange.from,"date-to":t.dateRange.to,granularity:t.dateRange.granularity,cumulative:!0}}),t._v(" "),r("b-row",[r("b-col",{attrs:{md:""}},[r("doughnut-chart-table-distribution-widget",{attrs:{title:t.$t("Currencies"),data:t.reportApi.fechCurrencyDistribution}})],1),t._v(" "),r("b-col",{attrs:{md:""}},[r("doughnut-chart-table-distribution-widget",{attrs:{title:t.$t("Channels"),data:t.reportApi.fetchChannelDistribution}})],1)],1)],1)}),[],!1,null,null,null).exports},1383:(t,e,r)=>{"use strict";r.r(e),r.d(e,{default:()=>d});var n=r(7757),a=r.n(n),i=r(381),s=r.n(i),o=r(6301);function u(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function c(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){u(i,n,a,s,o,"next",t)}function o(t){u(i,n,a,s,o,"throw",t)}s(void 0)}))}}const l={components:{},data:function(){return{dailyFields:[{key:"day",label:this.$t("Date")},{key:"visitors",label:this.$t("Visitors"),class:"text-right"},{key:"participants",label:this.$t("Participants"),class:"text-right"},{key:"staff",label:this.$t("Volunteers / Staff"),class:"text-right"},{key:"external",label:this.$t("External visitors"),class:"text-right"},{key:"total",label:this.$t("Total"),class:"text-right"}],monthlyFields:[{key:"date",label:this.$t("Date"),formatter:function(t,e,r){return s()({year:r.year,month:r.month-1}).format("MMMM YYYY")}},{key:"visitors",label:this.$t("Visitors"),class:"text-right"},{key:"participants",label:this.$t("Participants"),class:"text-right"},{key:"staff",label:this.$t("Volunteers / Staff"),class:"text-right"},{key:"external",label:this.$t("External visitors"),class:"text-right"},{key:"total",label:this.$t("Total"),class:"text-right"}]}},methods:{dailyitemProvider:function(t){return c(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,o.Z.dailyVisitors();case 2:return e=t.sent,t.abrupt("return",e||[]);case 4:case"end":return t.stop()}}),t)})))()},monthlyItemProvider:function(t){return c(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,o.Z.monthlyVisitors();case 2:return e=t.sent,t.abrupt("return",e||[]);case 4:case"end":return t.stop()}}),t)})))()}}};const d=(0,r(1900).Z)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("h3",[t._v(t._s(t.$t("Visitors by day")))]),t._v(" "),r("b-table",{attrs:{items:t.dailyitemProvider,fields:t.dailyFields,hover:"",responsive:"","show-empty":!0,"empty-text":t.$t("No data registered."),caption:t.$t("Showing the latest :days active days.",{days:30}),"tbody-class":"bg-white","thead-class":"bg-white"}},[r("div",{staticClass:"text-center my-2",attrs:{slot:"table-busy"},slot:"table-busy"},[r("b-spinner",{staticClass:"align-middle"}),t._v(" "),r("strong",[t._v(t._s(t.$t("Loading...")))])],1)]),t._v(" "),r("h3",[t._v(t._s(t.$t("Visitors by month")))]),t._v(" "),r("b-table",{staticClass:"bg-white",attrs:{items:t.monthlyItemProvider,fields:t.monthlyFields,hover:"",responsive:"","show-empty":!0,"empty-text":t.$t("No data registered.")}},[r("div",{staticClass:"text-center my-2",attrs:{slot:"table-busy"},slot:"table-busy"},[r("b-spinner",{staticClass:"align-middle"}),t._v(" "),r("strong",[t._v(t._s(t.$t("Loading...")))])],1)])],1)}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=reports.js.map