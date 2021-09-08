"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[443],{143:(t,e,r)=>{r.d(e,{Z:()=>u});var n=r(7757),a=r.n(n),s=r(892);function i(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function o(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var s=t.apply(e,r);function o(t){i(s,n,a,o,u,"next",t)}function u(t){i(s,n,a,o,u,"throw",t)}o(void 0)}))}}const u={list:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,s.BC)("api.cmtyvol.index",t),e.next=3,s.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},ageDistribution:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,s.BC)("api.cmtyvol.ageDistribution"),t.next=3,s.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},genderDistribution:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,s.BC)("api.cmtyvol.genderDistribution"),t.next=3,s.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},nationalityDistribution:function(){return o(a().mark((function t(){var e;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,s.BC)("api.cmtyvol.nationalityDistribution"),t.next=3,s.hi.get(e);case 3:return t.abrupt("return",t.sent);case 4:case"end":return t.stop()}}),t)})))()},listComments:function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,s.BC)("api.cmtyvol.comments.index",t),e.next=3,s.hi.get(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()},storeComment:function(t,e){return o(a().mark((function r(){var n;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return n=(0,s.BC)("api.cmtyvol.comments.store",t),r.next=3,s.hi.post(n,e);case 3:return r.abrupt("return",r.sent);case 4:case"end":return r.stop()}}),r)})))()}}},2851:(t,e,r)=>{r.d(e,{Z:()=>a});const n={props:["value"]};const a=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-alert",{attrs:{variant:"danger",show:null!=t.value}},[r("b-row",{attrs:{"align-v":"center"}},[r("b-col",[r("font-awesome-icon",{attrs:{icon:"times-circle"}}),t._v("\n            "+t._s(t.$t("Error: {err}",{err:t.value}))+"\n        ")],1),t._v(" "),r("b-col",{attrs:{sm:"auto"}},[r("b-button",{staticClass:"float-right",attrs:{variant:"danger",size:"sm"},on:{click:function(e){return t.$emit("retry")}}},[r("font-awesome-icon",{attrs:{icon:"redo"}}),t._v("\n                "+t._s(t.$t("Retry"))+"\n            ")],1)],1)],1)],1)}),[],!1,null,null,null).exports},1376:(t,e,r)=>{r.d(e,{Z:()=>p});var n=r(7757),a=r.n(n),s=r(3447),i=r.n(s),o=r(7297),u=r(9523),c=r.n(u);function l(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function d(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var s=t.apply(e,r);function i(t){l(s,n,a,i,o,"next",t)}function o(t){l(s,n,a,i,o,"throw",t)}i(void 0)}))}}r(7432).Chart.plugins.unregister(c());const m={extends:o.$I,props:{title:{type:String,required:!0},data:{type:[Function,Object],required:!0},limit:{type:Number,required:!1,default:12},hideLegend:Boolean},mounted:function(){var t=this;return d(a().mark((function e(){return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.addPlugin(c()),t.loadData();case 2:case"end":return e.stop()}}),e)})))()},methods:{loadData:function(){var t=this;return d(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(e.prev=0,"function"!=typeof t.data){e.next=7;break}return e.next=4,t.data();case 4:r=e.sent,e.next=8;break;case 7:r=t.data;case 8:t.renderChart(t.getChartData(r),t.getOptions()),e.next=14;break;case 11:e.prev=11,e.t0=e.catch(0),console.error(e.t0);case 14:case"end":return e.stop()}}),e,null,[[0,11]])})))()},getChartData:function(t){var e=Object.keys(t);if(0==e.length)return this.noDataData();var r=Object.values(t);if(r.length>this.limit){var n=r.slice(this.limit-1).reduce((function(t,e){return t+e}),0);(r=r.slice(0,this.limit-1)).push(n),(e=e.slice(0,this.limit-1)).push(this.$t("Others"))}var a=i()("tol",Math.min(r.length,this.limit));return{labels:e,datasets:[{data:r,backgroundColor:Array(a.length).fill().map((function(t,e){return"#"+a[e%a.length]}))}]}},noDataData:function(){return{labels:[this.$t("No data")],datasets:[{data:[1],datalabels:{color:"#000000"}}]}},getOptions:function(){return{title:{display:!0,text:this.title},legend:{display:!this.hideLegend,position:"bottom"},responsive:!0,maintainAspectRatio:!1,animation:{duration:500},tooltips:{callbacks:{label:this.toolTipLabel,title:this.toolTipTitle}},plugins:{datalabels:{color:"#ffffff",textAlign:"center",formatter:this.dataLabelFormat}}}},toolTipLabel:function(t,e){var r=e.datasets[t.datasetIndex],n=r._meta[Object.keys(r._meta)[0]].total,a=r.data[t.index],s=parseFloat((a/n*100).toFixed(1));return"".concat(this.numberFormat(a)," (").concat(s,"%)")},toolTipTitle:function(t,e){return e.labels[t[0].index]},dataLabelFormat:function(t,e){var r=e.chart.data.datasets[0],n=r._meta[Object.keys(r._meta)[0]].total,a=r.data[e.dataIndex],s=e.chart.data.labels[e.dataIndex],i=parseFloat((a/n*100).toFixed(1));return this.hideLegend?"".concat(s,"\n(").concat(i,"%)"):"".concat(i,"%")}}};const p=(0,r(1900).Z)(m,undefined,undefined,!1,null,null,null).exports},2633:(t,e,r)=>{r.d(e,{Z:()=>_});var n=r(7757),a=r.n(n),s=r(892);function i(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function o(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var s=t.apply(e,r);function o(t){i(s,n,a,o,u,"next",t)}function u(t){i(s,n,a,o,u,"throw",t)}o(void 0)}))}}const u=function(t,e){return o(a().mark((function r(){var n;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return n=(0,s.BC)("api.comments.update",t),r.next=3,s.hi.put(n,e);case 3:return r.abrupt("return",r.sent);case 4:case"end":return r.stop()}}),r)})))()},c=function(t){return o(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,s.BC)("api.comments.destroy",t),e.next=3,s.hi.delete(r);case 3:return e.abrupt("return",e.sent);case 4:case"end":return e.stop()}}),e)})))()};const l={props:{comment:{type:Object,required:!1},disabled:Boolean},data:function(){return{content:this.comment?this.comment.content:""}},methods:{submit:function(){this.$emit("submit",{content:this.content})},cancel:function(){this.$emit("cancel")}}};var d=r(1900);const m=(0,d.Z)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("form",{on:{submit:function(e){return e.preventDefault(),t.submit()}}},[r("p",[r("b-form-textarea",{attrs:{rows:"3","max-rows":"6",placeholder:t.$t("Add comment"),disabled:t.disabled,autofocus:""},model:{value:t.content,callback:function(e){t.content="string"==typeof e?e.trim():e},expression:"content"}})],1),t._v(" "),r("p",[r("b-button",{attrs:{type:"submit",variant:"primary",disabled:t.disabled||0==t.content.length}},[r("font-awesome-icon",{attrs:{icon:"check"}}),t._v("\n            "+t._s(t.$t("Save"))+"\n        ")],1),t._v(" "),r("b-button",{attrs:{type:"button",variant:"secondary",disabled:t.disabled},on:{click:function(e){return t.cancel()}}},[r("font-awesome-icon",{attrs:{icon:"times"}}),t._v("\n            "+t._s(t.$t("Cancel"))+"\n        ")],1)],1)])}),[],!1,null,null,null).exports;var p=r(7554);const f={components:{Nl2br:r.n(p)()},props:{comment:{required:!0,type:Object},busy:Boolean},data:function(){return{dateFromNow:!0}},computed:{userName:function(){return this.comment.user_name?this.comment.user_name:this.$t("Unknown")},formattedDate:function(){var t=this.toDateString(this.comment.created_at);if(this.comment.created_at!=this.comment.updated_at){var e=this.$t("Updated :time.",{time:this.toDateString(this.comment.updated_at)});t+=" (".concat(e,")")}return t}},methods:{toDateString:function(t){return this.dateFromNow?this.timeFromNow(t):this.dateTimeFormat(t)}}};const v=(0,d.Z)(f,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-3",attrs:{"footer-class":"d-flex justify-content-between align-items-center py-1"},scopedSlots:t._u([{key:"footer",fn:function(){return[r("small",[t.comment.user_url?r("a",{attrs:{href:t.comment.user_url,target:"_blank"}},[t._v(t._s(t.userName))]):r("span",[t._v(t._s(t.userName))]),t._v(",\n            "),r("span",{on:{click:function(e){t.dateFromNow=!t.dateFromNow}}},[t._v("\n                "+t._s(t.formattedDate)+"\n            ")])]),t._v(" "),r("span",[t.comment.can_update?r("b-button",{attrs:{variant:"link",size:"sm",disabled:t.busy},on:{click:function(e){return t.$emit("edit")}}},[r("font-awesome-icon",{attrs:{icon:"pencil-alt"}})],1):t._e(),t._v(" "),t.comment.can_delete?r("b-button",{attrs:{variant:"link",size:"sm",disabled:t.busy},on:{click:function(e){return t.$emit("delete")}}},[r("font-awesome-icon",{attrs:{icon:"trash"}})],1):t._e()],1)]},proxy:!0}])},[r("b-card-text",[r("nl2br",{attrs:{tag:"span",text:t.comment.content}})],1)],1)}),[],!1,null,null,null).exports;var h=r(1581);function b(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function y(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var s=t.apply(e,r);function i(t){b(s,n,a,i,o,"next",t)}function o(t){b(s,n,a,i,o,"throw",t)}i(void 0)}))}}const g={components:{CommentEditor:m,CommentCard:v},props:{apiListMethod:{type:Function,required:!0},apiCreateMethod:{type:Function,required:!1}},data:function(){return{comments:[],editComment:null,editor:!1,error:null,loaded:!1,busy:!1}},mounted:function(){this.loadComments()},watch:{comments:function(t){this.$emit("count",t.length)}},methods:{loadComments:function(){var t=this;return y(a().mark((function e(){var r;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.error=null,e.prev=1,e.next=4,t.apiListMethod();case 4:r=e.sent,t.comments=r.data,t.loaded=!0,e.next=12;break;case 9:e.prev=9,e.t0=e.catch(1),t.error=e.t0;case 12:case"end":return e.stop()}}),e,null,[[1,9]])})))()},openEditor:function(){this.editor=!0},closeEditor:function(){this.editor=!1},addComment:function(t){var e=this;return y(a().mark((function r(){var n;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return e.error=null,e.busy=!0,r.prev=2,r.next=5,e.apiCreateMethod(t);case 5:n=r.sent,e.comments.push(n.data),e.closeEditor(),(0,h.OV)(n.message),r.next=14;break;case 11:r.prev=11,r.t0=r.catch(2),e.error=r.t0;case 14:e.busy=!1;case 15:case"end":return r.stop()}}),r,null,[[2,11]])})))()},updateComment:function(t){var e=this;return y(a().mark((function r(){var n,s;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return e.error=null,e.busy=!0,r.prev=2,r.next=5,u(t.id,t);case 5:n=r.sent,(s=e.comments.findIndex((function(e){return e.id===t.id})))>=0&&e.$set(e.comments,s,n.data),e.editComment=null,(0,h.OV)(n.message),r.next=15;break;case 12:r.prev=12,r.t0=r.catch(2),e.error=r.t0;case 15:e.busy=!1;case 16:case"end":return r.stop()}}),r,null,[[2,12]])})))()},deleteComment:function(t){var e=this;return y(a().mark((function r(){var n,s;return a().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:if(!confirm(e.$t("Really delete this comment?"))){r.next=15;break}return e.busy=!0,r.prev=2,r.next=5,c(t.id);case 5:n=r.sent,(s=e.comments.findIndex((function(e){return e.id===t.id})))>=0&&e.comments.splice(s,1),(0,h.OV)(n.message),r.next=14;break;case 11:r.prev=11,r.t0=r.catch(2),e.error=r.t0;case 14:e.busy=!1;case 15:case"end":return r.stop()}}),r,null,[[2,11]])})))()}}};const _=(0,d.Z)(g,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[t.error?r("b-alert",{attrs:{variant:"danger",show:""}},[t._v("\n        "+t._s(t.error)+"\n    ")]):t._e(),t._v(" "),t.loaded||t.error?t.comments.length>0?t._l(t.comments,(function(e){return r("div",{key:e.id},[t.editComment==e.id?r("comment-editor",{attrs:{comment:e,disabled:t.busy},on:{submit:function(r){return t.updateComment(Object.assign({},e,r))},cancel:function(e){t.editComment=null}}}):r("comment-card",{attrs:{comment:e,busy:t.busy},on:{edit:function(r){t.editComment=e.id,t.editor=!1},delete:function(r){return t.deleteComment(e)}}})],1)})):r("p",[r("em",[t._v(t._s(t.$t("No comments found.")))])]):r("p",[r("font-awesome-icon",{attrs:{icon:"spinner",spin:""}}),t._v("\n        "+t._s(t.$t("Loading..."))+"\n    ")],1),t._v(" "),t.apiCreateMethod&&null==t.editComment?[t.editor?r("comment-editor",{attrs:{disabled:t.busy},on:{submit:function(e){return t.addComment(e)},cancel:function(e){return t.closeEditor()}}}):r("p",[r("b-button",{attrs:{variant:"primary",disabled:t.busy},on:{click:function(e){t.openEditor(),t.editComment=null}}},[r("font-awesome-icon",{attrs:{icon:"plus-circle"}}),t._v("\n                "+t._s(t.$t("Add comment"))+"\n            ")],1)],1)]:t._e()],2)}),[],!1,null,null,null).exports},6951:(t,e,r)=>{r.d(e,{Z:()=>l});var n=r(7757),a=r.n(n),s=r(2851),i=r(505),o=r(6281);function u(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}const c={components:{AlertWithRetry:s.Z,TableFilter:i.Z,TablePagination:o.Z},props:{id:{required:!0,type:String},fields:{required:!1,type:Array},apiMethod:{required:!0,type:Function},defaultSortBy:{required:!0,type:String},defaultSortDesc:{required:!1,type:Boolean,default:!1},emptyText:{required:!1,type:String},tbodyTrClass:{required:!1},itemsPerPage:{required:!1,type:Number,default:25},filterPlaceholder:{require:!1,type:String,default:function(){return this.$t("Type to search...")}},loadingLabel:{type:String,required:!1,default:function(){return this.$t("Loading...")}},noFilter:Boolean},data:function(){return{isBusy:!1,sortBy:sessionStorage.getItem(this.id+".sortBy")?sessionStorage.getItem(this.id+".sortBy"):this.defaultSortBy,sortDesc:sessionStorage.getItem(this.id+".sortDesc")?"true"==sessionStorage.getItem(this.id+".sortDesc"):this.defaultSortDesc,currentPage:sessionStorage.getItem(this.id+".currentPage")?parseInt(sessionStorage.getItem(this.id+".currentPage")):1,perPage:this.itemsPerPage,totalRows:0,errorText:null,filter:sessionStorage.getItem(this.id+".filter")?sessionStorage.getItem(this.id+".filter"):""}},watch:{filter:function(){this.currentPage=1}},methods:{itemProvider:function(t){var e,r=this;return(e=a().mark((function e(){var n,s;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r.errorText=null,n={filter:t.filter,page:t.currentPage,pageSize:t.perPage,sortBy:t.sortBy,sortDirection:t.sortDesc?"desc":"asc"},e.prev=2,e.next=5,r.apiMethod(n);case 5:return s=e.sent,r.totalRows=s.meta?s.meta.total:0,r.$emit("metadata",s.meta),s.meta&&t.perPage!=s.meta.per_page&&console.error("Table ".concat(r.id,": Discrepancies detected between requested items per page (").concat(t.perPage,") and returned items per page (").concat(s.meta.per_page,").")),sessionStorage.setItem(r.id+".sortBy",t.sortBy),sessionStorage.setItem(r.id+".sortDesc",t.sortDesc),sessionStorage.setItem(r.id+".currentPage",t.currentPage),t.filter.length>0?sessionStorage.setItem(r.id+".filter",t.filter):sessionStorage.removeItem(r.id+".filter"),e.abrupt("return",s.data||[]);case 16:return e.prev=16,e.t0=e.catch(2),r.errorText=e.t0,r.totalRows=0,sessionStorage.removeItem(r.id+".sortBy"),sessionStorage.removeItem(r.id+".sortDesc"),sessionStorage.removeItem(r.id+".currentPage"),sessionStorage.removeItem(r.id+".filter"),e.abrupt("return",[]);case 25:case"end":return e.stop()}}),e,null,[[2,16]])})),function(){var t=this,r=arguments;return new Promise((function(n,a){var s=e.apply(t,r);function i(t){u(s,n,a,i,o,"next",t)}function o(t){u(s,n,a,i,o,"throw",t)}i(void 0)}))})()},refresh:function(){this.$root.$emit("bv::refresh::table",this.id)}}};const l=(0,r(1900).Z)(c,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("alert-with-retry",{attrs:{value:t.errorText},on:{retry:t.refresh}}),t._v(" "),t._t("top"),t._v(" "),r("b-form-row",[t.$slots["filter-prepend"]||t.$scopedSlots["filter-prepend"]?r("b-col",{attrs:{cols:"auto"}},[t._t("filter-prepend",null,{filter:t.filter})],2):t._e(),t._v(" "),r("b-col",[t.noFilter?t._e():r("table-filter",{attrs:{placeholder:t.filterPlaceholder,"is-busy":t.isBusy,"total-rows":t.totalRows},model:{value:t.filter,callback:function(e){t.filter=e},expression:"filter"}})],1),t._v(" "),t.$slots["filter-append"]||t.$scopedSlots["filter-append"]?r("b-col",{attrs:{cols:"auto"}},[t._t("filter-append",null,{filter:t.filter})],2):t._e()],1),t._v(" "),r("b-table",{staticClass:"bg-white shadow-sm",attrs:{id:t.id,hover:"",responsive:"",items:t.itemProvider,fields:t.fields,"primary-key":"id",busy:t.isBusy,"sort-by":t.sortBy,"sort-desc":t.sortDesc,"per-page":t.perPage,"current-page":t.currentPage,"show-empty":!0,"empty-text":t.emptyText,"empty-filtered-text":t.$t("There are no records matching your request."),"no-sort-reset":!0,filter:t.filter,"tbody-tr-class":t.tbodyTrClass},on:{"update:busy":function(e){t.isBusy=e},"update:sortBy":function(e){t.sortBy=e},"update:sort-by":function(e){t.sortBy=e},"update:sortDesc":function(e){t.sortDesc=e},"update:sort-desc":function(e){t.sortDesc=e}},scopedSlots:t._u([t._l(t.$scopedSlots,(function(e,r){return{key:r,fn:function(e){return[t._t(r,null,null,e)]}}})),{key:"empty",fn:function(e){return[r("em",[t._v(t._s(e.emptyText))])]}},{key:"emptyfiltered",fn:function(e){return[r("em",[t._v(t._s(e.emptyFilteredText))])]}}],null,!0)},[t._v(" "),r("div",{staticClass:"text-center my-2",attrs:{slot:"table-busy"},slot:"table-busy"},[r("b-spinner",{staticClass:"align-middle"}),t._v(" "),r("strong",[t._v(t._s(t.loadingLabel))])],1)]),t._v(" "),t.totalRows>0?r("table-pagination",{attrs:{"total-rows":t.totalRows,"per-page":t.perPage,disabled:t.isBusy},model:{value:t.currentPage,callback:function(e){t.currentPage=e},expression:"currentPage"}}):t._e()],2)}),[],!1,null,null,null).exports},505:(t,e,r)=>{r.d(e,{Z:()=>a});const n={props:{value:{required:!0},placeholder:{required:!1,type:String,default:function(){return this.$t("Type to search...")}},totalRows:{type:Number},isBusy:Boolean},computed:{filterText:{get:function(){return this.value},set:function(t){this.$emit("input",t)}}}};const a=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-input-group",{staticClass:"mb-3"},[r("b-form-input",{attrs:{debounce:400,trim:"",type:"search",placeholder:t.placeholder,autocomplete:"off"},on:{keyup:function(e){if(!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"]))return null;t.filterText=""}},model:{value:t.filterText,callback:function(e){t.filterText=e},expression:"filterText"}}),t._v(" "),r("b-input-group-append",{staticClass:"d-none d-sm-block"},[t.isBusy?r("b-input-group-text",[t._v("\n            ...\n        ")]):r("b-input-group-text",[t._v("\n            "+t._s(t.$t("{num} results",{num:t.totalRows}))+"\n        ")])],1)],1)}),[],!1,null,null,null).exports},6281:(t,e,r)=>{r.d(e,{Z:()=>a});const n={props:{value:Number,totalRows:Number,perPage:Number,disabled:Boolean},computed:{currentPage:{get:function(){return this.value},set:function(t){this.$emit("input",t)}},itemsStart:function(){return(this.currentPage-1)*this.perPage+1},itemsEnd:function(){return Math.min(this.currentPage*this.perPage,this.totalRows)}}};const a=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-row",{staticClass:"mb-3",attrs:{"align-v":"center"}},[r("b-col",[t.totalRows>0?r("b-pagination",{staticClass:"mb-0",attrs:{size:"sm","total-rows":t.totalRows,"per-page":t.perPage,disabled:t.disabled},model:{value:t.currentPage,callback:function(e){t.currentPage=e},expression:"currentPage"}}):t._e()],1),t._v(" "),r("b-col",{staticClass:"text-right",attrs:{sm:"auto"}},[r("small",[t._v("\n            "+t._s(t.$t("{x} out of {y}",{x:t.itemsStart+" - "+t.itemsEnd,y:t.totalRows}))+"\n        ")])])],1)}),[],!1,null,null,null).exports},4900:(t,e,r)=>{r.r(e),r.d(e,{default:()=>$});var n=r(7554),a=r.n(n),s=r(6951),i=r(143);const o={props:{value:{type:String,required:!0}},data:function(){return{workStatuses:{active:this.$t("Active"),future:this.$t("Future"),alumni:this.$t("Alumni")}}}};var u=r(1900);const c=(0,u.Z)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-button-group",{attrs:{size:"sm"}},t._l(t.workStatuses,(function(e,n){return r("b-button",{key:n,attrs:{variant:t.value==n?"dark":"secondary"},on:{click:function(e){return t.$emit("input",n)}}},[t._v("\n        "+t._s(e)+"\n    ")])})),1)}),[],!1,null,null,null).exports;const l={props:{value:{type:String,required:!0}},data:function(){return{viewTypes:{list:{label:this.$t("List"),icon:"table"},grid:{label:this.$t("Grid"),icon:"grip-horizontal"}}}}};const d=(0,u.Z)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-button-group",{attrs:{size:"sm"}},t._l(t.viewTypes,(function(e,n){return r("b-button",{key:n,attrs:{variant:t.value==n?"dark":"secondary"},on:{click:function(e){return t.$emit("input",n)}}},[r("font-awesome-icon",{attrs:{icon:e.icon}})],1)})),1)}),[],!1,null,null,null).exports;var m=r(7757),p=r.n(m),f=r(2851),v=r(505),h=r(6281);const b={props:{item:{reuqired:!0}},data:function(){return{placeholderPicture:"/img/portrait_placeholder.png"}},methods:{arrayToString:function(t){return Array.isArray(t)?t.join(", "):t}}};const y=(0,u.Z)(b,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("b-card",{staticClass:"mb-4",attrs:{"no-body":"","header-class":"p-2"},scopedSlots:t._u([{key:"header",fn:function(){return["f"==t.item.gender?r("font-awesome-icon",{attrs:{icon:"female"}}):t._e(),t._v(" "),"m"==t.item.gender?r("font-awesome-icon",{attrs:{icon:"male"}}):t._e(),t._v(" "),t.item.nickname?[t._v("\n            "+t._s(t.item.nickname)+"\n        ")]:[t._v("\n            "+t._s(t.item.first_name)+"\n        ")]]},proxy:!0}])},[t._v(" "),r("b-card-body",{staticClass:"p-0"},[r("a",{attrs:{href:t.item.url}},[t.item.portrait_picture_url?r("img",{staticClass:"img-fluid",attrs:{src:t.item.portrait_picture_url,alt:"Portrait"}}):r("img",{staticClass:"img-fluid",attrs:{src:t.placeholderPicture,alt:"Placeholder"}})])]),t._v(" "),r("b-card-body",{staticClass:"py-1 px-2"},[r("small",[t._v("\n            "+t._s(t.item.full_name)),r("br"),t._v("\n            "+t._s(t.item.age)),t.item.nationality&&t.item.age?[t._v(",")]:t._e(),t._v("\n            "+t._s(t.item.nationality)),r("br")],2)])],1)}),[],!1,null,null,null).exports;function g(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}function _(t){return function(){var e=this,r=arguments;return new Promise((function(n,a){var s=t.apply(e,r);function i(t){g(s,n,a,i,o,"next",t)}function o(t){g(s,n,a,i,o,"throw",t)}i(void 0)}))}}const w={components:{AlertWithRetry:f.Z,TableFilter:v.Z,TablePagination:h.Z,GridItem:y},props:{apiMethod:{required:!0,type:Function},itemsPerPage:{required:!1,type:Number,default:25}},data:function(){var t="communityVolunteerGrid";return{id:t,data:null,isBusy:!1,errorText:null,currentPage:sessionStorage.getItem(t+".currentPage")?parseInt(sessionStorage.getItem(t+".currentPage")):1,perPage:this.itemsPerPage,sortBy:"first_name",sortDesc:!1,totalRows:0,filter:"",filterPlaceholder:this.$t("Search")}},watch:{filter:function(){this.currentPage=1,this.refresh()},currentPage:function(){this.refresh()}},created:function(){var t=this;return _(p().mark((function e(){return p().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.refresh();case 1:case"end":return e.stop()}}),e)})))()},methods:{itemProvider:function(){var t=this;return _(p().mark((function e(){var r,n;return p().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.isBusy=!0,t.errorText=null,r={filter:t.filter,page:t.currentPage,pageSize:t.perPage,sortBy:t.sortBy,sortDirection:t.sortDesc?"desc":"asc"},e.prev=3,e.next=6,t.apiMethod(r);case 6:return n=e.sent,t.totalRows=n.meta.total,t.isBusy=!1,sessionStorage.setItem(t.id+".currentPage",t.currentPage),e.abrupt("return",n.data||[]);case 13:return e.prev=13,e.t0=e.catch(3),t.isBusy=!1,t.errorText=e.t0,e.abrupt("return",[]);case 18:case"end":return e.stop()}}),e,null,[[3,13]])})))()},refresh:function(){var t=this;return _(p().mark((function e(){return p().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.itemProvider();case 2:t.data=e.sent;case 3:case"end":return e.stop()}}),e)})))()}}};const x=(0,u.Z)(w,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("alert-with-retry",{attrs:{value:t.errorText},on:{retry:t.refresh}}),t._v(" "),r("div",{staticClass:"form-row"},[t.$slots["filter-prepend"]?r("div",{staticClass:"col-auto"},[t._t("filter-prepend")],2):t._e(),t._v(" "),r("div",{staticClass:"col"},[r("table-filter",{attrs:{placeholder:t.filterPlaceholder,"is-busy":t.isBusy,"total-rows":t.totalRows},model:{value:t.filter,callback:function(e){t.filter=e},expression:"filter"}})],1),t._v(" "),t.$slots["filter-append"]?r("div",{staticClass:"col-auto"},[t._t("filter-append")],2):t._e()]),t._v(" "),null==t.data||t.isBusy?r("p",[t._v("\n        "+t._s(t.$t("Loading..."))+"\n    ")]):t.data.length>0?r("div",{staticClass:"row"},t._l(t.data,(function(t){return r("div",{key:t.id,staticClass:"col-lg-2 col-md-3 col-sm-4 col-6"},[r("grid-item",{attrs:{item:t}})],1)})),0):r("b-alert",{attrs:{show:"",variant:"info"}},[t._v("\n        "+t._s(t.$t("There are no records matching your request."))+"\n    ")]),t._v(" "),r("table-pagination",{attrs:{"total-rows":t.totalRows,"per-page":t.perPage,disabled:t.isBusy},model:{value:t.currentPage,callback:function(e){t.currentPage=e},expression:"currentPage"}})],1)}),[],!1,null,null,null).exports,k={title:function(){return this.$t("Community Volunteers")},components:{BaseTable:s.Z,Nl2br:a(),WorkStatusSelector:c,ViewTypeSelector:d,GridView:x},data:function(){return{fields:[{key:"first_name",label:this.$t("First Name"),sortable:!0},{key:"family_name",label:this.$t("Family Name"),sortable:!0},{key:"nickname",label:this.$t("Nickname")},{key:"nationality",label:this.$t("Nationality"),sortable:!0},{key:"gender",label:this.$t("Gender"),class:"text-center fit"},{key:"age",label:this.$t("Age"),sortable:!0,class:"text-right fit"},{key:"responsibilities",label:this.$t("Responsibilities")},{key:"languages",label:this.$t("Languages")}],workStatus:sessionStorage.getItem("cmtyvol.workStatus")?sessionStorage.getItem("cmtyvol.workStatus"):"active",viewType:sessionStorage.getItem("cmtyvol.viewType")?sessionStorage.getItem("cmtyvol.viewType"):"list",itemsPerPage:12}},watch:{workStatus:function(t){var e=this.$refs.table;e&&e.refresh();var r=this.$refs.grid;r&&r.refresh(),sessionStorage.setItem("cmtyvol.workStatus",t)},viewType:function(t){sessionStorage.setItem("cmtyvol.viewType",t)}},methods:{fetchData:function(t){return t.workStatus=this.workStatus,i.Z.list(t)},arrayToString:function(t){return Array.isArray(t)?t.join("\n"):t}}};const $=(0,u.Z)(k,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",["list"==t.viewType?r("base-table",{ref:"table",attrs:{id:"communityVolunteerTable",fields:t.fields,"api-method":t.fetchData,"default-sort-by":"first_name","empty-text":t.$t("No community volunteers found."),"filter-placeholder":t.$t("Search"),"items-per-page":t.itemsPerPage},scopedSlots:t._u([{key:"cell(first_name)",fn:function(e){return[null!=e.item.url?[""!=e.value?r("a",{attrs:{href:e.item.url}},[t._v(t._s(e.value))]):t._e()]:[t._v("\n                "+t._s(e.value)+"\n            ")]]}},{key:"cell(family_name)",fn:function(e){return[null!=e.item.url?[""!=e.value?r("a",{attrs:{href:e.item.url}},[t._v(t._s(e.value))]):t._e()]:[t._v("\n                "+t._s(e.value)+"\n            ")]]}},{key:"cell(gender)",fn:function(e){return["m"==e.value?r("font-awesome-icon",{attrs:{icon:"male"}}):t._e(),t._v(" "),"f"==e.value?r("font-awesome-icon",{attrs:{icon:"female"}}):t._e()]}},{key:"cell(languages)",fn:function(e){return[r("nl2br",{attrs:{tag:"span",text:t.arrayToString(e.value)}})]}},{key:"cell(responsibilities)",fn:function(e){return[t._l(e.value,(function(e,n){return[t._v("\n                "+t._s(n)+"\n                "),e.description?r("b-button",{directives:[{name:"b-popover",rawName:"v-b-popover.focus",value:e.description,expression:"attributes.description",modifiers:{focus:!0}}],key:n+"-a",staticClass:"description-tooltip p-0",attrs:{variant:"link",href:"#"}},[r("font-awesome-icon",{key:n+"-i",attrs:{icon:"info-circle"}})],1):t._e(),t._v(" "),e.start_date&&e.end_date?[t._v("\n                    ("+t._s(t.$t(":from - :until",{from:e.start_date,until:e.end_date}))+")\n                ")]:e.start_date?[t._v("\n                    ("+t._s(t.$t("from :from",{date:e.start_date}))+")\n                ")]:e.end_date?[t._v("\n                    ("+t._s(t.$t("until :until",{date:e.end_date}))+")\n                ")]:t._e(),t._v(" "),r("br",{key:n+"-b"})]}))]}},{key:"filter-prepend",fn:function(){return[r("work-status-selector",{model:{value:t.workStatus,callback:function(e){t.workStatus=e},expression:"workStatus"}})]},proxy:!0},{key:"filter-append",fn:function(){return[r("view-type-selector",{model:{value:t.viewType,callback:function(e){t.viewType=e},expression:"viewType"}})]},proxy:!0}],null,!1,291789436)}):"grid"==t.viewType?r("grid-view",{ref:"grid",attrs:{"api-method":t.fetchData,"items-per-page":t.itemsPerPage},scopedSlots:t._u([{key:"filter-prepend",fn:function(){return[r("work-status-selector",{model:{value:t.workStatus,callback:function(e){t.workStatus=e},expression:"workStatus"}})]},proxy:!0},{key:"filter-append",fn:function(){return[r("view-type-selector",{model:{value:t.viewType,callback:function(e){t.viewType=e},expression:"viewType"}})]},proxy:!0}])}):t._e()],1)}),[],!1,null,null,null).exports},4248:(t,e,r)=>{r.r(e),r.d(e,{default:()=>p});var n=r(143),a=r(7757),s=r.n(a),i=r(1581),o=r(7297);function u(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}var c=o.tA.reactiveData;const l={extends:o.$Q,mixins:[c],props:{title:{type:String,required:!0},data:{type:[Function,Object],required:!0},xLabel:{type:String,required:!1},yLabel:{type:String,required:!1}},data:function(){return{options:this.createOptions()}},mounted:function(){this.loadData()},methods:{refresh:function(){this.loadData()},loadData:function(){var t,e=this;return(t=s().mark((function t(){var r;return s().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(t.prev=0,"function"!=typeof e.data){t.next=7;break}return t.next=4,e.data();case 4:r=t.sent,t.next=8;break;case 7:r=e.data;case 8:e.chartData=e.parseDateFromResponse(r),t.next=14;break;case 11:t.prev=11,t.t0=t.catch(0),console.error(t.t0);case 14:case"end":return t.stop()}}),t,null,[[0,11]])})),function(){var e=this,r=arguments;return new Promise((function(n,a){var s=t.apply(e,r);function i(t){u(s,n,a,i,o,"next",t)}function o(t){u(s,n,a,i,o,"throw",t)}i(void 0)}))})()},parseDateFromResponse:function(t){var e={labels:t.labels,datasets:[]};return t.datasets.forEach((function(t){e.datasets.push({label:t.label,data:t.data})})),(0,i.qp)(e.datasets),e},createOptions:function(){return{title:{display:!0,text:this.title},legend:{display:!0,position:"bottom"},elements:{line:{tension:0}},scales:{xAxes:[{display:!0,gridLines:{display:!0},scaleLabel:{display:this.xLabel,labelString:this.xLabel}}],yAxes:[{display:!0,gridLines:{display:!0},ticks:{suggestedMin:0},scaleLabel:{display:this.yLabel,labelString:this.yLabel}}]},responsive:!0,maintainAspectRatio:!1,animation:{duration:500}}}}};var d=r(1900);const m={title:function(){return this.$t("Report")+": "+this.$t("Community Volunteers")},components:{BarChart:(0,d.Z)(l,undefined,undefined,!1,null,null,null).exports,DoughnutChart:r(1376).Z},data:function(){return{api:n.Z}}};const p=(0,d.Z)(m,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("bar-chart",{staticClass:"mb-2",attrs:{title:t.$t("Age distribution"),"x-label":t.$t("Age"),"y-label":t.$t("Quantity"),data:t.api.ageDistribution,height:350}}),t._v(" "),r("div",{staticClass:"row mb-4"},[r("div",{staticClass:"col-sm"},[r("doughnut-chart",{staticClass:"mb-2",attrs:{title:t.$t("Gender"),data:t.api.genderDistribution,"hide-legend":"",height:300}})],1),t._v(" "),r("div",{staticClass:"col-sm"},[r("doughnut-chart",{staticClass:"mb-2",attrs:{title:t.$t("Nationalities"),data:t.api.nationalityDistribution,height:300}})],1)])],1)}),[],!1,null,null,null).exports},117:(t,e,r)=>{r.r(e),r.d(e,{default:()=>d});var n=r(7757),a=r.n(n),s=r(2633),i=r(143);function o(t,e,r,n,a,s,i){try{var o=t[s](i),u=o.value}catch(t){return void r(t)}o.done?e(u):Promise.resolve(u).then(n,a)}const u={components:{CommentsList:s.Z},props:{id:{required:!0}},data:function(){return{canCreate:!1}},methods:{listComments:function(){var t,e=this;return(t=a().mark((function t(){var r;return a().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,i.Z.listComments(e.id);case 2:return r=t.sent,e.canCreate=r.meta.can_create,t.abrupt("return",r);case 5:case"end":return t.stop()}}),t)})),function(){var e=this,r=arguments;return new Promise((function(n,a){var s=t.apply(e,r);function i(t){o(s,n,a,i,u,"next",t)}function u(t){o(s,n,a,i,u,"throw",t)}i(void 0)}))})()},storeComment:function(t){return i.Z.storeComment(this.id,t)}}};var c=r(1900);const l={components:{CmtyvolComments:(0,c.Z)(u,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("comments-list",{key:t.id,attrs:{"api-list-method":t.listComments,"api-create-method":t.canCreate?t.storeComment:null},on:{count:function(e){return t.$emit("count",{type:"comments",value:e})}}})}),[],!1,null,null,null).exports},props:{id:{required:!0}}};const d=(0,c.Z)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("CmtyvolComments",{attrs:{id:t.id}})],1)}),[],!1,null,null,null).exports}}]);
//# sourceMappingURL=cmtyvol.js.map?id=12840e3e4ff665b7b567