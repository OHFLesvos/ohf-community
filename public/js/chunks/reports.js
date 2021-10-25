"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[992],{6301:(t,e,r)=>{r.d(e,{Z:()=>u});var n=r(7757),a=r.n(n),i=r(892);function s(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function o(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function o(t){s(i,n,a,o,u,"next",t)}function u(t){s(i,n,a,o,u,"throw",t)}o(void 0)}))}}const u={listCurrent:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,i.BC)("api.visitors.listCurrent",t),e.next=3,i.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},checkin:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,i.BC)("api.visitors.checkin"),e.next=3,i.hi.post(r,t);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},checkout:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,i.BC)("api.visitors.checkout",t),e.next=3,i.hi.put(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},checkoutAll:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.BC)("api.visitors.checkoutAll"),t.next=3,i.hi.post(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},dailyVisitors:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.BC)("api.visitors.dailyVisitors"),t.next=3,i.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},monthlyVisitors:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.BC)("api.visitors.monthlyVisitors"),t.next=3,i.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()}}},1376:(t,e,r)=>{r.d(e,{Z:()=>f});var n=r(7757),a=r.n(n),i=r(3447),s=r.n(i),o=r(7297),u=r(9523),c=r.n(u);function l(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function d(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){l(i,n,a,s,o,"next",t)}function o(t){l(i,n,a,s,o,"throw",t)}s(void 0)}))}}r(7432).Chart.plugins.unregister(c());const h={extends:o.$I,props:{title:{type:String,required:!0},data:{type:[Function,Object],required:!0},limit:{type:Number,required:!1,default:12},hideLegend:Boolean},mounted:function(){var t=this;return d(a().mark((function e(){return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.addPlugin(c()),t.loadData();case 2:case"end":return e.stop()}}),e)})))()},methods:{loadData:function(){var t=this;return d(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(e.prev=0,"function"!=typeof t.data){e.next=7;break}return e.next=4,t.data();case 4:r=e.sent,e.next=8;break;case 7:r=t.data;case 8:t.renderChart(t.getChartData(r),t.getOptions()),e.next=14;break;case 11:e.prev=11,e.t0=e.catch(0),console.error(e.t0);case 14:case"end":return e.stop()}}),e,null,[[0,11]])})))()},getChartData:function(t){var e=Object.keys(t);if(0==e.length)return this.noDataData();var r=Object.values(t);if(r.length>this.limit){var n=r.slice(this.limit-1).reduce((function(t,e){return t+e}),0);(r=r.slice(0,this.limit-1)).push(n),(e=e.slice(0,this.limit-1)).push(this.$t("Others"))}var a=s()("tol",Math.min(r.length,this.limit));return{labels:e,datasets:[{data:r,backgroundColor:Array(a.length).fill().map((function(t,e){return"#"+a[e%a.length]}))}]}},noDataData:function(){return{labels:[this.$t("No data")],datasets:[{data:[1],datalabels:{color:"#000000"}}]}},getOptions:function(){return{title:{display:!0,text:this.title},legend:{display:!this.hideLegend,position:"bottom"},responsive:!0,maintainAspectRatio:!1,animation:{duration:500},tooltips:{callbacks:{label:this.toolTipLabel,title:this.toolTipTitle}},plugins:{datalabels:{color:"#ffffff",textAlign:"center",formatter:this.dataLabelFormat}}}},toolTipLabel:function(t,e){var r=e.datasets[t.datasetIndex],n=r._meta[Object.keys(r._meta)[0]].total,a=r.data[t.index],i=parseFloat((a/n*100).toFixed(1));return"".concat(this.numberFormat(a)," (").concat(i,"%)")},toolTipTitle:function(t,e){return e.labels[t[0].index]},dataLabelFormat:function(t,e){var r=e.chart.data.datasets[0],n=r._meta[Object.keys(r._meta)[0]].total,a=r.data[e.dataIndex],i=e.chart.data.labels[e.dataIndex],s=parseFloat((a/n*100).toFixed(1));return this.hideLegend?"".concat(i,"\n(").concat(s,"%)"):"".concat(s,"%")}}};const f=(0,r(1900).Z)(h,undefined,undefined,!1,null,null,null).exports},5043:(t,e,r)=>{r.r(e),r.d(e,{default:()=>Z});var n=r(7757),a=r.n(n);const i={props:{header:{type:String},headerAddon:{required:!1,type:String},items:{required:!0,type:Array},loading:Boolean,error:{required:!1,type:String}}};var s=r(1900);const o=(0,s.Z)(i,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-4",attrs:{"no-body":!t.loading,header:t.loading?t.header:null}},[!t.loading&&t.header?r("b-card-header",{attrs:{"header-class":"d-flex justify-content-between align-items-center"}},[r("span",[t._v(t._s(t.header))]),t._v(" "),t.headerAddon?r("small",[t._v(t._s(t.headerAddon))]):t._e()]):t._e(),t._v(" "),t.error?r("b-card-text",[t.error?r("em",{staticClass:"text-danger"},[t._v(t._s(t.error))]):t._e()]):t.loading?r("b-card-text",[r("em",[t._v(t._s(t.$t("Loading...")))])]):[r("b-list-group",{attrs:{flush:""}},t._l(t.items,(function(e){return r("b-list-group-item",{key:e.name,staticClass:"d-flex justify-content-between"},[r("span",[t._v(t._s(e.name))]),t._v(" "),r("span",[t._v(t._s(e.value))])])})),1)]],2)}),[],!1,null,null,null).exports;const u={props:{header:{required:!0,type:String},items:{required:!0,type:Array},limit:{requireD:!1,type:Number,default:10},loading:Boolean,error:{required:!1,type:String}},data:function(){return{topTen:!0}},computed:{totalAmount:function(){return this.items.reduce((function(t,e){return t+e.amount}),0)},selectedItems:function(){return this.topTen?this.items.slice(0,this.limit):this.items},unselectedItemsAmount:function(){return this.topTen?this.items.slice(this.limit).reduce((function(t,e){return t+e.amount}),0):0}}};const c=(0,s.Z)(u,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-4",attrs:{"no-body":!t.loading&&t.items.length>0,header:t.loading||0==t.items.length?t.header:null}},[t.error?r("b-card-text",[t.error?r("em",{staticClass:"text-danger"},[t._v(t._s(t.error))]):t._e()]):t.loading?r("b-card-text",[r("em",[t._v(t._s(t.$t("Loading...")))])]):0==t.items.length?r("b-card-text",[r("em",[t._v(t._s(t.$t("No data registered.")))])]):[r("b-card-header",{attrs:{"header-class":"d-flex justify-content-between"}},[r("span",[t._v(t._s(t.header))]),t._v(" "),t.items.length>this.limit?r("a",{attrs:{href:"javascript:;"},on:{click:function(e){t.topTen=!t.topTen}}},[t._v("\n                "+t._s(t.topTen?t.$t("Show all :num",{num:t.items.length}):t.$t("Show Top :num",{num:t.limit}))+"\n            ")]):t._e()]),t._v(" "),r("b-list-group",{attrs:{flush:""}},[t._l(t.selectedItems,(function(e){return r("b-list-group-item",{key:e.name,staticClass:"d-flex justify-content-between align-items-center"},[r("span",[t._v(t._s(e.name))]),t._v(" "),r("span",[t._v("\n                    "+t._s(e.amount)+"  \n                    "),r("small",{staticClass:"text-muted"},[t._v(t._s(t.roundWithDecimals(e.amount/t.totalAmount*100,1))+"%")])])])})),t._v(" "),t.topTen&&t.items.length>t.limit?r("b-list-group-item",{staticClass:"d-flex justify-content-between align-items-center",attrs:{href:"javascript:;"},on:{click:function(e){t.topTen=!t.topTen}}},[r("em",[t._v(t._s(t.$t("Others")))]),t._v(" "),r("span",[t._v("\n                    "+t._s(t.unselectedItemsAmount)+"  \n                    "),r("small",{staticClass:"text-muted"},[t._v(t._s(t.roundWithDecimals(t.unselectedItemsAmount/t.totalAmount*100,1))+"%")])])]):t._e()],2)]],2)}),[],!1,null,null,null).exports;var l=r(381),d=r.n(l),h=r(1581),f=r(7297),p=f.tA.reactiveProp;const m={extends:f.$Q,mixins:[p],props:{options:{type:Object,required:!0}},mounted:function(){this.renderChart(this.chartData,this.options)}};const v=(0,s.Z)(m,undefined,undefined,!1,null,null,null).exports;var g=f.tA.reactiveProp;const y={extends:f.x1,mixins:[g],props:{options:{type:Object,required:!0}},mounted:function(){this.renderChart(this.chartData,this.options)}};const b=(0,s.Z)(y,undefined,undefined,!1,null,null,null).exports;var x=r(1304),_=r.n(x);function w(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==r)return;var n,a,i=[],s=!0,o=!1;try{for(r=r.call(t);!(s=(n=r.next()).done)&&(i.push(n.value),!e||i.length!==e);s=!0);}catch(t){o=!0,a=t}finally{try{s||null==r.return||r.return()}finally{if(o)throw a}}return i}(t,e)||C(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function C(t,e){if(t){if("string"==typeof t)return k(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?k(t,e):void 0}}function k(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}function $(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}const D={components:{ReactiveBarChart:v,ReactiveLineChart:b},props:{title:{type:String,required:!0},data:{type:[Function,Object],required:!1},error:{type:String,required:!1},dateFrom:{type:String,required:!0},dateTo:{type:String,required:!0},granularity:{type:String,required:!1,value:"days"},height:{type:Number,required:!1,default:350},cumulative:Boolean},data:function(){return{loaded:!1,asyncError:null,chartData:{},units:new Map}},computed:{options:function(){var t,e,r,n=this;switch(this.granularity){case"years":t="year",e="YYYY",r="YYYY";break;case"months":t="month",e="MMMM YYYY",r="YYYY-MM";break;case"weeks":t="week",e="[W]WW GGGG",r=void 0;break;default:t="day",e="dddd, LL",r="YYYY-MM-DD"}return{title:{display:!0,text:this.title},legend:{display:!0,position:"bottom"},scales:{xAxes:[{display:!0,type:"time",time:{tooltipFormat:e,unit:t,parser:r,minUnit:"day",isoWeekday:!0,displayFormats:{day:"ll",week:"[W]WW GGGG"}},ticks:{min:this.dateFrom,max:this.dateTo},gridLines:{display:!0},scaleLabel:{display:!0,labelString:this.$t("Date")}}],yAxes:this.yAxes()},responsive:!0,maintainAspectRatio:!1,animation:{duration:500},tooltips:{callbacks:{label:function(t,e){var r=e.datasets[t.datasetIndex].label||"";return"".concat(r,": ").concat(n.numberFormat(t.yLabel))}}}}}},watch:{granularity:function(){this.loadData()},dateFrom:function(){this.loadData()},dateTo:function(){this.loadData()},data:function(){this.loadData()}},mounted:function(){d().locale(this.$i18n.locale),this.loadData()},methods:{loadData:function(){var t,e=this;return(t=a().mark((function t(){var r;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(e.asyncError=null,e.loaded=!1,!e.data){t.next=18;break}if(t.prev=3,"function"!=typeof e.data){t.next=10;break}return t.next=7,e.data(e.granularity,e.dateFrom,e.dateTo);case 7:r=t.sent,t.next=11;break;case 10:r=e.data;case 11:e.chartData=e.chartDataFromResponse(r),e.loaded=!0,t.next=18;break;case 15:t.prev=15,t.t0=t.catch(3),e.asyncError=t.t0;case 18:case"end":return t.stop()}}),t,null,[[3,15]])})),function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){$(i,n,a,s,o,"next",t)}function o(t){$(i,n,a,s,o,"throw",t)}s(void 0)}))})()},chartDataFromResponse:function(t){var e=this,r={labels:t.labels,datasets:[]},n=new Map;return t.datasets.forEach((function(t){var a=_()(t.unit),i=t.data;if(e.cumulative)for(var s=0,o=0;o<i.length;o++)i[o]&&(i[o]+=s,s=i[o]);r.datasets.push({label:t.label,data:i,yAxisID:a}),n.set(a,t.unit)})),this.units=n,(0,h.qp)(r.datasets),r},yAxes:function(){var t,e=[],r=0,n=function(t,e){var r="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!r){if(Array.isArray(t)||(r=C(t))||e&&t&&"number"==typeof t.length){r&&(t=r);var n=0,a=function(){};return{s:a,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,s=!0,o=!1;return{s:function(){r=r.call(t)},n:function(){var t=r.next();return s=t.done,t},e:function(t){o=!0,i=t},f:function(){try{s||null==r.return||r.return()}finally{if(o)throw i}}}}(this.units);try{for(n.s();!(t=n.n()).done;){var a=w(t.value,2),i=a[0],s=a[1];e.push({display:!0,id:i,position:r++%2==1?"right":"left",gridLines:{display:!0},scaleLabel:{display:!0,labelString:s},ticks:{suggestedMin:0,precision:0,callback:this.numberFormat}})}}catch(t){n.e(t)}finally{n.f()}return e}}};const A=(0,s.Z)(D,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return t.asyncError||t.error||!t.loaded?r("div",{staticClass:"d-flex border",style:"height: "+t.height+"px"},[r("p",{staticClass:"justify-content-center align-self-center text-center w-100"},[t.asyncError||t.error?r("em",{staticClass:"text-danger"},[t._v(t._s(t.asyncError)+" "+t._s(t.error))]):r("em",[t._v(t._s(t.$t("Loading...")))])])]):r(t.cumulative?"reactive-line-chart":"reactive-bar-chart",{tag:"component",staticClass:"border",attrs:{"chart-data":t.chartData,options:t.options,height:t.height}})}),[],!1,null,null,null).exports;function T(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}const R={components:{DoughnutChart:r(1376).Z},props:{title:{required:!0,type:String},data:{type:[Function,Object],required:!0}},data:function(){return{myData:null}},computed:{total:function(){return Object.values(this.myData).reduce((function(t,e){return t+e}),0)}},created:function(){var t,e=this;return(t=a().mark((function t(){return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if("function"!=typeof e.data){t.next=6;break}return t.next=3,e.data();case 3:e.myData=t.sent,t.next=7;break;case 6:e.myData=e.data;case 7:case"end":return t.stop()}}),t)})),function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){T(i,n,a,s,o,"next",t)}function o(t){T(i,n,a,s,o,"throw",t)}s(void 0)}))})()}};const E=(0,s.Z)(R,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-4",attrs:{header:t.title,"no-body":""}},[t.myData?[r("b-card-body",[r("doughnut-chart",{staticClass:"mb-2",attrs:{title:t.title,data:t.myData,height:300}})],1),t._v(" "),Object.keys(t.myData).length>0?r("b-table-simple",{staticClass:"my-0",attrs:{responsive:"",small:""}},t._l(t.myData,(function(e,n){return r("b-tr",{key:n},[r("b-td",{staticClass:"fit"},[t._v("\n                    "+t._s(n)+"\n                ")]),t._v(" "),r("b-td",{staticClass:"align-middle d-none d-sm-table-cell"},[r("b-progress",{attrs:{value:e,max:t.total,"show-value":!1,variant:"secondary"}})],1),t._v(" "),r("b-td",{staticClass:"fit text-right"},[t._v("\n                    "+t._s(t.percentValue(e,t.total))+"%\n                ")]),t._v(" "),r("b-td",{staticClass:"fit text-right d-none d-sm-table-cell"},[t._v("\n                    "+t._s(t._f("numberFormat")(e))+"\n                ")])],1)})),1):t._e()]:r("b-card-body",[t._v("\n        "+t._s(t.$t("Loading..."))+"\n    ")])],2)}),[],!1,null,null,null).exports;function j(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function O(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?j(Object(r),!0).forEach((function(e){S(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):j(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function S(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}const F={props:{value:{type:Object,required:!0,validator:function(t){return t.from&&t.to}},noGranularity:Boolean,min:{type:String,required:!1,default:null},max:{type:String,required:!1,default:function(){return d()().format(d().HTML5_FMT.DATE)}}},data:function(){return{originalValues:O({},this.value),from:this.value.from,to:this.value.to,granularity:this.value.granularity,granularities:[{value:"days",text:(0,h.zf)(this.$t("days"))},{value:"weeks",text:(0,h.zf)(this.$t("Weeks"))},{value:"months",text:(0,h.zf)(this.$t("Months"))},{value:"years",text:(0,h.zf)(this.$t("Years"))}]}},watch:{from:function(){this.emitChange()},to:function(){this.emitChange()},granularity:function(){this.emitChange()}},methods:{emitChange:function(){this.from&&this.to&&this.$emit("input",{from:this.from,to:this.to,granularity:this.granularity})},reset:function(){this.from=this.originalValues.from,this.to=this.originalValues.to,this.granularity=this.originalValues.granularity}}};const P=(0,s.Z)(F,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"form-row"},[r("div",{class:[t.noGranularity?"col-md":"col-md-8"]},[r("b-input-group",{staticClass:"mb-2",attrs:{prepend:t.$t("Date range")}},[r("b-form-datepicker",{attrs:{placeholder:t.$t("From"),min:t.min,max:t.to,"date-format-options":{year:"numeric",month:"short",day:"numeric",weekday:"short"}},model:{value:t.from,callback:function(e){t.from=e},expression:"from"}}),t._v(" "),r("div",{staticClass:"input-group-prepend input-group-append"},[r("span",{staticClass:"input-group-text"},[t._v(":")])]),t._v(" "),r("b-form-datepicker",{attrs:{placeholder:t.$t("To"),min:t.from,max:t.max,"date-format-options":{year:"numeric",month:"short",day:"numeric",weekday:"short"}},model:{value:t.to,callback:function(e){t.to=e},expression:"to"}})],1)],1),t._v(" "),t.noGranularity?t._e():r("div",{staticClass:"col-md"},[r("b-input-group",{staticClass:"mb-2",attrs:{prepend:t.$t("Granularity")}},[r("b-form-select",{attrs:{options:t.granularities},model:{value:t.granularity,callback:function(e){t.granularity=e},expression:"granularity"}})],1)],1),t._v(" "),r("div",{staticClass:"col-auto"},[r("b-button",{staticClass:"mb-2",attrs:{variant:"secondary"},on:{click:function(e){return t.reset()}}},[r("font-awesome-icon",{attrs:{icon:"undo"}})],1)],1)])}),[],!1,null,null,null).exports;var L=r(892);function q(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function Y(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){q(i,n,a,s,o,"next",t)}function o(t){q(i,n,a,s,o,"throw",t)}s(void 0)}))}}const M={getCount:function(t){return Y(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="".concat((0,L.BC)("api.fundraising.report.donors.count"),"?date=").concat(t),e.next=3,L.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},getCountries:function(t){return Y(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="".concat((0,L.BC)("api.fundraising.report.donors.countries"),"?date=").concat(t),e.next=3,L.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},getLanguages:function(t){return Y(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="".concat((0,L.BC)("api.fundraising.report.donors.languages"),"?date=").concat(t),e.next=3,L.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},fetchDonorRegistrations:function(t,e,r){return Y(a().mark((function n(){var i,s;return a().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return i={granularity:t,from:e,to:r},s=(0,L.BC)("api.fundraising.report.donors.registrations",i),n.next=4,L.hi.get(s);case 4:return n.abrupt("return",n.sent);case 5:case"end":return n.stop()}}),n)})))()},fetchDonationRegistrations:function(t,e,r){return Y(a().mark((function n(){var i,s;return a().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return i={granularity:t,from:e,to:r},s=(0,L.BC)("api.fundraising.report.donations.registrations",i),n.next=4,L.hi.get(s);case 4:return n.abrupt("return",n.sent);case 5:case"end":return n.stop()}}),n)})))()},fechCurrencyDistribution:function(){return Y(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,L.BC)("api.fundraising.report.donations.currencies"),t.next=3,L.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},fetchChannelDistribution:function(){return Y(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,L.BC)("api.fundraising.report.donations.channels"),t.next=3,L.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()}};function B(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function V(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){B(i,n,a,s,o,"next",t)}function o(t){B(i,n,a,s,o,"throw",t)}s(void 0)}))}}const I={title:function(){return this.$t("Report")+": "+this.$t("Fundraising")},components:{SimpleTwoColumnListCard:o,AdvancedTwoColumnListCard:c,TimeBarChart:A,DoughnutChartTableDistributionWidget:E,DateRangeSelect:P},data:function(){return{firstDonorRegistration:null,count:null,countError:null,countries:null,countriesError:null,languages:null,languagesError:null,donationRegistrations:null,donationRegistrationsError:null,dateRange:{from:d()().subtract(3,"months").format(d().HTML5_FMT.DATE),to:d()().format(d().HTML5_FMT.DATE),granularity:"days"},reportApi:M}},watch:{dateRange:function(){this.loadData()}},created:function(){this.loadData()},methods:{loadData:function(){this.loadCount(),this.loadCountries(),this.loadLanguages()},loadCount:function(){var t=this;return V(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.countError=null,e.prev=1,e.next=4,M.getCount(t.dateRange.to);case 4:r=e.sent,t.count=t.mapCountData(r),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),t.countError=e.t0;case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()},mapCountData:function(t){return this.firstDonorRegistration=this.dateFormat(t.first),[{name:this.$t("Total"),value:t.total},{name:this.$t("Individual persons"),value:t.persons},{name:this.$t("Companies"),value:t.companies},{name:this.$t("with registered address"),value:t.with_address},{name:this.$t("with registered email address"),value:t.with_email},{name:this.$t("with registered phone number"),value:t.with_phone}]},loadCountries:function(){var t=this;return V(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.countriesError=null,e.prev=1,e.next=4,M.getCountries(t.dateRange.to);case 4:r=e.sent,t.countries=r,e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),t.countriesError=e.t0;case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()},loadLanguages:function(){var t=this;return V(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.languagesError=null,e.prev=1,e.next=4,M.getLanguages(t.dateRange.to);case 4:r=e.sent,t.languages=r,e.next=11;break;case 8:e.prev=8,e.t0=e.catch(1),t.languagesError=e.t0;case 11:case"end":return e.stop()}}),e,null,[[1,8]])})))()}}};const Z=(0,s.Z)(I,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("date-range-select",{model:{value:t.dateRange,callback:function(e){t.dateRange=e},expression:"dateRange"}}),t._v(" "),r("h2",[t._v("\n        "+t._s(t.$t("Donors"))+"\n    ")]),t._v(" "),r("div",{staticClass:"row"},[r("div",{staticClass:"col-md"},[r("simple-two-column-list-card",{attrs:{header:t.$t("Registered donors"),headerAddon:t.$t("since :date",{date:t.firstDonorRegistration}),items:t.count?t.count:[],loading:!t.count,error:t.countError}})],1),t._v(" "),r("div",{staticClass:"col-md"},[r("advanced-two-column-list-card",{attrs:{header:t.$t("Countries"),items:t.countries?t.countries:[],limit:5,loading:!t.countries,error:t.countriesError}})],1),t._v(" "),r("div",{staticClass:"col-md"},[r("advanced-two-column-list-card",{attrs:{header:t.$t("Languages"),items:t.languages?t.languages:[],limit:5,loading:!t.languages,error:t.languagesError}})],1)]),t._v(" "),r("time-bar-chart",{staticClass:"mb-3",attrs:{title:t.$t("New Donors registered"),data:t.reportApi.fetchDonorRegistrations,"date-from":t.dateRange.from,"date-to":t.dateRange.to,granularity:t.dateRange.granularity}}),t._v(" "),r("h2",[t._v("\n        "+t._s(t.$t("Donations"))+"\n    ")]),t._v(" "),r("time-bar-chart",{staticClass:"mb-3",attrs:{title:t.$t("Donations made"),data:t.reportApi.fetchDonationRegistrations,"date-from":t.dateRange.from,"date-to":t.dateRange.to,granularity:t.dateRange.granularity}}),t._v(" "),r("time-bar-chart",{staticClass:"mb-3",attrs:{title:t.$t("Total donations made"),data:t.reportApi.fetchDonationRegistrations,"date-from":t.dateRange.from,"date-to":t.dateRange.to,granularity:t.dateRange.granularity,cumulative:!0}}),t._v(" "),r("b-row",[r("b-col",{attrs:{md:""}},[r("doughnut-chart-table-distribution-widget",{attrs:{title:t.$t("Currencies"),data:t.reportApi.fechCurrencyDistribution}})],1),t._v(" "),r("b-col",{attrs:{md:""}},[r("doughnut-chart-table-distribution-widget",{attrs:{title:t.$t("Channels"),data:t.reportApi.fetchChannelDistribution}})],1)],1)],1)}),[],!1,null,null,null).exports},4449:(t,e,r)=>{r.r(e),r.d(e,{default:()=>a});const n={title:function(){return this.$t("Reports")},data:function(){return{reports:[{label:this.$t("Community Volunteers"),to:{name:"cmtyvol.report"},icon:"chart-bar",show:this.can("view-community-volunteer-reports")},{label:this.$t("Fundraising"),to:{name:"reports.fundraising.donations"},icon:"donate",show:this.can("view-fundraising-reports")},{label:this.$t("Visitor check-ins"),to:{name:"reports.visitors.checkins"},icon:"door-open",show:this.can("register-visitors")}]}}};const a=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-container",[r("b-list-group",{staticClass:"shadow-sm"},t._l(t.reports.filter((function(t){return t.show})),(function(e,n){return r("b-list-group-item",{key:n,attrs:{to:e.to}},[r("font-awesome-icon",{attrs:{icon:e.icon}}),t._v("\n            "+t._s(e.label)+"\n        ")],1)})),1)],1)}),[],!1,null,null,null).exports},952:(t,e,r)=>{r.r(e),r.d(e,{default:()=>d});var n=r(7757),a=r.n(n),i=r(381),s=r.n(i),o=r(6301);function u(t,e,r,n,a,i,s){try{var o=t[i](s),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function c(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var i=t.apply(e,r);function s(t){u(i,n,a,s,o,"next",t)}function o(t){u(i,n,a,s,o,"throw",t)}s(void 0)}))}}const l={title:function(){return this.$t("Report")+": "+this.$t("Visitor check-ins")},data:function(){return{dailyFields:[{key:"day",label:this.$t("Date")},{key:"visitors",label:this.$t("Visitors"),class:"text-right"},{key:"participants",label:this.$t("Participants"),class:"text-right"},{key:"staff",label:this.$t("Volunteers / Staff"),class:"text-right"},{key:"external",label:this.$t("External visitors"),class:"text-right"},{key:"total",label:this.$t("Total"),class:"text-right"}],monthlyFields:[{key:"date",label:this.$t("Date"),formatter:function(t,e,r){return s()({year:r.year,month:r.month-1}).format("MMMM YYYY")}},{key:"visitors",label:this.$t("Visitors"),class:"text-right"},{key:"participants",label:this.$t("Participants"),class:"text-right"},{key:"staff",label:this.$t("Volunteers / Staff"),class:"text-right"},{key:"external",label:this.$t("External visitors"),class:"text-right"},{key:"total",label:this.$t("Total"),class:"text-right"}]}},methods:{dailyitemProvider:function(t){return c(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,o.Z.dailyVisitors();case 2:return e=t.sent,t.abrupt("return",e||[]);case 4:case"end":return t.stop()}}),t)})))()},monthlyItemProvider:function(t){return c(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,o.Z.monthlyVisitors();case 2:return e=t.sent,t.abrupt("return",e||[]);case 4:case"end":return t.stop()}}),t)})))()}}};const d=(0,r(1900).Z)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("h3",[t._v(t._s(t.$t("Visitors by day")))]),t._v(" "),r("b-table",{attrs:{items:t.dailyitemProvider,fields:t.dailyFields,hover:"",responsive:"","show-empty":!0,"empty-text":t.$t("No data registered."),caption:t.$t("Showing the latest :days active days.",{days:30}),"tbody-class":"bg-white","thead-class":"bg-white"}},[r("div",{staticClass:"text-center my-2",attrs:{slot:"table-busy"},slot:"table-busy"},[r("b-spinner",{staticClass:"align-middle"}),t._v(" "),r("strong",[t._v(t._s(t.$t("Loading...")))])],1)]),t._v(" "),r("h3",[t._v(t._s(t.$t("Visitors by month")))]),t._v(" "),r("b-table",{staticClass:"bg-white",attrs:{items:t.monthlyItemProvider,fields:t.monthlyFields,hover:"",responsive:"","show-empty":!0,"empty-text":t.$t("No data registered.")}},[r("div",{staticClass:"text-center my-2",attrs:{slot:"table-busy"},slot:"table-busy"},[r("b-spinner",{staticClass:"align-middle"}),t._v(" "),r("strong",[t._v(t._s(t.$t("Loading...")))])],1)])],1)}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=reports.js.map?id=eee2a937d0f0f9cdbe00