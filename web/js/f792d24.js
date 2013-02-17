/*! jQuery v1.8.2 jquery.com | jquery.org/license */
(function(a,b){function G(a){var b=F[a]={};return p.each(a.split(s),function(a,c){b[c]=!0}),b}function J(a,c,d){if(d===b&&a.nodeType===1){var e="data-"+c.replace(I,"-$1").toLowerCase();d=a.getAttribute(e);if(typeof d=="string"){try{d=d==="true"?!0:d==="false"?!1:d==="null"?null:+d+""===d?+d:H.test(d)?p.parseJSON(d):d}catch(f){}p.data(a,c,d)}else d=b}return d}function K(a){var b;for(b in a){if(b==="data"&&p.isEmptyObject(a[b]))continue;if(b!=="toJSON")return!1}return!0}function ba(){return!1}function bb(){return!0}function bh(a){return!a||!a.parentNode||a.parentNode.nodeType===11}function bi(a,b){do a=a[b];while(a&&a.nodeType!==1);return a}function bj(a,b,c){b=b||0;if(p.isFunction(b))return p.grep(a,function(a,d){var e=!!b.call(a,d,a);return e===c});if(b.nodeType)return p.grep(a,function(a,d){return a===b===c});if(typeof b=="string"){var d=p.grep(a,function(a){return a.nodeType===1});if(be.test(b))return p.filter(b,d,!c);b=p.filter(b,d)}return p.grep(a,function(a,d){return p.inArray(a,b)>=0===c})}function bk(a){var b=bl.split("|"),c=a.createDocumentFragment();if(c.createElement)while(b.length)c.createElement(b.pop());return c}function bC(a,b){return a.getElementsByTagName(b)[0]||a.appendChild(a.ownerDocument.createElement(b))}function bD(a,b){if(b.nodeType!==1||!p.hasData(a))return;var c,d,e,f=p._data(a),g=p._data(b,f),h=f.events;if(h){delete g.handle,g.events={};for(c in h)for(d=0,e=h[c].length;d<e;d++)p.event.add(b,c,h[c][d])}g.data&&(g.data=p.extend({},g.data))}function bE(a,b){var c;if(b.nodeType!==1)return;b.clearAttributes&&b.clearAttributes(),b.mergeAttributes&&b.mergeAttributes(a),c=b.nodeName.toLowerCase(),c==="object"?(b.parentNode&&(b.outerHTML=a.outerHTML),p.support.html5Clone&&a.innerHTML&&!p.trim(b.innerHTML)&&(b.innerHTML=a.innerHTML)):c==="input"&&bv.test(a.type)?(b.defaultChecked=b.checked=a.checked,b.value!==a.value&&(b.value=a.value)):c==="option"?b.selected=a.defaultSelected:c==="input"||c==="textarea"?b.defaultValue=a.defaultValue:c==="script"&&b.text!==a.text&&(b.text=a.text),b.removeAttribute(p.expando)}function bF(a){return typeof a.getElementsByTagName!="undefined"?a.getElementsByTagName("*"):typeof a.querySelectorAll!="undefined"?a.querySelectorAll("*"):[]}function bG(a){bv.test(a.type)&&(a.defaultChecked=a.checked)}function bY(a,b){if(b in a)return b;var c=b.charAt(0).toUpperCase()+b.slice(1),d=b,e=bW.length;while(e--){b=bW[e]+c;if(b in a)return b}return d}function bZ(a,b){return a=b||a,p.css(a,"display")==="none"||!p.contains(a.ownerDocument,a)}function b$(a,b){var c,d,e=[],f=0,g=a.length;for(;f<g;f++){c=a[f];if(!c.style)continue;e[f]=p._data(c,"olddisplay"),b?(!e[f]&&c.style.display==="none"&&(c.style.display=""),c.style.display===""&&bZ(c)&&(e[f]=p._data(c,"olddisplay",cc(c.nodeName)))):(d=bH(c,"display"),!e[f]&&d!=="none"&&p._data(c,"olddisplay",d))}for(f=0;f<g;f++){c=a[f];if(!c.style)continue;if(!b||c.style.display==="none"||c.style.display==="")c.style.display=b?e[f]||"":"none"}return a}function b_(a,b,c){var d=bP.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function ca(a,b,c,d){var e=c===(d?"border":"content")?4:b==="width"?1:0,f=0;for(;e<4;e+=2)c==="margin"&&(f+=p.css(a,c+bV[e],!0)),d?(c==="content"&&(f-=parseFloat(bH(a,"padding"+bV[e]))||0),c!=="margin"&&(f-=parseFloat(bH(a,"border"+bV[e]+"Width"))||0)):(f+=parseFloat(bH(a,"padding"+bV[e]))||0,c!=="padding"&&(f+=parseFloat(bH(a,"border"+bV[e]+"Width"))||0));return f}function cb(a,b,c){var d=b==="width"?a.offsetWidth:a.offsetHeight,e=!0,f=p.support.boxSizing&&p.css(a,"boxSizing")==="border-box";if(d<=0||d==null){d=bH(a,b);if(d<0||d==null)d=a.style[b];if(bQ.test(d))return d;e=f&&(p.support.boxSizingReliable||d===a.style[b]),d=parseFloat(d)||0}return d+ca(a,b,c||(f?"border":"content"),e)+"px"}function cc(a){if(bS[a])return bS[a];var b=p("<"+a+">").appendTo(e.body),c=b.css("display");b.remove();if(c==="none"||c===""){bI=e.body.appendChild(bI||p.extend(e.createElement("iframe"),{frameBorder:0,width:0,height:0}));if(!bJ||!bI.createElement)bJ=(bI.contentWindow||bI.contentDocument).document,bJ.write("<!doctype html><html><body>"),bJ.close();b=bJ.body.appendChild(bJ.createElement(a)),c=bH(b,"display"),e.body.removeChild(bI)}return bS[a]=c,c}function ci(a,b,c,d){var e;if(p.isArray(b))p.each(b,function(b,e){c||ce.test(a)?d(a,e):ci(a+"["+(typeof e=="object"?b:"")+"]",e,c,d)});else if(!c&&p.type(b)==="object")for(e in b)ci(a+"["+e+"]",b[e],c,d);else d(a,b)}function cz(a){return function(b,c){typeof b!="string"&&(c=b,b="*");var d,e,f,g=b.toLowerCase().split(s),h=0,i=g.length;if(p.isFunction(c))for(;h<i;h++)d=g[h],f=/^\+/.test(d),f&&(d=d.substr(1)||"*"),e=a[d]=a[d]||[],e[f?"unshift":"push"](c)}}function cA(a,c,d,e,f,g){f=f||c.dataTypes[0],g=g||{},g[f]=!0;var h,i=a[f],j=0,k=i?i.length:0,l=a===cv;for(;j<k&&(l||!h);j++)h=i[j](c,d,e),typeof h=="string"&&(!l||g[h]?h=b:(c.dataTypes.unshift(h),h=cA(a,c,d,e,h,g)));return(l||!h)&&!g["*"]&&(h=cA(a,c,d,e,"*",g)),h}function cB(a,c){var d,e,f=p.ajaxSettings.flatOptions||{};for(d in c)c[d]!==b&&((f[d]?a:e||(e={}))[d]=c[d]);e&&p.extend(!0,a,e)}function cC(a,c,d){var e,f,g,h,i=a.contents,j=a.dataTypes,k=a.responseFields;for(f in k)f in d&&(c[k[f]]=d[f]);while(j[0]==="*")j.shift(),e===b&&(e=a.mimeType||c.getResponseHeader("content-type"));if(e)for(f in i)if(i[f]&&i[f].test(e)){j.unshift(f);break}if(j[0]in d)g=j[0];else{for(f in d){if(!j[0]||a.converters[f+" "+j[0]]){g=f;break}h||(h=f)}g=g||h}if(g)return g!==j[0]&&j.unshift(g),d[g]}function cD(a,b){var c,d,e,f,g=a.dataTypes.slice(),h=g[0],i={},j=0;a.dataFilter&&(b=a.dataFilter(b,a.dataType));if(g[1])for(c in a.converters)i[c.toLowerCase()]=a.converters[c];for(;e=g[++j];)if(e!=="*"){if(h!=="*"&&h!==e){c=i[h+" "+e]||i["* "+e];if(!c)for(d in i){f=d.split(" ");if(f[1]===e){c=i[h+" "+f[0]]||i["* "+f[0]];if(c){c===!0?c=i[d]:i[d]!==!0&&(e=f[0],g.splice(j--,0,e));break}}}if(c!==!0)if(c&&a["throws"])b=c(b);else try{b=c(b)}catch(k){return{state:"parsererror",error:c?k:"No conversion from "+h+" to "+e}}}h=e}return{state:"success",data:b}}function cL(){try{return new a.XMLHttpRequest}catch(b){}}function cM(){try{return new a.ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}function cU(){return setTimeout(function(){cN=b},0),cN=p.now()}function cV(a,b){p.each(b,function(b,c){var d=(cT[b]||[]).concat(cT["*"]),e=0,f=d.length;for(;e<f;e++)if(d[e].call(a,b,c))return})}function cW(a,b,c){var d,e=0,f=0,g=cS.length,h=p.Deferred().always(function(){delete i.elem}),i=function(){var b=cN||cU(),c=Math.max(0,j.startTime+j.duration-b),d=1-(c/j.duration||0),e=0,f=j.tweens.length;for(;e<f;e++)j.tweens[e].run(d);return h.notifyWith(a,[j,d,c]),d<1&&f?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:p.extend({},b),opts:p.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:cN||cU(),duration:c.duration,tweens:[],createTween:function(b,c,d){var e=p.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(e),e},stop:function(b){var c=0,d=b?j.tweens.length:0;for(;c<d;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;cX(k,j.opts.specialEasing);for(;e<g;e++){d=cS[e].call(j,a,k,j.opts);if(d)return d}return cV(j,k),p.isFunction(j.opts.start)&&j.opts.start.call(a,j),p.fx.timer(p.extend(i,{anim:j,queue:j.opts.queue,elem:a})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}function cX(a,b){var c,d,e,f,g;for(c in a){d=p.camelCase(c),e=b[d],f=a[c],p.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=p.cssHooks[d];if(g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}}function cY(a,b,c){var d,e,f,g,h,i,j,k,l=this,m=a.style,n={},o=[],q=a.nodeType&&bZ(a);c.queue||(j=p._queueHooks(a,"fx"),j.unqueued==null&&(j.unqueued=0,k=j.empty.fire,j.empty.fire=function(){j.unqueued||k()}),j.unqueued++,l.always(function(){l.always(function(){j.unqueued--,p.queue(a,"fx").length||j.empty.fire()})})),a.nodeType===1&&("height"in b||"width"in b)&&(c.overflow=[m.overflow,m.overflowX,m.overflowY],p.css(a,"display")==="inline"&&p.css(a,"float")==="none"&&(!p.support.inlineBlockNeedsLayout||cc(a.nodeName)==="inline"?m.display="inline-block":m.zoom=1)),c.overflow&&(m.overflow="hidden",p.support.shrinkWrapBlocks||l.done(function(){m.overflow=c.overflow[0],m.overflowX=c.overflow[1],m.overflowY=c.overflow[2]}));for(d in b){f=b[d];if(cP.exec(f)){delete b[d];if(f===(q?"hide":"show"))continue;o.push(d)}}g=o.length;if(g){h=p._data(a,"fxshow")||p._data(a,"fxshow",{}),q?p(a).show():l.done(function(){p(a).hide()}),l.done(function(){var b;p.removeData(a,"fxshow",!0);for(b in n)p.style(a,b,n[b])});for(d=0;d<g;d++)e=o[d],i=l.createTween(e,q?h[e]:0),n[e]=h[e]||p.style(a,e),e in h||(h[e]=i.start,q&&(i.end=i.start,i.start=e==="width"||e==="height"?1:0))}}function cZ(a,b,c,d,e){return new cZ.prototype.init(a,b,c,d,e)}function c$(a,b){var c,d={height:a},e=0;b=b?1:0;for(;e<4;e+=2-b)c=bV[e],d["margin"+c]=d["padding"+c]=a;return b&&(d.opacity=d.width=a),d}function da(a){return p.isWindow(a)?a:a.nodeType===9?a.defaultView||a.parentWindow:!1}var c,d,e=a.document,f=a.location,g=a.navigator,h=a.jQuery,i=a.$,j=Array.prototype.push,k=Array.prototype.slice,l=Array.prototype.indexOf,m=Object.prototype.toString,n=Object.prototype.hasOwnProperty,o=String.prototype.trim,p=function(a,b){return new p.fn.init(a,b,c)},q=/[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source,r=/\S/,s=/\s+/,t=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,u=/^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,v=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,w=/^[\],:{}\s]*$/,x=/(?:^|:|,)(?:\s*\[)+/g,y=/\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,z=/"[^"\\\r\n]*"|true|false|null|-?(?:\d\d*\.|)\d+(?:[eE][\-+]?\d+|)/g,A=/^-ms-/,B=/-([\da-z])/gi,C=function(a,b){return(b+"").toUpperCase()},D=function(){e.addEventListener?(e.removeEventListener("DOMContentLoaded",D,!1),p.ready()):e.readyState==="complete"&&(e.detachEvent("onreadystatechange",D),p.ready())},E={};p.fn=p.prototype={constructor:p,init:function(a,c,d){var f,g,h,i;if(!a)return this;if(a.nodeType)return this.context=this[0]=a,this.length=1,this;if(typeof a=="string"){a.charAt(0)==="<"&&a.charAt(a.length-1)===">"&&a.length>=3?f=[null,a,null]:f=u.exec(a);if(f&&(f[1]||!c)){if(f[1])return c=c instanceof p?c[0]:c,i=c&&c.nodeType?c.ownerDocument||c:e,a=p.parseHTML(f[1],i,!0),v.test(f[1])&&p.isPlainObject(c)&&this.attr.call(a,c,!0),p.merge(this,a);g=e.getElementById(f[2]);if(g&&g.parentNode){if(g.id!==f[2])return d.find(a);this.length=1,this[0]=g}return this.context=e,this.selector=a,this}return!c||c.jquery?(c||d).find(a):this.constructor(c).find(a)}return p.isFunction(a)?d.ready(a):(a.selector!==b&&(this.selector=a.selector,this.context=a.context),p.makeArray(a,this))},selector:"",jquery:"1.8.2",length:0,size:function(){return this.length},toArray:function(){return k.call(this)},get:function(a){return a==null?this.toArray():a<0?this[this.length+a]:this[a]},pushStack:function(a,b,c){var d=p.merge(this.constructor(),a);return d.prevObject=this,d.context=this.context,b==="find"?d.selector=this.selector+(this.selector?" ":"")+c:b&&(d.selector=this.selector+"."+b+"("+c+")"),d},each:function(a,b){return p.each(this,a,b)},ready:function(a){return p.ready.promise().done(a),this},eq:function(a){return a=+a,a===-1?this.slice(a):this.slice(a,a+1)},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},slice:function(){return this.pushStack(k.apply(this,arguments),"slice",k.call(arguments).join(","))},map:function(a){return this.pushStack(p.map(this,function(b,c){return a.call(b,c,b)}))},end:function(){return this.prevObject||this.constructor(null)},push:j,sort:[].sort,splice:[].splice},p.fn.init.prototype=p.fn,p.extend=p.fn.extend=function(){var a,c,d,e,f,g,h=arguments[0]||{},i=1,j=arguments.length,k=!1;typeof h=="boolean"&&(k=h,h=arguments[1]||{},i=2),typeof h!="object"&&!p.isFunction(h)&&(h={}),j===i&&(h=this,--i);for(;i<j;i++)if((a=arguments[i])!=null)for(c in a){d=h[c],e=a[c];if(h===e)continue;k&&e&&(p.isPlainObject(e)||(f=p.isArray(e)))?(f?(f=!1,g=d&&p.isArray(d)?d:[]):g=d&&p.isPlainObject(d)?d:{},h[c]=p.extend(k,g,e)):e!==b&&(h[c]=e)}return h},p.extend({noConflict:function(b){return a.$===p&&(a.$=i),b&&a.jQuery===p&&(a.jQuery=h),p},isReady:!1,readyWait:1,holdReady:function(a){a?p.readyWait++:p.ready(!0)},ready:function(a){if(a===!0?--p.readyWait:p.isReady)return;if(!e.body)return setTimeout(p.ready,1);p.isReady=!0;if(a!==!0&&--p.readyWait>0)return;d.resolveWith(e,[p]),p.fn.trigger&&p(e).trigger("ready").off("ready")},isFunction:function(a){return p.type(a)==="function"},isArray:Array.isArray||function(a){return p.type(a)==="array"},isWindow:function(a){return a!=null&&a==a.window},isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},type:function(a){return a==null?String(a):E[m.call(a)]||"object"},isPlainObject:function(a){if(!a||p.type(a)!=="object"||a.nodeType||p.isWindow(a))return!1;try{if(a.constructor&&!n.call(a,"constructor")&&!n.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(c){return!1}var d;for(d in a);return d===b||n.call(a,d)},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},error:function(a){throw new Error(a)},parseHTML:function(a,b,c){var d;return!a||typeof a!="string"?null:(typeof b=="boolean"&&(c=b,b=0),b=b||e,(d=v.exec(a))?[b.createElement(d[1])]:(d=p.buildFragment([a],b,c?null:[]),p.merge([],(d.cacheable?p.clone(d.fragment):d.fragment).childNodes)))},parseJSON:function(b){if(!b||typeof b!="string")return null;b=p.trim(b);if(a.JSON&&a.JSON.parse)return a.JSON.parse(b);if(w.test(b.replace(y,"@").replace(z,"]").replace(x,"")))return(new Function("return "+b))();p.error("Invalid JSON: "+b)},parseXML:function(c){var d,e;if(!c||typeof c!="string")return null;try{a.DOMParser?(e=new DOMParser,d=e.parseFromString(c,"text/xml")):(d=new ActiveXObject("Microsoft.XMLDOM"),d.async="false",d.loadXML(c))}catch(f){d=b}return(!d||!d.documentElement||d.getElementsByTagName("parsererror").length)&&p.error("Invalid XML: "+c),d},noop:function(){},globalEval:function(b){b&&r.test(b)&&(a.execScript||function(b){a.eval.call(a,b)})(b)},camelCase:function(a){return a.replace(A,"ms-").replace(B,C)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,c,d){var e,f=0,g=a.length,h=g===b||p.isFunction(a);if(d){if(h){for(e in a)if(c.apply(a[e],d)===!1)break}else for(;f<g;)if(c.apply(a[f++],d)===!1)break}else if(h){for(e in a)if(c.call(a[e],e,a[e])===!1)break}else for(;f<g;)if(c.call(a[f],f,a[f++])===!1)break;return a},trim:o&&!o.call("﻿ ")?function(a){return a==null?"":o.call(a)}:function(a){return a==null?"":(a+"").replace(t,"")},makeArray:function(a,b){var c,d=b||[];return a!=null&&(c=p.type(a),a.length==null||c==="string"||c==="function"||c==="regexp"||p.isWindow(a)?j.call(d,a):p.merge(d,a)),d},inArray:function(a,b,c){var d;if(b){if(l)return l.call(b,a,c);d=b.length,c=c?c<0?Math.max(0,d+c):c:0;for(;c<d;c++)if(c in b&&b[c]===a)return c}return-1},merge:function(a,c){var d=c.length,e=a.length,f=0;if(typeof d=="number")for(;f<d;f++)a[e++]=c[f];else while(c[f]!==b)a[e++]=c[f++];return a.length=e,a},grep:function(a,b,c){var d,e=[],f=0,g=a.length;c=!!c;for(;f<g;f++)d=!!b(a[f],f),c!==d&&e.push(a[f]);return e},map:function(a,c,d){var e,f,g=[],h=0,i=a.length,j=a instanceof p||i!==b&&typeof i=="number"&&(i>0&&a[0]&&a[i-1]||i===0||p.isArray(a));if(j)for(;h<i;h++)e=c(a[h],h,d),e!=null&&(g[g.length]=e);else for(f in a)e=c(a[f],f,d),e!=null&&(g[g.length]=e);return g.concat.apply([],g)},guid:1,proxy:function(a,c){var d,e,f;return typeof c=="string"&&(d=a[c],c=a,a=d),p.isFunction(a)?(e=k.call(arguments,2),f=function(){return a.apply(c,e.concat(k.call(arguments)))},f.guid=a.guid=a.guid||p.guid++,f):b},access:function(a,c,d,e,f,g,h){var i,j=d==null,k=0,l=a.length;if(d&&typeof d=="object"){for(k in d)p.access(a,c,k,d[k],1,g,e);f=1}else if(e!==b){i=h===b&&p.isFunction(e),j&&(i?(i=c,c=function(a,b,c){return i.call(p(a),c)}):(c.call(a,e),c=null));if(c)for(;k<l;k++)c(a[k],d,i?e.call(a[k],k,c(a[k],d)):e,h);f=1}return f?a:j?c.call(a):l?c(a[0],d):g},now:function(){return(new Date).getTime()}}),p.ready.promise=function(b){if(!d){d=p.Deferred();if(e.readyState==="complete")setTimeout(p.ready,1);else if(e.addEventListener)e.addEventListener("DOMContentLoaded",D,!1),a.addEventListener("load",p.ready,!1);else{e.attachEvent("onreadystatechange",D),a.attachEvent("onload",p.ready);var c=!1;try{c=a.frameElement==null&&e.documentElement}catch(f){}c&&c.doScroll&&function g(){if(!p.isReady){try{c.doScroll("left")}catch(a){return setTimeout(g,50)}p.ready()}}()}}return d.promise(b)},p.each("Boolean Number String Function Array Date RegExp Object".split(" "),function(a,b){E["[object "+b+"]"]=b.toLowerCase()}),c=p(e);var F={};p.Callbacks=function(a){a=typeof a=="string"?F[a]||G(a):p.extend({},a);var c,d,e,f,g,h,i=[],j=!a.once&&[],k=function(b){c=a.memory&&b,d=!0,h=f||0,f=0,g=i.length,e=!0;for(;i&&h<g;h++)if(i[h].apply(b[0],b[1])===!1&&a.stopOnFalse){c=!1;break}e=!1,i&&(j?j.length&&k(j.shift()):c?i=[]:l.disable())},l={add:function(){if(i){var b=i.length;(function d(b){p.each(b,function(b,c){var e=p.type(c);e==="function"&&(!a.unique||!l.has(c))?i.push(c):c&&c.length&&e!=="string"&&d(c)})})(arguments),e?g=i.length:c&&(f=b,k(c))}return this},remove:function(){return i&&p.each(arguments,function(a,b){var c;while((c=p.inArray(b,i,c))>-1)i.splice(c,1),e&&(c<=g&&g--,c<=h&&h--)}),this},has:function(a){return p.inArray(a,i)>-1},empty:function(){return i=[],this},disable:function(){return i=j=c=b,this},disabled:function(){return!i},lock:function(){return j=b,c||l.disable(),this},locked:function(){return!j},fireWith:function(a,b){return b=b||[],b=[a,b.slice?b.slice():b],i&&(!d||j)&&(e?j.push(b):k(b)),this},fire:function(){return l.fireWith(this,arguments),this},fired:function(){return!!d}};return l},p.extend({Deferred:function(a){var b=[["resolve","done",p.Callbacks("once memory"),"resolved"],["reject","fail",p.Callbacks("once memory"),"rejected"],["notify","progress",p.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return p.Deferred(function(c){p.each(b,function(b,d){var f=d[0],g=a[b];e[d[1]](p.isFunction(g)?function(){var a=g.apply(this,arguments);a&&p.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[f+"With"](this===e?c:this,[a])}:c[f])}),a=null}).promise()},promise:function(a){return a!=null?p.extend(a,d):d}},e={};return d.pipe=d.then,p.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[a^1][2].disable,b[2][2].lock),e[f[0]]=g.fire,e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=k.call(arguments),d=c.length,e=d!==1||a&&p.isFunction(a.promise)?d:0,f=e===1?a:p.Deferred(),g=function(a,b,c){return function(d){b[a]=this,c[a]=arguments.length>1?k.call(arguments):d,c===h?f.notifyWith(b,c):--e||f.resolveWith(b,c)}},h,i,j;if(d>1){h=new Array(d),i=new Array(d),j=new Array(d);for(;b<d;b++)c[b]&&p.isFunction(c[b].promise)?c[b].promise().done(g(b,j,c)).fail(f.reject).progress(g(b,i,h)):--e}return e||f.resolveWith(j,c),f.promise()}}),p.support=function(){var b,c,d,f,g,h,i,j,k,l,m,n=e.createElement("div");n.setAttribute("className","t"),n.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",c=n.getElementsByTagName("*"),d=n.getElementsByTagName("a")[0],d.style.cssText="top:1px;float:left;opacity:.5";if(!c||!c.length)return{};f=e.createElement("select"),g=f.appendChild(e.createElement("option")),h=n.getElementsByTagName("input")[0],b={leadingWhitespace:n.firstChild.nodeType===3,tbody:!n.getElementsByTagName("tbody").length,htmlSerialize:!!n.getElementsByTagName("link").length,style:/top/.test(d.getAttribute("style")),hrefNormalized:d.getAttribute("href")==="/a",opacity:/^0.5/.test(d.style.opacity),cssFloat:!!d.style.cssFloat,checkOn:h.value==="on",optSelected:g.selected,getSetAttribute:n.className!=="t",enctype:!!e.createElement("form").enctype,html5Clone:e.createElement("nav").cloneNode(!0).outerHTML!=="<:nav></:nav>",boxModel:e.compatMode==="CSS1Compat",submitBubbles:!0,changeBubbles:!0,focusinBubbles:!1,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0,boxSizingReliable:!0,pixelPosition:!1},h.checked=!0,b.noCloneChecked=h.cloneNode(!0).checked,f.disabled=!0,b.optDisabled=!g.disabled;try{delete n.test}catch(o){b.deleteExpando=!1}!n.addEventListener&&n.attachEvent&&n.fireEvent&&(n.attachEvent("onclick",m=function(){b.noCloneEvent=!1}),n.cloneNode(!0).fireEvent("onclick"),n.detachEvent("onclick",m)),h=e.createElement("input"),h.value="t",h.setAttribute("type","radio"),b.radioValue=h.value==="t",h.setAttribute("checked","checked"),h.setAttribute("name","t"),n.appendChild(h),i=e.createDocumentFragment(),i.appendChild(n.lastChild),b.checkClone=i.cloneNode(!0).cloneNode(!0).lastChild.checked,b.appendChecked=h.checked,i.removeChild(h),i.appendChild(n);if(n.attachEvent)for(k in{submit:!0,change:!0,focusin:!0})j="on"+k,l=j in n,l||(n.setAttribute(j,"return;"),l=typeof n[j]=="function"),b[k+"Bubbles"]=l;return p(function(){var c,d,f,g,h="padding:0;margin:0;border:0;display:block;overflow:hidden;",i=e.getElementsByTagName("body")[0];if(!i)return;c=e.createElement("div"),c.style.cssText="visibility:hidden;border:0;width:0;height:0;position:static;top:0;margin-top:1px",i.insertBefore(c,i.firstChild),d=e.createElement("div"),c.appendChild(d),d.innerHTML="<table><tr><td></td><td>t</td></tr></table>",f=d.getElementsByTagName("td"),f[0].style.cssText="padding:0;margin:0;border:0;display:none",l=f[0].offsetHeight===0,f[0].style.display="",f[1].style.display="none",b.reliableHiddenOffsets=l&&f[0].offsetHeight===0,d.innerHTML="",d.style.cssText="box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;",b.boxSizing=d.offsetWidth===4,b.doesNotIncludeMarginInBodyOffset=i.offsetTop!==1,a.getComputedStyle&&(b.pixelPosition=(a.getComputedStyle(d,null)||{}).top!=="1%",b.boxSizingReliable=(a.getComputedStyle(d,null)||{width:"4px"}).width==="4px",g=e.createElement("div"),g.style.cssText=d.style.cssText=h,g.style.marginRight=g.style.width="0",d.style.width="1px",d.appendChild(g),b.reliableMarginRight=!parseFloat((a.getComputedStyle(g,null)||{}).marginRight)),typeof d.style.zoom!="undefined"&&(d.innerHTML="",d.style.cssText=h+"width:1px;padding:1px;display:inline;zoom:1",b.inlineBlockNeedsLayout=d.offsetWidth===3,d.style.display="block",d.style.overflow="visible",d.innerHTML="<div></div>",d.firstChild.style.width="5px",b.shrinkWrapBlocks=d.offsetWidth!==3,c.style.zoom=1),i.removeChild(c),c=d=f=g=null}),i.removeChild(n),c=d=f=g=h=i=n=null,b}();var H=/(?:\{[\s\S]*\}|\[[\s\S]*\])$/,I=/([A-Z])/g;p.extend({cache:{},deletedIds:[],uuid:0,expando:"jQuery"+(p.fn.jquery+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(a){return a=a.nodeType?p.cache[a[p.expando]]:a[p.expando],!!a&&!K(a)},data:function(a,c,d,e){if(!p.acceptData(a))return;var f,g,h=p.expando,i=typeof c=="string",j=a.nodeType,k=j?p.cache:a,l=j?a[h]:a[h]&&h;if((!l||!k[l]||!e&&!k[l].data)&&i&&d===b)return;l||(j?a[h]=l=p.deletedIds.pop()||p.guid++:l=h),k[l]||(k[l]={},j||(k[l].toJSON=p.noop));if(typeof c=="object"||typeof c=="function")e?k[l]=p.extend(k[l],c):k[l].data=p.extend(k[l].data,c);return f=k[l],e||(f.data||(f.data={}),f=f.data),d!==b&&(f[p.camelCase(c)]=d),i?(g=f[c],g==null&&(g=f[p.camelCase(c)])):g=f,g},removeData:function(a,b,c){if(!p.acceptData(a))return;var d,e,f,g=a.nodeType,h=g?p.cache:a,i=g?a[p.expando]:p.expando;if(!h[i])return;if(b){d=c?h[i]:h[i].data;if(d){p.isArray(b)||(b in d?b=[b]:(b=p.camelCase(b),b in d?b=[b]:b=b.split(" ")));for(e=0,f=b.length;e<f;e++)delete d[b[e]];if(!(c?K:p.isEmptyObject)(d))return}}if(!c){delete h[i].data;if(!K(h[i]))return}g?p.cleanData([a],!0):p.support.deleteExpando||h!=h.window?delete h[i]:h[i]=null},_data:function(a,b,c){return p.data(a,b,c,!0)},acceptData:function(a){var b=a.nodeName&&p.noData[a.nodeName.toLowerCase()];return!b||b!==!0&&a.getAttribute("classid")===b}}),p.fn.extend({data:function(a,c){var d,e,f,g,h,i=this[0],j=0,k=null;if(a===b){if(this.length){k=p.data(i);if(i.nodeType===1&&!p._data(i,"parsedAttrs")){f=i.attributes;for(h=f.length;j<h;j++)g=f[j].name,g.indexOf("data-")||(g=p.camelCase(g.substring(5)),J(i,g,k[g]));p._data(i,"parsedAttrs",!0)}}return k}return typeof a=="object"?this.each(function(){p.data(this,a)}):(d=a.split(".",2),d[1]=d[1]?"."+d[1]:"",e=d[1]+"!",p.access(this,function(c){if(c===b)return k=this.triggerHandler("getData"+e,[d[0]]),k===b&&i&&(k=p.data(i,a),k=J(i,a,k)),k===b&&d[1]?this.data(d[0]):k;d[1]=c,this.each(function(){var b=p(this);b.triggerHandler("setData"+e,d),p.data(this,a,c),b.triggerHandler("changeData"+e,d)})},null,c,arguments.length>1,null,!1))},removeData:function(a){return this.each(function(){p.removeData(this,a)})}}),p.extend({queue:function(a,b,c){var d;if(a)return b=(b||"fx")+"queue",d=p._data(a,b),c&&(!d||p.isArray(c)?d=p._data(a,b,p.makeArray(c)):d.push(c)),d||[]},dequeue:function(a,b){b=b||"fx";var c=p.queue(a,b),d=c.length,e=c.shift(),f=p._queueHooks(a,b),g=function(){p.dequeue(a,b)};e==="inprogress"&&(e=c.shift(),d--),e&&(b==="fx"&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return p._data(a,c)||p._data(a,c,{empty:p.Callbacks("once memory").add(function(){p.removeData(a,b+"queue",!0),p.removeData(a,c,!0)})})}}),p.fn.extend({queue:function(a,c){var d=2;return typeof a!="string"&&(c=a,a="fx",d--),arguments.length<d?p.queue(this[0],a):c===b?this:this.each(function(){var b=p.queue(this,a,c);p._queueHooks(this,a),a==="fx"&&b[0]!=="inprogress"&&p.dequeue(this,a)})},dequeue:function(a){return this.each(function(){p.dequeue(this,a)})},delay:function(a,b){return a=p.fx?p.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,c){var d,e=1,f=p.Deferred(),g=this,h=this.length,i=function(){--e||f.resolveWith(g,[g])};typeof a!="string"&&(c=a,a=b),a=a||"fx";while(h--)d=p._data(g[h],a+"queueHooks"),d&&d.empty&&(e++,d.empty.add(i));return i(),f.promise(c)}});var L,M,N,O=/[\t\r\n]/g,P=/\r/g,Q=/^(?:button|input)$/i,R=/^(?:button|input|object|select|textarea)$/i,S=/^a(?:rea|)$/i,T=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,U=p.support.getSetAttribute;p.fn.extend({attr:function(a,b){return p.access(this,p.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){p.removeAttr(this,a)})},prop:function(a,b){return p.access(this,p.prop,a,b,arguments.length>1)},removeProp:function(a){return a=p.propFix[a]||a,this.each(function(){try{this[a]=b,delete this[a]}catch(c){}})},addClass:function(a){var b,c,d,e,f,g,h;if(p.isFunction(a))return this.each(function(b){p(this).addClass(a.call(this,b,this.className))});if(a&&typeof a=="string"){b=a.split(s);for(c=0,d=this.length;c<d;c++){e=this[c];if(e.nodeType===1)if(!e.className&&b.length===1)e.className=a;else{f=" "+e.className+" ";for(g=0,h=b.length;g<h;g++)f.indexOf(" "+b[g]+" ")<0&&(f+=b[g]+" ");e.className=p.trim(f)}}}return this},removeClass:function(a){var c,d,e,f,g,h,i;if(p.isFunction(a))return this.each(function(b){p(this).removeClass(a.call(this,b,this.className))});if(a&&typeof a=="string"||a===b){c=(a||"").split(s);for(h=0,i=this.length;h<i;h++){e=this[h];if(e.nodeType===1&&e.className){d=(" "+e.className+" ").replace(O," ");for(f=0,g=c.length;f<g;f++)while(d.indexOf(" "+c[f]+" ")>=0)d=d.replace(" "+c[f]+" "," ");e.className=a?p.trim(d):""}}}return this},toggleClass:function(a,b){var c=typeof a,d=typeof b=="boolean";return p.isFunction(a)?this.each(function(c){p(this).toggleClass(a.call(this,c,this.className,b),b)}):this.each(function(){if(c==="string"){var e,f=0,g=p(this),h=b,i=a.split(s);while(e=i[f++])h=d?h:!g.hasClass(e),g[h?"addClass":"removeClass"](e)}else if(c==="undefined"||c==="boolean")this.className&&p._data(this,"__className__",this.className),this.className=this.className||a===!1?"":p._data(this,"__className__")||""})},hasClass:function(a){var b=" "+a+" ",c=0,d=this.length;for(;c<d;c++)if(this[c].nodeType===1&&(" "+this[c].className+" ").replace(O," ").indexOf(b)>=0)return!0;return!1},val:function(a){var c,d,e,f=this[0];if(!arguments.length){if(f)return c=p.valHooks[f.type]||p.valHooks[f.nodeName.toLowerCase()],c&&"get"in c&&(d=c.get(f,"value"))!==b?d:(d=f.value,typeof d=="string"?d.replace(P,""):d==null?"":d);return}return e=p.isFunction(a),this.each(function(d){var f,g=p(this);if(this.nodeType!==1)return;e?f=a.call(this,d,g.val()):f=a,f==null?f="":typeof f=="number"?f+="":p.isArray(f)&&(f=p.map(f,function(a){return a==null?"":a+""})),c=p.valHooks[this.type]||p.valHooks[this.nodeName.toLowerCase()];if(!c||!("set"in c)||c.set(this,f,"value")===b)this.value=f})}}),p.extend({valHooks:{option:{get:function(a){var b=a.attributes.value;return!b||b.specified?a.value:a.text}},select:{get:function(a){var b,c,d,e,f=a.selectedIndex,g=[],h=a.options,i=a.type==="select-one";if(f<0)return null;c=i?f:0,d=i?f+1:h.length;for(;c<d;c++){e=h[c];if(e.selected&&(p.support.optDisabled?!e.disabled:e.getAttribute("disabled")===null)&&(!e.parentNode.disabled||!p.nodeName(e.parentNode,"optgroup"))){b=p(e).val();if(i)return b;g.push(b)}}return i&&!g.length&&h.length?p(h[f]).val():g},set:function(a,b){var c=p.makeArray(b);return p(a).find("option").each(function(){this.selected=p.inArray(p(this).val(),c)>=0}),c.length||(a.selectedIndex=-1),c}}},attrFn:{},attr:function(a,c,d,e){var f,g,h,i=a.nodeType;if(!a||i===3||i===8||i===2)return;if(e&&p.isFunction(p.fn[c]))return p(a)[c](d);if(typeof a.getAttribute=="undefined")return p.prop(a,c,d);h=i!==1||!p.isXMLDoc(a),h&&(c=c.toLowerCase(),g=p.attrHooks[c]||(T.test(c)?M:L));if(d!==b){if(d===null){p.removeAttr(a,c);return}return g&&"set"in g&&h&&(f=g.set(a,d,c))!==b?f:(a.setAttribute(c,d+""),d)}return g&&"get"in g&&h&&(f=g.get(a,c))!==null?f:(f=a.getAttribute(c),f===null?b:f)},removeAttr:function(a,b){var c,d,e,f,g=0;if(b&&a.nodeType===1){d=b.split(s);for(;g<d.length;g++)e=d[g],e&&(c=p.propFix[e]||e,f=T.test(e),f||p.attr(a,e,""),a.removeAttribute(U?e:c),f&&c in a&&(a[c]=!1))}},attrHooks:{type:{set:function(a,b){if(Q.test(a.nodeName)&&a.parentNode)p.error("type property can't be changed");else if(!p.support.radioValue&&b==="radio"&&p.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}},value:{get:function(a,b){return L&&p.nodeName(a,"button")?L.get(a,b):b in a?a.value:null},set:function(a,b,c){if(L&&p.nodeName(a,"button"))return L.set(a,b,c);a.value=b}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(a,c,d){var e,f,g,h=a.nodeType;if(!a||h===3||h===8||h===2)return;return g=h!==1||!p.isXMLDoc(a),g&&(c=p.propFix[c]||c,f=p.propHooks[c]),d!==b?f&&"set"in f&&(e=f.set(a,d,c))!==b?e:a[c]=d:f&&"get"in f&&(e=f.get(a,c))!==null?e:a[c]},propHooks:{tabIndex:{get:function(a){var c=a.getAttributeNode("tabindex");return c&&c.specified?parseInt(c.value,10):R.test(a.nodeName)||S.test(a.nodeName)&&a.href?0:b}}}}),M={get:function(a,c){var d,e=p.prop(a,c);return e===!0||typeof e!="boolean"&&(d=a.getAttributeNode(c))&&d.nodeValue!==!1?c.toLowerCase():b},set:function(a,b,c){var d;return b===!1?p.removeAttr(a,c):(d=p.propFix[c]||c,d in a&&(a[d]=!0),a.setAttribute(c,c.toLowerCase())),c}},U||(N={name:!0,id:!0,coords:!0},L=p.valHooks.button={get:function(a,c){var d;return d=a.getAttributeNode(c),d&&(N[c]?d.value!=="":d.specified)?d.value:b},set:function(a,b,c){var d=a.getAttributeNode(c);return d||(d=e.createAttribute(c),a.setAttributeNode(d)),d.value=b+""}},p.each(["width","height"],function(a,b){p.attrHooks[b]=p.extend(p.attrHooks[b],{set:function(a,c){if(c==="")return a.setAttribute(b,"auto"),c}})}),p.attrHooks.contenteditable={get:L.get,set:function(a,b,c){b===""&&(b="false"),L.set(a,b,c)}}),p.support.hrefNormalized||p.each(["href","src","width","height"],function(a,c){p.attrHooks[c]=p.extend(p.attrHooks[c],{get:function(a){var d=a.getAttribute(c,2);return d===null?b:d}})}),p.support.style||(p.attrHooks.style={get:function(a){return a.style.cssText.toLowerCase()||b},set:function(a,b){return a.style.cssText=b+""}}),p.support.optSelected||(p.propHooks.selected=p.extend(p.propHooks.selected,{get:function(a){var b=a.parentNode;return b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex),null}})),p.support.enctype||(p.propFix.enctype="encoding"),p.support.checkOn||p.each(["radio","checkbox"],function(){p.valHooks[this]={get:function(a){return a.getAttribute("value")===null?"on":a.value}}}),p.each(["radio","checkbox"],function(){p.valHooks[this]=p.extend(p.valHooks[this],{set:function(a,b){if(p.isArray(b))return a.checked=p.inArray(p(a).val(),b)>=0}})});var V=/^(?:textarea|input|select)$/i,W=/^([^\.]*|)(?:\.(.+)|)$/,X=/(?:^|\s)hover(\.\S+|)\b/,Y=/^key/,Z=/^(?:mouse|contextmenu)|click/,$=/^(?:focusinfocus|focusoutblur)$/,_=function(a){return p.event.special.hover?a:a.replace(X,"mouseenter$1 mouseleave$1")};p.event={add:function(a,c,d,e,f){var g,h,i,j,k,l,m,n,o,q,r;if(a.nodeType===3||a.nodeType===8||!c||!d||!(g=p._data(a)))return;d.handler&&(o=d,d=o.handler,f=o.selector),d.guid||(d.guid=p.guid++),i=g.events,i||(g.events=i={}),h=g.handle,h||(g.handle=h=function(a){return typeof p!="undefined"&&(!a||p.event.triggered!==a.type)?p.event.dispatch.apply(h.elem,arguments):b},h.elem=a),c=p.trim(_(c)).split(" ");for(j=0;j<c.length;j++){k=W.exec(c[j])||[],l=k[1],m=(k[2]||"").split(".").sort(),r=p.event.special[l]||{},l=(f?r.delegateType:r.bindType)||l,r=p.event.special[l]||{},n=p.extend({type:l,origType:k[1],data:e,handler:d,guid:d.guid,selector:f,needsContext:f&&p.expr.match.needsContext.test(f),namespace:m.join(".")},o),q=i[l];if(!q){q=i[l]=[],q.delegateCount=0;if(!r.setup||r.setup.call(a,e,m,h)===!1)a.addEventListener?a.addEventListener(l,h,!1):a.attachEvent&&a.attachEvent("on"+l,h)}r.add&&(r.add.call(a,n),n.handler.guid||(n.handler.guid=d.guid)),f?q.splice(q.delegateCount++,0,n):q.push(n),p.event.global[l]=!0}a=null},global:{},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,n,o,q,r=p.hasData(a)&&p._data(a);if(!r||!(m=r.events))return;b=p.trim(_(b||"")).split(" ");for(f=0;f<b.length;f++){g=W.exec(b[f])||[],h=i=g[1],j=g[2];if(!h){for(h in m)p.event.remove(a,h+b[f],c,d,!0);continue}n=p.event.special[h]||{},h=(d?n.delegateType:n.bindType)||h,o=m[h]||[],k=o.length,j=j?new RegExp("(^|\\.)"+j.split(".").sort().join("\\.(?:.*\\.|)")+"(\\.|$)"):null;for(l=0;l<o.length;l++)q=o[l],(e||i===q.origType)&&(!c||c.guid===q.guid)&&(!j||j.test(q.namespace))&&(!d||d===q.selector||d==="**"&&q.selector)&&(o.splice(l--,1),q.selector&&o.delegateCount--,n.remove&&n.remove.call(a,q));o.length===0&&k!==o.length&&((!n.teardown||n.teardown.call(a,j,r.handle)===!1)&&p.removeEvent(a,h,r.handle),delete m[h])}p.isEmptyObject(m)&&(delete r.handle,p.removeData(a,"events",!0))},customEvent:{getData:!0,setData:!0,changeData:!0},trigger:function(c,d,f,g){if(!f||f.nodeType!==3&&f.nodeType!==8){var h,i,j,k,l,m,n,o,q,r,s=c.type||c,t=[];if($.test(s+p.event.triggered))return;s.indexOf("!")>=0&&(s=s.slice(0,-1),i=!0),s.indexOf(".")>=0&&(t=s.split("."),s=t.shift(),t.sort());if((!f||p.event.customEvent[s])&&!p.event.global[s])return;c=typeof c=="object"?c[p.expando]?c:new p.Event(s,c):new p.Event(s),c.type=s,c.isTrigger=!0,c.exclusive=i,c.namespace=t.join("."),c.namespace_re=c.namespace?new RegExp("(^|\\.)"+t.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,m=s.indexOf(":")<0?"on"+s:"";if(!f){h=p.cache;for(j in h)h[j].events&&h[j].events[s]&&p.event.trigger(c,d,h[j].handle.elem,!0);return}c.result=b,c.target||(c.target=f),d=d!=null?p.makeArray(d):[],d.unshift(c),n=p.event.special[s]||{};if(n.trigger&&n.trigger.apply(f,d)===!1)return;q=[[f,n.bindType||s]];if(!g&&!n.noBubble&&!p.isWindow(f)){r=n.delegateType||s,k=$.test(r+s)?f:f.parentNode;for(l=f;k;k=k.parentNode)q.push([k,r]),l=k;l===(f.ownerDocument||e)&&q.push([l.defaultView||l.parentWindow||a,r])}for(j=0;j<q.length&&!c.isPropagationStopped();j++)k=q[j][0],c.type=q[j][1],o=(p._data(k,"events")||{})[c.type]&&p._data(k,"handle"),o&&o.apply(k,d),o=m&&k[m],o&&p.acceptData(k)&&o.apply&&o.apply(k,d)===!1&&c.preventDefault();return c.type=s,!g&&!c.isDefaultPrevented()&&(!n._default||n._default.apply(f.ownerDocument,d)===!1)&&(s!=="click"||!p.nodeName(f,"a"))&&p.acceptData(f)&&m&&f[s]&&(s!=="focus"&&s!=="blur"||c.target.offsetWidth!==0)&&!p.isWindow(f)&&(l=f[m],l&&(f[m]=null),p.event.triggered=s,f[s](),p.event.triggered=b,l&&(f[m]=l)),c.result}return},dispatch:function(c){c=p.event.fix(c||a.event);var d,e,f,g,h,i,j,l,m,n,o=(p._data(this,"events")||{})[c.type]||[],q=o.delegateCount,r=k.call(arguments),s=!c.exclusive&&!c.namespace,t=p.event.special[c.type]||{},u=[];r[0]=c,c.delegateTarget=this;if(t.preDispatch&&t.preDispatch.call(this,c)===!1)return;if(q&&(!c.button||c.type!=="click"))for(f=c.target;f!=this;f=f.parentNode||this)if(f.disabled!==!0||c.type!=="click"){h={},j=[];for(d=0;d<q;d++)l=o[d],m=l.selector,h[m]===b&&(h[m]=l.needsContext?p(m,this).index(f)>=0:p.find(m,this,null,[f]).length),h[m]&&j.push(l);j.length&&u.push({elem:f,matches:j})}o.length>q&&u.push({elem:this,matches:o.slice(q)});for(d=0;d<u.length&&!c.isPropagationStopped();d++){i=u[d],c.currentTarget=i.elem;for(e=0;e<i.matches.length&&!c.isImmediatePropagationStopped();e++){l=i.matches[e];if(s||!c.namespace&&!l.namespace||c.namespace_re&&c.namespace_re.test(l.namespace))c.data=l.data,c.handleObj=l,g=((p.event.special[l.origType]||{}).handle||l.handler).apply(i.elem,r),g!==b&&(c.result=g,g===!1&&(c.preventDefault(),c.stopPropagation()))}}return t.postDispatch&&t.postDispatch.call(this,c),c.result},props:"attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return a.which==null&&(a.which=b.charCode!=null?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,c){var d,f,g,h=c.button,i=c.fromElement;return a.pageX==null&&c.clientX!=null&&(d=a.target.ownerDocument||e,f=d.documentElement,g=d.body,a.pageX=c.clientX+(f&&f.scrollLeft||g&&g.scrollLeft||0)-(f&&f.clientLeft||g&&g.clientLeft||0),a.pageY=c.clientY+(f&&f.scrollTop||g&&g.scrollTop||0)-(f&&f.clientTop||g&&g.clientTop||0)),!a.relatedTarget&&i&&(a.relatedTarget=i===a.target?c.toElement:i),!a.which&&h!==b&&(a.which=h&1?1:h&2?3:h&4?2:0),a}},fix:function(a){if(a[p.expando])return a;var b,c,d=a,f=p.event.fixHooks[a.type]||{},g=f.props?this.props.concat(f.props):this.props;a=p.Event(d);for(b=g.length;b;)c=g[--b],a[c]=d[c];return a.target||(a.target=d.srcElement||e),a.target.nodeType===3&&(a.target=a.target.parentNode),a.metaKey=!!a.metaKey,f.filter?f.filter(a,d):a},special:{load:{noBubble:!0},focus:{delegateType:"focusin"},blur:{delegateType:"focusout"},beforeunload:{setup:function(a,b,c){p.isWindow(this)&&(this.onbeforeunload=c)},teardown:function(a,b){this.onbeforeunload===b&&(this.onbeforeunload=null)}}},simulate:function(a,b,c,d){var e=p.extend(new p.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?p.event.trigger(e,null,b):p.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},p.event.handle=p.event.dispatch,p.removeEvent=e.removeEventListener?function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)}:function(a,b,c){var d="on"+b;a.detachEvent&&(typeof a[d]=="undefined"&&(a[d]=null),a.detachEvent(d,c))},p.Event=function(a,b){if(this instanceof p.Event)a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||a.returnValue===!1||a.getPreventDefault&&a.getPreventDefault()?bb:ba):this.type=a,b&&p.extend(this,b),this.timeStamp=a&&a.timeStamp||p.now(),this[p.expando]=!0;else return new p.Event(a,b)},p.Event.prototype={preventDefault:function(){this.isDefaultPrevented=bb;var a=this.originalEvent;if(!a)return;a.preventDefault?a.preventDefault():a.returnValue=!1},stopPropagation:function(){this.isPropagationStopped=bb;var a=this.originalEvent;if(!a)return;a.stopPropagation&&a.stopPropagation(),a.cancelBubble=!0},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=bb,this.stopPropagation()},isDefaultPrevented:ba,isPropagationStopped:ba,isImmediatePropagationStopped:ba},p.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(a,b){p.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj,g=f.selector;if(!e||e!==d&&!p.contains(d,e))a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b;return c}}}),p.support.submitBubbles||(p.event.special.submit={setup:function(){if(p.nodeName(this,"form"))return!1;p.event.add(this,"click._submit keypress._submit",function(a){var c=a.target,d=p.nodeName(c,"input")||p.nodeName(c,"button")?c.form:b;d&&!p._data(d,"_submit_attached")&&(p.event.add(d,"submit._submit",function(a){a._submit_bubble=!0}),p._data(d,"_submit_attached",!0))})},postDispatch:function(a){a._submit_bubble&&(delete a._submit_bubble,this.parentNode&&!a.isTrigger&&p.event.simulate("submit",this.parentNode,a,!0))},teardown:function(){if(p.nodeName(this,"form"))return!1;p.event.remove(this,"._submit")}}),p.support.changeBubbles||(p.event.special.change={setup:function(){if(V.test(this.nodeName)){if(this.type==="checkbox"||this.type==="radio")p.event.add(this,"propertychange._change",function(a){a.originalEvent.propertyName==="checked"&&(this._just_changed=!0)}),p.event.add(this,"click._change",function(a){this._just_changed&&!a.isTrigger&&(this._just_changed=!1),p.event.simulate("change",this,a,!0)});return!1}p.event.add(this,"beforeactivate._change",function(a){var b=a.target;V.test(b.nodeName)&&!p._data(b,"_change_attached")&&(p.event.add(b,"change._change",function(a){this.parentNode&&!a.isSimulated&&!a.isTrigger&&p.event.simulate("change",this.parentNode,a,!0)}),p._data(b,"_change_attached",!0))})},handle:function(a){var b=a.target;if(this!==b||a.isSimulated||a.isTrigger||b.type!=="radio"&&b.type!=="checkbox")return a.handleObj.handler.apply(this,arguments)},teardown:function(){return p.event.remove(this,"._change"),!V.test(this.nodeName)}}),p.support.focusinBubbles||p.each({focus:"focusin",blur:"focusout"},function(a,b){var c=0,d=function(a){p.event.simulate(b,a.target,p.event.fix(a),!0)};p.event.special[b]={setup:function(){c++===0&&e.addEventListener(a,d,!0)},teardown:function(){--c===0&&e.removeEventListener(a,d,!0)}}}),p.fn.extend({on:function(a,c,d,e,f){var g,h;if(typeof a=="object"){typeof c!="string"&&(d=d||c,c=b);for(h in a)this.on(h,c,d,a[h],f);return this}d==null&&e==null?(e=c,d=c=b):e==null&&(typeof c=="string"?(e=d,d=b):(e=d,d=c,c=b));if(e===!1)e=ba;else if(!e)return this;return f===1&&(g=e,e=function(a){return p().off(a),g.apply(this,arguments)},e.guid=g.guid||(g.guid=p.guid++)),this.each(function(){p.event.add(this,a,e,d,c)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,c,d){var e,f;if(a&&a.preventDefault&&a.handleObj)return e=a.handleObj,p(a.delegateTarget).off(e.namespace?e.origType+"."+e.namespace:e.origType,e.selector,e.handler),this;if(typeof a=="object"){for(f in a)this.off(f,c,a[f]);return this}if(c===!1||typeof c=="function")d=c,c=b;return d===!1&&(d=ba),this.each(function(){p.event.remove(this,a,d,c)})},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},live:function(a,b,c){return p(this.context).on(a,this.selector,b,c),this},die:function(a,b){return p(this.context).off(a,this.selector||"**",b),this},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return arguments.length===1?this.off(a,"**"):this.off(b,a||"**",c)},trigger:function(a,b){return this.each(function(){p.event.trigger(a,b,this)})},triggerHandler:function(a,b){if(this[0])return p.event.trigger(a,b,this[0],!0)},toggle:function(a){var b=arguments,c=a.guid||p.guid++,d=0,e=function(c){var e=(p._data(this,"lastToggle"+a.guid)||0)%d;return p._data(this,"lastToggle"+a.guid,e+1),c.preventDefault(),b[e].apply(this,arguments)||!1};e.guid=c;while(d<b.length)b[d++].guid=c;return this.click(e)},hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}}),p.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){p.fn[b]=function(a,c){return c==null&&(c=a,a=null),arguments.length>0?this.on(b,null,a,c):this.trigger(b)},Y.test(b)&&(p.event.fixHooks[b]=p.event.keyHooks),Z.test(b)&&(p.event.fixHooks[b]=p.event.mouseHooks)}),function(a,b){function bc(a,b,c,d){c=c||[],b=b||r;var e,f,i,j,k=b.nodeType;if(!a||typeof a!="string")return c;if(k!==1&&k!==9)return[];i=g(b);if(!i&&!d)if(e=P.exec(a))if(j=e[1]){if(k===9){f=b.getElementById(j);if(!f||!f.parentNode)return c;if(f.id===j)return c.push(f),c}else if(b.ownerDocument&&(f=b.ownerDocument.getElementById(j))&&h(b,f)&&f.id===j)return c.push(f),c}else{if(e[2])return w.apply(c,x.call(b.getElementsByTagName(a),0)),c;if((j=e[3])&&_&&b.getElementsByClassName)return w.apply(c,x.call(b.getElementsByClassName(j),0)),c}return bp(a.replace(L,"$1"),b,c,d,i)}function bd(a){return function(b){var c=b.nodeName.toLowerCase();return c==="input"&&b.type===a}}function be(a){return function(b){var c=b.nodeName.toLowerCase();return(c==="input"||c==="button")&&b.type===a}}function bf(a){return z(function(b){return b=+b,z(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function bg(a,b,c){if(a===b)return c;var d=a.nextSibling;while(d){if(d===b)return-1;d=d.nextSibling}return 1}function bh(a,b){var c,d,f,g,h,i,j,k=C[o][a];if(k)return b?0:k.slice(0);h=a,i=[],j=e.preFilter;while(h){if(!c||(d=M.exec(h)))d&&(h=h.slice(d[0].length)),i.push(f=[]);c=!1;if(d=N.exec(h))f.push(c=new q(d.shift())),h=h.slice(c.length),c.type=d[0].replace(L," ");for(g in e.filter)(d=W[g].exec(h))&&(!j[g]||(d=j[g](d,r,!0)))&&(f.push(c=new q(d.shift())),h=h.slice(c.length),c.type=g,c.matches=d);if(!c)break}return b?h.length:h?bc.error(a):C(a,i).slice(0)}function bi(a,b,d){var e=b.dir,f=d&&b.dir==="parentNode",g=u++;return b.first?function(b,c,d){while(b=b[e])if(f||b.nodeType===1)return a(b,c,d)}:function(b,d,h){if(!h){var i,j=t+" "+g+" ",k=j+c;while(b=b[e])if(f||b.nodeType===1){if((i=b[o])===k)return b.sizset;if(typeof i=="string"&&i.indexOf(j)===0){if(b.sizset)return b}else{b[o]=k;if(a(b,d,h))return b.sizset=!0,b;b.sizset=!1}}}else while(b=b[e])if(f||b.nodeType===1)if(a(b,d,h))return b}}function bj(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function bk(a,b,c,d,e){var f,g=[],h=0,i=a.length,j=b!=null;for(;h<i;h++)if(f=a[h])if(!c||c(f,d,e))g.push(f),j&&b.push(h);return g}function bl(a,b,c,d,e,f){return d&&!d[o]&&(d=bl(d)),e&&!e[o]&&(e=bl(e,f)),z(function(f,g,h,i){if(f&&e)return;var j,k,l,m=[],n=[],o=g.length,p=f||bo(b||"*",h.nodeType?[h]:h,[],f),q=a&&(f||!b)?bk(p,m,a,h,i):p,r=c?e||(f?a:o||d)?[]:g:q;c&&c(q,r,h,i);if(d){l=bk(r,n),d(l,[],h,i),j=l.length;while(j--)if(k=l[j])r[n[j]]=!(q[n[j]]=k)}if(f){j=a&&r.length;while(j--)if(k=r[j])f[m[j]]=!(g[m[j]]=k)}else r=bk(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):w.apply(g,r)})}function bm(a){var b,c,d,f=a.length,g=e.relative[a[0].type],h=g||e.relative[" "],i=g?1:0,j=bi(function(a){return a===b},h,!0),k=bi(function(a){return y.call(b,a)>-1},h,!0),m=[function(a,c,d){return!g&&(d||c!==l)||((b=c).nodeType?j(a,c,d):k(a,c,d))}];for(;i<f;i++)if(c=e.relative[a[i].type])m=[bi(bj(m),c)];else{c=e.filter[a[i].type].apply(null,a[i].matches);if(c[o]){d=++i;for(;d<f;d++)if(e.relative[a[d].type])break;return bl(i>1&&bj(m),i>1&&a.slice(0,i-1).join("").replace(L,"$1"),c,i<d&&bm(a.slice(i,d)),d<f&&bm(a=a.slice(d)),d<f&&a.join(""))}m.push(c)}return bj(m)}function bn(a,b){var d=b.length>0,f=a.length>0,g=function(h,i,j,k,m){var n,o,p,q=[],s=0,u="0",x=h&&[],y=m!=null,z=l,A=h||f&&e.find.TAG("*",m&&i.parentNode||i),B=t+=z==null?1:Math.E;y&&(l=i!==r&&i,c=g.el);for(;(n=A[u])!=null;u++){if(f&&n){for(o=0;p=a[o];o++)if(p(n,i,j)){k.push(n);break}y&&(t=B,c=++g.el)}d&&((n=!p&&n)&&s--,h&&x.push(n))}s+=u;if(d&&u!==s){for(o=0;p=b[o];o++)p(x,q,i,j);if(h){if(s>0)while(u--)!x[u]&&!q[u]&&(q[u]=v.call(k));q=bk(q)}w.apply(k,q),y&&!h&&q.length>0&&s+b.length>1&&bc.uniqueSort(k)}return y&&(t=B,l=z),x};return g.el=0,d?z(g):g}function bo(a,b,c,d){var e=0,f=b.length;for(;e<f;e++)bc(a,b[e],c,d);return c}function bp(a,b,c,d,f){var g,h,j,k,l,m=bh(a),n=m.length;if(!d&&m.length===1){h=m[0]=m[0].slice(0);if(h.length>2&&(j=h[0]).type==="ID"&&b.nodeType===9&&!f&&e.relative[h[1].type]){b=e.find.ID(j.matches[0].replace(V,""),b,f)[0];if(!b)return c;a=a.slice(h.shift().length)}for(g=W.POS.test(a)?-1:h.length-1;g>=0;g--){j=h[g];if(e.relative[k=j.type])break;if(l=e.find[k])if(d=l(j.matches[0].replace(V,""),R.test(h[0].type)&&b.parentNode||b,f)){h.splice(g,1),a=d.length&&h.join("");if(!a)return w.apply(c,x.call(d,0)),c;break}}}return i(a,m)(d,b,f,c,R.test(a)),c}function bq(){}var c,d,e,f,g,h,i,j,k,l,m=!0,n="undefined",o=("sizcache"+Math.random()).replace(".",""),q=String,r=a.document,s=r.documentElement,t=0,u=0,v=[].pop,w=[].push,x=[].slice,y=[].indexOf||function(a){var b=0,c=this.length;for(;b<c;b++)if(this[b]===a)return b;return-1},z=function(a,b){return a[o]=b==null||b,a},A=function(){var a={},b=[];return z(function(c,d){return b.push(c)>e.cacheLength&&delete a[b.shift()],a[c]=d},a)},B=A(),C=A(),D=A(),E="[\\x20\\t\\r\\n\\f]",F="(?:\\\\.|[-\\w]|[^\\x00-\\xa0])+",G=F.replace("w","w#"),H="([*^$|!~]?=)",I="\\["+E+"*("+F+")"+E+"*(?:"+H+E+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+G+")|)|)"+E+"*\\]",J=":("+F+")(?:\\((?:(['\"])((?:\\\\.|[^\\\\])*?)\\2|([^()[\\]]*|(?:(?:"+I+")|[^:]|\\\\.)*|.*))\\)|)",K=":(even|odd|eq|gt|lt|nth|first|last)(?:\\("+E+"*((?:-\\d)?\\d*)"+E+"*\\)|)(?=[^-]|$)",L=new RegExp("^"+E+"+|((?:^|[^\\\\])(?:\\\\.)*)"+E+"+$","g"),M=new RegExp("^"+E+"*,"+E+"*"),N=new RegExp("^"+E+"*([\\x20\\t\\r\\n\\f>+~])"+E+"*"),O=new RegExp(J),P=/^(?:#([\w\-]+)|(\w+)|\.([\w\-]+))$/,Q=/^:not/,R=/[\x20\t\r\n\f]*[+~]/,S=/:not\($/,T=/h\d/i,U=/input|select|textarea|button/i,V=/\\(?!\\)/g,W={ID:new RegExp("^#("+F+")"),CLASS:new RegExp("^\\.("+F+")"),NAME:new RegExp("^\\[name=['\"]?("+F+")['\"]?\\]"),TAG:new RegExp("^("+F.replace("w","w*")+")"),ATTR:new RegExp("^"+I),PSEUDO:new RegExp("^"+J),POS:new RegExp(K,"i"),CHILD:new RegExp("^:(only|nth|first|last)-child(?:\\("+E+"*(even|odd|(([+-]|)(\\d*)n|)"+E+"*(?:([+-]|)"+E+"*(\\d+)|))"+E+"*\\)|)","i"),needsContext:new RegExp("^"+E+"*[>+~]|"+K,"i")},X=function(a){var b=r.createElement("div");try{return a(b)}catch(c){return!1}finally{b=null}},Y=X(function(a){return a.appendChild(r.createComment("")),!a.getElementsByTagName("*").length}),Z=X(function(a){return a.innerHTML="<a href='#'></a>",a.firstChild&&typeof a.firstChild.getAttribute!==n&&a.firstChild.getAttribute("href")==="#"}),$=X(function(a){a.innerHTML="<select></select>";var b=typeof a.lastChild.getAttribute("multiple");return b!=="boolean"&&b!=="string"}),_=X(function(a){return a.innerHTML="<div class='hidden e'></div><div class='hidden'></div>",!a.getElementsByClassName||!a.getElementsByClassName("e").length?!1:(a.lastChild.className="e",a.getElementsByClassName("e").length===2)}),ba=X(function(a){a.id=o+0,a.innerHTML="<a name='"+o+"'></a><div name='"+o+"'></div>",s.insertBefore(a,s.firstChild);var b=r.getElementsByName&&r.getElementsByName(o).length===2+r.getElementsByName(o+0).length;return d=!r.getElementById(o),s.removeChild(a),b});try{x.call(s.childNodes,0)[0].nodeType}catch(bb){x=function(a){var b,c=[];for(;b=this[a];a++)c.push(b);return c}}bc.matches=function(a,b){return bc(a,null,null,b)},bc.matchesSelector=function(a,b){return bc(b,null,null,[a]).length>0},f=bc.getText=function(a){var b,c="",d=0,e=a.nodeType;if(e){if(e===1||e===9||e===11){if(typeof a.textContent=="string")return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=f(a)}else if(e===3||e===4)return a.nodeValue}else for(;b=a[d];d++)c+=f(b);return c},g=bc.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?b.nodeName!=="HTML":!1},h=bc.contains=s.contains?function(a,b){var c=a.nodeType===9?a.documentElement:a,d=b&&b.parentNode;return a===d||!!(d&&d.nodeType===1&&c.contains&&c.contains(d))}:s.compareDocumentPosition?function(a,b){return b&&!!(a.compareDocumentPosition(b)&16)}:function(a,b){while(b=b.parentNode)if(b===a)return!0;return!1},bc.attr=function(a,b){var c,d=g(a);return d||(b=b.toLowerCase()),(c=e.attrHandle[b])?c(a):d||$?a.getAttribute(b):(c=a.getAttributeNode(b),c?typeof a[b]=="boolean"?a[b]?b:null:c.specified?c.value:null:null)},e=bc.selectors={cacheLength:50,createPseudo:z,match:W,attrHandle:Z?{}:{href:function(a){return a.getAttribute("href",2)},type:function(a){return a.getAttribute("type")}},find:{ID:d?function(a,b,c){if(typeof b.getElementById!==n&&!c){var d=b.getElementById(a);return d&&d.parentNode?[d]:[]}}:function(a,c,d){if(typeof c.getElementById!==n&&!d){var e=c.getElementById(a);return e?e.id===a||typeof e.getAttributeNode!==n&&e.getAttributeNode("id").value===a?[e]:b:[]}},TAG:Y?function(a,b){if(typeof b.getElementsByTagName!==n)return b.getElementsByTagName(a)}:function(a,b){var c=b.getElementsByTagName(a);if(a==="*"){var d,e=[],f=0;for(;d=c[f];f++)d.nodeType===1&&e.push(d);return e}return c},NAME:ba&&function(a,b){if(typeof b.getElementsByName!==n)return b.getElementsByName(name)},CLASS:_&&function(a,b,c){if(typeof b.getElementsByClassName!==n&&!c)return b.getElementsByClassName(a)}},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(V,""),a[3]=(a[4]||a[5]||"").replace(V,""),a[2]==="~="&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),a[1]==="nth"?(a[2]||bc.error(a[0]),a[3]=+(a[3]?a[4]+(a[5]||1):2*(a[2]==="even"||a[2]==="odd")),a[4]=+(a[6]+a[7]||a[2]==="odd")):a[2]&&bc.error(a[0]),a},PSEUDO:function(a){var b,c;if(W.CHILD.test(a[0]))return null;if(a[3])a[2]=a[3];else if(b=a[4])O.test(b)&&(c=bh(b,!0))&&(c=b.indexOf(")",b.length-c)-b.length)&&(b=b.slice(0,c),a[0]=a[0].slice(0,c)),a[2]=b;return a.slice(0,3)}},filter:{ID:d?function(a){return a=a.replace(V,""),function(b){return b.getAttribute("id")===a}}:function(a){return a=a.replace(V,""),function(b){var c=typeof b.getAttributeNode!==n&&b.getAttributeNode("id");return c&&c.value===a}},TAG:function(a){return a==="*"?function(){return!0}:(a=a.replace(V,"").toLowerCase(),function(b){return b.nodeName&&b.nodeName.toLowerCase()===a})},CLASS:function(a){var b=B[o][a];return b||(b=B(a,new RegExp("(^|"+E+")"+a+"("+E+"|$)"))),function(a){return b.test(a.className||typeof a.getAttribute!==n&&a.getAttribute("class")||"")}},ATTR:function(a,b,c){return function(d,e){var f=bc.attr(d,a);return f==null?b==="!=":b?(f+="",b==="="?f===c:b==="!="?f!==c:b==="^="?c&&f.indexOf(c)===0:b==="*="?c&&f.indexOf(c)>-1:b==="$="?c&&f.substr(f.length-c.length)===c:b==="~="?(" "+f+" ").indexOf(c)>-1:b==="|="?f===c||f.substr(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d){return a==="nth"?function(a){var b,e,f=a.parentNode;if(c===1&&d===0)return!0;if(f){e=0;for(b=f.firstChild;b;b=b.nextSibling)if(b.nodeType===1){e++;if(a===b)break}}return e-=d,e===c||e%c===0&&e/c>=0}:function(b){var c=b;switch(a){case"only":case"first":while(c=c.previousSibling)if(c.nodeType===1)return!1;if(a==="first")return!0;c=b;case"last":while(c=c.nextSibling)if(c.nodeType===1)return!1;return!0}}},PSEUDO:function(a,b){var c,d=e.pseudos[a]||e.setFilters[a.toLowerCase()]||bc.error("unsupported pseudo: "+a);return d[o]?d(b):d.length>1?(c=[a,a,"",b],e.setFilters.hasOwnProperty(a.toLowerCase())?z(function(a,c){var e,f=d(a,b),g=f.length;while(g--)e=y.call(a,f[g]),a[e]=!(c[e]=f[g])}):function(a){return d(a,0,c)}):d}},pseudos:{not:z(function(a){var b=[],c=[],d=i(a.replace(L,"$1"));return d[o]?z(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)if(f=g[h])a[h]=!(b[h]=f)}):function(a,e,f){return b[0]=a,d(b,null,f,c),!c.pop()}}),has:z(function(a){return function(b){return bc(a,b).length>0}}),contains:z(function(a){return function(b){return(b.textContent||b.innerText||f(b)).indexOf(a)>-1}}),enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&!!a.checked||b==="option"&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},parent:function(a){return!e.pseudos.empty(a)},empty:function(a){var b;a=a.firstChild;while(a){if(a.nodeName>"@"||(b=a.nodeType)===3||b===4)return!1;a=a.nextSibling}return!0},header:function(a){return T.test(a.nodeName)},text:function(a){var b,c;return a.nodeName.toLowerCase()==="input"&&(b=a.type)==="text"&&((c=a.getAttribute("type"))==null||c.toLowerCase()===b)},radio:bd("radio"),checkbox:bd("checkbox"),file:bd("file"),password:bd("password"),image:bd("image"),submit:be("submit"),reset:be("reset"),button:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&a.type==="button"||b==="button"},input:function(a){return U.test(a.nodeName)},focus:function(a){var b=a.ownerDocument;return a===b.activeElement&&(!b.hasFocus||b.hasFocus())&&(!!a.type||!!a.href)},active:function(a){return a===a.ownerDocument.activeElement},first:bf(function(a,b,c){return[0]}),last:bf(function(a,b,c){return[b-1]}),eq:bf(function(a,b,c){return[c<0?c+b:c]}),even:bf(function(a,b,c){for(var d=0;d<b;d+=2)a.push(d);return a}),odd:bf(function(a,b,c){for(var d=1;d<b;d+=2)a.push(d);return a}),lt:bf(function(a,b,c){for(var d=c<0?c+b:c;--d>=0;)a.push(d);return a}),gt:bf(function(a,b,c){for(var d=c<0?c+b:c;++d<b;)a.push(d);return a})}},j=s.compareDocumentPosition?function(a,b){return a===b?(k=!0,0):(!a.compareDocumentPosition||!b.compareDocumentPosition?a.compareDocumentPosition:a.compareDocumentPosition(b)&4)?-1:1}:function(a,b){if(a===b)return k=!0,0;if(a.sourceIndex&&b.sourceIndex)return a.sourceIndex-b.sourceIndex;var c,d,e=[],f=[],g=a.parentNode,h=b.parentNode,i=g;if(g===h)return bg(a,b);if(!g)return-1;if(!h)return 1;while(i)e.unshift(i),i=i.parentNode;i=h;while(i)f.unshift(i),i=i.parentNode;c=e.length,d=f.length;for(var j=0;j<c&&j<d;j++)if(e[j]!==f[j])return bg(e[j],f[j]);return j===c?bg(a,f[j],-1):bg(e[j],b,1)},[0,0].sort(j),m=!k,bc.uniqueSort=function(a){var b,c=1;k=m,a.sort(j);if(k)for(;b=a[c];c++)b===a[c-1]&&a.splice(c--,1);return a},bc.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},i=bc.compile=function(a,b){var c,d=[],e=[],f=D[o][a];if(!f){b||(b=bh(a)),c=b.length;while(c--)f=bm(b[c]),f[o]?d.push(f):e.push(f);f=D(a,bn(e,d))}return f},r.querySelectorAll&&function(){var a,b=bp,c=/'|\\/g,d=/\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,e=[":focus"],f=[":active",":focus"],h=s.matchesSelector||s.mozMatchesSelector||s.webkitMatchesSelector||s.oMatchesSelector||s.msMatchesSelector;X(function(a){a.innerHTML="<select><option selected=''></option></select>",a.querySelectorAll("[selected]").length||e.push("\\["+E+"*(?:checked|disabled|ismap|multiple|readonly|selected|value)"),a.querySelectorAll(":checked").length||e.push(":checked")}),X(function(a){a.innerHTML="<p test=''></p>",a.querySelectorAll("[test^='']").length&&e.push("[*^$]="+E+"*(?:\"\"|'')"),a.innerHTML="<input type='hidden'/>",a.querySelectorAll(":enabled").length||e.push(":enabled",":disabled")}),e=new RegExp(e.join("|")),bp=function(a,d,f,g,h){if(!g&&!h&&(!e||!e.test(a))){var i,j,k=!0,l=o,m=d,n=d.nodeType===9&&a;if(d.nodeType===1&&d.nodeName.toLowerCase()!=="object"){i=bh(a),(k=d.getAttribute("id"))?l=k.replace(c,"\\$&"):d.setAttribute("id",l),l="[id='"+l+"'] ",j=i.length;while(j--)i[j]=l+i[j].join("");m=R.test(a)&&d.parentNode||d,n=i.join(",")}if(n)try{return w.apply(f,x.call(m.querySelectorAll(n),0)),f}catch(p){}finally{k||d.removeAttribute("id")}}return b(a,d,f,g,h)},h&&(X(function(b){a=h.call(b,"div");try{h.call(b,"[test!='']:sizzle"),f.push("!=",J)}catch(c){}}),f=new RegExp(f.join("|")),bc.matchesSelector=function(b,c){c=c.replace(d,"='$1']");if(!g(b)&&!f.test(c)&&(!e||!e.test(c)))try{var i=h.call(b,c);if(i||a||b.document&&b.document.nodeType!==11)return i}catch(j){}return bc(c,null,null,[b]).length>0})}(),e.pseudos.nth=e.pseudos.eq,e.filters=bq.prototype=e.pseudos,e.setFilters=new bq,bc.attr=p.attr,p.find=bc,p.expr=bc.selectors,p.expr[":"]=p.expr.pseudos,p.unique=bc.uniqueSort,p.text=bc.getText,p.isXMLDoc=bc.isXML,p.contains=bc.contains}(a);var bc=/Until$/,bd=/^(?:parents|prev(?:Until|All))/,be=/^.[^:#\[\.,]*$/,bf=p.expr.match.needsContext,bg={children:!0,contents:!0,next:!0,prev:!0};p.fn.extend({find:function(a){var b,c,d,e,f,g,h=this;if(typeof a!="string")return p(a).filter(function(){for(b=0,c=h.length;b<c;b++)if(p.contains(h[b],this))return!0});g=this.pushStack("","find",a);for(b=0,c=this.length;b<c;b++){d=g.length,p.find(a,this[b],g);if(b>0)for(e=d;e<g.length;e++)for(f=0;f<d;f++)if(g[f]===g[e]){g.splice(e--,1);break}}return g},has:function(a){var b,c=p(a,this),d=c.length;return this.filter(function(){for(b=0;b<d;b++)if(p.contains(this,c[b]))return!0})},not:function(a){return this.pushStack(bj(this,a,!1),"not",a)},filter:function(a){return this.pushStack(bj(this,a,!0),"filter",a)},is:function(a){return!!a&&(typeof a=="string"?bf.test(a)?p(a,this.context).index(this[0])>=0:p.filter(a,this).length>0:this.filter(a).length>0)},closest:function(a,b){var c,d=0,e=this.length,f=[],g=bf.test(a)||typeof a!="string"?p(a,b||this.context):0;for(;d<e;d++){c=this[d];while(c&&c.ownerDocument&&c!==b&&c.nodeType!==11){if(g?g.index(c)>-1:p.find.matchesSelector(c,a)){f.push(c);break}c=c.parentNode}}return f=f.length>1?p.unique(f):f,this.pushStack(f,"closest",a)},index:function(a){return a?typeof a=="string"?p.inArray(this[0],p(a)):p.inArray(a.jquery?a[0]:a,this):this[0]&&this[0].parentNode?this.prevAll().length:-1},add:function(a,b){var c=typeof a=="string"?p(a,b):p.makeArray(a&&a.nodeType?[a]:a),d=p.merge(this.get(),c);return this.pushStack(bh(c[0])||bh(d[0])?d:p.unique(d))},addBack:function(a){return this.add(a==null?this.prevObject:this.prevObject.filter(a))}}),p.fn.andSelf=p.fn.addBack,p.each({parent:function(a){var b=a.parentNode;return b&&b.nodeType!==11?b:null},parents:function(a){return p.dir(a,"parentNode")},parentsUntil:function(a,b,c){return p.dir(a,"parentNode",c)},next:function(a){return bi(a,"nextSibling")},prev:function(a){return bi(a,"previousSibling")},nextAll:function(a){return p.dir(a,"nextSibling")},prevAll:function(a){return p.dir(a,"previousSibling")},nextUntil:function(a,b,c){return p.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return p.dir(a,"previousSibling",c)},siblings:function(a){return p.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return p.sibling(a.firstChild)},contents:function(a){return p.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:p.merge([],a.childNodes)}},function(a,b){p.fn[a]=function(c,d){var e=p.map(this,b,c);return bc.test(a)||(d=c),d&&typeof d=="string"&&(e=p.filter(d,e)),e=this.length>1&&!bg[a]?p.unique(e):e,this.length>1&&bd.test(a)&&(e=e.reverse()),this.pushStack(e,a,k.call(arguments).join(","))}}),p.extend({filter:function(a,b,c){return c&&(a=":not("+a+")"),b.length===1?p.find.matchesSelector(b[0],a)?[b[0]]:[]:p.find.matches(a,b)},dir:function(a,c,d){var e=[],f=a[c];while(f&&f.nodeType!==9&&(d===b||f.nodeType!==1||!p(f).is(d)))f.nodeType===1&&e.push(f),f=f[c];return e},sibling:function(a,b){var c=[];for(;a;a=a.nextSibling)a.nodeType===1&&a!==b&&c.push(a);return c}});var bl="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",bm=/ jQuery\d+="(?:null|\d+)"/g,bn=/^\s+/,bo=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,bp=/<([\w:]+)/,bq=/<tbody/i,br=/<|&#?\w+;/,bs=/<(?:script|style|link)/i,bt=/<(?:script|object|embed|option|style)/i,bu=new RegExp("<(?:"+bl+")[\\s/>]","i"),bv=/^(?:checkbox|radio)$/,bw=/checked\s*(?:[^=]|=\s*.checked.)/i,bx=/\/(java|ecma)script/i,by=/^\s*<!(?:\[CDATA\[|\-\-)|[\]\-]{2}>\s*$/g,bz={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],area:[1,"<map>","</map>"],_default:[0,"",""]},bA=bk(e),bB=bA.appendChild(e.createElement("div"));bz.optgroup=bz.option,bz.tbody=bz.tfoot=bz.colgroup=bz.caption=bz.thead,bz.th=bz.td,p.support.htmlSerialize||(bz._default=[1,"X<div>","</div>"]),p.fn.extend({text:function(a){return p.access(this,function(a){return a===b?p.text(this):this.empty().append((this[0]&&this[0].ownerDocument||e).createTextNode(a))},null,a,arguments.length)},wrapAll:function(a){if(p.isFunction(a))return this.each(function(b){p(this).wrapAll(a.call(this,b))});if(this[0]){var b=p(a,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstChild&&a.firstChild.nodeType===1)a=a.firstChild;return a}).append(this)}return this},wrapInner:function(a){return p.isFunction(a)?this.each(function(b){p(this).wrapInner(a.call(this,b))}):this.each(function(){var b=p(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=p.isFunction(a);return this.each(function(c){p(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){p.nodeName(this,"body")||p(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(a){(this.nodeType===1||this.nodeType===11)&&this.appendChild(a)})},prepend:function(){return this.domManip(arguments,!0,function(a){(this.nodeType===1||this.nodeType===11)&&this.insertBefore(a,this.firstChild)})},before:function(){if(!bh(this[0]))return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this)});if(arguments.length){var a=p.clean(arguments);return this.pushStack(p.merge(a,this),"before",this.selector)}},after:function(){if(!bh(this[0]))return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this.nextSibling)});if(arguments.length){var a=p.clean(arguments);return this.pushStack(p.merge(this,a),"after",this.selector)}},remove:function(a,b){var c,d=0;for(;(c=this[d])!=null;d++)if(!a||p.filter(a,[c]).length)!b&&c.nodeType===1&&(p.cleanData(c.getElementsByTagName("*")),p.cleanData([c])),c.parentNode&&c.parentNode.removeChild(c);return this},empty:function(){var a,b=0;for(;(a=this[b])!=null;b++){a.nodeType===1&&p.cleanData(a.getElementsByTagName("*"));while(a.firstChild)a.removeChild(a.firstChild)}return this},clone:function(a,b){return a=a==null?!1:a,b=b==null?a:b,this.map(function(){return p.clone(this,a,b)})},html:function(a){return p.access(this,function(a){var c=this[0]||{},d=0,e=this.length;if(a===b)return c.nodeType===1?c.innerHTML.replace(bm,""):b;if(typeof a=="string"&&!bs.test(a)&&(p.support.htmlSerialize||!bu.test(a))&&(p.support.leadingWhitespace||!bn.test(a))&&!bz[(bp.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(bo,"<$1></$2>");try{for(;d<e;d++)c=this[d]||{},c.nodeType===1&&(p.cleanData(c.getElementsByTagName("*")),c.innerHTML=a);c=0}catch(f){}}c&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(a){return bh(this[0])?this.length?this.pushStack(p(p.isFunction(a)?a():a),"replaceWith",a):this:p.isFunction(a)?this.each(function(b){var c=p(this),d=c.html();c.replaceWith(a.call(this,b,d))}):(typeof a!="string"&&(a=p(a).detach()),this.each(function(){var b=this.nextSibling,c=this.parentNode;p(this).remove(),b?p(b).before(a):p(c).append(a)}))},detach:function(a){return this.remove(a,!0)},domManip:function(a,c,d){a=[].concat.apply([],a);var e,f,g,h,i=0,j=a[0],k=[],l=this.length;if(!p.support.checkClone&&l>1&&typeof j=="string"&&bw.test(j))return this.each(function(){p(this).domManip(a,c,d)});if(p.isFunction(j))return this.each(function(e){var f=p(this);a[0]=j.call(this,e,c?f.html():b),f.domManip(a,c,d)});if(this[0]){e=p.buildFragment(a,this,k),g=e.fragment,f=g.firstChild,g.childNodes.length===1&&(g=f);if(f){c=c&&p.nodeName(f,"tr");for(h=e.cacheable||l-1;i<l;i++)d.call(c&&p.nodeName(this[i],"table")?bC(this[i],"tbody"):this[i],i===h?g:p.clone(g,!0,!0))}g=f=null,k.length&&p.each(k,function(a,b){b.src?p.ajax?p.ajax({url:b.src,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0}):p.error("no ajax"):p.globalEval((b.text||b.textContent||b.innerHTML||"").replace(by,"")),b.parentNode&&b.parentNode.removeChild(b)})}return this}}),p.buildFragment=function(a,c,d){var f,g,h,i=a[0];return c=c||e,c=!c.nodeType&&c[0]||c,c=c.ownerDocument||c,a.length===1&&typeof i=="string"&&i.length<512&&c===e&&i.charAt(0)==="<"&&!bt.test(i)&&(p.support.checkClone||!bw.test(i))&&(p.support.html5Clone||!bu.test(i))&&(g=!0,f=p.fragments[i],h=f!==b),f||(f=c.createDocumentFragment(),p.clean(a,c,f,d),g&&(p.fragments[i]=h&&f)),{fragment:f,cacheable:g}},p.fragments={},p.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){p.fn[a]=function(c){var d,e=0,f=[],g=p(c),h=g.length,i=this.length===1&&this[0].parentNode;if((i==null||i&&i.nodeType===11&&i.childNodes.length===1)&&h===1)return g[b](this[0]),this;for(;e<h;e++)d=(e>0?this.clone(!0):this).get(),p(g[e])[b](d),f=f.concat(d);return this.pushStack(f,a,g.selector)}}),p.extend({clone:function(a,b,c){var d,e,f,g;p.support.html5Clone||p.isXMLDoc(a)||!bu.test("<"+a.nodeName+">")?g=a.cloneNode(!0):(bB.innerHTML=a.outerHTML,bB.removeChild(g=bB.firstChild));if((!p.support.noCloneEvent||!p.support.noCloneChecked)&&(a.nodeType===1||a.nodeType===11)&&!p.isXMLDoc(a)){bE(a,g),d=bF(a),e=bF(g);for(f=0;d[f];++f)e[f]&&bE(d[f],e[f])}if(b){bD(a,g);if(c){d=bF(a),e=bF(g);for(f=0;d[f];++f)bD(d[f],e[f])}}return d=e=null,g},clean:function(a,b,c,d){var f,g,h,i,j,k,l,m,n,o,q,r,s=b===e&&bA,t=[];if(!b||typeof b.createDocumentFragment=="undefined")b=e;for(f=0;(h=a[f])!=null;f++){typeof h=="number"&&(h+="");if(!h)continue;if(typeof h=="string")if(!br.test(h))h=b.createTextNode(h);else{s=s||bk(b),l=b.createElement("div"),s.appendChild(l),h=h.replace(bo,"<$1></$2>"),i=(bp.exec(h)||["",""])[1].toLowerCase(),j=bz[i]||bz._default,k=j[0],l.innerHTML=j[1]+h+j[2];while(k--)l=l.lastChild;if(!p.support.tbody){m=bq.test(h),n=i==="table"&&!m?l.firstChild&&l.firstChild.childNodes:j[1]==="<table>"&&!m?l.childNodes:[];for(g=n.length-1;g>=0;--g)p.nodeName(n[g],"tbody")&&!n[g].childNodes.length&&n[g].parentNode.removeChild(n[g])}!p.support.leadingWhitespace&&bn.test(h)&&l.insertBefore(b.createTextNode(bn.exec(h)[0]),l.firstChild),h=l.childNodes,l.parentNode.removeChild(l)}h.nodeType?t.push(h):p.merge(t,h)}l&&(h=l=s=null);if(!p.support.appendChecked)for(f=0;(h=t[f])!=null;f++)p.nodeName(h,"input")?bG(h):typeof h.getElementsByTagName!="undefined"&&p.grep(h.getElementsByTagName("input"),bG);if(c){q=function(a){if(!a.type||bx.test(a.type))return d?d.push(a.parentNode?a.parentNode.removeChild(a):a):c.appendChild(a)};for(f=0;(h=t[f])!=null;f++)if(!p.nodeName(h,"script")||!q(h))c.appendChild(h),typeof h.getElementsByTagName!="undefined"&&(r=p.grep(p.merge([],h.getElementsByTagName("script")),q),t.splice.apply(t,[f+1,0].concat(r)),f+=r.length)}return t},cleanData:function(a,b){var c,d,e,f,g=0,h=p.expando,i=p.cache,j=p.support.deleteExpando,k=p.event.special;for(;(e=a[g])!=null;g++)if(b||p.acceptData(e)){d=e[h],c=d&&i[d];if(c){if(c.events)for(f in c.events)k[f]?p.event.remove(e,f):p.removeEvent(e,f,c.handle);i[d]&&(delete i[d],j?delete e[h]:e.removeAttribute?e.removeAttribute(h):e[h]=null,p.deletedIds.push(d))}}}}),function(){var a,b;p.uaMatch=function(a){a=a.toLowerCase();var b=/(chrome)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||a.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},a=p.uaMatch(g.userAgent),b={},a.browser&&(b[a.browser]=!0,b.version=a.version),b.chrome?b.webkit=!0:b.webkit&&(b.safari=!0),p.browser=b,p.sub=function(){function a(b,c){return new a.fn.init(b,c)}p.extend(!0,a,this),a.superclass=this,a.fn=a.prototype=this(),a.fn.constructor=a,a.sub=this.sub,a.fn.init=function c(c,d){return d&&d instanceof p&&!(d instanceof a)&&(d=a(d)),p.fn.init.call(this,c,d,b)},a.fn.init.prototype=a.fn;var b=a(e);return a}}();var bH,bI,bJ,bK=/alpha\([^)]*\)/i,bL=/opacity=([^)]*)/,bM=/^(top|right|bottom|left)$/,bN=/^(none|table(?!-c[ea]).+)/,bO=/^margin/,bP=new RegExp("^("+q+")(.*)$","i"),bQ=new RegExp("^("+q+")(?!px)[a-z%]+$","i"),bR=new RegExp("^([-+])=("+q+")","i"),bS={},bT={position:"absolute",visibility:"hidden",display:"block"},bU={letterSpacing:0,fontWeight:400},bV=["Top","Right","Bottom","Left"],bW=["Webkit","O","Moz","ms"],bX=p.fn.toggle;p.fn.extend({css:function(a,c){return p.access(this,function(a,c,d){return d!==b?p.style(a,c,d):p.css(a,c)},a,c,arguments.length>1)},show:function(){return b$(this,!0)},hide:function(){return b$(this)},toggle:function(a,b){var c=typeof a=="boolean";return p.isFunction(a)&&p.isFunction(b)?bX.apply(this,arguments):this.each(function(){(c?a:bZ(this))?p(this).show():p(this).hide()})}}),p.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=bH(a,"opacity");return c===""?"1":c}}}},cssNumber:{fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":p.support.cssFloat?"cssFloat":"styleFloat"},style:function(a,c,d,e){if(!a||a.nodeType===3||a.nodeType===8||!a.style)return;var f,g,h,i=p.camelCase(c),j=a.style;c=p.cssProps[i]||(p.cssProps[i]=bY(j,i)),h=p.cssHooks[c]||p.cssHooks[i];if(d===b)return h&&"get"in h&&(f=h.get(a,!1,e))!==b?f:j[c];g=typeof d,g==="string"&&(f=bR.exec(d))&&(d=(f[1]+1)*f[2]+parseFloat(p.css(a,c)),g="number");if(d==null||g==="number"&&isNaN(d))return;g==="number"&&!p.cssNumber[i]&&(d+="px");if(!h||!("set"in h)||(d=h.set(a,d,e))!==b)try{j[c]=d}catch(k){}},css:function(a,c,d,e){var f,g,h,i=p.camelCase(c);return c=p.cssProps[i]||(p.cssProps[i]=bY(a.style,i)),h=p.cssHooks[c]||p.cssHooks[i],h&&"get"in h&&(f=h.get(a,!0,e)),f===b&&(f=bH(a,c)),f==="normal"&&c in bU&&(f=bU[c]),d||e!==b?(g=parseFloat(f),d||p.isNumeric(g)?g||0:f):f},swap:function(a,b,c){var d,e,f={};for(e in b)f[e]=a.style[e],a.style[e]=b[e];d=c.call(a);for(e in b)a.style[e]=f[e];return d}}),a.getComputedStyle?bH=function(b,c){var d,e,f,g,h=a.getComputedStyle(b,null),i=b.style;return h&&(d=h[c],d===""&&!p.contains(b.ownerDocument,b)&&(d=p.style(b,c)),bQ.test(d)&&bO.test(c)&&(e=i.width,f=i.minWidth,g=i.maxWidth,i.minWidth=i.maxWidth=i.width=d,d=h.width,i.width=e,i.minWidth=f,i.maxWidth=g)),d}:e.documentElement.currentStyle&&(bH=function(a,b){var c,d,e=a.currentStyle&&a.currentStyle[b],f=a.style;return e==null&&f&&f[b]&&(e=f[b]),bQ.test(e)&&!bM.test(b)&&(c=f.left,d=a.runtimeStyle&&a.runtimeStyle.left,d&&(a.runtimeStyle.left=a.currentStyle.left),f.left=b==="fontSize"?"1em":e,e=f.pixelLeft+"px",f.left=c,d&&(a.runtimeStyle.left=d)),e===""?"auto":e}),p.each(["height","width"],function(a,b){p.cssHooks[b]={get:function(a,c,d){if(c)return a.offsetWidth===0&&bN.test(bH(a,"display"))?p.swap(a,bT,function(){return cb(a,b,d)}):cb(a,b,d)},set:function(a,c,d){return b_(a,c,d?ca(a,b,d,p.support.boxSizing&&p.css(a,"boxSizing")==="border-box"):0)}}}),p.support.opacity||(p.cssHooks.opacity={get:function(a,b){return bL.test((b&&a.currentStyle?a.currentStyle.filter:a.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":b?"1":""},set:function(a,b){var c=a.style,d=a.currentStyle,e=p.isNumeric(b)?"alpha(opacity="+b*100+")":"",f=d&&d.filter||c.filter||"";c.zoom=1;if(b>=1&&p.trim(f.replace(bK,""))===""&&c.removeAttribute){c.removeAttribute("filter");if(d&&!d.filter)return}c.filter=bK.test(f)?f.replace(bK,e):f+" "+e}}),p(function(){p.support.reliableMarginRight||(p.cssHooks.marginRight={get:function(a,b){return p.swap(a,{display:"inline-block"},function(){if(b)return bH(a,"marginRight")})}}),!p.support.pixelPosition&&p.fn.position&&p.each(["top","left"],function(a,b){p.cssHooks[b]={get:function(a,c){if(c){var d=bH(a,b);return bQ.test(d)?p(a).position()[b]+"px":d}}}})}),p.expr&&p.expr.filters&&(p.expr.filters.hidden=function(a){return a.offsetWidth===0&&a.offsetHeight===0||!p.support.reliableHiddenOffsets&&(a.style&&a.style.display||bH(a,"display"))==="none"},p.expr.filters.visible=function(a){return!p.expr.filters.hidden(a)}),p.each({margin:"",padding:"",border:"Width"},function(a,b){p.cssHooks[a+b]={expand:function(c){var d,e=typeof c=="string"?c.split(" "):[c],f={};for(d=0;d<4;d++)f[a+bV[d]+b]=e[d]||e[d-2]||e[0];return f}},bO.test(a)||(p.cssHooks[a+b].set=b_)});var cd=/%20/g,ce=/\[\]$/,cf=/\r?\n/g,cg=/^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,ch=/^(?:select|textarea)/i;p.fn.extend({serialize:function(){return p.param(this.serializeArray())},serializeArray:function(){return this.map(function(){return this.elements?p.makeArray(this.elements):this}).filter(function(){return this.name&&!this.disabled&&(this.checked||ch.test(this.nodeName)||cg.test(this.type))}).map(function(a,b){var c=p(this).val();return c==null?null:p.isArray(c)?p.map(c,function(a,c){return{name:b.name,value:a.replace(cf,"\r\n")}}):{name:b.name,value:c.replace(cf,"\r\n")}}).get()}}),p.param=function(a,c){var d,e=[],f=function(a,b){b=p.isFunction(b)?b():b==null?"":b,e[e.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};c===b&&(c=p.ajaxSettings&&p.ajaxSettings.traditional);if(p.isArray(a)||a.jquery&&!p.isPlainObject(a))p.each(a,function(){f(this.name,this.value)});else for(d in a)ci(d,a[d],c,f);return e.join("&").replace(cd,"+")};var cj,ck,cl=/#.*$/,cm=/^(.*?):[ \t]*([^\r\n]*)\r?$/mg,cn=/^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/,co=/^(?:GET|HEAD)$/,cp=/^\/\//,cq=/\?/,cr=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,cs=/([?&])_=[^&]*/,ct=/^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,cu=p.fn.load,cv={},cw={},cx=["*/"]+["*"];try{ck=f.href}catch(cy){ck=e.createElement("a"),ck.href="",ck=ck.href}cj=ct.exec(ck.toLowerCase())||[],p.fn.load=function(a,c,d){if(typeof a!="string"&&cu)return cu.apply(this,arguments);if(!this.length)return this;var e,f,g,h=this,i=a.indexOf(" ");return i>=0&&(e=a.slice(i,a.length),a=a.slice(0,i)),p.isFunction(c)?(d=c,c=b):c&&typeof c=="object"&&(f="POST"),p.ajax({url:a,type:f,dataType:"html",data:c,complete:function(a,b){d&&h.each(d,g||[a.responseText,b,a])}}).done(function(a){g=arguments,h.html(e?p("<div>").append(a.replace(cr,"")).find(e):a)}),this},p.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),function(a,b){p.fn[b]=function(a){return this.on(b,a)}}),p.each(["get","post"],function(a,c){p[c]=function(a,d,e,f){return p.isFunction(d)&&(f=f||e,e=d,d=b),p.ajax({type:c,url:a,data:d,success:e,dataType:f})}}),p.extend({getScript:function(a,c){return p.get(a,b,c,"script")},getJSON:function(a,b,c){return p.get(a,b,c,"json")},ajaxSetup:function(a,b){return b?cB(a,p.ajaxSettings):(b=a,a=p.ajaxSettings),cB(a,b),a},ajaxSettings:{url:ck,isLocal:cn.test(cj[1]),global:!0,type:"GET",contentType:"application/x-www-form-urlencoded; charset=UTF-8",processData:!0,async:!0,accepts:{xml:"application/xml, text/xml",html:"text/html",text:"text/plain",json:"application/json, text/javascript","*":cx},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":a.String,"text html":!0,"text json":p.parseJSON,"text xml":p.parseXML},flatOptions:{context:!0,url:!0}},ajaxPrefilter:cz(cv),ajaxTransport:cz(cw),ajax:function(a,c){function y(a,c,f,i){var k,s,t,u,w,y=c;if(v===2)return;v=2,h&&clearTimeout(h),g=b,e=i||"",x.readyState=a>0?4:0,f&&(u=cC(l,x,f));if(a>=200&&a<300||a===304)l.ifModified&&(w=x.getResponseHeader("Last-Modified"),w&&(p.lastModified[d]=w),w=x.getResponseHeader("Etag"),w&&(p.etag[d]=w)),a===304?(y="notmodified",k=!0):(k=cD(l,u),y=k.state,s=k.data,t=k.error,k=!t);else{t=y;if(!y||a)y="error",a<0&&(a=0)}x.status=a,x.statusText=(c||y)+"",k?o.resolveWith(m,[s,y,x]):o.rejectWith(m,[x,y,t]),x.statusCode(r),r=b,j&&n.trigger("ajax"+(k?"Success":"Error"),[x,l,k?s:t]),q.fireWith(m,[x,y]),j&&(n.trigger("ajaxComplete",[x,l]),--p.active||p.event.trigger("ajaxStop"))}typeof a=="object"&&(c=a,a=b),c=c||{};var d,e,f,g,h,i,j,k,l=p.ajaxSetup({},c),m=l.context||l,n=m!==l&&(m.nodeType||m instanceof p)?p(m):p.event,o=p.Deferred(),q=p.Callbacks("once memory"),r=l.statusCode||{},t={},u={},v=0,w="canceled",x={readyState:0,setRequestHeader:function(a,b){if(!v){var c=a.toLowerCase();a=u[c]=u[c]||a,t[a]=b}return this},getAllResponseHeaders:function(){return v===2?e:null},getResponseHeader:function(a){var c;if(v===2){if(!f){f={};while(c=cm.exec(e))f[c[1].toLowerCase()]=c[2]}c=f[a.toLowerCase()]}return c===b?null:c},overrideMimeType:function(a){return v||(l.mimeType=a),this},abort:function(a){return a=a||w,g&&g.abort(a),y(0,a),this}};o.promise(x),x.success=x.done,x.error=x.fail,x.complete=q.add,x.statusCode=function(a){if(a){var b;if(v<2)for(b in a)r[b]=[r[b],a[b]];else b=a[x.status],x.always(b)}return this},l.url=((a||l.url)+"").replace(cl,"").replace(cp,cj[1]+"//"),l.dataTypes=p.trim(l.dataType||"*").toLowerCase().split(s),l.crossDomain==null&&(i=ct.exec(l.url.toLowerCase())||!1,l.crossDomain=i&&i.join(":")+(i[3]?"":i[1]==="http:"?80:443)!==cj.join(":")+(cj[3]?"":cj[1]==="http:"?80:443)),l.data&&l.processData&&typeof l.data!="string"&&(l.data=p.param(l.data,l.traditional)),cA(cv,l,c,x);if(v===2)return x;j=l.global,l.type=l.type.toUpperCase(),l.hasContent=!co.test(l.type),j&&p.active++===0&&p.event.trigger("ajaxStart");if(!l.hasContent){l.data&&(l.url+=(cq.test(l.url)?"&":"?")+l.data,delete l.data),d=l.url;if(l.cache===!1){var z=p.now(),A=l.url.replace(cs,"$1_="+z);l.url=A+(A===l.url?(cq.test(l.url)?"&":"?")+"_="+z:"")}}(l.data&&l.hasContent&&l.contentType!==!1||c.contentType)&&x.setRequestHeader("Content-Type",l.contentType),l.ifModified&&(d=d||l.url,p.lastModified[d]&&x.setRequestHeader("If-Modified-Since",p.lastModified[d]),p.etag[d]&&x.setRequestHeader("If-None-Match",p.etag[d])),x.setRequestHeader("Accept",l.dataTypes[0]&&l.accepts[l.dataTypes[0]]?l.accepts[l.dataTypes[0]]+(l.dataTypes[0]!=="*"?", "+cx+"; q=0.01":""):l.accepts["*"]);for(k in l.headers)x.setRequestHeader(k,l.headers[k]);if(!l.beforeSend||l.beforeSend.call(m,x,l)!==!1&&v!==2){w="abort";for(k in{success:1,error:1,complete:1})x[k](l[k]);g=cA(cw,l,c,x);if(!g)y(-1,"No Transport");else{x.readyState=1,j&&n.trigger("ajaxSend",[x,l]),l.async&&l.timeout>0&&(h=setTimeout(function(){x.abort("timeout")},l.timeout));try{v=1,g.send(t,y)}catch(B){if(v<2)y(-1,B);else throw B}}return x}return x.abort()},active:0,lastModified:{},etag:{}});var cE=[],cF=/\?/,cG=/(=)\?(?=&|$)|\?\?/,cH=p.now();p.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=cE.pop()||p.expando+"_"+cH++;return this[a]=!0,a}}),p.ajaxPrefilter("json jsonp",function(c,d,e){var f,g,h,i=c.data,j=c.url,k=c.jsonp!==!1,l=k&&cG.test(j),m=k&&!l&&typeof i=="string"&&!(c.contentType||"").indexOf("application/x-www-form-urlencoded")&&cG.test(i);if(c.dataTypes[0]==="jsonp"||l||m)return f=c.jsonpCallback=p.isFunction(c.jsonpCallback)?c.jsonpCallback():c.jsonpCallback,g=a[f],l?c.url=j.replace(cG,"$1"+f):m?c.data=i.replace(cG,"$1"+f):k&&(c.url+=(cF.test(j)?"&":"?")+c.jsonp+"="+f),c.converters["script json"]=function(){return h||p.error(f+" was not called"),h[0]},c.dataTypes[0]="json",a[f]=function(){h=arguments},e.always(function(){a[f]=g,c[f]&&(c.jsonpCallback=d.jsonpCallback,cE.push(f)),h&&p.isFunction(g)&&g(h[0]),h=g=b}),"script"}),p.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/javascript|ecmascript/},converters:{"text script":function(a){return p.globalEval(a),a}}}),p.ajaxPrefilter("script",function(a){a.cache===b&&(a.cache=!1),a.crossDomain&&(a.type="GET",a.global=!1)}),p.ajaxTransport("script",function(a){if(a.crossDomain){var c,d=e.head||e.getElementsByTagName("head")[0]||e.documentElement;return{send:function(f,g){c=e.createElement("script"),c.async="async",a.scriptCharset&&(c.charset=a.scriptCharset),c.src=a.url,c.onload=c.onreadystatechange=function(a,e){if(e||!c.readyState||/loaded|complete/.test(c.readyState))c.onload=c.onreadystatechange=null,d&&c.parentNode&&d.removeChild(c),c=b,e||g(200,"success")},d.insertBefore(c,d.firstChild)},abort:function(){c&&c.onload(0,1)}}}});var cI,cJ=a.ActiveXObject?function(){for(var a in cI)cI[a](0,1)}:!1,cK=0;p.ajaxSettings.xhr=a.ActiveXObject?function(){return!this.isLocal&&cL()||cM()}:cL,function(a){p.extend(p.support,{ajax:!!a,cors:!!a&&"withCredentials"in a})}(p.ajaxSettings.xhr()),p.support.ajax&&p.ajaxTransport(function(c){if(!c.crossDomain||p.support.cors){var d;return{send:function(e,f){var g,h,i=c.xhr();c.username?i.open(c.type,c.url,c.async,c.username,c.password):i.open(c.type,c.url,c.async);if(c.xhrFields)for(h in c.xhrFields)i[h]=c.xhrFields[h];c.mimeType&&i.overrideMimeType&&i.overrideMimeType(c.mimeType),!c.crossDomain&&!e["X-Requested-With"]&&(e["X-Requested-With"]="XMLHttpRequest");try{for(h in e)i.setRequestHeader(h,e[h])}catch(j){}i.send(c.hasContent&&c.data||null),d=function(a,e){var h,j,k,l,m;try{if(d&&(e||i.readyState===4)){d=b,g&&(i.onreadystatechange=p.noop,cJ&&delete cI[g]);if(e)i.readyState!==4&&i.abort();else{h=i.status,k=i.getAllResponseHeaders(),l={},m=i.responseXML,m&&m.documentElement&&(l.xml=m);try{l.text=i.responseText}catch(a){}try{j=i.statusText}catch(n){j=""}!h&&c.isLocal&&!c.crossDomain?h=l.text?200:404:h===1223&&(h=204)}}}catch(o){e||f(-1,o)}l&&f(h,j,l,k)},c.async?i.readyState===4?setTimeout(d,0):(g=++cK,cJ&&(cI||(cI={},p(a).unload(cJ)),cI[g]=d),i.onreadystatechange=d):d()},abort:function(){d&&d(0,1)}}}});var cN,cO,cP=/^(?:toggle|show|hide)$/,cQ=new RegExp("^(?:([-+])=|)("+q+")([a-z%]*)$","i"),cR=/queueHooks$/,cS=[cY],cT={"*":[function(a,b){var c,d,e=this.createTween(a,b),f=cQ.exec(b),g=e.cur(),h=+g||0,i=1,j=20;if(f){c=+f[2],d=f[3]||(p.cssNumber[a]?"":"px");if(d!=="px"&&h){h=p.css(e.elem,a,!0)||c||1;do i=i||".5",h=h/i,p.style(e.elem,a,h+d);while(i!==(i=e.cur()/g)&&i!==1&&--j)}e.unit=d,e.start=h,e.end=f[1]?h+(f[1]+1)*c:c}return e}]};p.Animation=p.extend(cW,{tweener:function(a,b){p.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");var c,d=0,e=a.length;for(;d<e;d++)c=a[d],cT[c]=cT[c]||[],cT[c].unshift(b)},prefilter:function(a,b){b?cS.unshift(a):cS.push(a)}}),p.Tween=cZ,cZ.prototype={constructor:cZ,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(p.cssNumber[c]?"":"px")},cur:function(){var a=cZ.propHooks[this.prop];return a&&a.get?a.get(this):cZ.propHooks._default.get(this)},run:function(a){var b,c=cZ.propHooks[this.prop];return this.options.duration?this.pos=b=p.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):this.pos=b=a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):cZ.propHooks._default.set(this),this}},cZ.prototype.init.prototype=cZ.prototype,cZ.propHooks={_default:{get:function(a){var b;return a.elem[a.prop]==null||!!a.elem.style&&a.elem.style[a.prop]!=null?(b=p.css(a.elem,a.prop,!1,""),!b||b==="auto"?0:b):a.elem[a.prop]},set:function(a){p.fx.step[a.prop]?p.fx.step[a.prop](a):a.elem.style&&(a.elem.style[p.cssProps[a.prop]]!=null||p.cssHooks[a.prop])?p.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},cZ.propHooks.scrollTop=cZ.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},p.each(["toggle","show","hide"],function(a,b){var c=p.fn[b];p.fn[b]=function(d,e,f){return d==null||typeof d=="boolean"||!a&&p.isFunction(d)&&p.isFunction(e)?c.apply(this,arguments):this.animate(c$(b,!0),d,e,f)}}),p.fn.extend({fadeTo:function(a,b,c,d){return this.filter(bZ).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=p.isEmptyObject(a),f=p.speed(b,c,d),g=function(){var b=cW(this,p.extend({},a),f);e&&b.stop(!0)};return e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,c,d){var e=function(a){var b=a.stop;delete a.stop,b(d)};return typeof a!="string"&&(d=c,c=a,a=b),c&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,c=a!=null&&a+"queueHooks",f=p.timers,g=p._data(this);if(c)g[c]&&g[c].stop&&e(g[c]);else for(c in g)g[c]&&g[c].stop&&cR.test(c)&&e(g[c]);for(c=f.length;c--;)f[c].elem===this&&(a==null||f[c].queue===a)&&(f[c].anim.stop(d),b=!1,f.splice(c,1));(b||!d)&&p.dequeue(this,a)})}}),p.each({slideDown:c$("show"),slideUp:c$("hide"),slideToggle:c$("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){p.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),p.speed=function(a,b,c){var d=a&&typeof a=="object"?p.extend({},a):{complete:c||!c&&b||p.isFunction(a)&&a,duration:a,easing:c&&b||b&&!p.isFunction(b)&&b};d.duration=p.fx.off?0:typeof d.duration=="number"?d.duration:d.duration in p.fx.speeds?p.fx.speeds[d.duration]:p.fx.speeds._default;if(d.queue==null||d.queue===!0)d.queue="fx";return d.old=d.complete,d.complete=function(){p.isFunction(d.old)&&d.old.call(this),d.queue&&p.dequeue(this,d.queue)},d},p.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},p.timers=[],p.fx=cZ.prototype.init,p.fx.tick=function(){var a,b=p.timers,c=0;for(;c<b.length;c++)a=b[c],!a()&&b[c]===a&&b.splice(c--,1);b.length||p.fx.stop()},p.fx.timer=function(a){a()&&p.timers.push(a)&&!cO&&(cO=setInterval(p.fx.tick,p.fx.interval))},p.fx.interval=13,p.fx.stop=function(){clearInterval(cO),cO=null},p.fx.speeds={slow:600,fast:200,_default:400},p.fx.step={},p.expr&&p.expr.filters&&(p.expr.filters.animated=function(a){return p.grep(p.timers,function(b){return a===b.elem}).length});var c_=/^(?:body|html)$/i;p.fn.offset=function(a){if(arguments.length)return a===b?this:this.each(function(b){p.offset.setOffset(this,a,b)});var c,d,e,f,g,h,i,j={top:0,left:0},k=this[0],l=k&&k.ownerDocument;if(!l)return;return(d=l.body)===k?p.offset.bodyOffset(k):(c=l.documentElement,p.contains(c,k)?(typeof k.getBoundingClientRect!="undefined"&&(j=k.getBoundingClientRect()),e=da(l),f=c.clientTop||d.clientTop||0,g=c.clientLeft||d.clientLeft||0,h=e.pageYOffset||c.scrollTop,i=e.pageXOffset||c.scrollLeft,{top:j.top+h-f,left:j.left+i-g}):j)},p.offset={bodyOffset:function(a){var b=a.offsetTop,c=a.offsetLeft;return p.support.doesNotIncludeMarginInBodyOffset&&(b+=parseFloat(p.css(a,"marginTop"))||0,c+=parseFloat(p.css(a,"marginLeft"))||0),{top:b,left:c}},setOffset:function(a,b,c){var d=p.css(a,"position");d==="static"&&(a.style.position="relative");var e=p(a),f=e.offset(),g=p.css(a,"top"),h=p.css(a,"left"),i=(d==="absolute"||d==="fixed")&&p.inArray("auto",[g,h])>-1,j={},k={},l,m;i?(k=e.position(),l=k.top,m=k.left):(l=parseFloat(g)||0,m=parseFloat(h)||0),p.isFunction(b)&&(b=b.call(a,c,f)),b.top!=null&&(j.top=b.top-f.top+l),b.left!=null&&(j.left=b.left-f.left+m),"using"in b?b.using.call(a,j):e.css(j)}},p.fn.extend({position:function(){if(!this[0])return;var a=this[0],b=this.offsetParent(),c=this.offset(),d=c_.test(b[0].nodeName)?{top:0,left:0}:b.offset();return c.top-=parseFloat(p.css(a,"marginTop"))||0,c.left-=parseFloat(p.css(a,"marginLeft"))||0,d.top+=parseFloat(p.css(b[0],"borderTopWidth"))||0,d.left+=parseFloat(p.css(b[0],"borderLeftWidth"))||0,{top:c.top-d.top,left:c.left-d.left}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||e.body;while(a&&!c_.test(a.nodeName)&&p.css(a,"position")==="static")a=a.offsetParent;return a||e.body})}}),p.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(a,c){var d=/Y/.test(c);p.fn[a]=function(e){return p.access(this,function(a,e,f){var g=da(a);if(f===b)return g?c in g?g[c]:g.document.documentElement[e]:a[e];g?g.scrollTo(d?p(g).scrollLeft():f,d?f:p(g).scrollTop()):a[e]=f},a,e,arguments.length,null)}}),p.each({Height:"height",Width:"width"},function(a,c){p.each({padding:"inner"+a,content:c,"":"outer"+a},function(d,e){p.fn[e]=function(e,f){var g=arguments.length&&(d||typeof e!="boolean"),h=d||(e===!0||f===!0?"margin":"border");return p.access(this,function(c,d,e){var f;return p.isWindow(c)?c.document.documentElement["client"+a]:c.nodeType===9?(f=c.documentElement,Math.max(c.body["scroll"+a],f["scroll"+a],c.body["offset"+a],f["offset"+a],f["client"+a])):e===b?p.css(c,d,e,h):p.style(c,d,e,h)},c,g?e:b,g,null)}})}),a.jQuery=a.$=p,typeof define=="function"&&define.amd&&define.amd.jQuery&&define("jquery",[],function(){return p})})(window);
/*!
 * jQuery UI 1.8.7
 *
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI
 */
(function(b,c){function f(g){return!b(g).parents().andSelf().filter(function(){return b.curCSS(this,"visibility")==="hidden"||b.expr.filters.hidden(this)}).length}b.ui=b.ui||{};if(!b.ui.version){b.extend(b.ui,{version:"1.8.7",keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,
NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}});b.fn.extend({_focus:b.fn.focus,focus:function(g,e){return typeof g==="number"?this.each(function(){var a=this;setTimeout(function(){b(a).focus();e&&e.call(a)},g)}):this._focus.apply(this,arguments)},scrollParent:function(){var g;g=b.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(b.curCSS(this,
"position",1))&&/(auto|scroll)/.test(b.curCSS(this,"overflow",1)+b.curCSS(this,"overflow-y",1)+b.curCSS(this,"overflow-x",1))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(b.curCSS(this,"overflow",1)+b.curCSS(this,"overflow-y",1)+b.curCSS(this,"overflow-x",1))}).eq(0);return/fixed/.test(this.css("position"))||!g.length?b(document):g},zIndex:function(g){if(g!==c)return this.css("zIndex",g);if(this.length){g=b(this[0]);for(var e;g.length&&g[0]!==document;){e=g.css("position");
if(e==="absolute"||e==="relative"||e==="fixed"){e=parseInt(g.css("zIndex"),10);if(!isNaN(e)&&e!==0)return e}g=g.parent()}}return 0},disableSelection:function(){return this.bind((b.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(g){g.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}});b.each(["Width","Height"],function(g,e){function a(j,n,q,l){b.each(d,function(){n-=parseFloat(b.curCSS(j,"padding"+this,true))||0;if(q)n-=parseFloat(b.curCSS(j,
"border"+this+"Width",true))||0;if(l)n-=parseFloat(b.curCSS(j,"margin"+this,true))||0});return n}var d=e==="Width"?["Left","Right"]:["Top","Bottom"],h=e.toLowerCase(),i={innerWidth:b.fn.innerWidth,innerHeight:b.fn.innerHeight,outerWidth:b.fn.outerWidth,outerHeight:b.fn.outerHeight};b.fn["inner"+e]=function(j){if(j===c)return i["inner"+e].call(this);return this.each(function(){b(this).css(h,a(this,j)+"px")})};b.fn["outer"+e]=function(j,n){if(typeof j!=="number")return i["outer"+e].call(this,j);return this.each(function(){b(this).css(h,
a(this,j,true,n)+"px")})}});b.extend(b.expr[":"],{data:function(g,e,a){return!!b.data(g,a[3])},focusable:function(g){var e=g.nodeName.toLowerCase(),a=b.attr(g,"tabindex");if("area"===e){e=g.parentNode;a=e.name;if(!g.href||!a||e.nodeName.toLowerCase()!=="map")return false;g=b("img[usemap=#"+a+"]")[0];return!!g&&f(g)}return(/input|select|textarea|button|object/.test(e)?!g.disabled:"a"==e?g.href||!isNaN(a):!isNaN(a))&&f(g)},tabbable:function(g){var e=b.attr(g,"tabindex");return(isNaN(e)||e>=0)&&b(g).is(":focusable")}});
b(function(){var g=document.body,e=g.appendChild(e=document.createElement("div"));b.extend(e.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0});b.support.minHeight=e.offsetHeight===100;b.support.selectstart="onselectstart"in e;g.removeChild(e).style.display="none"});b.extend(b.ui,{plugin:{add:function(g,e,a){g=b.ui[g].prototype;for(var d in a){g.plugins[d]=g.plugins[d]||[];g.plugins[d].push([e,a[d]])}},call:function(g,e,a){if((e=g.plugins[e])&&g.element[0].parentNode)for(var d=0;d<e.length;d++)g.options[e[d][0]]&&
e[d][1].apply(g.element,a)}},contains:function(g,e){return document.compareDocumentPosition?g.compareDocumentPosition(e)&16:g!==e&&g.contains(e)},hasScroll:function(g,e){if(b(g).css("overflow")==="hidden")return false;e=e&&e==="left"?"scrollLeft":"scrollTop";var a=false;if(g[e]>0)return true;g[e]=1;a=g[e]>0;g[e]=0;return a},isOverAxis:function(g,e,a){return g>e&&g<e+a},isOver:function(g,e,a,d,h,i){return b.ui.isOverAxis(g,a,h)&&b.ui.isOverAxis(e,d,i)}})}})(jQuery);
(function(b,c){if(b.cleanData){var f=b.cleanData;b.cleanData=function(e){for(var a=0,d;(d=e[a])!=null;a++)b(d).triggerHandler("remove");f(e)}}else{var g=b.fn.remove;b.fn.remove=function(e,a){return this.each(function(){if(!a)if(!e||b.filter(e,[this]).length)b("*",this).add([this]).each(function(){b(this).triggerHandler("remove")});return g.call(b(this),e,a)})}}b.widget=function(e,a,d){var h=e.split(".")[0],i;e=e.split(".")[1];i=h+"-"+e;if(!d){d=a;a=b.Widget}b.expr[":"][i]=function(j){return!!b.data(j,
e)};b[h]=b[h]||{};b[h][e]=function(j,n){arguments.length&&this._createWidget(j,n)};a=new a;a.options=b.extend(true,{},a.options);b[h][e].prototype=b.extend(true,a,{namespace:h,widgetName:e,widgetEventPrefix:b[h][e].prototype.widgetEventPrefix||e,widgetBaseClass:i},d);b.widget.bridge(e,b[h][e])};b.widget.bridge=function(e,a){b.fn[e]=function(d){var h=typeof d==="string",i=Array.prototype.slice.call(arguments,1),j=this;d=!h&&i.length?b.extend.apply(null,[true,d].concat(i)):d;if(h&&d.charAt(0)==="_")return j;
h?this.each(function(){var n=b.data(this,e),q=n&&b.isFunction(n[d])?n[d].apply(n,i):n;if(q!==n&&q!==c){j=q;return false}}):this.each(function(){var n=b.data(this,e);n?n.option(d||{})._init():b.data(this,e,new a(d,this))});return j}};b.Widget=function(e,a){arguments.length&&this._createWidget(e,a)};b.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:false},_createWidget:function(e,a){b.data(a,this.widgetName,this);this.element=b(a);this.options=b.extend(true,{},this.options,
this._getCreateOptions(),e);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()});this._create();this._trigger("create");this._init()},_getCreateOptions:function(){return b.metadata&&b.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName);this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+"-disabled ui-state-disabled")},
widget:function(){return this.element},option:function(e,a){var d=e;if(arguments.length===0)return b.extend({},this.options);if(typeof e==="string"){if(a===c)return this.options[e];d={};d[e]=a}this._setOptions(d);return this},_setOptions:function(e){var a=this;b.each(e,function(d,h){a._setOption(d,h)});return this},_setOption:function(e,a){this.options[e]=a;if(e==="disabled")this.widget()[a?"addClass":"removeClass"](this.widgetBaseClass+"-disabled ui-state-disabled").attr("aria-disabled",a);return this},
enable:function(){return this._setOption("disabled",false)},disable:function(){return this._setOption("disabled",true)},_trigger:function(e,a,d){var h=this.options[e];a=b.Event(a);a.type=(e===this.widgetEventPrefix?e:this.widgetEventPrefix+e).toLowerCase();d=d||{};if(a.originalEvent){e=b.event.props.length;for(var i;e;){i=b.event.props[--e];a[i]=a.originalEvent[i]}}this.element.trigger(a,d);return!(b.isFunction(h)&&h.call(this.element[0],a,d)===false||a.isDefaultPrevented())}}})(jQuery);
(function(b){b.widget("ui.mouse",{options:{cancel:":input,option",distance:1,delay:0},_mouseInit:function(){var c=this;this.element.bind("mousedown."+this.widgetName,function(f){return c._mouseDown(f)}).bind("click."+this.widgetName,function(f){if(true===b.data(f.target,c.widgetName+".preventClickEvent")){b.removeData(f.target,c.widgetName+".preventClickEvent");f.stopImmediatePropagation();return false}});this.started=false},_mouseDestroy:function(){this.element.unbind("."+this.widgetName)},_mouseDown:function(c){c.originalEvent=
c.originalEvent||{};if(!c.originalEvent.mouseHandled){this._mouseStarted&&this._mouseUp(c);this._mouseDownEvent=c;var f=this,g=c.which==1,e=typeof this.options.cancel=="string"?b(c.target).parents().add(c.target).filter(this.options.cancel).length:false;if(!g||e||!this._mouseCapture(c))return true;this.mouseDelayMet=!this.options.delay;if(!this.mouseDelayMet)this._mouseDelayTimer=setTimeout(function(){f.mouseDelayMet=true},this.options.delay);if(this._mouseDistanceMet(c)&&this._mouseDelayMet(c)){this._mouseStarted=
this._mouseStart(c)!==false;if(!this._mouseStarted){c.preventDefault();return true}}this._mouseMoveDelegate=function(a){return f._mouseMove(a)};this._mouseUpDelegate=function(a){return f._mouseUp(a)};b(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate);c.preventDefault();return c.originalEvent.mouseHandled=true}},_mouseMove:function(c){if(b.browser.msie&&!(document.documentMode>=9)&&!c.button)return this._mouseUp(c);if(this._mouseStarted){this._mouseDrag(c);
return c.preventDefault()}if(this._mouseDistanceMet(c)&&this._mouseDelayMet(c))(this._mouseStarted=this._mouseStart(this._mouseDownEvent,c)!==false)?this._mouseDrag(c):this._mouseUp(c);return!this._mouseStarted},_mouseUp:function(c){b(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate);if(this._mouseStarted){this._mouseStarted=false;c.target==this._mouseDownEvent.target&&b.data(c.target,this.widgetName+".preventClickEvent",
true);this._mouseStop(c)}return false},_mouseDistanceMet:function(c){return Math.max(Math.abs(this._mouseDownEvent.pageX-c.pageX),Math.abs(this._mouseDownEvent.pageY-c.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return true}})})(jQuery);
(function(b){b.widget("ui.draggable",b.ui.mouse,{widgetEventPrefix:"drag",options:{addClasses:true,appendTo:"parent",axis:false,connectToSortable:false,containment:false,cursor:"auto",cursorAt:false,grid:false,handle:false,helper:"original",iframeFix:false,opacity:false,refreshPositions:false,revert:false,revertDuration:500,scope:"default",scroll:true,scrollSensitivity:20,scrollSpeed:20,snap:false,snapMode:"both",snapTolerance:20,stack:false,zIndex:false},_create:function(){if(this.options.helper==
"original"&&!/^(?:r|a|f)/.test(this.element.css("position")))this.element[0].style.position="relative";this.options.addClasses&&this.element.addClass("ui-draggable");this.options.disabled&&this.element.addClass("ui-draggable-disabled");this._mouseInit()},destroy:function(){if(this.element.data("draggable")){this.element.removeData("draggable").unbind(".draggable").removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled");this._mouseDestroy();return this}},_mouseCapture:function(c){var f=
this.options;if(this.helper||f.disabled||b(c.target).is(".ui-resizable-handle"))return false;this.handle=this._getHandle(c);if(!this.handle)return false;return true},_mouseStart:function(c){var f=this.options;this.helper=this._createHelper(c);this._cacheHelperProportions();if(b.ui.ddmanager)b.ui.ddmanager.current=this;this._cacheMargins();this.cssPosition=this.helper.css("position");this.scrollParent=this.helper.scrollParent();this.offset=this.positionAbs=this.element.offset();this.offset={top:this.offset.top-
this.margins.top,left:this.offset.left-this.margins.left};b.extend(this.offset,{click:{left:c.pageX-this.offset.left,top:c.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()});this.originalPosition=this.position=this._generatePosition(c);this.originalPageX=c.pageX;this.originalPageY=c.pageY;f.cursorAt&&this._adjustOffsetFromHelper(f.cursorAt);f.containment&&this._setContainment();if(this._trigger("start",c)===false){this._clear();return false}this._cacheHelperProportions();
b.ui.ddmanager&&!f.dropBehaviour&&b.ui.ddmanager.prepareOffsets(this,c);this.helper.addClass("ui-draggable-dragging");this._mouseDrag(c,true);return true},_mouseDrag:function(c,f){this.position=this._generatePosition(c);this.positionAbs=this._convertPositionTo("absolute");if(!f){f=this._uiHash();if(this._trigger("drag",c,f)===false){this._mouseUp({});return false}this.position=f.position}if(!this.options.axis||this.options.axis!="y")this.helper[0].style.left=this.position.left+"px";if(!this.options.axis||
this.options.axis!="x")this.helper[0].style.top=this.position.top+"px";b.ui.ddmanager&&b.ui.ddmanager.drag(this,c);return false},_mouseStop:function(c){var f=false;if(b.ui.ddmanager&&!this.options.dropBehaviour)f=b.ui.ddmanager.drop(this,c);if(this.dropped){f=this.dropped;this.dropped=false}if(!this.element[0]||!this.element[0].parentNode)return false;if(this.options.revert=="invalid"&&!f||this.options.revert=="valid"&&f||this.options.revert===true||b.isFunction(this.options.revert)&&this.options.revert.call(this.element,
f)){var g=this;b(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){g._trigger("stop",c)!==false&&g._clear()})}else this._trigger("stop",c)!==false&&this._clear();return false},cancel:function(){this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear();return this},_getHandle:function(c){var f=!this.options.handle||!b(this.options.handle,this.element).length?true:false;b(this.options.handle,this.element).find("*").andSelf().each(function(){if(this==
c.target)f=true});return f},_createHelper:function(c){var f=this.options;c=b.isFunction(f.helper)?b(f.helper.apply(this.element[0],[c])):f.helper=="clone"?this.element.clone():this.element;c.parents("body").length||c.appendTo(f.appendTo=="parent"?this.element[0].parentNode:f.appendTo);c[0]!=this.element[0]&&!/(fixed|absolute)/.test(c.css("position"))&&c.css("position","absolute");return c},_adjustOffsetFromHelper:function(c){if(typeof c=="string")c=c.split(" ");if(b.isArray(c))c={left:+c[0],top:+c[1]||
0};if("left"in c)this.offset.click.left=c.left+this.margins.left;if("right"in c)this.offset.click.left=this.helperProportions.width-c.right+this.margins.left;if("top"in c)this.offset.click.top=c.top+this.margins.top;if("bottom"in c)this.offset.click.top=this.helperProportions.height-c.bottom+this.margins.top},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var c=this.offsetParent.offset();if(this.cssPosition=="absolute"&&this.scrollParent[0]!=document&&b.ui.contains(this.scrollParent[0],
this.offsetParent[0])){c.left+=this.scrollParent.scrollLeft();c.top+=this.scrollParent.scrollTop()}if(this.offsetParent[0]==document.body||this.offsetParent[0].tagName&&this.offsetParent[0].tagName.toLowerCase()=="html"&&b.browser.msie)c={top:0,left:0};return{top:c.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:c.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if(this.cssPosition=="relative"){var c=this.element.position();return{top:c.top-
(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:c.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}else return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var c=this.options;if(c.containment==
"parent")c.containment=this.helper[0].parentNode;if(c.containment=="document"||c.containment=="window")this.containment=[(c.containment=="document"?0:b(window).scrollLeft())-this.offset.relative.left-this.offset.parent.left,(c.containment=="document"?0:b(window).scrollTop())-this.offset.relative.top-this.offset.parent.top,(c.containment=="document"?0:b(window).scrollLeft())+b(c.containment=="document"?document:window).width()-this.helperProportions.width-this.margins.left,(c.containment=="document"?
0:b(window).scrollTop())+(b(c.containment=="document"?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top];if(!/^(document|window|parent)$/.test(c.containment)&&c.containment.constructor!=Array){var f=b(c.containment)[0];if(f){c=b(c.containment).offset();var g=b(f).css("overflow")!="hidden";this.containment=[c.left+(parseInt(b(f).css("borderLeftWidth"),10)||0)+(parseInt(b(f).css("paddingLeft"),10)||0)-this.margins.left,c.top+(parseInt(b(f).css("borderTopWidth"),
10)||0)+(parseInt(b(f).css("paddingTop"),10)||0)-this.margins.top,c.left+(g?Math.max(f.scrollWidth,f.offsetWidth):f.offsetWidth)-(parseInt(b(f).css("borderLeftWidth"),10)||0)-(parseInt(b(f).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,c.top+(g?Math.max(f.scrollHeight,f.offsetHeight):f.offsetHeight)-(parseInt(b(f).css("borderTopWidth"),10)||0)-(parseInt(b(f).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top]}}else if(c.containment.constructor==
Array)this.containment=c.containment},_convertPositionTo:function(c,f){if(!f)f=this.position;c=c=="absolute"?1:-1;var g=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&b.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,e=/(html|body)/i.test(g[0].tagName);return{top:f.top+this.offset.relative.top*c+this.offset.parent.top*c-(b.browser.safari&&b.browser.version<526&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollTop():
e?0:g.scrollTop())*c),left:f.left+this.offset.relative.left*c+this.offset.parent.left*c-(b.browser.safari&&b.browser.version<526&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():e?0:g.scrollLeft())*c)}},_generatePosition:function(c){var f=this.options,g=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&b.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,e=/(html|body)/i.test(g[0].tagName),a=c.pageX,d=c.pageY;
if(this.originalPosition){if(this.containment){if(c.pageX-this.offset.click.left<this.containment[0])a=this.containment[0]+this.offset.click.left;if(c.pageY-this.offset.click.top<this.containment[1])d=this.containment[1]+this.offset.click.top;if(c.pageX-this.offset.click.left>this.containment[2])a=this.containment[2]+this.offset.click.left;if(c.pageY-this.offset.click.top>this.containment[3])d=this.containment[3]+this.offset.click.top}if(f.grid){d=this.originalPageY+Math.round((d-this.originalPageY)/
f.grid[1])*f.grid[1];d=this.containment?!(d-this.offset.click.top<this.containment[1]||d-this.offset.click.top>this.containment[3])?d:!(d-this.offset.click.top<this.containment[1])?d-f.grid[1]:d+f.grid[1]:d;a=this.originalPageX+Math.round((a-this.originalPageX)/f.grid[0])*f.grid[0];a=this.containment?!(a-this.offset.click.left<this.containment[0]||a-this.offset.click.left>this.containment[2])?a:!(a-this.offset.click.left<this.containment[0])?a-f.grid[0]:a+f.grid[0]:a}}return{top:d-this.offset.click.top-
this.offset.relative.top-this.offset.parent.top+(b.browser.safari&&b.browser.version<526&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollTop():e?0:g.scrollTop()),left:a-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+(b.browser.safari&&b.browser.version<526&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():e?0:g.scrollLeft())}},_clear:function(){this.helper.removeClass("ui-draggable-dragging");this.helper[0]!=
this.element[0]&&!this.cancelHelperRemoval&&this.helper.remove();this.helper=null;this.cancelHelperRemoval=false},_trigger:function(c,f,g){g=g||this._uiHash();b.ui.plugin.call(this,c,[f,g]);if(c=="drag")this.positionAbs=this._convertPositionTo("absolute");return b.Widget.prototype._trigger.call(this,c,f,g)},plugins:{},_uiHash:function(){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}});b.extend(b.ui.draggable,{version:"1.8.7"});
b.ui.plugin.add("draggable","connectToSortable",{start:function(c,f){var g=b(this).data("draggable"),e=g.options,a=b.extend({},f,{item:g.element});g.sortables=[];b(e.connectToSortable).each(function(){var d=b.data(this,"sortable");if(d&&!d.options.disabled){g.sortables.push({instance:d,shouldRevert:d.options.revert});d._refreshItems();d._trigger("activate",c,a)}})},stop:function(c,f){var g=b(this).data("draggable"),e=b.extend({},f,{item:g.element});b.each(g.sortables,function(){if(this.instance.isOver){this.instance.isOver=
0;g.cancelHelperRemoval=true;this.instance.cancelHelperRemoval=false;if(this.shouldRevert)this.instance.options.revert=true;this.instance._mouseStop(c);this.instance.options.helper=this.instance.options._helper;g.options.helper=="original"&&this.instance.currentItem.css({top:"auto",left:"auto"})}else{this.instance.cancelHelperRemoval=false;this.instance._trigger("deactivate",c,e)}})},drag:function(c,f){var g=b(this).data("draggable"),e=this;b.each(g.sortables,function(){this.instance.positionAbs=
g.positionAbs;this.instance.helperProportions=g.helperProportions;this.instance.offset.click=g.offset.click;if(this.instance._intersectsWith(this.instance.containerCache)){if(!this.instance.isOver){this.instance.isOver=1;this.instance.currentItem=b(e).clone().appendTo(this.instance.element).data("sortable-item",true);this.instance.options._helper=this.instance.options.helper;this.instance.options.helper=function(){return f.helper[0]};c.target=this.instance.currentItem[0];this.instance._mouseCapture(c,
true);this.instance._mouseStart(c,true,true);this.instance.offset.click.top=g.offset.click.top;this.instance.offset.click.left=g.offset.click.left;this.instance.offset.parent.left-=g.offset.parent.left-this.instance.offset.parent.left;this.instance.offset.parent.top-=g.offset.parent.top-this.instance.offset.parent.top;g._trigger("toSortable",c);g.dropped=this.instance.element;g.currentItem=g.element;this.instance.fromOutside=g}this.instance.currentItem&&this.instance._mouseDrag(c)}else if(this.instance.isOver){this.instance.isOver=
0;this.instance.cancelHelperRemoval=true;this.instance.options.revert=false;this.instance._trigger("out",c,this.instance._uiHash(this.instance));this.instance._mouseStop(c,true);this.instance.options.helper=this.instance.options._helper;this.instance.currentItem.remove();this.instance.placeholder&&this.instance.placeholder.remove();g._trigger("fromSortable",c);g.dropped=false}})}});b.ui.plugin.add("draggable","cursor",{start:function(){var c=b("body"),f=b(this).data("draggable").options;if(c.css("cursor"))f._cursor=
c.css("cursor");c.css("cursor",f.cursor)},stop:function(){var c=b(this).data("draggable").options;c._cursor&&b("body").css("cursor",c._cursor)}});b.ui.plugin.add("draggable","iframeFix",{start:function(){var c=b(this).data("draggable").options;b(c.iframeFix===true?"iframe":c.iframeFix).each(function(){b('<div class="ui-draggable-iframeFix" style="background: #fff;"></div>').css({width:this.offsetWidth+"px",height:this.offsetHeight+"px",position:"absolute",opacity:"0.001",zIndex:1E3}).css(b(this).offset()).appendTo("body")})},
stop:function(){b("div.ui-draggable-iframeFix").each(function(){this.parentNode.removeChild(this)})}});b.ui.plugin.add("draggable","opacity",{start:function(c,f){c=b(f.helper);f=b(this).data("draggable").options;if(c.css("opacity"))f._opacity=c.css("opacity");c.css("opacity",f.opacity)},stop:function(c,f){c=b(this).data("draggable").options;c._opacity&&b(f.helper).css("opacity",c._opacity)}});b.ui.plugin.add("draggable","scroll",{start:function(){var c=b(this).data("draggable");if(c.scrollParent[0]!=
document&&c.scrollParent[0].tagName!="HTML")c.overflowOffset=c.scrollParent.offset()},drag:function(c){var f=b(this).data("draggable"),g=f.options,e=false;if(f.scrollParent[0]!=document&&f.scrollParent[0].tagName!="HTML"){if(!g.axis||g.axis!="x")if(f.overflowOffset.top+f.scrollParent[0].offsetHeight-c.pageY<g.scrollSensitivity)f.scrollParent[0].scrollTop=e=f.scrollParent[0].scrollTop+g.scrollSpeed;else if(c.pageY-f.overflowOffset.top<g.scrollSensitivity)f.scrollParent[0].scrollTop=e=f.scrollParent[0].scrollTop-
g.scrollSpeed;if(!g.axis||g.axis!="y")if(f.overflowOffset.left+f.scrollParent[0].offsetWidth-c.pageX<g.scrollSensitivity)f.scrollParent[0].scrollLeft=e=f.scrollParent[0].scrollLeft+g.scrollSpeed;else if(c.pageX-f.overflowOffset.left<g.scrollSensitivity)f.scrollParent[0].scrollLeft=e=f.scrollParent[0].scrollLeft-g.scrollSpeed}else{if(!g.axis||g.axis!="x")if(c.pageY-b(document).scrollTop()<g.scrollSensitivity)e=b(document).scrollTop(b(document).scrollTop()-g.scrollSpeed);else if(b(window).height()-
(c.pageY-b(document).scrollTop())<g.scrollSensitivity)e=b(document).scrollTop(b(document).scrollTop()+g.scrollSpeed);if(!g.axis||g.axis!="y")if(c.pageX-b(document).scrollLeft()<g.scrollSensitivity)e=b(document).scrollLeft(b(document).scrollLeft()-g.scrollSpeed);else if(b(window).width()-(c.pageX-b(document).scrollLeft())<g.scrollSensitivity)e=b(document).scrollLeft(b(document).scrollLeft()+g.scrollSpeed)}e!==false&&b.ui.ddmanager&&!g.dropBehaviour&&b.ui.ddmanager.prepareOffsets(f,c)}});b.ui.plugin.add("draggable",
"snap",{start:function(){var c=b(this).data("draggable"),f=c.options;c.snapElements=[];b(f.snap.constructor!=String?f.snap.items||":data(draggable)":f.snap).each(function(){var g=b(this),e=g.offset();this!=c.element[0]&&c.snapElements.push({item:this,width:g.outerWidth(),height:g.outerHeight(),top:e.top,left:e.left})})},drag:function(c,f){for(var g=b(this).data("draggable"),e=g.options,a=e.snapTolerance,d=f.offset.left,h=d+g.helperProportions.width,i=f.offset.top,j=i+g.helperProportions.height,n=
g.snapElements.length-1;n>=0;n--){var q=g.snapElements[n].left,l=q+g.snapElements[n].width,k=g.snapElements[n].top,m=k+g.snapElements[n].height;if(q-a<d&&d<l+a&&k-a<i&&i<m+a||q-a<d&&d<l+a&&k-a<j&&j<m+a||q-a<h&&h<l+a&&k-a<i&&i<m+a||q-a<h&&h<l+a&&k-a<j&&j<m+a){if(e.snapMode!="inner"){var o=Math.abs(k-j)<=a,p=Math.abs(m-i)<=a,s=Math.abs(q-h)<=a,r=Math.abs(l-d)<=a;if(o)f.position.top=g._convertPositionTo("relative",{top:k-g.helperProportions.height,left:0}).top-g.margins.top;if(p)f.position.top=g._convertPositionTo("relative",
{top:m,left:0}).top-g.margins.top;if(s)f.position.left=g._convertPositionTo("relative",{top:0,left:q-g.helperProportions.width}).left-g.margins.left;if(r)f.position.left=g._convertPositionTo("relative",{top:0,left:l}).left-g.margins.left}var u=o||p||s||r;if(e.snapMode!="outer"){o=Math.abs(k-i)<=a;p=Math.abs(m-j)<=a;s=Math.abs(q-d)<=a;r=Math.abs(l-h)<=a;if(o)f.position.top=g._convertPositionTo("relative",{top:k,left:0}).top-g.margins.top;if(p)f.position.top=g._convertPositionTo("relative",{top:m-g.helperProportions.height,
left:0}).top-g.margins.top;if(s)f.position.left=g._convertPositionTo("relative",{top:0,left:q}).left-g.margins.left;if(r)f.position.left=g._convertPositionTo("relative",{top:0,left:l-g.helperProportions.width}).left-g.margins.left}if(!g.snapElements[n].snapping&&(o||p||s||r||u))g.options.snap.snap&&g.options.snap.snap.call(g.element,c,b.extend(g._uiHash(),{snapItem:g.snapElements[n].item}));g.snapElements[n].snapping=o||p||s||r||u}else{g.snapElements[n].snapping&&g.options.snap.release&&g.options.snap.release.call(g.element,
c,b.extend(g._uiHash(),{snapItem:g.snapElements[n].item}));g.snapElements[n].snapping=false}}}});b.ui.plugin.add("draggable","stack",{start:function(){var c=b(this).data("draggable").options;c=b.makeArray(b(c.stack)).sort(function(g,e){return(parseInt(b(g).css("zIndex"),10)||0)-(parseInt(b(e).css("zIndex"),10)||0)});if(c.length){var f=parseInt(c[0].style.zIndex)||0;b(c).each(function(g){this.style.zIndex=f+g});this[0].style.zIndex=f+c.length}}});b.ui.plugin.add("draggable","zIndex",{start:function(c,
f){c=b(f.helper);f=b(this).data("draggable").options;if(c.css("zIndex"))f._zIndex=c.css("zIndex");c.css("zIndex",f.zIndex)},stop:function(c,f){c=b(this).data("draggable").options;c._zIndex&&b(f.helper).css("zIndex",c._zIndex)}})})(jQuery);
(function(b){b.widget("ui.droppable",{widgetEventPrefix:"drop",options:{accept:"*",activeClass:false,addClasses:true,greedy:false,hoverClass:false,scope:"default",tolerance:"intersect"},_create:function(){var c=this.options,f=c.accept;this.isover=0;this.isout=1;this.accept=b.isFunction(f)?f:function(g){return g.is(f)};this.proportions={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight};b.ui.ddmanager.droppables[c.scope]=b.ui.ddmanager.droppables[c.scope]||[];b.ui.ddmanager.droppables[c.scope].push(this);
c.addClasses&&this.element.addClass("ui-droppable")},destroy:function(){for(var c=b.ui.ddmanager.droppables[this.options.scope],f=0;f<c.length;f++)c[f]==this&&c.splice(f,1);this.element.removeClass("ui-droppable ui-droppable-disabled").removeData("droppable").unbind(".droppable");return this},_setOption:function(c,f){if(c=="accept")this.accept=b.isFunction(f)?f:function(g){return g.is(f)};b.Widget.prototype._setOption.apply(this,arguments)},_activate:function(c){var f=b.ui.ddmanager.current;this.options.activeClass&&
this.element.addClass(this.options.activeClass);f&&this._trigger("activate",c,this.ui(f))},_deactivate:function(c){var f=b.ui.ddmanager.current;this.options.activeClass&&this.element.removeClass(this.options.activeClass);f&&this._trigger("deactivate",c,this.ui(f))},_over:function(c){var f=b.ui.ddmanager.current;if(!(!f||(f.currentItem||f.element)[0]==this.element[0]))if(this.accept.call(this.element[0],f.currentItem||f.element)){this.options.hoverClass&&this.element.addClass(this.options.hoverClass);
this._trigger("over",c,this.ui(f))}},_out:function(c){var f=b.ui.ddmanager.current;if(!(!f||(f.currentItem||f.element)[0]==this.element[0]))if(this.accept.call(this.element[0],f.currentItem||f.element)){this.options.hoverClass&&this.element.removeClass(this.options.hoverClass);this._trigger("out",c,this.ui(f))}},_drop:function(c,f){var g=f||b.ui.ddmanager.current;if(!g||(g.currentItem||g.element)[0]==this.element[0])return false;var e=false;this.element.find(":data(droppable)").not(".ui-draggable-dragging").each(function(){var a=
b.data(this,"droppable");if(a.options.greedy&&!a.options.disabled&&a.options.scope==g.options.scope&&a.accept.call(a.element[0],g.currentItem||g.element)&&b.ui.intersect(g,b.extend(a,{offset:a.element.offset()}),a.options.tolerance)){e=true;return false}});if(e)return false;if(this.accept.call(this.element[0],g.currentItem||g.element)){this.options.activeClass&&this.element.removeClass(this.options.activeClass);this.options.hoverClass&&this.element.removeClass(this.options.hoverClass);this._trigger("drop",
c,this.ui(g));return this.element}return false},ui:function(c){return{draggable:c.currentItem||c.element,helper:c.helper,position:c.position,offset:c.positionAbs}}});b.extend(b.ui.droppable,{version:"1.8.7"});b.ui.intersect=function(c,f,g){if(!f.offset)return false;var e=(c.positionAbs||c.position.absolute).left,a=e+c.helperProportions.width,d=(c.positionAbs||c.position.absolute).top,h=d+c.helperProportions.height,i=f.offset.left,j=i+f.proportions.width,n=f.offset.top,q=n+f.proportions.height;
switch(g){case "fit":return i<=e&&a<=j&&n<=d&&h<=q;case "intersect":return i<e+c.helperProportions.width/2&&a-c.helperProportions.width/2<j&&n<d+c.helperProportions.height/2&&h-c.helperProportions.height/2<q;case "pointer":return b.ui.isOver((c.positionAbs||c.position.absolute).top+(c.clickOffset||c.offset.click).top,(c.positionAbs||c.position.absolute).left+(c.clickOffset||c.offset.click).left,n,i,f.proportions.height,f.proportions.width);case "touch":return(d>=n&&d<=q||h>=n&&h<=q||d<n&&h>q)&&(e>=
i&&e<=j||a>=i&&a<=j||e<i&&a>j);default:return false}};b.ui.ddmanager={current:null,droppables:{"default":[]},prepareOffsets:function(c,f){var g=b.ui.ddmanager.droppables[c.options.scope]||[],e=f?f.type:null,a=(c.currentItem||c.element).find(":data(droppable)").andSelf(),d=0;a:for(;d<g.length;d++)if(!(g[d].options.disabled||c&&!g[d].accept.call(g[d].element[0],c.currentItem||c.element))){for(var h=0;h<a.length;h++)if(a[h]==g[d].element[0]){g[d].proportions.height=0;continue a}g[d].visible=g[d].element.css("display")!=
"none";if(g[d].visible){g[d].offset=g[d].element.offset();g[d].proportions={width:g[d].element[0].offsetWidth,height:g[d].element[0].offsetHeight};e=="mousedown"&&g[d]._activate.call(g[d],f)}}},drop:function(c,f){var g=false;b.each(b.ui.ddmanager.droppables[c.options.scope]||[],function(){if(this.options){if(!this.options.disabled&&this.visible&&b.ui.intersect(c,this,this.options.tolerance))g=g||this._drop.call(this,f);if(!this.options.disabled&&this.visible&&this.accept.call(this.element[0],c.currentItem||
c.element)){this.isout=1;this.isover=0;this._deactivate.call(this,f)}}});return g},drag:function(c,f){c.options.refreshPositions&&b.ui.ddmanager.prepareOffsets(c,f);b.each(b.ui.ddmanager.droppables[c.options.scope]||[],function(){if(!(this.options.disabled||this.greedyChild||!this.visible)){var g=b.ui.intersect(c,this,this.options.tolerance);if(g=!g&&this.isover==1?"isout":g&&this.isover==0?"isover":null){var e;if(this.options.greedy){var a=this.element.parents(":data(droppable):eq(0)");if(a.length){e=
b.data(a[0],"droppable");e.greedyChild=g=="isover"?1:0}}if(e&&g=="isover"){e.isover=0;e.isout=1;e._out.call(e,f)}this[g]=1;this[g=="isout"?"isover":"isout"]=0;this[g=="isover"?"_over":"_out"].call(this,f);if(e&&g=="isout"){e.isout=0;e.isover=1;e._over.call(e,f)}}}})}}})(jQuery);
(function(b){b.widget("ui.resizable",b.ui.mouse,{widgetEventPrefix:"resize",options:{alsoResize:false,animate:false,animateDuration:"slow",animateEasing:"swing",aspectRatio:false,autoHide:false,containment:false,ghost:false,grid:false,handles:"e,s,se",helper:false,maxHeight:null,maxWidth:null,minHeight:10,minWidth:10,zIndex:1E3},_create:function(){var g=this,e=this.options;this.element.addClass("ui-resizable");b.extend(this,{_aspectRatio:!!e.aspectRatio,aspectRatio:e.aspectRatio,originalElement:this.element,
_proportionallyResizeElements:[],_helper:e.helper||e.ghost||e.animate?e.helper||"ui-resizable-helper":null});if(this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i)){/relative/.test(this.element.css("position"))&&b.browser.opera&&this.element.css({position:"relative",top:"auto",left:"auto"});this.element.wrap(b('<div class="ui-wrapper" style="overflow: hidden;"></div>').css({position:this.element.css("position"),width:this.element.outerWidth(),height:this.element.outerHeight(),
top:this.element.css("top"),left:this.element.css("left")}));this.element=this.element.parent().data("resizable",this.element.data("resizable"));this.elementIsWrapper=true;this.element.css({marginLeft:this.originalElement.css("marginLeft"),marginTop:this.originalElement.css("marginTop"),marginRight:this.originalElement.css("marginRight"),marginBottom:this.originalElement.css("marginBottom")});this.originalElement.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0});this.originalResizeStyle=
this.originalElement.css("resize");this.originalElement.css("resize","none");this._proportionallyResizeElements.push(this.originalElement.css({position:"static",zoom:1,display:"block"}));this.originalElement.css({margin:this.originalElement.css("margin")});this._proportionallyResize()}this.handles=e.handles||(!b(".ui-resizable-handle",this.element).length?"e,s,se":{n:".ui-resizable-n",e:".ui-resizable-e",s:".ui-resizable-s",w:".ui-resizable-w",se:".ui-resizable-se",sw:".ui-resizable-sw",ne:".ui-resizable-ne",
nw:".ui-resizable-nw"});if(this.handles.constructor==String){if(this.handles=="all")this.handles="n,e,s,w,se,sw,ne,nw";var a=this.handles.split(",");this.handles={};for(var d=0;d<a.length;d++){var h=b.trim(a[d]),i=b('<div class="ui-resizable-handle '+("ui-resizable-"+h)+'"></div>');/sw|se|ne|nw/.test(h)&&i.css({zIndex:++e.zIndex});"se"==h&&i.addClass("ui-icon ui-icon-gripsmall-diagonal-se");this.handles[h]=".ui-resizable-"+h;this.element.append(i)}}this._renderAxis=function(j){j=j||this.element;for(var n in this.handles){if(this.handles[n].constructor==
String)this.handles[n]=b(this.handles[n],this.element).show();if(this.elementIsWrapper&&this.originalElement[0].nodeName.match(/textarea|input|select|button/i)){var q=b(this.handles[n],this.element),l=0;l=/sw|ne|nw|se|n|s/.test(n)?q.outerHeight():q.outerWidth();q=["padding",/ne|nw|n/.test(n)?"Top":/se|sw|s/.test(n)?"Bottom":/^e$/.test(n)?"Right":"Left"].join("");j.css(q,l);this._proportionallyResize()}b(this.handles[n])}};this._renderAxis(this.element);this._handles=b(".ui-resizable-handle",this.element).disableSelection();
this._handles.mouseover(function(){if(!g.resizing){if(this.className)var j=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i);g.axis=j&&j[1]?j[1]:"se"}});if(e.autoHide){this._handles.hide();b(this.element).addClass("ui-resizable-autohide").hover(function(){b(this).removeClass("ui-resizable-autohide");g._handles.show()},function(){if(!g.resizing){b(this).addClass("ui-resizable-autohide");g._handles.hide()}})}this._mouseInit()},destroy:function(){this._mouseDestroy();var g=function(a){b(a).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").unbind(".resizable").find(".ui-resizable-handle").remove()};
if(this.elementIsWrapper){g(this.element);var e=this.element;e.after(this.originalElement.css({position:e.css("position"),width:e.outerWidth(),height:e.outerHeight(),top:e.css("top"),left:e.css("left")})).remove()}this.originalElement.css("resize",this.originalResizeStyle);g(this.originalElement);return this},_mouseCapture:function(g){var e=false;for(var a in this.handles)if(b(this.handles[a])[0]==g.target)e=true;return!this.options.disabled&&e},_mouseStart:function(g){var e=this.options,a=this.element.position(),
d=this.element;this.resizing=true;this.documentScroll={top:b(document).scrollTop(),left:b(document).scrollLeft()};if(d.is(".ui-draggable")||/absolute/.test(d.css("position")))d.css({position:"absolute",top:a.top,left:a.left});b.browser.opera&&/relative/.test(d.css("position"))&&d.css({position:"relative",top:"auto",left:"auto"});this._renderProxy();a=c(this.helper.css("left"));var h=c(this.helper.css("top"));if(e.containment){a+=b(e.containment).scrollLeft()||0;h+=b(e.containment).scrollTop()||0}this.offset=
this.helper.offset();this.position={left:a,top:h};this.size=this._helper?{width:d.outerWidth(),height:d.outerHeight()}:{width:d.width(),height:d.height()};this.originalSize=this._helper?{width:d.outerWidth(),height:d.outerHeight()}:{width:d.width(),height:d.height()};this.originalPosition={left:a,top:h};this.sizeDiff={width:d.outerWidth()-d.width(),height:d.outerHeight()-d.height()};this.originalMousePosition={left:g.pageX,top:g.pageY};this.aspectRatio=typeof e.aspectRatio=="number"?e.aspectRatio:
this.originalSize.width/this.originalSize.height||1;e=b(".ui-resizable-"+this.axis).css("cursor");b("body").css("cursor",e=="auto"?this.axis+"-resize":e);d.addClass("ui-resizable-resizing");this._propagate("start",g);return true},_mouseDrag:function(g){var e=this.helper,a=this.originalMousePosition,d=this._change[this.axis];if(!d)return false;a=d.apply(this,[g,g.pageX-a.left||0,g.pageY-a.top||0]);if(this._aspectRatio||g.shiftKey)a=this._updateRatio(a,g);a=this._respectSize(a,g);this._propagate("resize",
g);e.css({top:this.position.top+"px",left:this.position.left+"px",width:this.size.width+"px",height:this.size.height+"px"});!this._helper&&this._proportionallyResizeElements.length&&this._proportionallyResize();this._updateCache(a);this._trigger("resize",g,this.ui());return false},_mouseStop:function(g){this.resizing=false;var e=this.options,a=this;if(this._helper){var d=this._proportionallyResizeElements,h=d.length&&/textarea/i.test(d[0].nodeName);d=h&&b.ui.hasScroll(d[0],"left")?0:a.sizeDiff.height;
h={width:a.size.width-(h?0:a.sizeDiff.width),height:a.size.height-d};d=parseInt(a.element.css("left"),10)+(a.position.left-a.originalPosition.left)||null;var i=parseInt(a.element.css("top"),10)+(a.position.top-a.originalPosition.top)||null;e.animate||this.element.css(b.extend(h,{top:i,left:d}));a.helper.height(a.size.height);a.helper.width(a.size.width);this._helper&&!e.animate&&this._proportionallyResize()}b("body").css("cursor","auto");this.element.removeClass("ui-resizable-resizing");this._propagate("stop",
g);this._helper&&this.helper.remove();return false},_updateCache:function(g){this.offset=this.helper.offset();if(f(g.left))this.position.left=g.left;if(f(g.top))this.position.top=g.top;if(f(g.height))this.size.height=g.height;if(f(g.width))this.size.width=g.width},_updateRatio:function(g){var e=this.position,a=this.size,d=this.axis;if(g.height)g.width=a.height*this.aspectRatio;else if(g.width)g.height=a.width/this.aspectRatio;if(d=="sw"){g.left=e.left+(a.width-g.width);g.top=null}if(d=="nw"){g.top=
e.top+(a.height-g.height);g.left=e.left+(a.width-g.width)}return g},_respectSize:function(g){var e=this.options,a=this.axis,d=f(g.width)&&e.maxWidth&&e.maxWidth<g.width,h=f(g.height)&&e.maxHeight&&e.maxHeight<g.height,i=f(g.width)&&e.minWidth&&e.minWidth>g.width,j=f(g.height)&&e.minHeight&&e.minHeight>g.height;if(i)g.width=e.minWidth;if(j)g.height=e.minHeight;if(d)g.width=e.maxWidth;if(h)g.height=e.maxHeight;var n=this.originalPosition.left+this.originalSize.width,q=this.position.top+this.size.height,
l=/sw|nw|w/.test(a);a=/nw|ne|n/.test(a);if(i&&l)g.left=n-e.minWidth;if(d&&l)g.left=n-e.maxWidth;if(j&&a)g.top=q-e.minHeight;if(h&&a)g.top=q-e.maxHeight;if((e=!g.width&&!g.height)&&!g.left&&g.top)g.top=null;else if(e&&!g.top&&g.left)g.left=null;return g},_proportionallyResize:function(){if(this._proportionallyResizeElements.length)for(var g=this.helper||this.element,e=0;e<this._proportionallyResizeElements.length;e++){var a=this._proportionallyResizeElements[e];if(!this.borderDif){var d=[a.css("borderTopWidth"),
a.css("borderRightWidth"),a.css("borderBottomWidth"),a.css("borderLeftWidth")],h=[a.css("paddingTop"),a.css("paddingRight"),a.css("paddingBottom"),a.css("paddingLeft")];this.borderDif=b.map(d,function(i,j){i=parseInt(i,10)||0;j=parseInt(h[j],10)||0;return i+j})}b.browser.msie&&(b(g).is(":hidden")||b(g).parents(":hidden").length)||a.css({height:g.height()-this.borderDif[0]-this.borderDif[2]||0,width:g.width()-this.borderDif[1]-this.borderDif[3]||0})}},_renderProxy:function(){var g=this.options;this.elementOffset=
this.element.offset();if(this._helper){this.helper=this.helper||b('<div style="overflow:hidden;"></div>');var e=b.browser.msie&&b.browser.version<7,a=e?1:0;e=e?2:-1;this.helper.addClass(this._helper).css({width:this.element.outerWidth()+e,height:this.element.outerHeight()+e,position:"absolute",left:this.elementOffset.left-a+"px",top:this.elementOffset.top-a+"px",zIndex:++g.zIndex});this.helper.appendTo("body").disableSelection()}else this.helper=this.element},_change:{e:function(g,e){return{width:this.originalSize.width+
e}},w:function(g,e){return{left:this.originalPosition.left+e,width:this.originalSize.width-e}},n:function(g,e,a){return{top:this.originalPosition.top+a,height:this.originalSize.height-a}},s:function(g,e,a){return{height:this.originalSize.height+a}},se:function(g,e,a){return b.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[g,e,a]))},sw:function(g,e,a){return b.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[g,e,a]))},ne:function(g,e,a){return b.extend(this._change.n.apply(this,
arguments),this._change.e.apply(this,[g,e,a]))},nw:function(g,e,a){return b.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[g,e,a]))}},_propagate:function(g,e){b.ui.plugin.call(this,g,[e,this.ui()]);g!="resize"&&this._trigger(g,e,this.ui())},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,originalSize:this.originalSize,originalPosition:this.originalPosition}}});b.extend(b.ui.resizable,
{version:"1.8.7"});b.ui.plugin.add("resizable","alsoResize",{start:function(){var g=b(this).data("resizable").options,e=function(a){b(a).each(function(){var d=b(this);d.data("resizable-alsoresize",{width:parseInt(d.width(),10),height:parseInt(d.height(),10),left:parseInt(d.css("left"),10),top:parseInt(d.css("top"),10),position:d.css("position")})})};if(typeof g.alsoResize=="object"&&!g.alsoResize.parentNode)if(g.alsoResize.length){g.alsoResize=g.alsoResize[0];e(g.alsoResize)}else b.each(g.alsoResize,
function(a){e(a)});else e(g.alsoResize)},resize:function(g,e){var a=b(this).data("resizable");g=a.options;var d=a.originalSize,h=a.originalPosition,i={height:a.size.height-d.height||0,width:a.size.width-d.width||0,top:a.position.top-h.top||0,left:a.position.left-h.left||0},j=function(n,q){b(n).each(function(){var l=b(this),k=b(this).data("resizable-alsoresize"),m={},o=q&&q.length?q:l.parents(e.originalElement[0]).length?["width","height"]:["width","height","top","left"];b.each(o,function(p,s){if((p=
(k[s]||0)+(i[s]||0))&&p>=0)m[s]=p||null});if(b.browser.opera&&/relative/.test(l.css("position"))){a._revertToRelativePosition=true;l.css({position:"absolute",top:"auto",left:"auto"})}l.css(m)})};typeof g.alsoResize=="object"&&!g.alsoResize.nodeType?b.each(g.alsoResize,function(n,q){j(n,q)}):j(g.alsoResize)},stop:function(){var g=b(this).data("resizable"),e=g.options,a=function(d){b(d).each(function(){var h=b(this);h.css({position:h.data("resizable-alsoresize").position})})};if(g._revertToRelativePosition){g._revertToRelativePosition=
false;typeof e.alsoResize=="object"&&!e.alsoResize.nodeType?b.each(e.alsoResize,function(d){a(d)}):a(e.alsoResize)}b(this).removeData("resizable-alsoresize")}});b.ui.plugin.add("resizable","animate",{stop:function(g){var e=b(this).data("resizable"),a=e.options,d=e._proportionallyResizeElements,h=d.length&&/textarea/i.test(d[0].nodeName),i=h&&b.ui.hasScroll(d[0],"left")?0:e.sizeDiff.height;h={width:e.size.width-(h?0:e.sizeDiff.width),height:e.size.height-i};i=parseInt(e.element.css("left"),10)+(e.position.left-
e.originalPosition.left)||null;var j=parseInt(e.element.css("top"),10)+(e.position.top-e.originalPosition.top)||null;e.element.animate(b.extend(h,j&&i?{top:j,left:i}:{}),{duration:a.animateDuration,easing:a.animateEasing,step:function(){var n={width:parseInt(e.element.css("width"),10),height:parseInt(e.element.css("height"),10),top:parseInt(e.element.css("top"),10),left:parseInt(e.element.css("left"),10)};d&&d.length&&b(d[0]).css({width:n.width,height:n.height});e._updateCache(n);e._propagate("resize",
g)}})}});b.ui.plugin.add("resizable","containment",{start:function(){var g=b(this).data("resizable"),e=g.element,a=g.options.containment;if(e=a instanceof b?a.get(0):/parent/.test(a)?e.parent().get(0):a){g.containerElement=b(e);if(/document/.test(a)||a==document){g.containerOffset={left:0,top:0};g.containerPosition={left:0,top:0};g.parentData={element:b(document),left:0,top:0,width:b(document).width(),height:b(document).height()||document.body.parentNode.scrollHeight}}else{var d=b(e),h=[];b(["Top",
"Right","Left","Bottom"]).each(function(n,q){h[n]=c(d.css("padding"+q))});g.containerOffset=d.offset();g.containerPosition=d.position();g.containerSize={height:d.innerHeight()-h[3],width:d.innerWidth()-h[1]};a=g.containerOffset;var i=g.containerSize.height,j=g.containerSize.width;j=b.ui.hasScroll(e,"left")?e.scrollWidth:j;i=b.ui.hasScroll(e)?e.scrollHeight:i;g.parentData={element:e,left:a.left,top:a.top,width:j,height:i}}}},resize:function(g){var e=b(this).data("resizable"),a=e.options,d=e.containerOffset,
h=e.position;g=e._aspectRatio||g.shiftKey;var i={top:0,left:0},j=e.containerElement;if(j[0]!=document&&/static/.test(j.css("position")))i=d;if(h.left<(e._helper?d.left:0)){e.size.width+=e._helper?e.position.left-d.left:e.position.left-i.left;if(g)e.size.height=e.size.width/a.aspectRatio;e.position.left=a.helper?d.left:0}if(h.top<(e._helper?d.top:0)){e.size.height+=e._helper?e.position.top-d.top:e.position.top;if(g)e.size.width=e.size.height*a.aspectRatio;e.position.top=e._helper?d.top:0}e.offset.left=
e.parentData.left+e.position.left;e.offset.top=e.parentData.top+e.position.top;a=Math.abs((e._helper?e.offset.left-i.left:e.offset.left-i.left)+e.sizeDiff.width);d=Math.abs((e._helper?e.offset.top-i.top:e.offset.top-d.top)+e.sizeDiff.height);h=e.containerElement.get(0)==e.element.parent().get(0);i=/relative|absolute/.test(e.containerElement.css("position"));if(h&&i)a-=e.parentData.left;if(a+e.size.width>=e.parentData.width){e.size.width=e.parentData.width-a;if(g)e.size.height=e.size.width/e.aspectRatio}if(d+
e.size.height>=e.parentData.height){e.size.height=e.parentData.height-d;if(g)e.size.width=e.size.height*e.aspectRatio}},stop:function(){var g=b(this).data("resizable"),e=g.options,a=g.containerOffset,d=g.containerPosition,h=g.containerElement,i=b(g.helper),j=i.offset(),n=i.outerWidth()-g.sizeDiff.width;i=i.outerHeight()-g.sizeDiff.height;g._helper&&!e.animate&&/relative/.test(h.css("position"))&&b(this).css({left:j.left-d.left-a.left,width:n,height:i});g._helper&&!e.animate&&/static/.test(h.css("position"))&&
b(this).css({left:j.left-d.left-a.left,width:n,height:i})}});b.ui.plugin.add("resizable","ghost",{start:function(){var g=b(this).data("resizable"),e=g.options,a=g.size;g.ghost=g.originalElement.clone();g.ghost.css({opacity:0.25,display:"block",position:"relative",height:a.height,width:a.width,margin:0,left:0,top:0}).addClass("ui-resizable-ghost").addClass(typeof e.ghost=="string"?e.ghost:"");g.ghost.appendTo(g.helper)},resize:function(){var g=b(this).data("resizable");g.ghost&&g.ghost.css({position:"relative",
height:g.size.height,width:g.size.width})},stop:function(){var g=b(this).data("resizable");g.ghost&&g.helper&&g.helper.get(0).removeChild(g.ghost.get(0))}});b.ui.plugin.add("resizable","grid",{resize:function(){var g=b(this).data("resizable"),e=g.options,a=g.size,d=g.originalSize,h=g.originalPosition,i=g.axis;e.grid=typeof e.grid=="number"?[e.grid,e.grid]:e.grid;var j=Math.round((a.width-d.width)/(e.grid[0]||1))*(e.grid[0]||1);e=Math.round((a.height-d.height)/(e.grid[1]||1))*(e.grid[1]||1);if(/^(se|s|e)$/.test(i)){g.size.width=
d.width+j;g.size.height=d.height+e}else if(/^(ne)$/.test(i)){g.size.width=d.width+j;g.size.height=d.height+e;g.position.top=h.top-e}else{if(/^(sw)$/.test(i)){g.size.width=d.width+j;g.size.height=d.height+e}else{g.size.width=d.width+j;g.size.height=d.height+e;g.position.top=h.top-e}g.position.left=h.left-j}}});var c=function(g){return parseInt(g,10)||0},f=function(g){return!isNaN(parseInt(g,10))}})(jQuery);
(function(b){b.widget("ui.selectable",b.ui.mouse,{options:{appendTo:"body",autoRefresh:true,distance:0,filter:"*",tolerance:"touch"},_create:function(){var c=this;this.element.addClass("ui-selectable");this.dragged=false;var f;this.refresh=function(){f=b(c.options.filter,c.element[0]);f.each(function(){var g=b(this),e=g.offset();b.data(this,"selectable-item",{element:this,$element:g,left:e.left,top:e.top,right:e.left+g.outerWidth(),bottom:e.top+g.outerHeight(),startselected:false,selected:g.hasClass("ui-selected"),
selecting:g.hasClass("ui-selecting"),unselecting:g.hasClass("ui-unselecting")})})};this.refresh();this.selectees=f.addClass("ui-selectee");this._mouseInit();this.helper=b("<div class='ui-selectable-helper'></div>")},destroy:function(){this.selectees.removeClass("ui-selectee").removeData("selectable-item");this.element.removeClass("ui-selectable ui-selectable-disabled").removeData("selectable").unbind(".selectable");this._mouseDestroy();return this},_mouseStart:function(c){var f=this;this.opos=[c.pageX,
c.pageY];if(!this.options.disabled){var g=this.options;this.selectees=b(g.filter,this.element[0]);this._trigger("start",c);b(g.appendTo).append(this.helper);this.helper.css({left:c.clientX,top:c.clientY,width:0,height:0});g.autoRefresh&&this.refresh();this.selectees.filter(".ui-selected").each(function(){var e=b.data(this,"selectable-item");e.startselected=true;if(!c.metaKey){e.$element.removeClass("ui-selected");e.selected=false;e.$element.addClass("ui-unselecting");e.unselecting=true;f._trigger("unselecting",
c,{unselecting:e.element})}});b(c.target).parents().andSelf().each(function(){var e=b.data(this,"selectable-item");if(e){var a=!c.metaKey||!e.$element.hasClass("ui-selected");e.$element.removeClass(a?"ui-unselecting":"ui-selected").addClass(a?"ui-selecting":"ui-unselecting");e.unselecting=!a;e.selecting=a;(e.selected=a)?f._trigger("selecting",c,{selecting:e.element}):f._trigger("unselecting",c,{unselecting:e.element});return false}})}},_mouseDrag:function(c){var f=this;this.dragged=true;if(!this.options.disabled){var g=
this.options,e=this.opos[0],a=this.opos[1],d=c.pageX,h=c.pageY;if(e>d){var i=d;d=e;e=i}if(a>h){i=h;h=a;a=i}this.helper.css({left:e,top:a,width:d-e,height:h-a});this.selectees.each(function(){var j=b.data(this,"selectable-item");if(!(!j||j.element==f.element[0])){var n=false;if(g.tolerance=="touch")n=!(j.left>d||j.right<e||j.top>h||j.bottom<a);else if(g.tolerance=="fit")n=j.left>e&&j.right<d&&j.top>a&&j.bottom<h;if(n){if(j.selected){j.$element.removeClass("ui-selected");j.selected=false}if(j.unselecting){j.$element.removeClass("ui-unselecting");
j.unselecting=false}if(!j.selecting){j.$element.addClass("ui-selecting");j.selecting=true;f._trigger("selecting",c,{selecting:j.element})}}else{if(j.selecting)if(c.metaKey&&j.startselected){j.$element.removeClass("ui-selecting");j.selecting=false;j.$element.addClass("ui-selected");j.selected=true}else{j.$element.removeClass("ui-selecting");j.selecting=false;if(j.startselected){j.$element.addClass("ui-unselecting");j.unselecting=true}f._trigger("unselecting",c,{unselecting:j.element})}if(j.selected)if(!c.metaKey&&
!j.startselected){j.$element.removeClass("ui-selected");j.selected=false;j.$element.addClass("ui-unselecting");j.unselecting=true;f._trigger("unselecting",c,{unselecting:j.element})}}}});return false}},_mouseStop:function(c){var f=this;this.dragged=false;b(".ui-unselecting",this.element[0]).each(function(){var g=b.data(this,"selectable-item");g.$element.removeClass("ui-unselecting");g.unselecting=false;g.startselected=false;f._trigger("unselected",c,{unselected:g.element})});b(".ui-selecting",this.element[0]).each(function(){var g=
b.data(this,"selectable-item");g.$element.removeClass("ui-selecting").addClass("ui-selected");g.selecting=false;g.selected=true;g.startselected=true;f._trigger("selected",c,{selected:g.element})});this._trigger("stop",c);this.helper.remove();return false}});b.extend(b.ui.selectable,{version:"1.8.7"})})(jQuery);
(function(b){b.widget("ui.sortable",b.ui.mouse,{widgetEventPrefix:"sort",options:{appendTo:"parent",axis:false,connectWith:false,containment:false,cursor:"auto",cursorAt:false,dropOnEmpty:true,forcePlaceholderSize:false,forceHelperSize:false,grid:false,handle:false,helper:"original",items:"> *",opacity:false,placeholder:false,revert:false,scroll:true,scrollSensitivity:20,scrollSpeed:20,scope:"default",tolerance:"intersect",zIndex:1E3},_create:function(){this.containerCache={};this.element.addClass("ui-sortable");
this.refresh();this.floating=this.items.length?/left|right/.test(this.items[0].item.css("float")):false;this.offset=this.element.offset();this._mouseInit()},destroy:function(){this.element.removeClass("ui-sortable ui-sortable-disabled").removeData("sortable").unbind(".sortable");this._mouseDestroy();for(var c=this.items.length-1;c>=0;c--)this.items[c].item.removeData("sortable-item");return this},_setOption:function(c,f){if(c==="disabled"){this.options[c]=f;this.widget()[f?"addClass":"removeClass"]("ui-sortable-disabled")}else b.Widget.prototype._setOption.apply(this,
arguments)},_mouseCapture:function(c,f){if(this.reverting)return false;if(this.options.disabled||this.options.type=="static")return false;this._refreshItems(c);var g=null,e=this;b(c.target).parents().each(function(){if(b.data(this,"sortable-item")==e){g=b(this);return false}});if(b.data(c.target,"sortable-item")==e)g=b(c.target);if(!g)return false;if(this.options.handle&&!f){var a=false;b(this.options.handle,g).find("*").andSelf().each(function(){if(this==c.target)a=true});if(!a)return false}this.currentItem=
g;this._removeCurrentsFromItems();return true},_mouseStart:function(c,f,g){f=this.options;var e=this;this.currentContainer=this;this.refreshPositions();this.helper=this._createHelper(c);this._cacheHelperProportions();this._cacheMargins();this.scrollParent=this.helper.scrollParent();this.offset=this.currentItem.offset();this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left};this.helper.css("position","absolute");this.cssPosition=this.helper.css("position");b.extend(this.offset,
{click:{left:c.pageX-this.offset.left,top:c.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()});this.originalPosition=this._generatePosition(c);this.originalPageX=c.pageX;this.originalPageY=c.pageY;f.cursorAt&&this._adjustOffsetFromHelper(f.cursorAt);this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]};this.helper[0]!=this.currentItem[0]&&this.currentItem.hide();this._createPlaceholder();f.containment&&this._setContainment();
if(f.cursor){if(b("body").css("cursor"))this._storedCursor=b("body").css("cursor");b("body").css("cursor",f.cursor)}if(f.opacity){if(this.helper.css("opacity"))this._storedOpacity=this.helper.css("opacity");this.helper.css("opacity",f.opacity)}if(f.zIndex){if(this.helper.css("zIndex"))this._storedZIndex=this.helper.css("zIndex");this.helper.css("zIndex",f.zIndex)}if(this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML")this.overflowOffset=this.scrollParent.offset();this._trigger("start",
c,this._uiHash());this._preserveHelperProportions||this._cacheHelperProportions();if(!g)for(g=this.containers.length-1;g>=0;g--)this.containers[g]._trigger("activate",c,e._uiHash(this));if(b.ui.ddmanager)b.ui.ddmanager.current=this;b.ui.ddmanager&&!f.dropBehaviour&&b.ui.ddmanager.prepareOffsets(this,c);this.dragging=true;this.helper.addClass("ui-sortable-helper");this._mouseDrag(c);return true},_mouseDrag:function(c){this.position=this._generatePosition(c);this.positionAbs=this._convertPositionTo("absolute");
if(!this.lastPositionAbs)this.lastPositionAbs=this.positionAbs;if(this.options.scroll){var f=this.options,g=false;if(this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML"){if(this.overflowOffset.top+this.scrollParent[0].offsetHeight-c.pageY<f.scrollSensitivity)this.scrollParent[0].scrollTop=g=this.scrollParent[0].scrollTop+f.scrollSpeed;else if(c.pageY-this.overflowOffset.top<f.scrollSensitivity)this.scrollParent[0].scrollTop=g=this.scrollParent[0].scrollTop-f.scrollSpeed;if(this.overflowOffset.left+
this.scrollParent[0].offsetWidth-c.pageX<f.scrollSensitivity)this.scrollParent[0].scrollLeft=g=this.scrollParent[0].scrollLeft+f.scrollSpeed;else if(c.pageX-this.overflowOffset.left<f.scrollSensitivity)this.scrollParent[0].scrollLeft=g=this.scrollParent[0].scrollLeft-f.scrollSpeed}else{if(c.pageY-b(document).scrollTop()<f.scrollSensitivity)g=b(document).scrollTop(b(document).scrollTop()-f.scrollSpeed);else if(b(window).height()-(c.pageY-b(document).scrollTop())<f.scrollSensitivity)g=b(document).scrollTop(b(document).scrollTop()+
f.scrollSpeed);if(c.pageX-b(document).scrollLeft()<f.scrollSensitivity)g=b(document).scrollLeft(b(document).scrollLeft()-f.scrollSpeed);else if(b(window).width()-(c.pageX-b(document).scrollLeft())<f.scrollSensitivity)g=b(document).scrollLeft(b(document).scrollLeft()+f.scrollSpeed)}g!==false&&b.ui.ddmanager&&!f.dropBehaviour&&b.ui.ddmanager.prepareOffsets(this,c)}this.positionAbs=this._convertPositionTo("absolute");if(!this.options.axis||this.options.axis!="y")this.helper[0].style.left=this.position.left+
"px";if(!this.options.axis||this.options.axis!="x")this.helper[0].style.top=this.position.top+"px";for(f=this.items.length-1;f>=0;f--){g=this.items[f];var e=g.item[0],a=this._intersectsWithPointer(g);if(a)if(e!=this.currentItem[0]&&this.placeholder[a==1?"next":"prev"]()[0]!=e&&!b.ui.contains(this.placeholder[0],e)&&(this.options.type=="semi-dynamic"?!b.ui.contains(this.element[0],e):true)){this.direction=a==1?"down":"up";if(this.options.tolerance=="pointer"||this._intersectsWithSides(g))this._rearrange(c,
g);else break;this._trigger("change",c,this._uiHash());break}}this._contactContainers(c);b.ui.ddmanager&&b.ui.ddmanager.drag(this,c);this._trigger("sort",c,this._uiHash());this.lastPositionAbs=this.positionAbs;return false},_mouseStop:function(c,f){if(c){b.ui.ddmanager&&!this.options.dropBehaviour&&b.ui.ddmanager.drop(this,c);if(this.options.revert){var g=this;f=g.placeholder.offset();g.reverting=true;b(this.helper).animate({left:f.left-this.offset.parent.left-g.margins.left+(this.offsetParent[0]==
document.body?0:this.offsetParent[0].scrollLeft),top:f.top-this.offset.parent.top-g.margins.top+(this.offsetParent[0]==document.body?0:this.offsetParent[0].scrollTop)},parseInt(this.options.revert,10)||500,function(){g._clear(c)})}else this._clear(c,f);return false}},cancel:function(){var c=this;if(this.dragging){this._mouseUp();this.options.helper=="original"?this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper"):this.currentItem.show();for(var f=this.containers.length-1;f>=0;f--){this.containers[f]._trigger("deactivate",
null,c._uiHash(this));if(this.containers[f].containerCache.over){this.containers[f]._trigger("out",null,c._uiHash(this));this.containers[f].containerCache.over=0}}}this.placeholder[0].parentNode&&this.placeholder[0].parentNode.removeChild(this.placeholder[0]);this.options.helper!="original"&&this.helper&&this.helper[0].parentNode&&this.helper.remove();b.extend(this,{helper:null,dragging:false,reverting:false,_noFinalSort:null});this.domPosition.prev?b(this.domPosition.prev).after(this.currentItem):
b(this.domPosition.parent).prepend(this.currentItem);return this},serialize:function(c){var f=this._getItemsAsjQuery(c&&c.connected),g=[];c=c||{};b(f).each(function(){var e=(b(c.item||this).attr(c.attribute||"id")||"").match(c.expression||/(.+)[-=_](.+)/);if(e)g.push((c.key||e[1]+"[]")+"="+(c.key&&c.expression?e[1]:e[2]))});!g.length&&c.key&&g.push(c.key+"=");return g.join("&")},toArray:function(c){var f=this._getItemsAsjQuery(c&&c.connected),g=[];c=c||{};f.each(function(){g.push(b(c.item||this).attr(c.attribute||
"id")||"")});return g},_intersectsWith:function(c){var f=this.positionAbs.left,g=f+this.helperProportions.width,e=this.positionAbs.top,a=e+this.helperProportions.height,d=c.left,h=d+c.width,i=c.top,j=i+c.height,n=this.offset.click.top,q=this.offset.click.left;n=e+n>i&&e+n<j&&f+q>d&&f+q<h;return this.options.tolerance=="pointer"||this.options.forcePointerForContainers||this.options.tolerance!="pointer"&&this.helperProportions[this.floating?"width":"height"]>c[this.floating?"width":"height"]?n:d<f+
this.helperProportions.width/2&&g-this.helperProportions.width/2<h&&i<e+this.helperProportions.height/2&&a-this.helperProportions.height/2<j},_intersectsWithPointer:function(c){var f=b.ui.isOverAxis(this.positionAbs.top+this.offset.click.top,c.top,c.height);c=b.ui.isOverAxis(this.positionAbs.left+this.offset.click.left,c.left,c.width);f=f&&c;c=this._getDragVerticalDirection();var g=this._getDragHorizontalDirection();if(!f)return false;return this.floating?g&&g=="right"||c=="down"?2:1:c&&(c=="down"?
2:1)},_intersectsWithSides:function(c){var f=b.ui.isOverAxis(this.positionAbs.top+this.offset.click.top,c.top+c.height/2,c.height);c=b.ui.isOverAxis(this.positionAbs.left+this.offset.click.left,c.left+c.width/2,c.width);var g=this._getDragVerticalDirection(),e=this._getDragHorizontalDirection();return this.floating&&e?e=="right"&&c||e=="left"&&!c:g&&(g=="down"&&f||g=="up"&&!f)},_getDragVerticalDirection:function(){var c=this.positionAbs.top-this.lastPositionAbs.top;return c!=0&&(c>0?"down":"up")},
_getDragHorizontalDirection:function(){var c=this.positionAbs.left-this.lastPositionAbs.left;return c!=0&&(c>0?"right":"left")},refresh:function(c){this._refreshItems(c);this.refreshPositions();return this},_connectWith:function(){var c=this.options;return c.connectWith.constructor==String?[c.connectWith]:c.connectWith},_getItemsAsjQuery:function(c){var f=[],g=[],e=this._connectWith();if(e&&c)for(c=e.length-1;c>=0;c--)for(var a=b(e[c]),d=a.length-1;d>=0;d--){var h=b.data(a[d],"sortable");if(h&&h!=
this&&!h.options.disabled)g.push([b.isFunction(h.options.items)?h.options.items.call(h.element):b(h.options.items,h.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),h])}g.push([b.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):b(this.options.items,this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),this]);for(c=g.length-1;c>=0;c--)g[c][0].each(function(){f.push(this)});return b(f)},_removeCurrentsFromItems:function(){for(var c=
this.currentItem.find(":data(sortable-item)"),f=0;f<this.items.length;f++)for(var g=0;g<c.length;g++)c[g]==this.items[f].item[0]&&this.items.splice(f,1)},_refreshItems:function(c){this.items=[];this.containers=[this];var f=this.items,g=[[b.isFunction(this.options.items)?this.options.items.call(this.element[0],c,{item:this.currentItem}):b(this.options.items,this.element),this]],e=this._connectWith();if(e)for(var a=e.length-1;a>=0;a--)for(var d=b(e[a]),h=d.length-1;h>=0;h--){var i=b.data(d[h],"sortable");
if(i&&i!=this&&!i.options.disabled){g.push([b.isFunction(i.options.items)?i.options.items.call(i.element[0],c,{item:this.currentItem}):b(i.options.items,i.element),i]);this.containers.push(i)}}for(a=g.length-1;a>=0;a--){c=g[a][1];e=g[a][0];h=0;for(d=e.length;h<d;h++){i=b(e[h]);i.data("sortable-item",c);f.push({item:i,instance:c,width:0,height:0,left:0,top:0})}}},refreshPositions:function(c){if(this.offsetParent&&this.helper)this.offset.parent=this._getParentOffset();for(var f=this.items.length-1;f>=
0;f--){var g=this.items[f],e=this.options.toleranceElement?b(this.options.toleranceElement,g.item):g.item;if(!c){g.width=e.outerWidth();g.height=e.outerHeight()}e=e.offset();g.left=e.left;g.top=e.top}if(this.options.custom&&this.options.custom.refreshContainers)this.options.custom.refreshContainers.call(this);else for(f=this.containers.length-1;f>=0;f--){e=this.containers[f].element.offset();this.containers[f].containerCache.left=e.left;this.containers[f].containerCache.top=e.top;this.containers[f].containerCache.width=
this.containers[f].element.outerWidth();this.containers[f].containerCache.height=this.containers[f].element.outerHeight()}return this},_createPlaceholder:function(c){var f=c||this,g=f.options;if(!g.placeholder||g.placeholder.constructor==String){var e=g.placeholder;g.placeholder={element:function(){var a=b(document.createElement(f.currentItem[0].nodeName)).addClass(e||f.currentItem[0].className+" ui-sortable-placeholder").removeClass("ui-sortable-helper")[0];if(!e)a.style.visibility="hidden";return a},
update:function(a,d){if(!(e&&!g.forcePlaceholderSize)){d.height()||d.height(f.currentItem.innerHeight()-parseInt(f.currentItem.css("paddingTop")||0,10)-parseInt(f.currentItem.css("paddingBottom")||0,10));d.width()||d.width(f.currentItem.innerWidth()-parseInt(f.currentItem.css("paddingLeft")||0,10)-parseInt(f.currentItem.css("paddingRight")||0,10))}}}}f.placeholder=b(g.placeholder.element.call(f.element,f.currentItem));f.currentItem.after(f.placeholder);g.placeholder.update(f,f.placeholder)},_contactContainers:function(c){for(var f=
null,g=null,e=this.containers.length-1;e>=0;e--)if(!b.ui.contains(this.currentItem[0],this.containers[e].element[0]))if(this._intersectsWith(this.containers[e].containerCache)){if(!(f&&b.ui.contains(this.containers[e].element[0],f.element[0]))){f=this.containers[e];g=e}}else if(this.containers[e].containerCache.over){this.containers[e]._trigger("out",c,this._uiHash(this));this.containers[e].containerCache.over=0}if(f)if(this.containers.length===1){this.containers[g]._trigger("over",c,this._uiHash(this));
this.containers[g].containerCache.over=1}else if(this.currentContainer!=this.containers[g]){f=1E4;e=null;for(var a=this.positionAbs[this.containers[g].floating?"left":"top"],d=this.items.length-1;d>=0;d--)if(b.ui.contains(this.containers[g].element[0],this.items[d].item[0])){var h=this.items[d][this.containers[g].floating?"left":"top"];if(Math.abs(h-a)<f){f=Math.abs(h-a);e=this.items[d]}}if(e||this.options.dropOnEmpty){this.currentContainer=this.containers[g];e?this._rearrange(c,e,null,true):this._rearrange(c,
null,this.containers[g].element,true);this._trigger("change",c,this._uiHash());this.containers[g]._trigger("change",c,this._uiHash(this));this.options.placeholder.update(this.currentContainer,this.placeholder);this.containers[g]._trigger("over",c,this._uiHash(this));this.containers[g].containerCache.over=1}}},_createHelper:function(c){var f=this.options;c=b.isFunction(f.helper)?b(f.helper.apply(this.element[0],[c,this.currentItem])):f.helper=="clone"?this.currentItem.clone():this.currentItem;c.parents("body").length||
b(f.appendTo!="parent"?f.appendTo:this.currentItem[0].parentNode)[0].appendChild(c[0]);if(c[0]==this.currentItem[0])this._storedCSS={width:this.currentItem[0].style.width,height:this.currentItem[0].style.height,position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left")};if(c[0].style.width==""||f.forceHelperSize)c.width(this.currentItem.width());if(c[0].style.height==""||f.forceHelperSize)c.height(this.currentItem.height());return c},_adjustOffsetFromHelper:function(c){if(typeof c==
"string")c=c.split(" ");if(b.isArray(c))c={left:+c[0],top:+c[1]||0};if("left"in c)this.offset.click.left=c.left+this.margins.left;if("right"in c)this.offset.click.left=this.helperProportions.width-c.right+this.margins.left;if("top"in c)this.offset.click.top=c.top+this.margins.top;if("bottom"in c)this.offset.click.top=this.helperProportions.height-c.bottom+this.margins.top},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var c=this.offsetParent.offset();if(this.cssPosition==
"absolute"&&this.scrollParent[0]!=document&&b.ui.contains(this.scrollParent[0],this.offsetParent[0])){c.left+=this.scrollParent.scrollLeft();c.top+=this.scrollParent.scrollTop()}if(this.offsetParent[0]==document.body||this.offsetParent[0].tagName&&this.offsetParent[0].tagName.toLowerCase()=="html"&&b.browser.msie)c={top:0,left:0};return{top:c.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:c.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if(this.cssPosition==
"relative"){var c=this.currentItem.position();return{top:c.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:c.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}else return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.currentItem.css("marginLeft"),10)||0,top:parseInt(this.currentItem.css("marginTop"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},
_setContainment:function(){var c=this.options;if(c.containment=="parent")c.containment=this.helper[0].parentNode;if(c.containment=="document"||c.containment=="window")this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,b(c.containment=="document"?document:window).width()-this.helperProportions.width-this.margins.left,(b(c.containment=="document"?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-
this.margins.top];if(!/^(document|window|parent)$/.test(c.containment)){var f=b(c.containment)[0];c=b(c.containment).offset();var g=b(f).css("overflow")!="hidden";this.containment=[c.left+(parseInt(b(f).css("borderLeftWidth"),10)||0)+(parseInt(b(f).css("paddingLeft"),10)||0)-this.margins.left,c.top+(parseInt(b(f).css("borderTopWidth"),10)||0)+(parseInt(b(f).css("paddingTop"),10)||0)-this.margins.top,c.left+(g?Math.max(f.scrollWidth,f.offsetWidth):f.offsetWidth)-(parseInt(b(f).css("borderLeftWidth"),
10)||0)-(parseInt(b(f).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,c.top+(g?Math.max(f.scrollHeight,f.offsetHeight):f.offsetHeight)-(parseInt(b(f).css("borderTopWidth"),10)||0)-(parseInt(b(f).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top]}},_convertPositionTo:function(c,f){if(!f)f=this.position;c=c=="absolute"?1:-1;var g=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&b.ui.contains(this.scrollParent[0],this.offsetParent[0]))?
this.offsetParent:this.scrollParent,e=/(html|body)/i.test(g[0].tagName);return{top:f.top+this.offset.relative.top*c+this.offset.parent.top*c-(b.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollTop():e?0:g.scrollTop())*c),left:f.left+this.offset.relative.left*c+this.offset.parent.left*c-(b.browser.safari&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():e?0:g.scrollLeft())*c)}},_generatePosition:function(c){var f=
this.options,g=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&b.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,e=/(html|body)/i.test(g[0].tagName);if(this.cssPosition=="relative"&&!(this.scrollParent[0]!=document&&this.scrollParent[0]!=this.offsetParent[0]))this.offset.relative=this._getRelativeOffset();var a=c.pageX,d=c.pageY;if(this.originalPosition){if(this.containment){if(c.pageX-this.offset.click.left<this.containment[0])a=this.containment[0]+
this.offset.click.left;if(c.pageY-this.offset.click.top<this.containment[1])d=this.containment[1]+this.offset.click.top;if(c.pageX-this.offset.click.left>this.containment[2])a=this.containment[2]+this.offset.click.left;if(c.pageY-this.offset.click.top>this.containment[3])d=this.containment[3]+this.offset.click.top}if(f.grid){d=this.originalPageY+Math.round((d-this.originalPageY)/f.grid[1])*f.grid[1];d=this.containment?!(d-this.offset.click.top<this.containment[1]||d-this.offset.click.top>this.containment[3])?
d:!(d-this.offset.click.top<this.containment[1])?d-f.grid[1]:d+f.grid[1]:d;a=this.originalPageX+Math.round((a-this.originalPageX)/f.grid[0])*f.grid[0];a=this.containment?!(a-this.offset.click.left<this.containment[0]||a-this.offset.click.left>this.containment[2])?a:!(a-this.offset.click.left<this.containment[0])?a-f.grid[0]:a+f.grid[0]:a}}return{top:d-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+(b.browser.safari&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollTop():
e?0:g.scrollTop()),left:a-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+(b.browser.safari&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():e?0:g.scrollLeft())}},_rearrange:function(c,f,g,e){g?g[0].appendChild(this.placeholder[0]):f.item[0].parentNode.insertBefore(this.placeholder[0],this.direction=="down"?f.item[0]:f.item[0].nextSibling);this.counter=this.counter?++this.counter:1;var a=this,d=this.counter;window.setTimeout(function(){d==
a.counter&&a.refreshPositions(!e)},0)},_clear:function(c,f){this.reverting=false;var g=[];!this._noFinalSort&&this.currentItem[0].parentNode&&this.placeholder.before(this.currentItem);this._noFinalSort=null;if(this.helper[0]==this.currentItem[0]){for(var e in this._storedCSS)if(this._storedCSS[e]=="auto"||this._storedCSS[e]=="static")this._storedCSS[e]="";this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else this.currentItem.show();this.fromOutside&&!f&&g.push(function(a){this._trigger("receive",
a,this._uiHash(this.fromOutside))});if((this.fromOutside||this.domPosition.prev!=this.currentItem.prev().not(".ui-sortable-helper")[0]||this.domPosition.parent!=this.currentItem.parent()[0])&&!f)g.push(function(a){this._trigger("update",a,this._uiHash())});if(!b.ui.contains(this.element[0],this.currentItem[0])){f||g.push(function(a){this._trigger("remove",a,this._uiHash())});for(e=this.containers.length-1;e>=0;e--)if(b.ui.contains(this.containers[e].element[0],this.currentItem[0])&&!f){g.push(function(a){return function(d){a._trigger("receive",
d,this._uiHash(this))}}.call(this,this.containers[e]));g.push(function(a){return function(d){a._trigger("update",d,this._uiHash(this))}}.call(this,this.containers[e]))}}for(e=this.containers.length-1;e>=0;e--){f||g.push(function(a){return function(d){a._trigger("deactivate",d,this._uiHash(this))}}.call(this,this.containers[e]));if(this.containers[e].containerCache.over){g.push(function(a){return function(d){a._trigger("out",d,this._uiHash(this))}}.call(this,this.containers[e]));this.containers[e].containerCache.over=
0}}this._storedCursor&&b("body").css("cursor",this._storedCursor);this._storedOpacity&&this.helper.css("opacity",this._storedOpacity);if(this._storedZIndex)this.helper.css("zIndex",this._storedZIndex=="auto"?"":this._storedZIndex);this.dragging=false;if(this.cancelHelperRemoval){if(!f){this._trigger("beforeStop",c,this._uiHash());for(e=0;e<g.length;e++)g[e].call(this,c);this._trigger("stop",c,this._uiHash())}return false}f||this._trigger("beforeStop",c,this._uiHash());this.placeholder[0].parentNode.removeChild(this.placeholder[0]);
this.helper[0]!=this.currentItem[0]&&this.helper.remove();this.helper=null;if(!f){for(e=0;e<g.length;e++)g[e].call(this,c);this._trigger("stop",c,this._uiHash())}this.fromOutside=false;return true},_trigger:function(){b.Widget.prototype._trigger.apply(this,arguments)===false&&this.cancel()},_uiHash:function(c){var f=c||this;return{helper:f.helper,placeholder:f.placeholder||b([]),position:f.position,originalPosition:f.originalPosition,offset:f.positionAbs,item:f.currentItem,sender:c?c.element:null}}});
b.extend(b.ui.sortable,{version:"1.8.7"})})(jQuery);
jQuery.effects||function(b,c){function f(l){var k;if(l&&l.constructor==Array&&l.length==3)return l;if(k=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(l))return[parseInt(k[1],10),parseInt(k[2],10),parseInt(k[3],10)];if(k=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(l))return[parseFloat(k[1])*2.55,parseFloat(k[2])*2.55,parseFloat(k[3])*2.55];if(k=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(l))return[parseInt(k[1],16),
parseInt(k[2],16),parseInt(k[3],16)];if(k=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(l))return[parseInt(k[1]+k[1],16),parseInt(k[2]+k[2],16),parseInt(k[3]+k[3],16)];if(/rgba\(0, 0, 0, 0\)/.exec(l))return j.transparent;return j[b.trim(l).toLowerCase()]}function g(l,k){var m;do{m=b.curCSS(l,k);if(m!=""&&m!="transparent"||b.nodeName(l,"body"))break;k="backgroundColor"}while(l=l.parentNode);return f(m)}function e(){var l=document.defaultView?document.defaultView.getComputedStyle(this,null):this.currentStyle,
k={},m,o;if(l&&l.length&&l[0]&&l[l[0]])for(var p=l.length;p--;){m=l[p];if(typeof l[m]=="string"){o=m.replace(/\-(\w)/g,function(s,r){return r.toUpperCase()});k[o]=l[m]}}else for(m in l)if(typeof l[m]==="string")k[m]=l[m];return k}function a(l){var k,m;for(k in l){m=l[k];if(m==null||b.isFunction(m)||k in q||/scrollbar/.test(k)||!/color/i.test(k)&&isNaN(parseFloat(m)))delete l[k]}return l}function d(l,k){var m={_:0},o;for(o in k)if(l[o]!=k[o])m[o]=k[o];return m}function h(l,k,m,o){if(typeof l=="object"){o=
k;m=null;k=l;l=k.effect}if(b.isFunction(k)){o=k;m=null;k={}}if(typeof k=="number"||b.fx.speeds[k]){o=m;m=k;k={}}if(b.isFunction(m)){o=m;m=null}k=k||{};m=m||k.duration;m=b.fx.off?0:typeof m=="number"?m:m in b.fx.speeds?b.fx.speeds[m]:b.fx.speeds._default;o=o||k.complete;return[l,k,m,o]}function i(l){if(!l||typeof l==="number"||b.fx.speeds[l])return true;if(typeof l==="string"&&!b.effects[l])return true;return false}b.effects={};b.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor",
"borderTopColor","borderColor","color","outlineColor"],function(l,k){b.fx.step[k]=function(m){if(!m.colorInit){m.start=g(m.elem,k);m.end=f(m.end);m.colorInit=true}m.elem.style[k]="rgb("+Math.max(Math.min(parseInt(m.pos*(m.end[0]-m.start[0])+m.start[0],10),255),0)+","+Math.max(Math.min(parseInt(m.pos*(m.end[1]-m.start[1])+m.start[1],10),255),0)+","+Math.max(Math.min(parseInt(m.pos*(m.end[2]-m.start[2])+m.start[2],10),255),0)+")"}});var j={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,
0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,
211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]},n=["add","remove","toggle"],q={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};b.effects.animateClass=function(l,k,m,
o){if(b.isFunction(m)){o=m;m=null}return this.each(function(){b.queue(this,"fx",function(){var p=b(this),s=p.attr("style")||" ",r=a(e.call(this)),u,v=p.attr("className");b.each(n,function(w,y){l[y]&&p[y+"Class"](l[y])});u=a(e.call(this));p.attr("className",v);p.animate(d(r,u),k,m,function(){b.each(n,function(w,y){l[y]&&p[y+"Class"](l[y])});if(typeof p.attr("style")=="object"){p.attr("style").cssText="";p.attr("style").cssText=s}else p.attr("style",s);o&&o.apply(this,arguments)});r=b.queue(this);u=
r.splice(r.length-1,1)[0];r.splice(1,0,u);b.dequeue(this)})})};b.fn.extend({_addClass:b.fn.addClass,addClass:function(l,k,m,o){return k?b.effects.animateClass.apply(this,[{add:l},k,m,o]):this._addClass(l)},_removeClass:b.fn.removeClass,removeClass:function(l,k,m,o){return k?b.effects.animateClass.apply(this,[{remove:l},k,m,o]):this._removeClass(l)},_toggleClass:b.fn.toggleClass,toggleClass:function(l,k,m,o,p){return typeof k=="boolean"||k===c?m?b.effects.animateClass.apply(this,[k?{add:l}:{remove:l},
m,o,p]):this._toggleClass(l,k):b.effects.animateClass.apply(this,[{toggle:l},k,m,o])},switchClass:function(l,k,m,o,p){return b.effects.animateClass.apply(this,[{add:k,remove:l},m,o,p])}});b.extend(b.effects,{version:"1.8.7",save:function(l,k){for(var m=0;m<k.length;m++)k[m]!==null&&l.data("ec.storage."+k[m],l[0].style[k[m]])},restore:function(l,k){for(var m=0;m<k.length;m++)k[m]!==null&&l.css(k[m],l.data("ec.storage."+k[m]))},setMode:function(l,k){if(k=="toggle")k=l.is(":hidden")?"show":"hide";
return k},getBaseline:function(l,k){var m;switch(l[0]){case "top":m=0;break;case "middle":m=0.5;break;case "bottom":m=1;break;default:m=l[0]/k.height}switch(l[1]){case "left":l=0;break;case "center":l=0.5;break;case "right":l=1;break;default:l=l[1]/k.width}return{x:l,y:m}},createWrapper:function(l){if(l.parent().is(".ui-effects-wrapper"))return l.parent();var k={width:l.outerWidth(true),height:l.outerHeight(true),"float":l.css("float")},m=b("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",
background:"transparent",border:"none",margin:0,padding:0});l.wrap(m);m=l.parent();if(l.css("position")=="static"){m.css({position:"relative"});l.css({position:"relative"})}else{b.extend(k,{position:l.css("position"),zIndex:l.css("z-index")});b.each(["top","left","bottom","right"],function(o,p){k[p]=l.css(p);if(isNaN(parseInt(k[p],10)))k[p]="auto"});l.css({position:"relative",top:0,left:0})}return m.css(k).show()},removeWrapper:function(l){if(l.parent().is(".ui-effects-wrapper"))return l.parent().replaceWith(l);
return l},setTransition:function(l,k,m,o){o=o||{};b.each(k,function(p,s){unit=l.cssUnit(s);if(unit[0]>0)o[s]=unit[0]*m+unit[1]});return o}});b.fn.extend({effect:function(l){var k=h.apply(this,arguments),m={options:k[1],duration:k[2],callback:k[3]};k=m.options.mode;var o=b.effects[l];if(b.fx.off||!o)return k?this[k](m.duration,m.callback):this.each(function(){m.callback&&m.callback.call(this)});return o.call(this,m)},_show:b.fn.show,show:function(l){if(i(l))return this._show.apply(this,arguments);
else{var k=h.apply(this,arguments);k[1].mode="show";return this.effect.apply(this,k)}},_hide:b.fn.hide,hide:function(l){if(i(l))return this._hide.apply(this,arguments);else{var k=h.apply(this,arguments);k[1].mode="hide";return this.effect.apply(this,k)}},__toggle:b.fn.toggle,toggle:function(l){if(i(l)||typeof l==="boolean"||b.isFunction(l))return this.__toggle.apply(this,arguments);else{var k=h.apply(this,arguments);k[1].mode="toggle";return this.effect.apply(this,k)}},cssUnit:function(l){var k=this.css(l),
m=[];b.each(["em","px","%","pt"],function(o,p){if(k.indexOf(p)>0)m=[parseFloat(k),p]});return m}});b.easing.jswing=b.easing.swing;b.extend(b.easing,{def:"easeOutQuad",swing:function(l,k,m,o,p){return b.easing[b.easing.def](l,k,m,o,p)},easeInQuad:function(l,k,m,o,p){return o*(k/=p)*k+m},easeOutQuad:function(l,k,m,o,p){return-o*(k/=p)*(k-2)+m},easeInOutQuad:function(l,k,m,o,p){if((k/=p/2)<1)return o/2*k*k+m;return-o/2*(--k*(k-2)-1)+m},easeInCubic:function(l,k,m,o,p){return o*(k/=p)*k*k+m},easeOutCubic:function(l,
k,m,o,p){return o*((k=k/p-1)*k*k+1)+m},easeInOutCubic:function(l,k,m,o,p){if((k/=p/2)<1)return o/2*k*k*k+m;return o/2*((k-=2)*k*k+2)+m},easeInQuart:function(l,k,m,o,p){return o*(k/=p)*k*k*k+m},easeOutQuart:function(l,k,m,o,p){return-o*((k=k/p-1)*k*k*k-1)+m},easeInOutQuart:function(l,k,m,o,p){if((k/=p/2)<1)return o/2*k*k*k*k+m;return-o/2*((k-=2)*k*k*k-2)+m},easeInQuint:function(l,k,m,o,p){return o*(k/=p)*k*k*k*k+m},easeOutQuint:function(l,k,m,o,p){return o*((k=k/p-1)*k*k*k*k+1)+m},easeInOutQuint:function(l,
k,m,o,p){if((k/=p/2)<1)return o/2*k*k*k*k*k+m;return o/2*((k-=2)*k*k*k*k+2)+m},easeInSine:function(l,k,m,o,p){return-o*Math.cos(k/p*(Math.PI/2))+o+m},easeOutSine:function(l,k,m,o,p){return o*Math.sin(k/p*(Math.PI/2))+m},easeInOutSine:function(l,k,m,o,p){return-o/2*(Math.cos(Math.PI*k/p)-1)+m},easeInExpo:function(l,k,m,o,p){return k==0?m:o*Math.pow(2,10*(k/p-1))+m},easeOutExpo:function(l,k,m,o,p){return k==p?m+o:o*(-Math.pow(2,-10*k/p)+1)+m},easeInOutExpo:function(l,k,m,o,p){if(k==0)return m;if(k==
p)return m+o;if((k/=p/2)<1)return o/2*Math.pow(2,10*(k-1))+m;return o/2*(-Math.pow(2,-10*--k)+2)+m},easeInCirc:function(l,k,m,o,p){return-o*(Math.sqrt(1-(k/=p)*k)-1)+m},easeOutCirc:function(l,k,m,o,p){return o*Math.sqrt(1-(k=k/p-1)*k)+m},easeInOutCirc:function(l,k,m,o,p){if((k/=p/2)<1)return-o/2*(Math.sqrt(1-k*k)-1)+m;return o/2*(Math.sqrt(1-(k-=2)*k)+1)+m},easeInElastic:function(l,k,m,o,p){l=1.70158;var s=0,r=o;if(k==0)return m;if((k/=p)==1)return m+o;s||(s=p*0.3);if(r<Math.abs(o)){r=o;l=s/4}else l=
s/(2*Math.PI)*Math.asin(o/r);return-(r*Math.pow(2,10*(k-=1))*Math.sin((k*p-l)*2*Math.PI/s))+m},easeOutElastic:function(l,k,m,o,p){l=1.70158;var s=0,r=o;if(k==0)return m;if((k/=p)==1)return m+o;s||(s=p*0.3);if(r<Math.abs(o)){r=o;l=s/4}else l=s/(2*Math.PI)*Math.asin(o/r);return r*Math.pow(2,-10*k)*Math.sin((k*p-l)*2*Math.PI/s)+o+m},easeInOutElastic:function(l,k,m,o,p){l=1.70158;var s=0,r=o;if(k==0)return m;if((k/=p/2)==2)return m+o;s||(s=p*0.3*1.5);if(r<Math.abs(o)){r=o;l=s/4}else l=s/(2*Math.PI)*Math.asin(o/
r);if(k<1)return-0.5*r*Math.pow(2,10*(k-=1))*Math.sin((k*p-l)*2*Math.PI/s)+m;return r*Math.pow(2,-10*(k-=1))*Math.sin((k*p-l)*2*Math.PI/s)*0.5+o+m},easeInBack:function(l,k,m,o,p,s){if(s==c)s=1.70158;return o*(k/=p)*k*((s+1)*k-s)+m},easeOutBack:function(l,k,m,o,p,s){if(s==c)s=1.70158;return o*((k=k/p-1)*k*((s+1)*k+s)+1)+m},easeInOutBack:function(l,k,m,o,p,s){if(s==c)s=1.70158;if((k/=p/2)<1)return o/2*k*k*(((s*=1.525)+1)*k-s)+m;return o/2*((k-=2)*k*(((s*=1.525)+1)*k+s)+2)+m},easeInBounce:function(l,
k,m,o,p){return o-b.easing.easeOutBounce(l,p-k,0,o,p)+m},easeOutBounce:function(l,k,m,o,p){return(k/=p)<1/2.75?o*7.5625*k*k+m:k<2/2.75?o*(7.5625*(k-=1.5/2.75)*k+0.75)+m:k<2.5/2.75?o*(7.5625*(k-=2.25/2.75)*k+0.9375)+m:o*(7.5625*(k-=2.625/2.75)*k+0.984375)+m},easeInOutBounce:function(l,k,m,o,p){if(k<p/2)return b.easing.easeInBounce(l,k*2,0,o,p)*0.5+m;return b.easing.easeOutBounce(l,k*2-p,0,o,p)*0.5+o*0.5+m}})}(jQuery);
(function(b){b.effects.blind=function(c){return this.queue(function(){var f=b(this),g=["position","top","left"],e=b.effects.setMode(f,c.options.mode||"hide"),a=c.options.direction||"vertical";b.effects.save(f,g);f.show();var d=b.effects.createWrapper(f).css({overflow:"hidden"}),h=a=="vertical"?"height":"width";a=a=="vertical"?d.height():d.width();e=="show"&&d.css(h,0);var i={};i[h]=e=="show"?a:0;d.animate(i,c.duration,c.options.easing,function(){e=="hide"&&f.hide();b.effects.restore(f,g);b.effects.removeWrapper(f);
c.callback&&c.callback.apply(f[0],arguments);f.dequeue()})})}})(jQuery);
(function(b){b.effects.bounce=function(c){return this.queue(function(){var f=b(this),g=["position","top","left"],e=b.effects.setMode(f,c.options.mode||"effect"),a=c.options.direction||"up",d=c.options.distance||20,h=c.options.times||5,i=c.duration||250;/show|hide/.test(e)&&g.push("opacity");b.effects.save(f,g);f.show();b.effects.createWrapper(f);var j=a=="up"||a=="down"?"top":"left";a=a=="up"||a=="left"?"pos":"neg";d=c.options.distance||(j=="top"?f.outerHeight({margin:true})/3:f.outerWidth({margin:true})/
3);if(e=="show")f.css("opacity",0).css(j,a=="pos"?-d:d);if(e=="hide")d/=h*2;e!="hide"&&h--;if(e=="show"){var n={opacity:1};n[j]=(a=="pos"?"+=":"-=")+d;f.animate(n,i/2,c.options.easing);d/=2;h--}for(n=0;n<h;n++){var q={},l={};q[j]=(a=="pos"?"-=":"+=")+d;l[j]=(a=="pos"?"+=":"-=")+d;f.animate(q,i/2,c.options.easing).animate(l,i/2,c.options.easing);d=e=="hide"?d*2:d/2}if(e=="hide"){n={opacity:0};n[j]=(a=="pos"?"-=":"+=")+d;f.animate(n,i/2,c.options.easing,function(){f.hide();b.effects.restore(f,g);b.effects.removeWrapper(f);
c.callback&&c.callback.apply(this,arguments)})}else{q={};l={};q[j]=(a=="pos"?"-=":"+=")+d;l[j]=(a=="pos"?"+=":"-=")+d;f.animate(q,i/2,c.options.easing).animate(l,i/2,c.options.easing,function(){b.effects.restore(f,g);b.effects.removeWrapper(f);c.callback&&c.callback.apply(this,arguments)})}f.queue("fx",function(){f.dequeue()});f.dequeue()})}})(jQuery);
(function(b){b.effects.clip=function(c){return this.queue(function(){var f=b(this),g=["position","top","left","height","width"],e=b.effects.setMode(f,c.options.mode||"hide"),a=c.options.direction||"vertical";b.effects.save(f,g);f.show();var d=b.effects.createWrapper(f).css({overflow:"hidden"});d=f[0].tagName=="IMG"?d:f;var h={size:a=="vertical"?"height":"width",position:a=="vertical"?"top":"left"};a=a=="vertical"?d.height():d.width();if(e=="show"){d.css(h.size,0);d.css(h.position,a/2)}var i={};i[h.size]=
e=="show"?a:0;i[h.position]=e=="show"?0:a/2;d.animate(i,{queue:false,duration:c.duration,easing:c.options.easing,complete:function(){e=="hide"&&f.hide();b.effects.restore(f,g);b.effects.removeWrapper(f);c.callback&&c.callback.apply(f[0],arguments);f.dequeue()}})})}})(jQuery);
(function(b){b.effects.drop=function(c){return this.queue(function(){var f=b(this),g=["position","top","left","opacity"],e=b.effects.setMode(f,c.options.mode||"hide"),a=c.options.direction||"left";b.effects.save(f,g);f.show();b.effects.createWrapper(f);var d=a=="up"||a=="down"?"top":"left";a=a=="up"||a=="left"?"pos":"neg";var h=c.options.distance||(d=="top"?f.outerHeight({margin:true})/2:f.outerWidth({margin:true})/2);if(e=="show")f.css("opacity",0).css(d,a=="pos"?-h:h);var i={opacity:e=="show"?1:
0};i[d]=(e=="show"?a=="pos"?"+=":"-=":a=="pos"?"-=":"+=")+h;f.animate(i,{queue:false,duration:c.duration,easing:c.options.easing,complete:function(){e=="hide"&&f.hide();b.effects.restore(f,g);b.effects.removeWrapper(f);c.callback&&c.callback.apply(this,arguments);f.dequeue()}})})}})(jQuery);
(function(b){b.effects.explode=function(c){return this.queue(function(){var f=c.options.pieces?Math.round(Math.sqrt(c.options.pieces)):3,g=c.options.pieces?Math.round(Math.sqrt(c.options.pieces)):3;c.options.mode=c.options.mode=="toggle"?b(this).is(":visible")?"hide":"show":c.options.mode;var e=b(this).show().css("visibility","hidden"),a=e.offset();a.top-=parseInt(e.css("marginTop"),10)||0;a.left-=parseInt(e.css("marginLeft"),10)||0;for(var d=e.outerWidth(true),h=e.outerHeight(true),i=0;i<f;i++)for(var j=
0;j<g;j++)e.clone().appendTo("body").wrap("<div></div>").css({position:"absolute",visibility:"visible",left:-j*(d/g),top:-i*(h/f)}).parent().addClass("ui-effects-explode").css({position:"absolute",overflow:"hidden",width:d/g,height:h/f,left:a.left+j*(d/g)+(c.options.mode=="show"?(j-Math.floor(g/2))*(d/g):0),top:a.top+i*(h/f)+(c.options.mode=="show"?(i-Math.floor(f/2))*(h/f):0),opacity:c.options.mode=="show"?0:1}).animate({left:a.left+j*(d/g)+(c.options.mode=="show"?0:(j-Math.floor(g/2))*(d/g)),top:a.top+
i*(h/f)+(c.options.mode=="show"?0:(i-Math.floor(f/2))*(h/f)),opacity:c.options.mode=="show"?1:0},c.duration||500);setTimeout(function(){c.options.mode=="show"?e.css({visibility:"visible"}):e.css({visibility:"visible"}).hide();c.callback&&c.callback.apply(e[0]);e.dequeue();b("div.ui-effects-explode").remove()},c.duration||500)})}})(jQuery);
(function(b){b.effects.fade=function(c){return this.queue(function(){var f=b(this),g=b.effects.setMode(f,c.options.mode||"hide");f.animate({opacity:g},{queue:false,duration:c.duration,easing:c.options.easing,complete:function(){c.callback&&c.callback.apply(this,arguments);f.dequeue()}})})}})(jQuery);
(function(b){b.effects.fold=function(c){return this.queue(function(){var f=b(this),g=["position","top","left"],e=b.effects.setMode(f,c.options.mode||"hide"),a=c.options.size||15,d=!!c.options.horizFirst,h=c.duration?c.duration/2:b.fx.speeds._default/2;b.effects.save(f,g);f.show();var i=b.effects.createWrapper(f).css({overflow:"hidden"}),j=e=="show"!=d,n=j?["width","height"]:["height","width"];j=j?[i.width(),i.height()]:[i.height(),i.width()];var q=/([0-9]+)%/.exec(a);if(q)a=parseInt(q[1],10)/100*
j[e=="hide"?0:1];if(e=="show")i.css(d?{height:0,width:a}:{height:a,width:0});d={};q={};d[n[0]]=e=="show"?j[0]:a;q[n[1]]=e=="show"?j[1]:0;i.animate(d,h,c.options.easing).animate(q,h,c.options.easing,function(){e=="hide"&&f.hide();b.effects.restore(f,g);b.effects.removeWrapper(f);c.callback&&c.callback.apply(f[0],arguments);f.dequeue()})})}})(jQuery);
(function(b){b.effects.highlight=function(c){return this.queue(function(){var f=b(this),g=["backgroundImage","backgroundColor","opacity"],e=b.effects.setMode(f,c.options.mode||"show"),a={backgroundColor:f.css("backgroundColor")};if(e=="hide")a.opacity=0;b.effects.save(f,g);f.show().css({backgroundImage:"none",backgroundColor:c.options.color||"#ffff99"}).animate(a,{queue:false,duration:c.duration,easing:c.options.easing,complete:function(){e=="hide"&&f.hide();b.effects.restore(f,g);e=="show"&&!b.support.opacity&&
this.style.removeAttribute("filter");c.callback&&c.callback.apply(this,arguments);f.dequeue()}})})}})(jQuery);
(function(b){b.effects.pulsate=function(c){return this.queue(function(){var f=b(this),g=b.effects.setMode(f,c.options.mode||"show");times=(c.options.times||5)*2-1;duration=c.duration?c.duration/2:b.fx.speeds._default/2;isVisible=f.is(":visible");animateTo=0;if(!isVisible){f.css("opacity",0).show();animateTo=1}if(g=="hide"&&isVisible||g=="show"&&!isVisible)times--;for(g=0;g<times;g++){f.animate({opacity:animateTo},duration,c.options.easing);animateTo=(animateTo+1)%2}f.animate({opacity:animateTo},duration,
c.options.easing,function(){animateTo==0&&f.hide();c.callback&&c.callback.apply(this,arguments)});f.queue("fx",function(){f.dequeue()}).dequeue()})}})(jQuery);
(function(b){b.effects.puff=function(c){return this.queue(function(){var f=b(this),g=b.effects.setMode(f,c.options.mode||"hide"),e=parseInt(c.options.percent,10)||150,a=e/100,d={height:f.height(),width:f.width()};b.extend(c.options,{fade:true,mode:g,percent:g=="hide"?e:100,from:g=="hide"?d:{height:d.height*a,width:d.width*a}});f.effect("scale",c.options,c.duration,c.callback);f.dequeue()})};b.effects.scale=function(c){return this.queue(function(){var f=b(this),g=b.extend(true,{},c.options),e=b.effects.setMode(f,
c.options.mode||"effect"),a=parseInt(c.options.percent,10)||(parseInt(c.options.percent,10)==0?0:e=="hide"?0:100),d=c.options.direction||"both",h=c.options.origin;if(e!="effect"){g.origin=h||["middle","center"];g.restore=true}h={height:f.height(),width:f.width()};f.from=c.options.from||(e=="show"?{height:0,width:0}:h);a={y:d!="horizontal"?a/100:1,x:d!="vertical"?a/100:1};f.to={height:h.height*a.y,width:h.width*a.x};if(c.options.fade){if(e=="show"){f.from.opacity=0;f.to.opacity=1}if(e=="hide"){f.from.opacity=
1;f.to.opacity=0}}g.from=f.from;g.to=f.to;g.mode=e;f.effect("size",g,c.duration,c.callback);f.dequeue()})};b.effects.size=function(c){return this.queue(function(){var f=b(this),g=["position","top","left","width","height","overflow","opacity"],e=["position","top","left","overflow","opacity"],a=["width","height","overflow"],d=["fontSize"],h=["borderTopWidth","borderBottomWidth","paddingTop","paddingBottom"],i=["borderLeftWidth","borderRightWidth","paddingLeft","paddingRight"],j=b.effects.setMode(f,
c.options.mode||"effect"),n=c.options.restore||false,q=c.options.scale||"both",l=c.options.origin,k={height:f.height(),width:f.width()};f.from=c.options.from||k;f.to=c.options.to||k;if(l){l=b.effects.getBaseline(l,k);f.from.top=(k.height-f.from.height)*l.y;f.from.left=(k.width-f.from.width)*l.x;f.to.top=(k.height-f.to.height)*l.y;f.to.left=(k.width-f.to.width)*l.x}var m={from:{y:f.from.height/k.height,x:f.from.width/k.width},to:{y:f.to.height/k.height,x:f.to.width/k.width}};if(q=="box"||q=="both"){if(m.from.y!=
m.to.y){g=g.concat(h);f.from=b.effects.setTransition(f,h,m.from.y,f.from);f.to=b.effects.setTransition(f,h,m.to.y,f.to)}if(m.from.x!=m.to.x){g=g.concat(i);f.from=b.effects.setTransition(f,i,m.from.x,f.from);f.to=b.effects.setTransition(f,i,m.to.x,f.to)}}if(q=="content"||q=="both")if(m.from.y!=m.to.y){g=g.concat(d);f.from=b.effects.setTransition(f,d,m.from.y,f.from);f.to=b.effects.setTransition(f,d,m.to.y,f.to)}b.effects.save(f,n?g:e);f.show();b.effects.createWrapper(f);f.css("overflow","hidden").css(f.from);
if(q=="content"||q=="both"){h=h.concat(["marginTop","marginBottom"]).concat(d);i=i.concat(["marginLeft","marginRight"]);a=g.concat(h).concat(i);f.find("*[width]").each(function(){child=b(this);n&&b.effects.save(child,a);var o={height:child.height(),width:child.width()};child.from={height:o.height*m.from.y,width:o.width*m.from.x};child.to={height:o.height*m.to.y,width:o.width*m.to.x};if(m.from.y!=m.to.y){child.from=b.effects.setTransition(child,h,m.from.y,child.from);child.to=b.effects.setTransition(child,
h,m.to.y,child.to)}if(m.from.x!=m.to.x){child.from=b.effects.setTransition(child,i,m.from.x,child.from);child.to=b.effects.setTransition(child,i,m.to.x,child.to)}child.css(child.from);child.animate(child.to,c.duration,c.options.easing,function(){n&&b.effects.restore(child,a)})})}f.animate(f.to,{queue:false,duration:c.duration,easing:c.options.easing,complete:function(){f.to.opacity===0&&f.css("opacity",f.from.opacity);j=="hide"&&f.hide();b.effects.restore(f,n?g:e);b.effects.removeWrapper(f);c.callback&&
c.callback.apply(this,arguments);f.dequeue()}})})}})(jQuery);
(function(b){b.effects.shake=function(c){return this.queue(function(){var f=b(this),g=["position","top","left"];b.effects.setMode(f,c.options.mode||"effect");var e=c.options.direction||"left",a=c.options.distance||20,d=c.options.times||3,h=c.duration||c.options.duration||140;b.effects.save(f,g);f.show();b.effects.createWrapper(f);var i=e=="up"||e=="down"?"top":"left",j=e=="up"||e=="left"?"pos":"neg";e={};var n={},q={};e[i]=(j=="pos"?"-=":"+=")+a;n[i]=(j=="pos"?"+=":"-=")+a*2;q[i]=(j=="pos"?"-=":"+=")+
a*2;f.animate(e,h,c.options.easing);for(a=1;a<d;a++)f.animate(n,h,c.options.easing).animate(q,h,c.options.easing);f.animate(n,h,c.options.easing).animate(e,h/2,c.options.easing,function(){b.effects.restore(f,g);b.effects.removeWrapper(f);c.callback&&c.callback.apply(this,arguments)});f.queue("fx",function(){f.dequeue()});f.dequeue()})}})(jQuery);
(function(b){b.effects.slide=function(c){return this.queue(function(){var f=b(this),g=["position","top","left"],e=b.effects.setMode(f,c.options.mode||"show"),a=c.options.direction||"left";b.effects.save(f,g);f.show();b.effects.createWrapper(f).css({overflow:"hidden"});var d=a=="up"||a=="down"?"top":"left";a=a=="up"||a=="left"?"pos":"neg";var h=c.options.distance||(d=="top"?f.outerHeight({margin:true}):f.outerWidth({margin:true}));if(e=="show")f.css(d,a=="pos"?isNaN(h)?"-"+h:-h:h);var i={};i[d]=(e==
"show"?a=="pos"?"+=":"-=":a=="pos"?"-=":"+=")+h;f.animate(i,{queue:false,duration:c.duration,easing:c.options.easing,complete:function(){e=="hide"&&f.hide();b.effects.restore(f,g);b.effects.removeWrapper(f);c.callback&&c.callback.apply(this,arguments);f.dequeue()}})})}})(jQuery);
(function(b){b.effects.transfer=function(c){return this.queue(function(){var f=b(this),g=b(c.options.to),e=g.offset();g={top:e.top,left:e.left,height:g.innerHeight(),width:g.innerWidth()};e=f.offset();var a=b('<div class="ui-effects-transfer"></div>').appendTo(document.body).addClass(c.options.className).css({top:e.top,left:e.left,height:f.innerHeight(),width:f.innerWidth(),position:"absolute"}).animate(g,c.duration,c.options.easing,function(){a.remove();c.callback&&c.callback.apply(f[0],arguments);
f.dequeue()})})}})(jQuery);
(function(b){b.widget("ui.accordion",{options:{active:0,animated:"slide",autoHeight:true,clearStyle:false,collapsible:false,event:"click",fillSpace:false,header:"> li > :first-child,> :not(li):even",icons:{header:"ui-icon-triangle-1-e",headerSelected:"ui-icon-triangle-1-s"},navigation:false,navigationFilter:function(){return this.href.toLowerCase()===location.href.toLowerCase()}},_create:function(){var c=this,f=c.options;c.running=0;c.element.addClass("ui-accordion ui-widget ui-helper-reset").children("li").addClass("ui-accordion-li-fix");c.headers=
c.element.find(f.header).addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-all").bind("mouseenter.accordion",function(){f.disabled||b(this).addClass("ui-state-hover")}).bind("mouseleave.accordion",function(){f.disabled||b(this).removeClass("ui-state-hover")}).bind("focus.accordion",function(){f.disabled||b(this).addClass("ui-state-focus")}).bind("blur.accordion",function(){f.disabled||b(this).removeClass("ui-state-focus")});c.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom");
if(f.navigation){var g=c.element.find("a").filter(f.navigationFilter).eq(0);if(g.length){var e=g.closest(".ui-accordion-header");c.active=e.length?e:g.closest(".ui-accordion-content").prev()}}c.active=c._findActive(c.active||f.active).addClass("ui-state-default ui-state-active").toggleClass("ui-corner-all").toggleClass("ui-corner-top");c.active.next().addClass("ui-accordion-content-active");c._createIcons();c.resize();c.element.attr("role","tablist");c.headers.attr("role","tab").bind("keydown.accordion",
function(a){return c._keydown(a)}).next().attr("role","tabpanel");c.headers.not(c.active||"").attr({"aria-expanded":"false",tabIndex:-1}).next().hide();c.active.length?c.active.attr({"aria-expanded":"true",tabIndex:0}):c.headers.eq(0).attr("tabIndex",0);b.browser.safari||c.headers.find("a").attr("tabIndex",-1);f.event&&c.headers.bind(f.event.split(" ").join(".accordion ")+".accordion",function(a){c._clickHandler.call(c,a,this);a.preventDefault()})},_createIcons:function(){var c=this.options;if(c.icons){b("<span></span>").addClass("ui-icon "+
c.icons.header).prependTo(this.headers);this.active.children(".ui-icon").toggleClass(c.icons.header).toggleClass(c.icons.headerSelected);this.element.addClass("ui-accordion-icons")}},_destroyIcons:function(){this.headers.children(".ui-icon").remove();this.element.removeClass("ui-accordion-icons")},destroy:function(){var c=this.options;this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role");this.headers.unbind(".accordion").removeClass("ui-accordion-header ui-accordion-disabled ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-expanded").removeAttr("tabIndex");
this.headers.find("a").removeAttr("tabIndex");this._destroyIcons();var f=this.headers.next().css("display","").removeAttr("role").removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-accordion-disabled ui-state-disabled");if(c.autoHeight||c.fillHeight)f.css("height","");return b.Widget.prototype.destroy.call(this)},_setOption:function(c,f){b.Widget.prototype._setOption.apply(this,arguments);c=="active"&&this.activate(f);if(c=="icons"){this._destroyIcons();
f&&this._createIcons()}if(c=="disabled")this.headers.add(this.headers.next())[f?"addClass":"removeClass"]("ui-accordion-disabled ui-state-disabled")},_keydown:function(c){if(!(this.options.disabled||c.altKey||c.ctrlKey)){var f=b.ui.keyCode,g=this.headers.length,e=this.headers.index(c.target),a=false;switch(c.keyCode){case f.RIGHT:case f.DOWN:a=this.headers[(e+1)%g];break;case f.LEFT:case f.UP:a=this.headers[(e-1+g)%g];break;case f.SPACE:case f.ENTER:this._clickHandler({target:c.target},c.target);
c.preventDefault()}if(a){b(c.target).attr("tabIndex",-1);b(a).attr("tabIndex",0);a.focus();return false}return true}},resize:function(){var c=this.options,f;if(c.fillSpace){if(b.browser.msie){var g=this.element.parent().css("overflow");this.element.parent().css("overflow","hidden")}f=this.element.parent().height();b.browser.msie&&this.element.parent().css("overflow",g);this.headers.each(function(){f-=b(this).outerHeight(true)});this.headers.next().each(function(){b(this).height(Math.max(0,f-b(this).innerHeight()+
b(this).height()))}).css("overflow","auto")}else if(c.autoHeight){f=0;this.headers.next().each(function(){f=Math.max(f,b(this).height("").height())}).height(f)}return this},activate:function(c){this.options.active=c;c=this._findActive(c)[0];this._clickHandler({target:c},c);return this},_findActive:function(c){return c?typeof c==="number"?this.headers.filter(":eq("+c+")"):this.headers.not(this.headers.not(c)):c===false?b([]):this.headers.filter(":eq(0)")},_clickHandler:function(c,f){var g=this.options;
if(!g.disabled)if(c.target){c=b(c.currentTarget||f);f=c[0]===this.active[0];g.active=g.collapsible&&f?false:this.headers.index(c);if(!(this.running||!g.collapsible&&f)){this.active.removeClass("ui-state-active ui-corner-top").addClass("ui-state-default ui-corner-all").children(".ui-icon").removeClass(g.icons.headerSelected).addClass(g.icons.header);if(!f){c.removeClass("ui-state-default ui-corner-all").addClass("ui-state-active ui-corner-top").children(".ui-icon").removeClass(g.icons.header).addClass(g.icons.headerSelected);
c.next().addClass("ui-accordion-content-active")}d=c.next();e=this.active.next();a={options:g,newHeader:f&&g.collapsible?b([]):c,oldHeader:this.active,newContent:f&&g.collapsible?b([]):d,oldContent:e};g=this.headers.index(this.active[0])>this.headers.index(c[0]);this.active=f?b([]):c;this._toggle(d,e,a,f,g)}}else if(g.collapsible){this.active.removeClass("ui-state-active ui-corner-top").addClass("ui-state-default ui-corner-all").children(".ui-icon").removeClass(g.icons.headerSelected).addClass(g.icons.header);
this.active.next().addClass("ui-accordion-content-active");var e=this.active.next(),a={options:g,newHeader:b([]),oldHeader:g.active,newContent:b([]),oldContent:e},d=this.active=b([]);this._toggle(d,e,a)}},_toggle:function(c,f,g,e,a){var d=this,h=d.options;d.toShow=c;d.toHide=f;d.data=g;var i=function(){if(d)return d._completed.apply(d,arguments)};d._trigger("changestart",null,d.data);d.running=f.size()===0?c.size():f.size();if(h.animated){g={};g=h.collapsible&&e?{toShow:b([]),toHide:f,complete:i,
down:a,autoHeight:h.autoHeight||h.fillSpace}:{toShow:c,toHide:f,complete:i,down:a,autoHeight:h.autoHeight||h.fillSpace};if(!h.proxied)h.proxied=h.animated;if(!h.proxiedDuration)h.proxiedDuration=h.duration;h.animated=b.isFunction(h.proxied)?h.proxied(g):h.proxied;h.duration=b.isFunction(h.proxiedDuration)?h.proxiedDuration(g):h.proxiedDuration;e=b.ui.accordion.animations;var j=h.duration,n=h.animated;if(n&&!e[n]&&!b.easing[n])n="slide";e[n]||(e[n]=function(q){this.slide(q,{easing:n,duration:j||700})});
e[n](g)}else{if(h.collapsible&&e)c.toggle();else{f.hide();c.show()}i(true)}f.prev().attr({"aria-expanded":"false",tabIndex:-1}).blur();c.prev().attr({"aria-expanded":"true",tabIndex:0}).focus()},_completed:function(c){this.running=c?0:--this.running;if(!this.running){this.options.clearStyle&&this.toShow.add(this.toHide).css({height:"",overflow:""});this.toHide.removeClass("ui-accordion-content-active");this._trigger("change",null,this.data)}}});b.extend(b.ui.accordion,{version:"1.8.7",animations:{slide:function(c,
f){c=b.extend({easing:"swing",duration:300},c,f);if(c.toHide.size())if(c.toShow.size()){var g=c.toShow.css("overflow"),e=0,a={},d={},h;f=c.toShow;h=f[0].style.width;f.width(parseInt(f.parent().width(),10)-parseInt(f.css("paddingLeft"),10)-parseInt(f.css("paddingRight"),10)-(parseInt(f.css("borderLeftWidth"),10)||0)-(parseInt(f.css("borderRightWidth"),10)||0));b.each(["height","paddingTop","paddingBottom"],function(i,j){d[j]="hide";i=(""+b.css(c.toShow[0],j)).match(/^([\d+-.]+)(.*)$/);a[j]={value:i[1],
unit:i[2]||"px"}});c.toShow.css({height:0,overflow:"hidden"}).show();c.toHide.filter(":hidden").each(c.complete).end().filter(":visible").animate(d,{step:function(i,j){if(j.prop=="height")e=j.end-j.start===0?0:(j.now-j.start)/(j.end-j.start);c.toShow[0].style[j.prop]=e*a[j.prop].value+a[j.prop].unit},duration:c.duration,easing:c.easing,complete:function(){c.autoHeight||c.toShow.css("height","");c.toShow.css({width:h,overflow:g});c.complete()}})}else c.toHide.animate({height:"hide",paddingTop:"hide",
paddingBottom:"hide"},c);else c.toShow.animate({height:"show",paddingTop:"show",paddingBottom:"show"},c)},bounceslide:function(c){this.slide(c,{easing:c.down?"easeOutBounce":"swing",duration:c.down?1E3:200})}}})})(jQuery);
(function(b){b.widget("ui.autocomplete",{options:{appendTo:"body",delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null},_create:function(){var c=this,f=this.element[0].ownerDocument,g;this.element.addClass("ui-autocomplete-input").attr("autocomplete","off").attr({role:"textbox","aria-autocomplete":"list","aria-haspopup":"true"}).bind("keydown.autocomplete",function(e){if(!(c.options.disabled||c.element.attr("readonly"))){g=false;var a=b.ui.keyCode;switch(e.keyCode){case a.PAGE_UP:c._move("previousPage",
e);break;case a.PAGE_DOWN:c._move("nextPage",e);break;case a.UP:c._move("previous",e);e.preventDefault();break;case a.DOWN:c._move("next",e);e.preventDefault();break;case a.ENTER:case a.NUMPAD_ENTER:if(c.menu.active){g=true;e.preventDefault()}case a.TAB:if(!c.menu.active)return;c.menu.select(e);break;case a.ESCAPE:c.element.val(c.term);c.close(e);break;default:clearTimeout(c.searching);c.searching=setTimeout(function(){if(c.term!=c.element.val()){c.selectedItem=null;c.search(null,e)}},c.options.delay);
break}}}).bind("keypress.autocomplete",function(e){if(g){g=false;e.preventDefault()}}).bind("focus.autocomplete",function(){if(!c.options.disabled){c.selectedItem=null;c.previous=c.element.val()}}).bind("blur.autocomplete",function(e){if(!c.options.disabled){clearTimeout(c.searching);c.closing=setTimeout(function(){c.close(e);c._change(e)},150)}});this._initSource();this.response=function(){return c._response.apply(c,arguments)};this.menu=b("<ul></ul>").addClass("ui-autocomplete").appendTo(b(this.options.appendTo||
"body",f)[0]).mousedown(function(e){var a=c.menu.element[0];b(e.target).closest(".ui-menu-item").length||setTimeout(function(){b(document).one("mousedown",function(d){d.target!==c.element[0]&&d.target!==a&&!b.ui.contains(a,d.target)&&c.close()})},1);setTimeout(function(){clearTimeout(c.closing)},13)}).menu({focus:function(e,a){a=a.item.data("item.autocomplete");false!==c._trigger("focus",e,{item:a})&&/^key/.test(e.originalEvent.type)&&c.element.val(a.value)},selected:function(e,a){var d=a.item.data("item.autocomplete"),
h=c.previous;if(c.element[0]!==f.activeElement){c.element.focus();c.previous=h;setTimeout(function(){c.previous=h;c.selectedItem=d},1)}false!==c._trigger("select",e,{item:d})&&c.element.val(d.value);c.term=c.element.val();c.close(e);c.selectedItem=d},blur:function(){c.menu.element.is(":visible")&&c.element.val()!==c.term&&c.element.val(c.term)}}).zIndex(this.element.zIndex()+1).css({top:0,left:0}).hide().data("menu");b.fn.bgiframe&&this.menu.element.bgiframe()},destroy:function(){this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete").removeAttr("role").removeAttr("aria-autocomplete").removeAttr("aria-haspopup");
this.menu.element.remove();b.Widget.prototype.destroy.call(this)},_setOption:function(c,f){b.Widget.prototype._setOption.apply(this,arguments);c==="source"&&this._initSource();if(c==="appendTo")this.menu.element.appendTo(b(f||"body",this.element[0].ownerDocument)[0])},_initSource:function(){var c=this,f,g;if(b.isArray(this.options.source)){f=this.options.source;this.source=function(e,a){a(b.ui.autocomplete.filter(f,e.term))}}else if(typeof this.options.source==="string"){g=this.options.source;this.source=
function(e,a){c.xhr&&c.xhr.abort();c.xhr=b.ajax({url:g,data:e,dataType:"json",success:function(d,h,i){i===c.xhr&&a(d);c.xhr=null},error:function(d){d===c.xhr&&a([]);c.xhr=null}})}}else this.source=this.options.source},search:function(c,f){c=c!=null?c:this.element.val();this.term=this.element.val();if(c.length<this.options.minLength)return this.close(f);clearTimeout(this.closing);if(this._trigger("search",f)!==false)return this._search(c)},_search:function(c){this.element.addClass("ui-autocomplete-loading");
this.source({term:c},this.response)},_response:function(c){if(c&&c.length){c=this._normalize(c);this._suggest(c);this._trigger("open")}else this.close();this.element.removeClass("ui-autocomplete-loading")},close:function(c){clearTimeout(this.closing);if(this.menu.element.is(":visible")){this.menu.element.hide();this.menu.deactivate();this._trigger("close",c)}},_change:function(c){this.previous!==this.element.val()&&this._trigger("change",c,{item:this.selectedItem})},_normalize:function(c){if(c.length&&
c[0].label&&c[0].value)return c;return b.map(c,function(f){if(typeof f==="string")return{label:f,value:f};return b.extend({label:f.label||f.value,value:f.value||f.label},f)})},_suggest:function(c){var f=this.menu.element.empty().zIndex(this.element.zIndex()+1);this._renderMenu(f,c);this.menu.deactivate();this.menu.refresh();f.show();this._resizeMenu();f.position(b.extend({of:this.element},this.options.position))},_resizeMenu:function(){var c=this.menu.element;c.outerWidth(Math.max(c.width("").outerWidth(),
this.element.outerWidth()))},_renderMenu:function(c,f){var g=this;b.each(f,function(e,a){g._renderItem(c,a)})},_renderItem:function(c,f){return b("<li></li>").data("item.autocomplete",f).append(b("<a></a>").text(f.label)).appendTo(c)},_move:function(c,f){if(this.menu.element.is(":visible"))if(this.menu.first()&&/^previous/.test(c)||this.menu.last()&&/^next/.test(c)){this.element.val(this.term);this.menu.deactivate()}else this.menu[c](f);else this.search(null,f)},widget:function(){return this.menu.element}});
b.extend(b.ui.autocomplete,{escapeRegex:function(c){return c.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&")},filter:function(c,f){var g=new RegExp(b.ui.autocomplete.escapeRegex(f),"i");return b.grep(c,function(e){return g.test(e.label||e.value||e)})}})})(jQuery);
(function(b){b.widget("ui.menu",{_create:function(){var c=this;this.element.addClass("ui-menu ui-widget ui-widget-content ui-corner-all").attr({role:"listbox","aria-activedescendant":"ui-active-menuitem"}).click(function(f){if(b(f.target).closest(".ui-menu-item a").length){f.preventDefault();c.select(f)}});this.refresh()},refresh:function(){var c=this;this.element.children("li:not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role","menuitem").children("a").addClass("ui-corner-all").attr("tabindex",
-1).mouseenter(function(f){c.activate(f,b(this).parent())}).mouseleave(function(){c.deactivate()})},activate:function(c,f){this.deactivate();if(this.hasScroll()){var g=f.offset().top-this.element.offset().top,e=this.element.attr("scrollTop"),a=this.element.height();if(g<0)this.element.attr("scrollTop",e+g);else g>=a&&this.element.attr("scrollTop",e+g-a+f.height())}this.active=f.eq(0).children("a").addClass("ui-state-hover").attr("id","ui-active-menuitem").end();this._trigger("focus",c,{item:f})},
deactivate:function(){if(this.active){this.active.children("a").removeClass("ui-state-hover").removeAttr("id");this._trigger("blur");this.active=null}},next:function(c){this.move("next",".ui-menu-item:first",c)},previous:function(c){this.move("prev",".ui-menu-item:last",c)},first:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},last:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},move:function(c,f,g){if(this.active){c=this.active[c+"All"](".ui-menu-item").eq(0);
c.length?this.activate(g,c):this.activate(g,this.element.children(f))}else this.activate(g,this.element.children(f))},nextPage:function(c){if(this.hasScroll())if(!this.active||this.last())this.activate(c,this.element.children(".ui-menu-item:first"));else{var f=this.active.offset().top,g=this.element.height(),e=this.element.children(".ui-menu-item").filter(function(){var a=b(this).offset().top-f-g+b(this).height();return a<10&&a>-10});e.length||(e=this.element.children(".ui-menu-item:last"));this.activate(c,
e)}else this.activate(c,this.element.children(".ui-menu-item").filter(!this.active||this.last()?":first":":last"))},previousPage:function(c){if(this.hasScroll())if(!this.active||this.first())this.activate(c,this.element.children(".ui-menu-item:last"));else{var f=this.active.offset().top,g=this.element.height();result=this.element.children(".ui-menu-item").filter(function(){var e=b(this).offset().top-f+g-b(this).height();return e<10&&e>-10});result.length||(result=this.element.children(".ui-menu-item:first"));
this.activate(c,result)}else this.activate(c,this.element.children(".ui-menu-item").filter(!this.active||this.first()?":last":":first"))},hasScroll:function(){return this.element.height()<this.element.attr("scrollHeight")},select:function(c){this._trigger("selected",c,{item:this.active})}})})(jQuery);
(function(b){var c,f=function(e){b(":ui-button",e.target.form).each(function(){var a=b(this).data("button");setTimeout(function(){a.refresh()},1)})},g=function(e){var a=e.name,d=e.form,h=b([]);if(a)h=d?b(d).find("[name='"+a+"']"):b("[name='"+a+"']",e.ownerDocument).filter(function(){return!this.form});return h};b.widget("ui.button",{options:{disabled:null,text:true,label:null,icons:{primary:null,secondary:null}},_create:function(){this.element.closest("form").unbind("reset.button").bind("reset.button",
f);if(typeof this.options.disabled!=="boolean")this.options.disabled=this.element.attr("disabled");this._determineButtonType();this.hasTitle=!!this.buttonElement.attr("title");var e=this,a=this.options,d=this.type==="checkbox"||this.type==="radio",h="ui-state-hover"+(!d?" ui-state-active":"");if(a.label===null)a.label=this.buttonElement.html();if(this.element.is(":disabled"))a.disabled=true;this.buttonElement.addClass("ui-button ui-widget ui-state-default ui-corner-all").attr("role","button").bind("mouseenter.button",
function(){if(!a.disabled){b(this).addClass("ui-state-hover");this===c&&b(this).addClass("ui-state-active")}}).bind("mouseleave.button",function(){a.disabled||b(this).removeClass(h)}).bind("focus.button",function(){b(this).addClass("ui-state-focus")}).bind("blur.button",function(){b(this).removeClass("ui-state-focus")});d&&this.element.bind("change.button",function(){e.refresh()});if(this.type==="checkbox")this.buttonElement.bind("click.button",function(){if(a.disabled)return false;b(this).toggleClass("ui-state-active");
e.buttonElement.attr("aria-pressed",e.element[0].checked)});else if(this.type==="radio")this.buttonElement.bind("click.button",function(){if(a.disabled)return false;b(this).addClass("ui-state-active");e.buttonElement.attr("aria-pressed",true);var i=e.element[0];g(i).not(i).map(function(){return b(this).button("widget")[0]}).removeClass("ui-state-active").attr("aria-pressed",false)});else{this.buttonElement.bind("mousedown.button",function(){if(a.disabled)return false;b(this).addClass("ui-state-active");
c=this;b(document).one("mouseup",function(){c=null})}).bind("mouseup.button",function(){if(a.disabled)return false;b(this).removeClass("ui-state-active")}).bind("keydown.button",function(i){if(a.disabled)return false;if(i.keyCode==b.ui.keyCode.SPACE||i.keyCode==b.ui.keyCode.ENTER)b(this).addClass("ui-state-active")}).bind("keyup.button",function(){b(this).removeClass("ui-state-active")});this.buttonElement.is("a")&&this.buttonElement.keyup(function(i){i.keyCode===b.ui.keyCode.SPACE&&b(this).click()})}this._setOption("disabled",
a.disabled)},_determineButtonType:function(){this.type=this.element.is(":checkbox")?"checkbox":this.element.is(":radio")?"radio":this.element.is("input")?"input":"button";if(this.type==="checkbox"||this.type==="radio"){this.buttonElement=this.element.parents().last().find("label[for="+this.element.attr("id")+"]");this.element.addClass("ui-helper-hidden-accessible");var e=this.element.is(":checked");e&&this.buttonElement.addClass("ui-state-active");this.buttonElement.attr("aria-pressed",e)}else this.buttonElement=
this.element},widget:function(){return this.buttonElement},destroy:function(){this.element.removeClass("ui-helper-hidden-accessible");this.buttonElement.removeClass("ui-button ui-widget ui-state-default ui-corner-all ui-state-hover ui-state-active  ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only").removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html());this.hasTitle||
this.buttonElement.removeAttr("title");b.Widget.prototype.destroy.call(this)},_setOption:function(e,a){b.Widget.prototype._setOption.apply(this,arguments);if(e==="disabled")a?this.element.attr("disabled",true):this.element.removeAttr("disabled");this._resetButton()},refresh:function(){var e=this.element.is(":disabled");e!==this.options.disabled&&this._setOption("disabled",e);if(this.type==="radio")g(this.element[0]).each(function(){b(this).is(":checked")?b(this).button("widget").addClass("ui-state-active").attr("aria-pressed",
true):b(this).button("widget").removeClass("ui-state-active").attr("aria-pressed",false)});else if(this.type==="checkbox")this.element.is(":checked")?this.buttonElement.addClass("ui-state-active").attr("aria-pressed",true):this.buttonElement.removeClass("ui-state-active").attr("aria-pressed",false)},_resetButton:function(){if(this.type==="input")this.options.label&&this.element.val(this.options.label);else{var e=this.buttonElement.removeClass("ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only"),
a=b("<span></span>").addClass("ui-button-text").html(this.options.label).appendTo(e.empty()).text(),d=this.options.icons,h=d.primary&&d.secondary;if(d.primary||d.secondary){e.addClass("ui-button-text-icon"+(h?"s":d.primary?"-primary":"-secondary"));d.primary&&e.prepend("<span class='ui-button-icon-primary ui-icon "+d.primary+"'></span>");d.secondary&&e.append("<span class='ui-button-icon-secondary ui-icon "+d.secondary+"'></span>");if(!this.options.text){e.addClass(h?"ui-button-icons-only":"ui-button-icon-only").removeClass("ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary");
this.hasTitle||e.attr("title",a)}}else e.addClass("ui-button-text-only")}}});b.widget("ui.buttonset",{options:{items:":button, :submit, :reset, :checkbox, :radio, a, :data(button)"},_create:function(){this.element.addClass("ui-buttonset")},_init:function(){this.refresh()},_setOption:function(e,a){e==="disabled"&&this.buttons.button("option",e,a);b.Widget.prototype._setOption.apply(this,arguments)},refresh:function(){this.buttons=this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function(){return b(this).button("widget")[0]}).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass("ui-corner-left").end().filter(":last").addClass("ui-corner-right").end().end()},
destroy:function(){this.element.removeClass("ui-buttonset");this.buttons.map(function(){return b(this).button("widget")[0]}).removeClass("ui-corner-left ui-corner-right").end().button("destroy");b.Widget.prototype.destroy.call(this)}})})(jQuery);
(function(b,c){function f(){this.debug=false;this._curInst=null;this._keyEvent=false;this._disabledInputs=[];this._inDialog=this._datepickerShowing=false;this._mainDivId="ui-datepicker-div";this._inlineClass="ui-datepicker-inline";this._appendClass="ui-datepicker-append";this._triggerClass="ui-datepicker-trigger";this._dialogClass="ui-datepicker-dialog";this._disableClass="ui-datepicker-disabled";this._unselectableClass="ui-datepicker-unselectable";this._currentClass="ui-datepicker-current-day";this._dayOverClass=
"ui-datepicker-days-cell-over";this.regional=[];this.regional[""]={closeText:"Done",prevText:"Prev",nextText:"Next",currentText:"Today",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["Su",
"Mo","Tu","We","Th","Fr","Sa"],weekHeader:"Wk",dateFormat:"mm/dd/yy",firstDay:0,isRTL:false,showMonthAfterYear:false,yearSuffix:""};this._defaults={showOn:"focus",showAnim:"fadeIn",showOptions:{},defaultDate:null,appendText:"",buttonText:"...",buttonImage:"",buttonImageOnly:false,hideIfNoPrevNext:false,navigationAsDateFormat:false,gotoCurrent:false,changeMonth:false,changeYear:false,yearRange:"c-10:c+10",showOtherMonths:false,selectOtherMonths:false,showWeek:false,calculateWeek:this.iso8601Week,shortYearCutoff:"+10",
minDate:null,maxDate:null,duration:"fast",beforeShowDay:null,beforeShow:null,onSelect:null,onChangeMonthYear:null,onClose:null,numberOfMonths:1,showCurrentAtPos:0,stepMonths:1,stepBigMonths:12,altField:"",altFormat:"",constrainInput:true,showButtonPanel:false,autoSize:false};b.extend(this._defaults,this.regional[""]);this.dpDiv=b('<div id="'+this._mainDivId+'" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>')}function g(a,d){b.extend(a,d);for(var h in d)if(d[h]==
null||d[h]==c)a[h]=d[h];return a}b.extend(b.ui,{datepicker:{version:"1.8.7"}});var e=(new Date).getTime();b.extend(f.prototype,{markerClassName:"hasDatepicker",log:function(){this.debug&&console.log.apply("",arguments)},_widgetDatepicker:function(){return this.dpDiv},setDefaults:function(a){g(this._defaults,a||{});return this},_attachDatepicker:function(a,d){var h=null;for(var i in this._defaults){var j=a.getAttribute("date:"+i);if(j){h=h||{};try{h[i]=eval(j)}catch(n){h[i]=j}}}i=a.nodeName.toLowerCase();
j=i=="div"||i=="span";if(!a.id){this.uuid+=1;a.id="dp"+this.uuid}var q=this._newInst(b(a),j);q.settings=b.extend({},d||{},h||{});if(i=="input")this._connectDatepicker(a,q);else j&&this._inlineDatepicker(a,q)},_newInst:function(a,d){return{id:a[0].id.replace(/([^A-Za-z0-9_-])/g,"\\\\$1"),input:a,selectedDay:0,selectedMonth:0,selectedYear:0,drawMonth:0,drawYear:0,inline:d,dpDiv:!d?this.dpDiv:b('<div class="'+this._inlineClass+' ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>')}},
_connectDatepicker:function(a,d){var h=b(a);d.append=b([]);d.trigger=b([]);if(!h.hasClass(this.markerClassName)){this._attachments(h,d);h.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp).bind("setData.datepicker",function(i,j,n){d.settings[j]=n}).bind("getData.datepicker",function(i,j){return this._get(d,j)});this._autoSize(d);b.data(a,"datepicker",d)}},_attachments:function(a,d){var h=this._get(d,"appendText"),i=this._get(d,"isRTL");d.append&&
d.append.remove();if(h){d.append=b('<span class="'+this._appendClass+'">'+h+"</span>");a[i?"before":"after"](d.append)}a.unbind("focus",this._showDatepicker);d.trigger&&d.trigger.remove();h=this._get(d,"showOn");if(h=="focus"||h=="both")a.focus(this._showDatepicker);if(h=="button"||h=="both"){h=this._get(d,"buttonText");var j=this._get(d,"buttonImage");d.trigger=b(this._get(d,"buttonImageOnly")?b("<img/>").addClass(this._triggerClass).attr({src:j,alt:h,title:h}):b('<button type="button"></button>').addClass(this._triggerClass).html(j==
""?h:b("<img/>").attr({src:j,alt:h,title:h})));a[i?"before":"after"](d.trigger);d.trigger.click(function(){b.datepicker._datepickerShowing&&b.datepicker._lastInput==a[0]?b.datepicker._hideDatepicker():b.datepicker._showDatepicker(a[0]);return false})}},_autoSize:function(a){if(this._get(a,"autoSize")&&!a.inline){var d=new Date(2009,11,20),h=this._get(a,"dateFormat");if(h.match(/[DM]/)){var i=function(j){for(var n=0,q=0,l=0;l<j.length;l++)if(j[l].length>n){n=j[l].length;q=l}return q};d.setMonth(i(this._get(a,
h.match(/MM/)?"monthNames":"monthNamesShort")));d.setDate(i(this._get(a,h.match(/DD/)?"dayNames":"dayNamesShort"))+20-d.getDay())}a.input.attr("size",this._formatDate(a,d).length)}},_inlineDatepicker:function(a,d){var h=b(a);if(!h.hasClass(this.markerClassName)){h.addClass(this.markerClassName).append(d.dpDiv).bind("setData.datepicker",function(i,j,n){d.settings[j]=n}).bind("getData.datepicker",function(i,j){return this._get(d,j)});b.data(a,"datepicker",d);this._setDate(d,this._getDefaultDate(d),
true);this._updateDatepicker(d);this._updateAlternate(d);d.dpDiv.show()}},_dialogDatepicker:function(a,d,h,i,j){a=this._dialogInst;if(!a){this.uuid+=1;this._dialogInput=b('<input type="text" id="'+("dp"+this.uuid)+'" style="position: absolute; top: -100px; width: 0px; z-index: -10;"/>');this._dialogInput.keydown(this._doKeyDown);b("body").append(this._dialogInput);a=this._dialogInst=this._newInst(this._dialogInput,false);a.settings={};b.data(this._dialogInput[0],"datepicker",a)}g(a.settings,i||{});
d=d&&d.constructor==Date?this._formatDate(a,d):d;this._dialogInput.val(d);this._pos=j?j.length?j:[j.pageX,j.pageY]:null;if(!this._pos)this._pos=[document.documentElement.clientWidth/2-100+(document.documentElement.scrollLeft||document.body.scrollLeft),document.documentElement.clientHeight/2-150+(document.documentElement.scrollTop||document.body.scrollTop)];this._dialogInput.css("left",this._pos[0]+20+"px").css("top",this._pos[1]+"px");a.settings.onSelect=h;this._inDialog=true;this.dpDiv.addClass(this._dialogClass);
this._showDatepicker(this._dialogInput[0]);b.blockUI&&b.blockUI(this.dpDiv);b.data(this._dialogInput[0],"datepicker",a);return this},_destroyDatepicker:function(a){var d=b(a),h=b.data(a,"datepicker");if(d.hasClass(this.markerClassName)){var i=a.nodeName.toLowerCase();b.removeData(a,"datepicker");if(i=="input"){h.append.remove();h.trigger.remove();d.removeClass(this.markerClassName).unbind("focus",this._showDatepicker).unbind("keydown",this._doKeyDown).unbind("keypress",this._doKeyPress).unbind("keyup",
this._doKeyUp)}else if(i=="div"||i=="span")d.removeClass(this.markerClassName).empty()}},_enableDatepicker:function(a){var d=b(a),h=b.data(a,"datepicker");if(d.hasClass(this.markerClassName)){var i=a.nodeName.toLowerCase();if(i=="input"){a.disabled=false;h.trigger.filter("button").each(function(){this.disabled=false}).end().filter("img").css({opacity:"1.0",cursor:""})}else if(i=="div"||i=="span")d.children("."+this._inlineClass).children().removeClass("ui-state-disabled");this._disabledInputs=b.map(this._disabledInputs,
function(j){return j==a?null:j})}},_disableDatepicker:function(a){var d=b(a),h=b.data(a,"datepicker");if(d.hasClass(this.markerClassName)){var i=a.nodeName.toLowerCase();if(i=="input"){a.disabled=true;h.trigger.filter("button").each(function(){this.disabled=true}).end().filter("img").css({opacity:"0.5",cursor:"default"})}else if(i=="div"||i=="span")d.children("."+this._inlineClass).children().addClass("ui-state-disabled");this._disabledInputs=b.map(this._disabledInputs,function(j){return j==a?null:
j});this._disabledInputs[this._disabledInputs.length]=a}},_isDisabledDatepicker:function(a){if(!a)return false;for(var d=0;d<this._disabledInputs.length;d++)if(this._disabledInputs[d]==a)return true;return false},_getInst:function(a){try{return b.data(a,"datepicker")}catch(d){throw"Missing instance data for this datepicker";}},_optionDatepicker:function(a,d,h){var i=this._getInst(a);if(arguments.length==2&&typeof d=="string")return d=="defaults"?b.extend({},b.datepicker._defaults):i?d=="all"?b.extend({},
i.settings):this._get(i,d):null;var j=d||{};if(typeof d=="string"){j={};j[d]=h}if(i){this._curInst==i&&this._hideDatepicker();var n=this._getDateDatepicker(a,true);g(i.settings,j);this._attachments(b(a),i);this._autoSize(i);this._setDateDatepicker(a,n);this._updateDatepicker(i)}},_changeDatepicker:function(a,d,h){this._optionDatepicker(a,d,h)},_refreshDatepicker:function(a){(a=this._getInst(a))&&this._updateDatepicker(a)},_setDateDatepicker:function(a,d){if(a=this._getInst(a)){this._setDate(a,d);
this._updateDatepicker(a);this._updateAlternate(a)}},_getDateDatepicker:function(a,d){(a=this._getInst(a))&&!a.inline&&this._setDateFromField(a,d);return a?this._getDate(a):null},_doKeyDown:function(a){var d=b.datepicker._getInst(a.target),h=true,i=d.dpDiv.is(".ui-datepicker-rtl");d._keyEvent=true;if(b.datepicker._datepickerShowing)switch(a.keyCode){case 9:b.datepicker._hideDatepicker();h=false;break;case 13:h=b("td."+b.datepicker._dayOverClass+":not(."+b.datepicker._currentClass+")",d.dpDiv);h[0]?
b.datepicker._selectDay(a.target,d.selectedMonth,d.selectedYear,h[0]):b.datepicker._hideDatepicker();return false;case 27:b.datepicker._hideDatepicker();break;case 33:b.datepicker._adjustDate(a.target,a.ctrlKey?-b.datepicker._get(d,"stepBigMonths"):-b.datepicker._get(d,"stepMonths"),"M");break;case 34:b.datepicker._adjustDate(a.target,a.ctrlKey?+b.datepicker._get(d,"stepBigMonths"):+b.datepicker._get(d,"stepMonths"),"M");break;case 35:if(a.ctrlKey||a.metaKey)b.datepicker._clearDate(a.target);h=a.ctrlKey||
a.metaKey;break;case 36:if(a.ctrlKey||a.metaKey)b.datepicker._gotoToday(a.target);h=a.ctrlKey||a.metaKey;break;case 37:if(a.ctrlKey||a.metaKey)b.datepicker._adjustDate(a.target,i?+1:-1,"D");h=a.ctrlKey||a.metaKey;if(a.originalEvent.altKey)b.datepicker._adjustDate(a.target,a.ctrlKey?-b.datepicker._get(d,"stepBigMonths"):-b.datepicker._get(d,"stepMonths"),"M");break;case 38:if(a.ctrlKey||a.metaKey)b.datepicker._adjustDate(a.target,-7,"D");h=a.ctrlKey||a.metaKey;break;case 39:if(a.ctrlKey||a.metaKey)b.datepicker._adjustDate(a.target,
i?-1:+1,"D");h=a.ctrlKey||a.metaKey;if(a.originalEvent.altKey)b.datepicker._adjustDate(a.target,a.ctrlKey?+b.datepicker._get(d,"stepBigMonths"):+b.datepicker._get(d,"stepMonths"),"M");break;case 40:if(a.ctrlKey||a.metaKey)b.datepicker._adjustDate(a.target,+7,"D");h=a.ctrlKey||a.metaKey;break;default:h=false}else if(a.keyCode==36&&a.ctrlKey)b.datepicker._showDatepicker(this);else h=false;if(h){a.preventDefault();a.stopPropagation()}},_doKeyPress:function(a){var d=b.datepicker._getInst(a.target);if(b.datepicker._get(d,
"constrainInput")){d=b.datepicker._possibleChars(b.datepicker._get(d,"dateFormat"));var h=String.fromCharCode(a.charCode==c?a.keyCode:a.charCode);return a.ctrlKey||a.metaKey||h<" "||!d||d.indexOf(h)>-1}},_doKeyUp:function(a){a=b.datepicker._getInst(a.target);if(a.input.val()!=a.lastVal)try{if(b.datepicker.parseDate(b.datepicker._get(a,"dateFormat"),a.input?a.input.val():null,b.datepicker._getFormatConfig(a))){b.datepicker._setDateFromField(a);b.datepicker._updateAlternate(a);b.datepicker._updateDatepicker(a)}}catch(d){b.datepicker.log(d)}return true},
_showDatepicker:function(a){a=a.target||a;if(a.nodeName.toLowerCase()!="input")a=b("input",a.parentNode)[0];if(!(b.datepicker._isDisabledDatepicker(a)||b.datepicker._lastInput==a)){var d=b.datepicker._getInst(a);b.datepicker._curInst&&b.datepicker._curInst!=d&&b.datepicker._curInst.dpDiv.stop(true,true);var h=b.datepicker._get(d,"beforeShow");g(d.settings,h?h.apply(a,[a,d]):{});d.lastVal=null;b.datepicker._lastInput=a;b.datepicker._setDateFromField(d);if(b.datepicker._inDialog)a.value="";if(!b.datepicker._pos){b.datepicker._pos=
b.datepicker._findPos(a);b.datepicker._pos[1]+=a.offsetHeight}var i=false;b(a).parents().each(function(){i|=b(this).css("position")=="fixed";return!i});if(i&&b.browser.opera){b.datepicker._pos[0]-=document.documentElement.scrollLeft;b.datepicker._pos[1]-=document.documentElement.scrollTop}h={left:b.datepicker._pos[0],top:b.datepicker._pos[1]};b.datepicker._pos=null;d.dpDiv.empty();d.dpDiv.css({position:"absolute",display:"block",top:"-1000px"});b.datepicker._updateDatepicker(d);h=b.datepicker._checkOffset(d,
h,i);d.dpDiv.css({position:b.datepicker._inDialog&&b.blockUI?"static":i?"fixed":"absolute",display:"none",left:h.left+"px",top:h.top+"px"});if(!d.inline){h=b.datepicker._get(d,"showAnim");var j=b.datepicker._get(d,"duration"),n=function(){b.datepicker._datepickerShowing=true;var q=d.dpDiv.find("iframe.ui-datepicker-cover");if(q.length){var l=b.datepicker._getBorders(d.dpDiv);q.css({left:-l[0],top:-l[1],width:d.dpDiv.outerWidth(),height:d.dpDiv.outerHeight()})}};d.dpDiv.zIndex(b(a).zIndex()+1);b.effects&&
b.effects[h]?d.dpDiv.show(h,b.datepicker._get(d,"showOptions"),j,n):d.dpDiv[h||"show"](h?j:null,n);if(!h||!j)n();d.input.is(":visible")&&!d.input.is(":disabled")&&d.input.focus();b.datepicker._curInst=d}}},_updateDatepicker:function(a){var d=this,h=b.datepicker._getBorders(a.dpDiv);a.dpDiv.empty().append(this._generateHTML(a));var i=a.dpDiv.find("iframe.ui-datepicker-cover");i.length&&i.css({left:-h[0],top:-h[1],width:a.dpDiv.outerWidth(),height:a.dpDiv.outerHeight()});a.dpDiv.find("button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a").bind("mouseout",
function(){b(this).removeClass("ui-state-hover");this.className.indexOf("ui-datepicker-prev")!=-1&&b(this).removeClass("ui-datepicker-prev-hover");this.className.indexOf("ui-datepicker-next")!=-1&&b(this).removeClass("ui-datepicker-next-hover")}).bind("mouseover",function(){if(!d._isDisabledDatepicker(a.inline?a.dpDiv.parent()[0]:a.input[0])){b(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover");b(this).addClass("ui-state-hover");this.className.indexOf("ui-datepicker-prev")!=
-1&&b(this).addClass("ui-datepicker-prev-hover");this.className.indexOf("ui-datepicker-next")!=-1&&b(this).addClass("ui-datepicker-next-hover")}}).end().find("."+this._dayOverClass+" a").trigger("mouseover").end();h=this._getNumberOfMonths(a);i=h[1];i>1?a.dpDiv.addClass("ui-datepicker-multi-"+i).css("width",17*i+"em"):a.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width("");a.dpDiv[(h[0]!=1||h[1]!=1?"add":"remove")+"Class"]("ui-datepicker-multi");a.dpDiv[(this._get(a,
"isRTL")?"add":"remove")+"Class"]("ui-datepicker-rtl");a==b.datepicker._curInst&&b.datepicker._datepickerShowing&&a.input&&a.input.is(":visible")&&!a.input.is(":disabled")&&a.input.focus();if(a.yearshtml){var j=a.yearshtml;setTimeout(function(){j===a.yearshtml&&a.dpDiv.find("select.ui-datepicker-year:first").replaceWith(a.yearshtml);j=a.yearshtml=null},0)}},_getBorders:function(a){var d=function(h){return{thin:1,medium:2,thick:3}[h]||h};return[parseFloat(d(a.css("border-left-width"))),parseFloat(d(a.css("border-top-width")))]},
_checkOffset:function(a,d,h){var i=a.dpDiv.outerWidth(),j=a.dpDiv.outerHeight(),n=a.input?a.input.outerWidth():0,q=a.input?a.input.outerHeight():0,l=document.documentElement.clientWidth+b(document).scrollLeft(),k=document.documentElement.clientHeight+b(document).scrollTop();d.left-=this._get(a,"isRTL")?i-n:0;d.left-=h&&d.left==a.input.offset().left?b(document).scrollLeft():0;d.top-=h&&d.top==a.input.offset().top+q?b(document).scrollTop():0;d.left-=Math.min(d.left,d.left+i>l&&l>i?Math.abs(d.left+i-
l):0);d.top-=Math.min(d.top,d.top+j>k&&k>j?Math.abs(j+q):0);return d},_findPos:function(a){for(var d=this._get(this._getInst(a),"isRTL");a&&(a.type=="hidden"||a.nodeType!=1);)a=a[d?"previousSibling":"nextSibling"];a=b(a).offset();return[a.left,a.top]},_hideDatepicker:function(a){var d=this._curInst;if(!(!d||a&&d!=b.data(a,"datepicker")))if(this._datepickerShowing){a=this._get(d,"showAnim");var h=this._get(d,"duration"),i=function(){b.datepicker._tidyDialog(d);this._curInst=null};b.effects&&b.effects[a]?
d.dpDiv.hide(a,b.datepicker._get(d,"showOptions"),h,i):d.dpDiv[a=="slideDown"?"slideUp":a=="fadeIn"?"fadeOut":"hide"](a?h:null,i);a||i();if(a=this._get(d,"onClose"))a.apply(d.input?d.input[0]:null,[d.input?d.input.val():"",d]);this._datepickerShowing=false;this._lastInput=null;if(this._inDialog){this._dialogInput.css({position:"absolute",left:"0",top:"-100px"});if(b.blockUI){b.unblockUI();b("body").append(this.dpDiv)}}this._inDialog=false}},_tidyDialog:function(a){a.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")},
_checkExternalClick:function(a){if(b.datepicker._curInst){a=b(a.target);a[0].id!=b.datepicker._mainDivId&&a.parents("#"+b.datepicker._mainDivId).length==0&&!a.hasClass(b.datepicker.markerClassName)&&!a.hasClass(b.datepicker._triggerClass)&&b.datepicker._datepickerShowing&&!(b.datepicker._inDialog&&b.blockUI)&&b.datepicker._hideDatepicker()}},_adjustDate:function(a,d,h){a=b(a);var i=this._getInst(a[0]);if(!this._isDisabledDatepicker(a[0])){this._adjustInstDate(i,d+(h=="M"?this._get(i,"showCurrentAtPos"):
0),h);this._updateDatepicker(i)}},_gotoToday:function(a){a=b(a);var d=this._getInst(a[0]);if(this._get(d,"gotoCurrent")&&d.currentDay){d.selectedDay=d.currentDay;d.drawMonth=d.selectedMonth=d.currentMonth;d.drawYear=d.selectedYear=d.currentYear}else{var h=new Date;d.selectedDay=h.getDate();d.drawMonth=d.selectedMonth=h.getMonth();d.drawYear=d.selectedYear=h.getFullYear()}this._notifyChange(d);this._adjustDate(a)},_selectMonthYear:function(a,d,h){a=b(a);var i=this._getInst(a[0]);i._selectingMonthYear=
false;i["selected"+(h=="M"?"Month":"Year")]=i["draw"+(h=="M"?"Month":"Year")]=parseInt(d.options[d.selectedIndex].value,10);this._notifyChange(i);this._adjustDate(a)},_clickMonthYear:function(a){var d=this._getInst(b(a)[0]);d.input&&d._selectingMonthYear&&setTimeout(function(){d.input.focus()},0);d._selectingMonthYear=!d._selectingMonthYear},_selectDay:function(a,d,h,i){var j=b(a);if(!(b(i).hasClass(this._unselectableClass)||this._isDisabledDatepicker(j[0]))){j=this._getInst(j[0]);j.selectedDay=j.currentDay=
b("a",i).html();j.selectedMonth=j.currentMonth=d;j.selectedYear=j.currentYear=h;this._selectDate(a,this._formatDate(j,j.currentDay,j.currentMonth,j.currentYear))}},_clearDate:function(a){a=b(a);this._getInst(a[0]);this._selectDate(a,"")},_selectDate:function(a,d){a=this._getInst(b(a)[0]);d=d!=null?d:this._formatDate(a);a.input&&a.input.val(d);this._updateAlternate(a);var h=this._get(a,"onSelect");if(h)h.apply(a.input?a.input[0]:null,[d,a]);else a.input&&a.input.trigger("change");if(a.inline)this._updateDatepicker(a);
else{this._hideDatepicker();this._lastInput=a.input[0];typeof a.input[0]!="object"&&a.input.focus();this._lastInput=null}},_updateAlternate:function(a){var d=this._get(a,"altField");if(d){var h=this._get(a,"altFormat")||this._get(a,"dateFormat"),i=this._getDate(a),j=this.formatDate(h,i,this._getFormatConfig(a));b(d).each(function(){b(this).val(j)})}},noWeekends:function(a){a=a.getDay();return[a>0&&a<6,""]},iso8601Week:function(a){a=new Date(a.getTime());a.setDate(a.getDate()+4-(a.getDay()||7));var d=
a.getTime();a.setMonth(0);a.setDate(1);return Math.floor(Math.round((d-a)/864E5)/7)+1},parseDate:function(a,d,h){if(a==null||d==null)throw"Invalid arguments";d=typeof d=="object"?d.toString():d+"";if(d=="")return null;for(var i=(h?h.shortYearCutoff:null)||this._defaults.shortYearCutoff,j=(h?h.dayNamesShort:null)||this._defaults.dayNamesShort,n=(h?h.dayNames:null)||this._defaults.dayNames,q=(h?h.monthNamesShort:null)||this._defaults.monthNamesShort,l=(h?h.monthNames:null)||this._defaults.monthNames,
k=h=-1,m=-1,o=-1,p=false,s=function(x){(x=y+1<a.length&&a.charAt(y+1)==x)&&y++;return x},r=function(x){var C=s(x);x=new RegExp("^\\d{1,"+(x=="@"?14:x=="!"?20:x=="y"&&C?4:x=="o"?3:2)+"}");x=d.substring(w).match(x);if(!x)throw"Missing number at position "+w;w+=x[0].length;return parseInt(x[0],10)},u=function(x,C,J){x=s(x)?J:C;for(C=0;C<x.length;C++)if(d.substr(w,x[C].length).toLowerCase()==x[C].toLowerCase()){w+=x[C].length;return C+1}throw"Unknown name at position "+w;},v=function(){if(d.charAt(w)!=
a.charAt(y))throw"Unexpected literal at position "+w;w++},w=0,y=0;y<a.length;y++)if(p)if(a.charAt(y)=="'"&&!s("'"))p=false;else v();else switch(a.charAt(y)){case "d":m=r("d");break;case "D":u("D",j,n);break;case "o":o=r("o");break;case "m":k=r("m");break;case "M":k=u("M",q,l);break;case "y":h=r("y");break;case "@":var B=new Date(r("@"));h=B.getFullYear();k=B.getMonth()+1;m=B.getDate();break;case "!":B=new Date((r("!")-this._ticksTo1970)/1E4);h=B.getFullYear();k=B.getMonth()+1;m=B.getDate();break;
case "'":if(s("'"))v();else p=true;break;default:v()}if(h==-1)h=(new Date).getFullYear();else if(h<100)h+=(new Date).getFullYear()-(new Date).getFullYear()%100+(h<=i?0:-100);if(o>-1){k=1;m=o;do{i=this._getDaysInMonth(h,k-1);if(m<=i)break;k++;m-=i}while(1)}B=this._daylightSavingAdjust(new Date(h,k-1,m));if(B.getFullYear()!=h||B.getMonth()+1!=k||B.getDate()!=m)throw"Invalid date";return B},ATOM:"yy-mm-dd",COOKIE:"D, dd M yy",ISO_8601:"yy-mm-dd",RFC_822:"D, d M y",RFC_850:"DD, dd-M-y",RFC_1036:"D, d M y",
RFC_1123:"D, d M yy",RFC_2822:"D, d M yy",RSS:"D, d M y",TICKS:"!",TIMESTAMP:"@",W3C:"yy-mm-dd",_ticksTo1970:(718685+Math.floor(492.5)-Math.floor(19.7)+Math.floor(4.925))*24*60*60*1E7,formatDate:function(a,d,h){if(!d)return"";var i=(h?h.dayNamesShort:null)||this._defaults.dayNamesShort,j=(h?h.dayNames:null)||this._defaults.dayNames,n=(h?h.monthNamesShort:null)||this._defaults.monthNamesShort;h=(h?h.monthNames:null)||this._defaults.monthNames;var q=function(s){(s=p+1<a.length&&a.charAt(p+1)==s)&&p++;
return s},l=function(s,r,u){r=""+r;if(q(s))for(;r.length<u;)r="0"+r;return r},k=function(s,r,u,v){return q(s)?v[r]:u[r]},m="",o=false;if(d)for(var p=0;p<a.length;p++)if(o)if(a.charAt(p)=="'"&&!q("'"))o=false;else m+=a.charAt(p);else switch(a.charAt(p)){case "d":m+=l("d",d.getDate(),2);break;case "D":m+=k("D",d.getDay(),i,j);break;case "o":m+=l("o",(d.getTime()-(new Date(d.getFullYear(),0,0)).getTime())/864E5,3);break;case "m":m+=l("m",d.getMonth()+1,2);break;case "M":m+=k("M",d.getMonth(),n,h);break;
case "y":m+=q("y")?d.getFullYear():(d.getYear()%100<10?"0":"")+d.getYear()%100;break;case "@":m+=d.getTime();break;case "!":m+=d.getTime()*1E4+this._ticksTo1970;break;case "'":if(q("'"))m+="'";else o=true;break;default:m+=a.charAt(p)}return m},_possibleChars:function(a){for(var d="",h=false,i=function(n){(n=j+1<a.length&&a.charAt(j+1)==n)&&j++;return n},j=0;j<a.length;j++)if(h)if(a.charAt(j)=="'"&&!i("'"))h=false;else d+=a.charAt(j);else switch(a.charAt(j)){case "d":case "m":case "y":case "@":d+=
"0123456789";break;case "D":case "M":return null;case "'":if(i("'"))d+="'";else h=true;break;default:d+=a.charAt(j)}return d},_get:function(a,d){return a.settings[d]!==c?a.settings[d]:this._defaults[d]},_setDateFromField:function(a,d){if(a.input.val()!=a.lastVal){var h=this._get(a,"dateFormat"),i=a.lastVal=a.input?a.input.val():null,j,n;j=n=this._getDefaultDate(a);var q=this._getFormatConfig(a);try{j=this.parseDate(h,i,q)||n}catch(l){this.log(l);i=d?"":i}a.selectedDay=j.getDate();a.drawMonth=a.selectedMonth=
j.getMonth();a.drawYear=a.selectedYear=j.getFullYear();a.currentDay=i?j.getDate():0;a.currentMonth=i?j.getMonth():0;a.currentYear=i?j.getFullYear():0;this._adjustInstDate(a)}},_getDefaultDate:function(a){return this._restrictMinMax(a,this._determineDate(a,this._get(a,"defaultDate"),new Date))},_determineDate:function(a,d,h){var i=function(n){var q=new Date;q.setDate(q.getDate()+n);return q},j=function(n){try{return b.datepicker.parseDate(b.datepicker._get(a,"dateFormat"),n,b.datepicker._getFormatConfig(a))}catch(q){}var l=
(n.toLowerCase().match(/^c/)?b.datepicker._getDate(a):null)||new Date,k=l.getFullYear(),m=l.getMonth();l=l.getDate();for(var o=/([+-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,p=o.exec(n);p;){switch(p[2]||"d"){case "d":case "D":l+=parseInt(p[1],10);break;case "w":case "W":l+=parseInt(p[1],10)*7;break;case "m":case "M":m+=parseInt(p[1],10);l=Math.min(l,b.datepicker._getDaysInMonth(k,m));break;case "y":case "Y":k+=parseInt(p[1],10);l=Math.min(l,b.datepicker._getDaysInMonth(k,m));break}p=o.exec(n)}return new Date(k,
m,l)};if(d=(d=d==null||d===""?h:typeof d=="string"?j(d):typeof d=="number"?isNaN(d)?h:i(d):new Date(d.getTime()))&&d.toString()=="Invalid Date"?h:d){d.setHours(0);d.setMinutes(0);d.setSeconds(0);d.setMilliseconds(0)}return this._daylightSavingAdjust(d)},_daylightSavingAdjust:function(a){if(!a)return null;a.setHours(a.getHours()>12?a.getHours()+2:0);return a},_setDate:function(a,d,h){var i=!d,j=a.selectedMonth,n=a.selectedYear;d=this._restrictMinMax(a,this._determineDate(a,d,new Date));a.selectedDay=
a.currentDay=d.getDate();a.drawMonth=a.selectedMonth=a.currentMonth=d.getMonth();a.drawYear=a.selectedYear=a.currentYear=d.getFullYear();if((j!=a.selectedMonth||n!=a.selectedYear)&&!h)this._notifyChange(a);this._adjustInstDate(a);if(a.input)a.input.val(i?"":this._formatDate(a))},_getDate:function(a){return!a.currentYear||a.input&&a.input.val()==""?null:this._daylightSavingAdjust(new Date(a.currentYear,a.currentMonth,a.currentDay))},_generateHTML:function(a){var d=new Date;d=this._daylightSavingAdjust(new Date(d.getFullYear(),
d.getMonth(),d.getDate()));var h=this._get(a,"isRTL"),i=this._get(a,"showButtonPanel"),j=this._get(a,"hideIfNoPrevNext"),n=this._get(a,"navigationAsDateFormat"),q=this._getNumberOfMonths(a),l=this._get(a,"showCurrentAtPos"),k=this._get(a,"stepMonths"),m=q[0]!=1||q[1]!=1,o=this._daylightSavingAdjust(!a.currentDay?new Date(9999,9,9):new Date(a.currentYear,a.currentMonth,a.currentDay)),p=this._getMinMaxDate(a,"min"),s=this._getMinMaxDate(a,"max");l=a.drawMonth-l;var r=a.drawYear;if(l<0){l+=12;r--}if(s){var u=
this._daylightSavingAdjust(new Date(s.getFullYear(),s.getMonth()-q[0]*q[1]+1,s.getDate()));for(u=p&&u<p?p:u;this._daylightSavingAdjust(new Date(r,l,1))>u;){l--;if(l<0){l=11;r--}}}a.drawMonth=l;a.drawYear=r;u=this._get(a,"prevText");u=!n?u:this.formatDate(u,this._daylightSavingAdjust(new Date(r,l-k,1)),this._getFormatConfig(a));u=this._canAdjustMonth(a,-1,r,l)?'<a class="ui-datepicker-prev ui-corner-all" onclick="DP_jQuery_'+e+".datepicker._adjustDate('#"+a.id+"', -"+k+", 'M');\" title=\""+u+'"><span class="ui-icon ui-icon-circle-triangle-'+
(h?"e":"w")+'">'+u+"</span></a>":j?"":'<a class="ui-datepicker-prev ui-corner-all ui-state-disabled" title="'+u+'"><span class="ui-icon ui-icon-circle-triangle-'+(h?"e":"w")+'">'+u+"</span></a>";var v=this._get(a,"nextText");v=!n?v:this.formatDate(v,this._daylightSavingAdjust(new Date(r,l+k,1)),this._getFormatConfig(a));j=this._canAdjustMonth(a,+1,r,l)?'<a class="ui-datepicker-next ui-corner-all" onclick="DP_jQuery_'+e+".datepicker._adjustDate('#"+a.id+"', +"+k+", 'M');\" title=\""+v+'"><span class="ui-icon ui-icon-circle-triangle-'+
(h?"w":"e")+'">'+v+"</span></a>":j?"":'<a class="ui-datepicker-next ui-corner-all ui-state-disabled" title="'+v+'"><span class="ui-icon ui-icon-circle-triangle-'+(h?"w":"e")+'">'+v+"</span></a>";k=this._get(a,"currentText");v=this._get(a,"gotoCurrent")&&a.currentDay?o:d;k=!n?k:this.formatDate(k,v,this._getFormatConfig(a));n=!a.inline?'<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" onclick="DP_jQuery_'+e+'.datepicker._hideDatepicker();">'+this._get(a,
"closeText")+"</button>":"";i=i?'<div class="ui-datepicker-buttonpane ui-widget-content">'+(h?n:"")+(this._isInRange(a,v)?'<button type="button" class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" onclick="DP_jQuery_'+e+".datepicker._gotoToday('#"+a.id+"');\">"+k+"</button>":"")+(h?"":n)+"</div>":"";n=parseInt(this._get(a,"firstDay"),10);n=isNaN(n)?0:n;k=this._get(a,"showWeek");v=this._get(a,"dayNames");this._get(a,"dayNamesShort");var w=this._get(a,"dayNamesMin"),y=
this._get(a,"monthNames"),B=this._get(a,"monthNamesShort"),x=this._get(a,"beforeShowDay"),C=this._get(a,"showOtherMonths"),J=this._get(a,"selectOtherMonths");this._get(a,"calculateWeek");for(var M=this._getDefaultDate(a),K="",G=0;G<q[0];G++){for(var N="",H=0;H<q[1];H++){var O=this._daylightSavingAdjust(new Date(r,l,a.selectedDay)),A=" ui-corner-all",D="";if(m){D+='<div class="ui-datepicker-group';if(q[1]>1)switch(H){case 0:D+=" ui-datepicker-group-first";A=" ui-corner-"+(h?"right":"left");break;case q[1]-
1:D+=" ui-datepicker-group-last";A=" ui-corner-"+(h?"left":"right");break;default:D+=" ui-datepicker-group-middle";A="";break}D+='">'}D+='<div class="ui-datepicker-header ui-widget-header ui-helper-clearfix'+A+'">'+(/all|left/.test(A)&&G==0?h?j:u:"")+(/all|right/.test(A)&&G==0?h?u:j:"")+this._generateMonthYearHeader(a,l,r,p,s,G>0||H>0,y,B)+'</div><table class="ui-datepicker-calendar"><thead><tr>';var E=k?'<th class="ui-datepicker-week-col">'+this._get(a,"weekHeader")+"</th>":"";for(A=0;A<7;A++){var z=
(A+n)%7;E+="<th"+((A+n+6)%7>=5?' class="ui-datepicker-week-end"':"")+'><span title="'+v[z]+'">'+w[z]+"</span></th>"}D+=E+"</tr></thead><tbody>";E=this._getDaysInMonth(r,l);if(r==a.selectedYear&&l==a.selectedMonth)a.selectedDay=Math.min(a.selectedDay,E);A=(this._getFirstDayOfMonth(r,l)-n+7)%7;E=m?6:Math.ceil((A+E)/7);z=this._daylightSavingAdjust(new Date(r,l,1-A));for(var P=0;P<E;P++){D+="<tr>";var Q=!k?"":'<td class="ui-datepicker-week-col">'+this._get(a,"calculateWeek")(z)+"</td>";for(A=0;A<7;A++){var I=
x?x.apply(a.input?a.input[0]:null,[z]):[true,""],F=z.getMonth()!=l,L=F&&!J||!I[0]||p&&z<p||s&&z>s;Q+='<td class="'+((A+n+6)%7>=5?" ui-datepicker-week-end":"")+(F?" ui-datepicker-other-month":"")+(z.getTime()==O.getTime()&&l==a.selectedMonth&&a._keyEvent||M.getTime()==z.getTime()&&M.getTime()==O.getTime()?" "+this._dayOverClass:"")+(L?" "+this._unselectableClass+" ui-state-disabled":"")+(F&&!C?"":" "+I[1]+(z.getTime()==o.getTime()?" "+this._currentClass:"")+(z.getTime()==d.getTime()?" ui-datepicker-today":
""))+'"'+((!F||C)&&I[2]?' title="'+I[2]+'"':"")+(L?"":' onclick="DP_jQuery_'+e+".datepicker._selectDay('#"+a.id+"',"+z.getMonth()+","+z.getFullYear()+', this);return false;"')+">"+(F&&!C?"&#xa0;":L?'<span class="ui-state-default">'+z.getDate()+"</span>":'<a class="ui-state-default'+(z.getTime()==d.getTime()?" ui-state-highlight":"")+(z.getTime()==o.getTime()?" ui-state-active":"")+(F?" ui-priority-secondary":"")+'" href="#">'+z.getDate()+"</a>")+"</td>";z.setDate(z.getDate()+1);z=this._daylightSavingAdjust(z)}D+=
Q+"</tr>"}l++;if(l>11){l=0;r++}D+="</tbody></table>"+(m?"</div>"+(q[0]>0&&H==q[1]-1?'<div class="ui-datepicker-row-break"></div>':""):"");N+=D}K+=N}K+=i+(b.browser.msie&&parseInt(b.browser.version,10)<7&&!a.inline?'<iframe src="javascript:false;" class="ui-datepicker-cover" frameborder="0"></iframe>':"");a._keyEvent=false;return K},_generateMonthYearHeader:function(a,d,h,i,j,n,q,l){var k=this._get(a,"changeMonth"),m=this._get(a,"changeYear"),o=this._get(a,"showMonthAfterYear"),p='<div class="ui-datepicker-title">',
s="";if(n||!k)s+='<span class="ui-datepicker-month">'+q[d]+"</span>";else{q=i&&i.getFullYear()==h;var r=j&&j.getFullYear()==h;s+='<select class="ui-datepicker-month" onchange="DP_jQuery_'+e+".datepicker._selectMonthYear('#"+a.id+"', this, 'M');\" onclick=\"DP_jQuery_"+e+".datepicker._clickMonthYear('#"+a.id+"');\">";for(var u=0;u<12;u++)if((!q||u>=i.getMonth())&&(!r||u<=j.getMonth()))s+='<option value="'+u+'"'+(u==d?' selected="selected"':"")+">"+l[u]+"</option>";s+="</select>"}o||(p+=s+(n||!(k&&
m)?"&#xa0;":""));a.yearshtml="";if(n||!m)p+='<span class="ui-datepicker-year">'+h+"</span>";else{l=this._get(a,"yearRange").split(":");var v=(new Date).getFullYear();q=function(w){w=w.match(/c[+-].*/)?h+parseInt(w.substring(1),10):w.match(/[+-].*/)?v+parseInt(w,10):parseInt(w,10);return isNaN(w)?v:w};d=q(l[0]);l=Math.max(d,q(l[1]||""));d=i?Math.max(d,i.getFullYear()):d;l=j?Math.min(l,j.getFullYear()):l;for(a.yearshtml+='<select class="ui-datepicker-year" onchange="DP_jQuery_'+e+".datepicker._selectMonthYear('#"+
a.id+"', this, 'Y');\" onclick=\"DP_jQuery_"+e+".datepicker._clickMonthYear('#"+a.id+"');\">";d<=l;d++)a.yearshtml+='<option value="'+d+'"'+(d==h?' selected="selected"':"")+">"+d+"</option>";a.yearshtml+="</select>";if(b.browser.mozilla)p+='<select class="ui-datepicker-year"><option value="'+h+'" selected="selected">'+h+"</option></select>";else{p+=a.yearshtml;a.yearshtml=null}}p+=this._get(a,"yearSuffix");if(o)p+=(n||!(k&&m)?"&#xa0;":"")+s;p+="</div>";return p},_adjustInstDate:function(a,d,h){var i=
a.drawYear+(h=="Y"?d:0),j=a.drawMonth+(h=="M"?d:0);d=Math.min(a.selectedDay,this._getDaysInMonth(i,j))+(h=="D"?d:0);i=this._restrictMinMax(a,this._daylightSavingAdjust(new Date(i,j,d)));a.selectedDay=i.getDate();a.drawMonth=a.selectedMonth=i.getMonth();a.drawYear=a.selectedYear=i.getFullYear();if(h=="M"||h=="Y")this._notifyChange(a)},_restrictMinMax:function(a,d){var h=this._getMinMaxDate(a,"min");a=this._getMinMaxDate(a,"max");d=h&&d<h?h:d;return d=a&&d>a?a:d},_notifyChange:function(a){var d=this._get(a,
"onChangeMonthYear");if(d)d.apply(a.input?a.input[0]:null,[a.selectedYear,a.selectedMonth+1,a])},_getNumberOfMonths:function(a){a=this._get(a,"numberOfMonths");return a==null?[1,1]:typeof a=="number"?[1,a]:a},_getMinMaxDate:function(a,d){return this._determineDate(a,this._get(a,d+"Date"),null)},_getDaysInMonth:function(a,d){return 32-(new Date(a,d,32)).getDate()},_getFirstDayOfMonth:function(a,d){return(new Date(a,d,1)).getDay()},_canAdjustMonth:function(a,d,h,i){var j=this._getNumberOfMonths(a);
h=this._daylightSavingAdjust(new Date(h,i+(d<0?d:j[0]*j[1]),1));d<0&&h.setDate(this._getDaysInMonth(h.getFullYear(),h.getMonth()));return this._isInRange(a,h)},_isInRange:function(a,d){var h=this._getMinMaxDate(a,"min");a=this._getMinMaxDate(a,"max");return(!h||d.getTime()>=h.getTime())&&(!a||d.getTime()<=a.getTime())},_getFormatConfig:function(a){var d=this._get(a,"shortYearCutoff");d=typeof d!="string"?d:(new Date).getFullYear()%100+parseInt(d,10);return{shortYearCutoff:d,dayNamesShort:this._get(a,
"dayNamesShort"),dayNames:this._get(a,"dayNames"),monthNamesShort:this._get(a,"monthNamesShort"),monthNames:this._get(a,"monthNames")}},_formatDate:function(a,d,h,i){if(!d){a.currentDay=a.selectedDay;a.currentMonth=a.selectedMonth;a.currentYear=a.selectedYear}d=d?typeof d=="object"?d:this._daylightSavingAdjust(new Date(i,h,d)):this._daylightSavingAdjust(new Date(a.currentYear,a.currentMonth,a.currentDay));return this.formatDate(this._get(a,"dateFormat"),d,this._getFormatConfig(a))}});b.fn.datepicker=
function(a){if(!b.datepicker.initialized){b(document).mousedown(b.datepicker._checkExternalClick).find("body").append(b.datepicker.dpDiv);b.datepicker.initialized=true}var d=Array.prototype.slice.call(arguments,1);if(typeof a=="string"&&(a=="isDisabled"||a=="getDate"||a=="widget"))return b.datepicker["_"+a+"Datepicker"].apply(b.datepicker,[this[0]].concat(d));if(a=="option"&&arguments.length==2&&typeof arguments[1]=="string")return b.datepicker["_"+a+"Datepicker"].apply(b.datepicker,[this[0]].concat(d));
return this.each(function(){typeof a=="string"?b.datepicker["_"+a+"Datepicker"].apply(b.datepicker,[this].concat(d)):b.datepicker._attachDatepicker(this,a)})};b.datepicker=new f;b.datepicker.initialized=false;b.datepicker.uuid=(new Date).getTime();b.datepicker.version="1.8.7";window["DP_jQuery_"+e]=b})(jQuery);
(function(b,c){var f={buttons:true,height:true,maxHeight:true,maxWidth:true,minHeight:true,minWidth:true,width:true},g={maxHeight:true,maxWidth:true,minHeight:true,minWidth:true};b.widget("ui.dialog",{options:{autoOpen:true,buttons:{},closeOnEscape:true,closeText:"close",dialogClass:"",draggable:true,hide:null,height:"auto",maxHeight:false,maxWidth:false,minHeight:150,minWidth:150,modal:false,position:{my:"center",at:"center",collision:"fit",using:function(e){var a=b(this).css(e).offset().top;a<0&&
b(this).css("top",e.top-a)}},resizable:true,show:null,stack:true,title:"",width:300,zIndex:1E3},_create:function(){this.originalTitle=this.element.attr("title");if(typeof this.originalTitle!=="string")this.originalTitle="";this.options.title=this.options.title||this.originalTitle;var e=this,a=e.options,d=a.title||"&#160;",h=b.ui.dialog.getTitleId(e.element),i=(e.uiDialog=b("<div></div>")).appendTo(document.body).hide().addClass("ui-dialog ui-widget ui-widget-content ui-corner-all "+a.dialogClass).css({zIndex:a.zIndex}).attr("tabIndex",
-1).css("outline",0).keydown(function(q){if(a.closeOnEscape&&q.keyCode&&q.keyCode===b.ui.keyCode.ESCAPE){e.close(q);q.preventDefault()}}).attr({role:"dialog","aria-labelledby":h}).mousedown(function(q){e.moveToTop(false,q)});e.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(i);var j=(e.uiDialogTitlebar=b("<div></div>")).addClass("ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix").prependTo(i),n=b('<a href="#"></a>').addClass("ui-dialog-titlebar-close ui-corner-all").attr("role",
"button").hover(function(){n.addClass("ui-state-hover")},function(){n.removeClass("ui-state-hover")}).focus(function(){n.addClass("ui-state-focus")}).blur(function(){n.removeClass("ui-state-focus")}).click(function(q){e.close(q);return false}).appendTo(j);(e.uiDialogTitlebarCloseText=b("<span></span>")).addClass("ui-icon ui-icon-closethick").text(a.closeText).appendTo(n);b("<span></span>").addClass("ui-dialog-title").attr("id",h).html(d).prependTo(j);if(b.isFunction(a.beforeclose)&&!b.isFunction(a.beforeClose))a.beforeClose=
a.beforeclose;j.find("*").add(j).disableSelection();a.draggable&&b.fn.draggable&&e._makeDraggable();a.resizable&&b.fn.resizable&&e._makeResizable();e._createButtons(a.buttons);e._isOpen=false;b.fn.bgiframe&&i.bgiframe()},_init:function(){this.options.autoOpen&&this.open()},destroy:function(){var e=this;e.overlay&&e.overlay.destroy();e.uiDialog.hide();e.element.unbind(".dialog").removeData("dialog").removeClass("ui-dialog-content ui-widget-content").hide().appendTo("body");e.uiDialog.remove();e.originalTitle&&
e.element.attr("title",e.originalTitle);return e},widget:function(){return this.uiDialog},close:function(e){var a=this,d,h;if(false!==a._trigger("beforeClose",e)){a.overlay&&a.overlay.destroy();a.uiDialog.unbind("keypress.ui-dialog");a._isOpen=false;if(a.options.hide)a.uiDialog.hide(a.options.hide,function(){a._trigger("close",e)});else{a.uiDialog.hide();a._trigger("close",e)}b.ui.dialog.overlay.resize();if(a.options.modal){d=0;b(".ui-dialog").each(function(){if(this!==a.uiDialog[0]){h=b(this).css("z-index");
isNaN(h)||(d=Math.max(d,h))}});b.ui.dialog.maxZ=d}return a}},isOpen:function(){return this._isOpen},moveToTop:function(e,a){var d=this,h=d.options;if(h.modal&&!e||!h.stack&&!h.modal)return d._trigger("focus",a);if(h.zIndex>b.ui.dialog.maxZ)b.ui.dialog.maxZ=h.zIndex;if(d.overlay){b.ui.dialog.maxZ+=1;d.overlay.$el.css("z-index",b.ui.dialog.overlay.maxZ=b.ui.dialog.maxZ)}e={scrollTop:d.element.attr("scrollTop"),scrollLeft:d.element.attr("scrollLeft")};b.ui.dialog.maxZ+=1;d.uiDialog.css("z-index",b.ui.dialog.maxZ);
d.element.attr(e);d._trigger("focus",a);return d},open:function(){if(!this._isOpen){var e=this,a=e.options,d=e.uiDialog;e.overlay=a.modal?new b.ui.dialog.overlay(e):null;e._size();e._position(a.position);d.show(a.show);e.moveToTop(true);a.modal&&d.bind("keypress.ui-dialog",function(h){if(h.keyCode===b.ui.keyCode.TAB){var i=b(":tabbable",this),j=i.filter(":first");i=i.filter(":last");if(h.target===i[0]&&!h.shiftKey){j.focus(1);return false}else if(h.target===j[0]&&h.shiftKey){i.focus(1);return false}}});
b(e.element.find(":tabbable").get().concat(d.find(".ui-dialog-buttonpane :tabbable").get().concat(d.get()))).eq(0).focus();e._isOpen=true;e._trigger("open");return e}},_createButtons:function(e){var a=this,d=false,h=b("<div></div>").addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"),i=b("<div></div>").addClass("ui-dialog-buttonset").appendTo(h);a.uiDialog.find(".ui-dialog-buttonpane").remove();typeof e==="object"&&e!==null&&b.each(e,function(){return!(d=true)});if(d){b.each(e,function(j,
n){n=b.isFunction(n)?{click:n,text:j}:n;j=b('<button type="button"></button>').attr(n,true).unbind("click").click(function(){n.click.apply(a.element[0],arguments)}).appendTo(i);b.fn.button&&j.button()});h.appendTo(a.uiDialog)}},_makeDraggable:function(){function e(j){return{position:j.position,offset:j.offset}}var a=this,d=a.options,h=b(document),i;a.uiDialog.draggable({cancel:".ui-dialog-content, .ui-dialog-titlebar-close",handle:".ui-dialog-titlebar",containment:"document",start:function(j,n){i=
d.height==="auto"?"auto":b(this).height();b(this).height(b(this).height()).addClass("ui-dialog-dragging");a._trigger("dragStart",j,e(n))},drag:function(j,n){a._trigger("drag",j,e(n))},stop:function(j,n){d.position=[n.position.left-h.scrollLeft(),n.position.top-h.scrollTop()];b(this).removeClass("ui-dialog-dragging").height(i);a._trigger("dragStop",j,e(n));b.ui.dialog.overlay.resize()}})},_makeResizable:function(e){function a(j){return{originalPosition:j.originalPosition,originalSize:j.originalSize,
position:j.position,size:j.size}}e=e===c?this.options.resizable:e;var d=this,h=d.options,i=d.uiDialog.css("position");e=typeof e==="string"?e:"n,e,s,w,se,sw,ne,nw";d.uiDialog.resizable({cancel:".ui-dialog-content",containment:"document",alsoResize:d.element,maxWidth:h.maxWidth,maxHeight:h.maxHeight,minWidth:h.minWidth,minHeight:d._minHeight(),handles:e,start:function(j,n){b(this).addClass("ui-dialog-resizing");d._trigger("resizeStart",j,a(n))},resize:function(j,n){d._trigger("resize",j,a(n))},stop:function(j,
n){b(this).removeClass("ui-dialog-resizing");h.height=b(this).height();h.width=b(this).width();d._trigger("resizeStop",j,a(n));b.ui.dialog.overlay.resize()}}).css("position",i).find(".ui-resizable-se").addClass("ui-icon ui-icon-grip-diagonal-se")},_minHeight:function(){var e=this.options;return e.height==="auto"?e.minHeight:Math.min(e.minHeight,e.height)},_position:function(e){var a=[],d=[0,0],h;if(e){if(typeof e==="string"||typeof e==="object"&&"0"in e){a=e.split?e.split(" "):[e[0],e[1]];if(a.length===
1)a[1]=a[0];b.each(["left","top"],function(i,j){if(+a[i]===a[i]){d[i]=a[i];a[i]=j}});e={my:a.join(" "),at:a.join(" "),offset:d.join(" ")}}e=b.extend({},b.ui.dialog.prototype.options.position,e)}else e=b.ui.dialog.prototype.options.position;(h=this.uiDialog.is(":visible"))||this.uiDialog.show();this.uiDialog.css({top:0,left:0}).position(b.extend({of:window},e));h||this.uiDialog.hide()},_setOptions:function(e){var a=this,d={},h=false;b.each(e,function(i,j){a._setOption(i,j);if(i in f)h=true;if(i in
g)d[i]=j});h&&this._size();this.uiDialog.is(":data(resizable)")&&this.uiDialog.resizable("option",d)},_setOption:function(e,a){var d=this,h=d.uiDialog;switch(e){case "beforeclose":e="beforeClose";break;case "buttons":d._createButtons(a);break;case "closeText":d.uiDialogTitlebarCloseText.text(""+a);break;case "dialogClass":h.removeClass(d.options.dialogClass).addClass("ui-dialog ui-widget ui-widget-content ui-corner-all "+a);break;case "disabled":a?h.addClass("ui-dialog-disabled"):h.removeClass("ui-dialog-disabled");
break;case "draggable":var i=h.is(":data(draggable)");i&&!a&&h.draggable("destroy");!i&&a&&d._makeDraggable();break;case "position":d._position(a);break;case "resizable":(i=h.is(":data(resizable)"))&&!a&&h.resizable("destroy");i&&typeof a==="string"&&h.resizable("option","handles",a);!i&&a!==false&&d._makeResizable(a);break;case "title":b(".ui-dialog-title",d.uiDialogTitlebar).html(""+(a||"&#160;"));break}b.Widget.prototype._setOption.apply(d,arguments)},_size:function(){var e=this.options,a,d,h=
this.uiDialog.is(":visible");this.element.show().css({width:"auto",minHeight:0,height:0});if(e.minWidth>e.width)e.width=e.minWidth;a=this.uiDialog.css({height:"auto",width:e.width}).height();d=Math.max(0,e.minHeight-a);if(e.height==="auto")if(b.support.minHeight)this.element.css({minHeight:d,height:"auto"});else{this.uiDialog.show();e=this.element.css("height","auto").height();h||this.uiDialog.hide();this.element.height(Math.max(e,d))}else this.element.height(Math.max(e.height-a,0));this.uiDialog.is(":data(resizable)")&&
this.uiDialog.resizable("option","minHeight",this._minHeight())}});b.extend(b.ui.dialog,{version:"1.8.7",uuid:0,maxZ:0,getTitleId:function(e){e=e.attr("id");if(!e){this.uuid+=1;e=this.uuid}return"ui-dialog-title-"+e},overlay:function(e){this.$el=b.ui.dialog.overlay.create(e)}});b.extend(b.ui.dialog.overlay,{instances:[],oldInstances:[],maxZ:0,events:b.map("focus,mousedown,mouseup,keydown,keypress,click".split(","),function(e){return e+".dialog-overlay"}).join(" "),create:function(e){if(this.instances.length===
0){setTimeout(function(){b.ui.dialog.overlay.instances.length&&b(document).bind(b.ui.dialog.overlay.events,function(d){if(b(d.target).zIndex()<b.ui.dialog.overlay.maxZ)return false})},1);b(document).bind("keydown.dialog-overlay",function(d){if(e.options.closeOnEscape&&d.keyCode&&d.keyCode===b.ui.keyCode.ESCAPE){e.close(d);d.preventDefault()}});b(window).bind("resize.dialog-overlay",b.ui.dialog.overlay.resize)}var a=(this.oldInstances.pop()||b("<div></div>").addClass("ui-widget-overlay")).appendTo(document.body).css({width:this.width(),
height:this.height()});b.fn.bgiframe&&a.bgiframe();this.instances.push(a);return a},destroy:function(e){var a=b.inArray(e,this.instances);a!=-1&&this.oldInstances.push(this.instances.splice(a,1)[0]);this.instances.length===0&&b([document,window]).unbind(".dialog-overlay");e.remove();var d=0;b.each(this.instances,function(){d=Math.max(d,this.css("z-index"))});this.maxZ=d},height:function(){var e,a;if(b.browser.msie&&b.browser.version<7){e=Math.max(document.documentElement.scrollHeight,document.body.scrollHeight);
a=Math.max(document.documentElement.offsetHeight,document.body.offsetHeight);return e<a?b(window).height()+"px":e+"px"}else return b(document).height()+"px"},width:function(){var e,a;if(b.browser.msie&&b.browser.version<7){e=Math.max(document.documentElement.scrollWidth,document.body.scrollWidth);a=Math.max(document.documentElement.offsetWidth,document.body.offsetWidth);return e<a?b(window).width()+"px":e+"px"}else return b(document).width()+"px"},resize:function(){var e=b([]);b.each(b.ui.dialog.overlay.instances,
function(){e=e.add(this)});e.css({width:0,height:0}).css({width:b.ui.dialog.overlay.width(),height:b.ui.dialog.overlay.height()})}});b.extend(b.ui.dialog.overlay.prototype,{destroy:function(){b.ui.dialog.overlay.destroy(this.$el)}})})(jQuery);
(function(b){b.ui=b.ui||{};var c=/left|center|right/,f=/top|center|bottom/,g=b.fn.position,e=b.fn.offset;b.fn.position=function(a){if(!a||!a.of)return g.apply(this,arguments);a=b.extend({},a);var d=b(a.of),h=d[0],i=(a.collision||"flip").split(" "),j=a.offset?a.offset.split(" "):[0,0],n,q,l;if(h.nodeType===9){n=d.width();q=d.height();l={top:0,left:0}}else if(h.setTimeout){n=d.width();q=d.height();l={top:d.scrollTop(),left:d.scrollLeft()}}else if(h.preventDefault){a.at="left top";n=q=0;l={top:a.of.pageY,
left:a.of.pageX}}else{n=d.outerWidth();q=d.outerHeight();l=d.offset()}b.each(["my","at"],function(){var k=(a[this]||"").split(" ");if(k.length===1)k=c.test(k[0])?k.concat(["center"]):f.test(k[0])?["center"].concat(k):["center","center"];k[0]=c.test(k[0])?k[0]:"center";k[1]=f.test(k[1])?k[1]:"center";a[this]=k});if(i.length===1)i[1]=i[0];j[0]=parseInt(j[0],10)||0;if(j.length===1)j[1]=j[0];j[1]=parseInt(j[1],10)||0;if(a.at[0]==="right")l.left+=n;else if(a.at[0]==="center")l.left+=n/2;if(a.at[1]==="bottom")l.top+=
q;else if(a.at[1]==="center")l.top+=q/2;l.left+=j[0];l.top+=j[1];return this.each(function(){var k=b(this),m=k.outerWidth(),o=k.outerHeight(),p=parseInt(b.curCSS(this,"marginLeft",true))||0,s=parseInt(b.curCSS(this,"marginTop",true))||0,r=m+p+parseInt(b.curCSS(this,"marginRight",true))||0,u=o+s+parseInt(b.curCSS(this,"marginBottom",true))||0,v=b.extend({},l),w;if(a.my[0]==="right")v.left-=m;else if(a.my[0]==="center")v.left-=m/2;if(a.my[1]==="bottom")v.top-=o;else if(a.my[1]==="center")v.top-=o/2;
v.left=Math.round(v.left);v.top=Math.round(v.top);w={left:v.left-p,top:v.top-s};b.each(["left","top"],function(y,B){b.ui.position[i[y]]&&b.ui.position[i[y]][B](v,{targetWidth:n,targetHeight:q,elemWidth:m,elemHeight:o,collisionPosition:w,collisionWidth:r,collisionHeight:u,offset:j,my:a.my,at:a.at})});b.fn.bgiframe&&k.bgiframe();k.offset(b.extend(v,{using:a.using}))})};b.ui.position={fit:{left:function(a,d){var h=b(window);h=d.collisionPosition.left+d.collisionWidth-h.width()-h.scrollLeft();a.left=
h>0?a.left-h:Math.max(a.left-d.collisionPosition.left,a.left)},top:function(a,d){var h=b(window);h=d.collisionPosition.top+d.collisionHeight-h.height()-h.scrollTop();a.top=h>0?a.top-h:Math.max(a.top-d.collisionPosition.top,a.top)}},flip:{left:function(a,d){if(d.at[0]!=="center"){var h=b(window);h=d.collisionPosition.left+d.collisionWidth-h.width()-h.scrollLeft();var i=d.my[0]==="left"?-d.elemWidth:d.my[0]==="right"?d.elemWidth:0,j=d.at[0]==="left"?d.targetWidth:-d.targetWidth,n=-2*d.offset[0];a.left+=
d.collisionPosition.left<0?i+j+n:h>0?i+j+n:0}},top:function(a,d){if(d.at[1]!=="center"){var h=b(window);h=d.collisionPosition.top+d.collisionHeight-h.height()-h.scrollTop();var i=d.my[1]==="top"?-d.elemHeight:d.my[1]==="bottom"?d.elemHeight:0,j=d.at[1]==="top"?d.targetHeight:-d.targetHeight,n=-2*d.offset[1];a.top+=d.collisionPosition.top<0?i+j+n:h>0?i+j+n:0}}}};if(!b.offset.setOffset){b.offset.setOffset=function(a,d){if(/static/.test(b.curCSS(a,"position")))a.style.position="relative";var h=b(a),
i=h.offset(),j=parseInt(b.curCSS(a,"top",true),10)||0,n=parseInt(b.curCSS(a,"left",true),10)||0;i={top:d.top-i.top+j,left:d.left-i.left+n};"using"in d?d.using.call(a,i):h.css(i)};b.fn.offset=function(a){var d=this[0];if(!d||!d.ownerDocument)return null;if(a)return this.each(function(){b.offset.setOffset(this,a)});return e.call(this)}}})(jQuery);
(function(b,c){b.widget("ui.progressbar",{options:{value:0,max:100},min:0,_create:function(){this.element.addClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").attr({role:"progressbar","aria-valuemin":this.min,"aria-valuemax":this.options.max,"aria-valuenow":this._value()});this.valueDiv=b("<div class='ui-progressbar-value ui-widget-header ui-corner-left'></div>").appendTo(this.element);this.oldValue=this._value();this._refreshValue()},destroy:function(){this.element.removeClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow");
this.valueDiv.remove();b.Widget.prototype.destroy.apply(this,arguments)},value:function(f){if(f===c)return this._value();this._setOption("value",f);return this},_setOption:function(f,g){if(f==="value"){this.options.value=g;this._refreshValue();this._value()===this.options.max&&this._trigger("complete")}b.Widget.prototype._setOption.apply(this,arguments)},_value:function(){var f=this.options.value;if(typeof f!=="number")f=0;return Math.min(this.options.max,Math.max(this.min,f))},_percentage:function(){return 100*
this._value()/this.options.max},_refreshValue:function(){var f=this.value(),g=this._percentage();if(this.oldValue!==f){this.oldValue=f;this._trigger("change")}this.valueDiv.toggleClass("ui-corner-right",f===this.options.max).width(g.toFixed(0)+"%");this.element.attr("aria-valuenow",f)}});b.extend(b.ui.progressbar,{version:"1.8.7"})})(jQuery);
(function(b){b.widget("ui.slider",b.ui.mouse,{widgetEventPrefix:"slide",options:{animate:false,distance:0,max:100,min:0,orientation:"horizontal",range:false,step:1,value:0,values:null},_create:function(){var c=this,f=this.options;this._mouseSliding=this._keySliding=false;this._animateOff=true;this._handleIndex=null;this._detectOrientation();this._mouseInit();this.element.addClass("ui-slider ui-slider-"+this.orientation+" ui-widget ui-widget-content ui-corner-all");f.disabled&&this.element.addClass("ui-slider-disabled ui-disabled");
this.range=b([]);if(f.range){if(f.range===true){this.range=b("<div></div>");if(!f.values)f.values=[this._valueMin(),this._valueMin()];if(f.values.length&&f.values.length!==2)f.values=[f.values[0],f.values[0]]}else this.range=b("<div></div>");this.range.appendTo(this.element).addClass("ui-slider-range");if(f.range==="min"||f.range==="max")this.range.addClass("ui-slider-range-"+f.range);this.range.addClass("ui-widget-header")}b(".ui-slider-handle",this.element).length===0&&b("<a href='#'></a>").appendTo(this.element).addClass("ui-slider-handle");
if(f.values&&f.values.length)for(;b(".ui-slider-handle",this.element).length<f.values.length;)b("<a href='#'></a>").appendTo(this.element).addClass("ui-slider-handle");this.handles=b(".ui-slider-handle",this.element).addClass("ui-state-default ui-corner-all");this.handle=this.handles.eq(0);this.handles.add(this.range).filter("a").click(function(g){g.preventDefault()}).hover(function(){f.disabled||b(this).addClass("ui-state-hover")},function(){b(this).removeClass("ui-state-hover")}).focus(function(){if(f.disabled)b(this).blur();
else{b(".ui-slider .ui-state-focus").removeClass("ui-state-focus");b(this).addClass("ui-state-focus")}}).blur(function(){b(this).removeClass("ui-state-focus")});this.handles.each(function(g){b(this).data("index.ui-slider-handle",g)});this.handles.keydown(function(g){var e=true,a=b(this).data("index.ui-slider-handle"),d,h,i;if(!c.options.disabled){switch(g.keyCode){case b.ui.keyCode.HOME:case b.ui.keyCode.END:case b.ui.keyCode.PAGE_UP:case b.ui.keyCode.PAGE_DOWN:case b.ui.keyCode.UP:case b.ui.keyCode.RIGHT:case b.ui.keyCode.DOWN:case b.ui.keyCode.LEFT:e=
false;if(!c._keySliding){c._keySliding=true;b(this).addClass("ui-state-active");d=c._start(g,a);if(d===false)return}break}i=c.options.step;d=c.options.values&&c.options.values.length?(h=c.values(a)):(h=c.value());switch(g.keyCode){case b.ui.keyCode.HOME:h=c._valueMin();break;case b.ui.keyCode.END:h=c._valueMax();break;case b.ui.keyCode.PAGE_UP:h=c._trimAlignValue(d+(c._valueMax()-c._valueMin())/5);break;case b.ui.keyCode.PAGE_DOWN:h=c._trimAlignValue(d-(c._valueMax()-c._valueMin())/5);break;case b.ui.keyCode.UP:case b.ui.keyCode.RIGHT:if(d===
c._valueMax())return;h=c._trimAlignValue(d+i);break;case b.ui.keyCode.DOWN:case b.ui.keyCode.LEFT:if(d===c._valueMin())return;h=c._trimAlignValue(d-i);break}c._slide(g,a,h);return e}}).keyup(function(g){var e=b(this).data("index.ui-slider-handle");if(c._keySliding){c._keySliding=false;c._stop(g,e);c._change(g,e);b(this).removeClass("ui-state-active")}});this._refreshValue();this._animateOff=false},destroy:function(){this.handles.remove();this.range.remove();this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-slider-disabled ui-widget ui-widget-content ui-corner-all").removeData("slider").unbind(".slider");
this._mouseDestroy();return this},_mouseCapture:function(c){var f=this.options,g,e,a,d,h;if(f.disabled)return false;this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()};this.elementOffset=this.element.offset();g=this._normValueFromMouse({x:c.pageX,y:c.pageY});e=this._valueMax()-this._valueMin()+1;d=this;this.handles.each(function(i){var j=Math.abs(g-d.values(i));if(e>j){e=j;a=b(this);h=i}});if(f.range===true&&this.values(1)===f.min){h+=1;a=b(this.handles[h])}if(this._start(c,
h)===false)return false;this._mouseSliding=true;d._handleIndex=h;a.addClass("ui-state-active").focus();f=a.offset();this._clickOffset=!b(c.target).parents().andSelf().is(".ui-slider-handle")?{left:0,top:0}:{left:c.pageX-f.left-a.width()/2,top:c.pageY-f.top-a.height()/2-(parseInt(a.css("borderTopWidth"),10)||0)-(parseInt(a.css("borderBottomWidth"),10)||0)+(parseInt(a.css("marginTop"),10)||0)};this.handles.hasClass("ui-state-hover")||this._slide(c,h,g);return this._animateOff=true},_mouseStart:function(){return true},
_mouseDrag:function(c){var f=this._normValueFromMouse({x:c.pageX,y:c.pageY});this._slide(c,this._handleIndex,f);return false},_mouseStop:function(c){this.handles.removeClass("ui-state-active");this._mouseSliding=false;this._stop(c,this._handleIndex);this._change(c,this._handleIndex);this._clickOffset=this._handleIndex=null;return this._animateOff=false},_detectOrientation:function(){this.orientation=this.options.orientation==="vertical"?"vertical":"horizontal"},_normValueFromMouse:function(c){var f;
if(this.orientation==="horizontal"){f=this.elementSize.width;c=c.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)}else{f=this.elementSize.height;c=c.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)}f=c/f;if(f>1)f=1;if(f<0)f=0;if(this.orientation==="vertical")f=1-f;c=this._valueMax()-this._valueMin();return this._trimAlignValue(this._valueMin()+f*c)},_start:function(c,f){var g={handle:this.handles[f],value:this.value()};if(this.options.values&&this.options.values.length){g.value=
this.values(f);g.values=this.values()}return this._trigger("start",c,g)},_slide:function(c,f,g){var e;if(this.options.values&&this.options.values.length){e=this.values(f?0:1);if(this.options.values.length===2&&this.options.range===true&&(f===0&&g>e||f===1&&g<e))g=e;if(g!==this.values(f)){e=this.values();e[f]=g;c=this._trigger("slide",c,{handle:this.handles[f],value:g,values:e});this.values(f?0:1);c!==false&&this.values(f,g,true)}}else if(g!==this.value()){c=this._trigger("slide",c,{handle:this.handles[f],
value:g});c!==false&&this.value(g)}},_stop:function(c,f){var g={handle:this.handles[f],value:this.value()};if(this.options.values&&this.options.values.length){g.value=this.values(f);g.values=this.values()}this._trigger("stop",c,g)},_change:function(c,f){if(!this._keySliding&&!this._mouseSliding){var g={handle:this.handles[f],value:this.value()};if(this.options.values&&this.options.values.length){g.value=this.values(f);g.values=this.values()}this._trigger("change",c,g)}},value:function(c){if(arguments.length){this.options.value=
this._trimAlignValue(c);this._refreshValue();this._change(null,0)}return this._value()},values:function(c,f){var g,e,a;if(arguments.length>1){this.options.values[c]=this._trimAlignValue(f);this._refreshValue();this._change(null,c)}if(arguments.length)if(b.isArray(arguments[0])){g=this.options.values;e=arguments[0];for(a=0;a<g.length;a+=1){g[a]=this._trimAlignValue(e[a]);this._change(null,a)}this._refreshValue()}else return this.options.values&&this.options.values.length?this._values(c):this.value();
else return this._values()},_setOption:function(c,f){var g,e=0;if(b.isArray(this.options.values))e=this.options.values.length;b.Widget.prototype._setOption.apply(this,arguments);switch(c){case "disabled":if(f){this.handles.filter(".ui-state-focus").blur();this.handles.removeClass("ui-state-hover");this.handles.attr("disabled","disabled");this.element.addClass("ui-disabled")}else{this.handles.removeAttr("disabled");this.element.removeClass("ui-disabled")}break;case "orientation":this._detectOrientation();
this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation);this._refreshValue();break;case "value":this._animateOff=true;this._refreshValue();this._change(null,0);this._animateOff=false;break;case "values":this._animateOff=true;this._refreshValue();for(g=0;g<e;g+=1)this._change(null,g);this._animateOff=false;break}},_value:function(){var c=this.options.value;return c=this._trimAlignValue(c)},_values:function(c){var f,g;if(arguments.length){f=this.options.values[c];
return f=this._trimAlignValue(f)}else{f=this.options.values.slice();for(g=0;g<f.length;g+=1)f[g]=this._trimAlignValue(f[g]);return f}},_trimAlignValue:function(c){if(c<=this._valueMin())return this._valueMin();if(c>=this._valueMax())return this._valueMax();var f=this.options.step>0?this.options.step:1,g=(c-this._valueMin())%f;alignValue=c-g;if(Math.abs(g)*2>=f)alignValue+=g>0?f:-f;return parseFloat(alignValue.toFixed(5))},_valueMin:function(){return this.options.min},_valueMax:function(){return this.options.max},
_refreshValue:function(){var c=this.options.range,f=this.options,g=this,e=!this._animateOff?f.animate:false,a,d={},h,i,j,n;if(this.options.values&&this.options.values.length)this.handles.each(function(q){a=(g.values(q)-g._valueMin())/(g._valueMax()-g._valueMin())*100;d[g.orientation==="horizontal"?"left":"bottom"]=a+"%";b(this).stop(1,1)[e?"animate":"css"](d,f.animate);if(g.options.range===true)if(g.orientation==="horizontal"){if(q===0)g.range.stop(1,1)[e?"animate":"css"]({left:a+"%"},f.animate);
if(q===1)g.range[e?"animate":"css"]({width:a-h+"%"},{queue:false,duration:f.animate})}else{if(q===0)g.range.stop(1,1)[e?"animate":"css"]({bottom:a+"%"},f.animate);if(q===1)g.range[e?"animate":"css"]({height:a-h+"%"},{queue:false,duration:f.animate})}h=a});else{i=this.value();j=this._valueMin();n=this._valueMax();a=n!==j?(i-j)/(n-j)*100:0;d[g.orientation==="horizontal"?"left":"bottom"]=a+"%";this.handle.stop(1,1)[e?"animate":"css"](d,f.animate);if(c==="min"&&this.orientation==="horizontal")this.range.stop(1,
1)[e?"animate":"css"]({width:a+"%"},f.animate);if(c==="max"&&this.orientation==="horizontal")this.range[e?"animate":"css"]({width:100-a+"%"},{queue:false,duration:f.animate});if(c==="min"&&this.orientation==="vertical")this.range.stop(1,1)[e?"animate":"css"]({height:a+"%"},f.animate);if(c==="max"&&this.orientation==="vertical")this.range[e?"animate":"css"]({height:100-a+"%"},{queue:false,duration:f.animate})}}});b.extend(b.ui.slider,{version:"1.8.7"})})(jQuery);
(function(b,c){function f(){return++e}function g(){return++a}var e=0,a=0;b.widget("ui.tabs",{options:{add:null,ajaxOptions:null,cache:false,cookie:null,collapsible:false,disable:null,disabled:[],enable:null,event:"click",fx:null,idPrefix:"ui-tabs-",load:null,panelTemplate:"<div></div>",remove:null,select:null,show:null,spinner:"<em>Loading&#8230;</em>",tabTemplate:"<li><a href='#{href}'><span>#{label}</span></a></li>"},_create:function(){this._tabify(true)},_setOption:function(d,h){if(d=="selected")this.options.collapsible&&
h==this.options.selected||this.select(h);else{this.options[d]=h;this._tabify()}},_tabId:function(d){return d.title&&d.title.replace(/\s/g,"_").replace(/[^\w\u00c0-\uFFFF-]/g,"")||this.options.idPrefix+f()},_sanitizeSelector:function(d){return d.replace(/:/g,"\\:")},_cookie:function(){var d=this.cookie||(this.cookie=this.options.cookie.name||"ui-tabs-"+g());return b.cookie.apply(null,[d].concat(b.makeArray(arguments)))},_ui:function(d,h){return{tab:d,panel:h,index:this.anchors.index(d)}},_cleanup:function(){this.lis.filter(".ui-state-processing").removeClass("ui-state-processing").find("span:data(label.tabs)").each(function(){var d=
b(this);d.html(d.data("label.tabs")).removeData("label.tabs")})},_tabify:function(d){function h(r,u){r.css("display","");!b.support.opacity&&u.opacity&&r[0].style.removeAttribute("filter")}var i=this,j=this.options,n=/^#.+/;this.list=this.element.find("ol,ul").eq(0);this.lis=b(" > li:has(a[href])",this.list);this.anchors=this.lis.map(function(){return b("a",this)[0]});this.panels=b([]);this.anchors.each(function(r,u){var v=b(u).attr("href"),w=v.split("#")[0],y;if(w&&(w===location.toString().split("#")[0]||
(y=b("base")[0])&&w===y.href)){v=u.hash;u.href=v}if(n.test(v))i.panels=i.panels.add(i.element.find(i._sanitizeSelector(v)));else if(v&&v!=="#"){b.data(u,"href.tabs",v);b.data(u,"load.tabs",v.replace(/#.*$/,""));v=i._tabId(u);u.href="#"+v;u=i.element.find("#"+v);if(!u.length){u=b(j.panelTemplate).attr("id",v).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").insertAfter(i.panels[r-1]||i.list);u.data("destroy.tabs",true)}i.panels=i.panels.add(u)}else j.disabled.push(r)});if(d){this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all");
this.list.addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all");this.lis.addClass("ui-state-default ui-corner-top");this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom");if(j.selected===c){location.hash&&this.anchors.each(function(r,u){if(u.hash==location.hash){j.selected=r;return false}});if(typeof j.selected!=="number"&&j.cookie)j.selected=parseInt(i._cookie(),10);if(typeof j.selected!=="number"&&this.lis.filter(".ui-tabs-selected").length)j.selected=
this.lis.index(this.lis.filter(".ui-tabs-selected"));j.selected=j.selected||(this.lis.length?0:-1)}else if(j.selected===null)j.selected=-1;j.selected=j.selected>=0&&this.anchors[j.selected]||j.selected<0?j.selected:0;j.disabled=b.unique(j.disabled.concat(b.map(this.lis.filter(".ui-state-disabled"),function(r){return i.lis.index(r)}))).sort();b.inArray(j.selected,j.disabled)!=-1&&j.disabled.splice(b.inArray(j.selected,j.disabled),1);this.panels.addClass("ui-tabs-hide");this.lis.removeClass("ui-tabs-selected ui-state-active");
if(j.selected>=0&&this.anchors.length){i.element.find(i._sanitizeSelector(i.anchors[j.selected].hash)).removeClass("ui-tabs-hide");this.lis.eq(j.selected).addClass("ui-tabs-selected ui-state-active");i.element.queue("tabs",function(){i._trigger("show",null,i._ui(i.anchors[j.selected],i.element.find(i._sanitizeSelector(i.anchors[j.selected].hash))))});this.load(j.selected)}b(window).bind("unload",function(){i.lis.add(i.anchors).unbind(".tabs");i.lis=i.anchors=i.panels=null})}else j.selected=this.lis.index(this.lis.filter(".ui-tabs-selected"));
this.element[j.collapsible?"addClass":"removeClass"]("ui-tabs-collapsible");j.cookie&&this._cookie(j.selected,j.cookie);d=0;for(var q;q=this.lis[d];d++)b(q)[b.inArray(d,j.disabled)!=-1&&!b(q).hasClass("ui-tabs-selected")?"addClass":"removeClass"]("ui-state-disabled");j.cache===false&&this.anchors.removeData("cache.tabs");this.lis.add(this.anchors).unbind(".tabs");if(j.event!=="mouseover"){var l=function(r,u){u.is(":not(.ui-state-disabled)")&&u.addClass("ui-state-"+r)},k=function(r,u){u.removeClass("ui-state-"+
r)};this.lis.bind("mouseover.tabs",function(){l("hover",b(this))});this.lis.bind("mouseout.tabs",function(){k("hover",b(this))});this.anchors.bind("focus.tabs",function(){l("focus",b(this).closest("li"))});this.anchors.bind("blur.tabs",function(){k("focus",b(this).closest("li"))})}var m,o;if(j.fx)if(b.isArray(j.fx)){m=j.fx[0];o=j.fx[1]}else m=o=j.fx;var p=o?function(r,u){b(r).closest("li").addClass("ui-tabs-selected ui-state-active");u.hide().removeClass("ui-tabs-hide").animate(o,o.duration||"normal",
function(){h(u,o);i._trigger("show",null,i._ui(r,u[0]))})}:function(r,u){b(r).closest("li").addClass("ui-tabs-selected ui-state-active");u.removeClass("ui-tabs-hide");i._trigger("show",null,i._ui(r,u[0]))},s=m?function(r,u){u.animate(m,m.duration||"normal",function(){i.lis.removeClass("ui-tabs-selected ui-state-active");u.addClass("ui-tabs-hide");h(u,m);i.element.dequeue("tabs")})}:function(r,u){i.lis.removeClass("ui-tabs-selected ui-state-active");u.addClass("ui-tabs-hide");i.element.dequeue("tabs")};
this.anchors.bind(j.event+".tabs",function(){var r=this,u=b(r).closest("li"),v=i.panels.filter(":not(.ui-tabs-hide)"),w=i.element.find(i._sanitizeSelector(r.hash));if(u.hasClass("ui-tabs-selected")&&!j.collapsible||u.hasClass("ui-state-disabled")||u.hasClass("ui-state-processing")||i.panels.filter(":animated").length||i._trigger("select",null,i._ui(this,w[0]))===false){this.blur();return false}j.selected=i.anchors.index(this);i.abort();if(j.collapsible)if(u.hasClass("ui-tabs-selected")){j.selected=
-1;j.cookie&&i._cookie(j.selected,j.cookie);i.element.queue("tabs",function(){s(r,v)}).dequeue("tabs");this.blur();return false}else if(!v.length){j.cookie&&i._cookie(j.selected,j.cookie);i.element.queue("tabs",function(){p(r,w)});i.load(i.anchors.index(this));this.blur();return false}j.cookie&&i._cookie(j.selected,j.cookie);if(w.length){v.length&&i.element.queue("tabs",function(){s(r,v)});i.element.queue("tabs",function(){p(r,w)});i.load(i.anchors.index(this))}else throw"jQuery UI Tabs: Mismatching fragment identifier.";
b.browser.msie&&this.blur()});this.anchors.bind("click.tabs",function(){return false})},_getIndex:function(d){if(typeof d=="string")d=this.anchors.index(this.anchors.filter("[href$="+d+"]"));return d},destroy:function(){var d=this.options;this.abort();this.element.unbind(".tabs").removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible").removeData("tabs");this.list.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all");this.anchors.each(function(){var h=
b.data(this,"href.tabs");if(h)this.href=h;var i=b(this).unbind(".tabs");b.each(["href","load","cache"],function(j,n){i.removeData(n+".tabs")})});this.lis.unbind(".tabs").add(this.panels).each(function(){b.data(this,"destroy.tabs")?b(this).remove():b(this).removeClass("ui-state-default ui-corner-top ui-tabs-selected ui-state-active ui-state-hover ui-state-focus ui-state-disabled ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide")});d.cookie&&this._cookie(null,d.cookie);return this},add:function(d,
h,i){if(i===c)i=this.anchors.length;var j=this,n=this.options;h=b(n.tabTemplate.replace(/#\{href\}/g,d).replace(/#\{label\}/g,h));d=!d.indexOf("#")?d.replace("#",""):this._tabId(b("a",h)[0]);h.addClass("ui-state-default ui-corner-top").data("destroy.tabs",true);var q=j.element.find("#"+d);q.length||(q=b(n.panelTemplate).attr("id",d).data("destroy.tabs",true));q.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide");if(i>=this.lis.length){h.appendTo(this.list);q.appendTo(this.list[0].parentNode)}else{h.insertBefore(this.lis[i]);
q.insertBefore(this.panels[i])}n.disabled=b.map(n.disabled,function(l){return l>=i?++l:l});this._tabify();if(this.anchors.length==1){n.selected=0;h.addClass("ui-tabs-selected ui-state-active");q.removeClass("ui-tabs-hide");this.element.queue("tabs",function(){j._trigger("show",null,j._ui(j.anchors[0],j.panels[0]))});this.load(0)}this._trigger("add",null,this._ui(this.anchors[i],this.panels[i]));return this},remove:function(d){d=this._getIndex(d);var h=this.options,i=this.lis.eq(d).remove(),j=this.panels.eq(d).remove();
if(i.hasClass("ui-tabs-selected")&&this.anchors.length>1)this.select(d+(d+1<this.anchors.length?1:-1));h.disabled=b.map(b.grep(h.disabled,function(n){return n!=d}),function(n){return n>=d?--n:n});this._tabify();this._trigger("remove",null,this._ui(i.find("a")[0],j[0]));return this},enable:function(d){d=this._getIndex(d);var h=this.options;if(b.inArray(d,h.disabled)!=-1){this.lis.eq(d).removeClass("ui-state-disabled");h.disabled=b.grep(h.disabled,function(i){return i!=d});this._trigger("enable",null,
this._ui(this.anchors[d],this.panels[d]));return this}},disable:function(d){d=this._getIndex(d);var h=this.options;if(d!=h.selected){this.lis.eq(d).addClass("ui-state-disabled");h.disabled.push(d);h.disabled.sort();this._trigger("disable",null,this._ui(this.anchors[d],this.panels[d]))}return this},select:function(d){d=this._getIndex(d);if(d==-1)if(this.options.collapsible&&this.options.selected!=-1)d=this.options.selected;else return this;this.anchors.eq(d).trigger(this.options.event+".tabs");return this},
load:function(d){d=this._getIndex(d);var h=this,i=this.options,j=this.anchors.eq(d)[0],n=b.data(j,"load.tabs");this.abort();if(!n||this.element.queue("tabs").length!==0&&b.data(j,"cache.tabs"))this.element.dequeue("tabs");else{this.lis.eq(d).addClass("ui-state-processing");if(i.spinner){var q=b("span",j);q.data("label.tabs",q.html()).html(i.spinner)}this.xhr=b.ajax(b.extend({},i.ajaxOptions,{url:n,success:function(l,k){h.element.find(h._sanitizeSelector(j.hash)).html(l);h._cleanup();i.cache&&b.data(j,
"cache.tabs",true);h._trigger("load",null,h._ui(h.anchors[d],h.panels[d]));try{i.ajaxOptions.success(l,k)}catch(m){}},error:function(l,k){h._cleanup();h._trigger("load",null,h._ui(h.anchors[d],h.panels[d]));try{i.ajaxOptions.error(l,k,d,j)}catch(m){}}}));h.element.dequeue("tabs");return this}},abort:function(){this.element.queue([]);this.panels.stop(false,true);this.element.queue("tabs",this.element.queue("tabs").splice(-2,2));if(this.xhr){this.xhr.abort();delete this.xhr}this._cleanup();return this},
url:function(d,h){this.anchors.eq(d).removeData("cache.tabs").data("load.tabs",h);return this},length:function(){return this.anchors.length}});b.extend(b.ui.tabs,{version:"1.8.7"});b.extend(b.ui.tabs.prototype,{rotation:null,rotate:function(d,h){var i=this,j=this.options,n=i._rotate||(i._rotate=function(q){clearTimeout(i.rotation);i.rotation=setTimeout(function(){var l=j.selected;i.select(++l<i.anchors.length?l:0)},d);q&&q.stopPropagation()});h=i._unrotate||(i._unrotate=!h?function(q){q.clientX&&
i.rotate(null)}:function(){t=j.selected;n()});if(d){this.element.bind("tabsshow",n);this.anchors.bind(j.event+".tabs",h);n()}else{clearTimeout(i.rotation);this.element.unbind("tabsshow",n);this.anchors.unbind(j.event+".tabs",h);delete this._rotate;delete this._unrotate}return this}})})(jQuery);

/*	SWFObject v2.2 <http://code.google.com/p/swfobject/> 
	is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
var swfobject=function(){var D="undefined",r="object",S="Shockwave Flash",W="ShockwaveFlash.ShockwaveFlash",q="application/x-shockwave-flash",R="SWFObjectExprInst",x="onreadystatechange",O=window,j=document,t=navigator,T=false,U=[h],o=[],N=[],I=[],l,Q,E,B,J=false,a=false,n,G,m=true,M=function(){var aa=typeof j.getElementById!=D&&typeof j.getElementsByTagName!=D&&typeof j.createElement!=D,ah=t.userAgent.toLowerCase(),Y=t.platform.toLowerCase(),ae=Y?/win/.test(Y):/win/.test(ah),ac=Y?/mac/.test(Y):/mac/.test(ah),af=/webkit/.test(ah)?parseFloat(ah.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,X=!+"\v1",ag=[0,0,0],ab=null;if(typeof t.plugins!=D&&typeof t.plugins[S]==r){ab=t.plugins[S].description;if(ab&&!(typeof t.mimeTypes!=D&&t.mimeTypes[q]&&!t.mimeTypes[q].enabledPlugin)){T=true;X=false;ab=ab.replace(/^.*\s+(\S+\s+\S+$)/,"$1");ag[0]=parseInt(ab.replace(/^(.*)\..*$/,"$1"),10);ag[1]=parseInt(ab.replace(/^.*\.(.*)\s.*$/,"$1"),10);ag[2]=/[a-zA-Z]/.test(ab)?parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0}}else{if(typeof O.ActiveXObject!=D){try{var ad=new ActiveXObject(W);if(ad){ab=ad.GetVariable("$version");if(ab){X=true;ab=ab.split(" ")[1].split(",");ag=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}}catch(Z){}}}return{w3:aa,pv:ag,wk:af,ie:X,win:ae,mac:ac}}(),k=function(){if(!M.w3){return}if((typeof j.readyState!=D&&j.readyState=="complete")||(typeof j.readyState==D&&(j.getElementsByTagName("body")[0]||j.body))){f()}if(!J){if(typeof j.addEventListener!=D){j.addEventListener("DOMContentLoaded",f,false)}if(M.ie&&M.win){j.attachEvent(x,function(){if(j.readyState=="complete"){j.detachEvent(x,arguments.callee);f()}});if(O==top){(function(){if(J){return}try{j.documentElement.doScroll("left")}catch(X){setTimeout(arguments.callee,0);return}f()})()}}if(M.wk){(function(){if(J){return}if(!/loaded|complete/.test(j.readyState)){setTimeout(arguments.callee,0);return}f()})()}s(f)}}();function f(){if(J){return}try{var Z=j.getElementsByTagName("body")[0].appendChild(C("span"));Z.parentNode.removeChild(Z)}catch(aa){return}J=true;var X=U.length;for(var Y=0;Y<X;Y++){U[Y]()}}function K(X){if(J){X()}else{U[U.length]=X}}function s(Y){if(typeof O.addEventListener!=D){O.addEventListener("load",Y,false)}else{if(typeof j.addEventListener!=D){j.addEventListener("load",Y,false)}else{if(typeof O.attachEvent!=D){i(O,"onload",Y)}else{if(typeof O.onload=="function"){var X=O.onload;O.onload=function(){X();Y()}}else{O.onload=Y}}}}}function h(){if(T){V()}else{H()}}function V(){var X=j.getElementsByTagName("body")[0];var aa=C(r);aa.setAttribute("type",q);var Z=X.appendChild(aa);if(Z){var Y=0;(function(){if(typeof Z.GetVariable!=D){var ab=Z.GetVariable("$version");if(ab){ab=ab.split(" ")[1].split(",");M.pv=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}else{if(Y<10){Y++;setTimeout(arguments.callee,10);return}}X.removeChild(aa);Z=null;H()})()}else{H()}}function H(){var ag=o.length;if(ag>0){for(var af=0;af<ag;af++){var Y=o[af].id;var ab=o[af].callbackFn;var aa={success:false,id:Y};if(M.pv[0]>0){var ae=c(Y);if(ae){if(F(o[af].swfVersion)&&!(M.wk&&M.wk<312)){w(Y,true);if(ab){aa.success=true;aa.ref=z(Y);ab(aa)}}else{if(o[af].expressInstall&&A()){var ai={};ai.data=o[af].expressInstall;ai.width=ae.getAttribute("width")||"0";ai.height=ae.getAttribute("height")||"0";if(ae.getAttribute("class")){ai.styleclass=ae.getAttribute("class")}if(ae.getAttribute("align")){ai.align=ae.getAttribute("align")}var ah={};var X=ae.getElementsByTagName("param");var ac=X.length;for(var ad=0;ad<ac;ad++){if(X[ad].getAttribute("name").toLowerCase()!="movie"){ah[X[ad].getAttribute("name")]=X[ad].getAttribute("value")}}P(ai,ah,Y,ab)}else{p(ae);if(ab){ab(aa)}}}}}else{w(Y,true);if(ab){var Z=z(Y);if(Z&&typeof Z.SetVariable!=D){aa.success=true;aa.ref=Z}ab(aa)}}}}}function z(aa){var X=null;var Y=c(aa);if(Y&&Y.nodeName=="OBJECT"){if(typeof Y.SetVariable!=D){X=Y}else{var Z=Y.getElementsByTagName(r)[0];if(Z){X=Z}}}return X}function A(){return !a&&F("6.0.65")&&(M.win||M.mac)&&!(M.wk&&M.wk<312)}function P(aa,ab,X,Z){a=true;E=Z||null;B={success:false,id:X};var ae=c(X);if(ae){if(ae.nodeName=="OBJECT"){l=g(ae);Q=null}else{l=ae;Q=X}aa.id=R;if(typeof aa.width==D||(!/%$/.test(aa.width)&&parseInt(aa.width,10)<310)){aa.width="310"}if(typeof aa.height==D||(!/%$/.test(aa.height)&&parseInt(aa.height,10)<137)){aa.height="137"}j.title=j.title.slice(0,47)+" - Flash Player Installation";var ad=M.ie&&M.win?"ActiveX":"PlugIn",ac="MMredirectURL="+O.location.toString().replace(/&/g,"%26")+"&MMplayerType="+ad+"&MMdoctitle="+j.title;if(typeof ab.flashvars!=D){ab.flashvars+="&"+ac}else{ab.flashvars=ac}if(M.ie&&M.win&&ae.readyState!=4){var Y=C("div");X+="SWFObjectNew";Y.setAttribute("id",X);ae.parentNode.insertBefore(Y,ae);ae.style.display="none";(function(){if(ae.readyState==4){ae.parentNode.removeChild(ae)}else{setTimeout(arguments.callee,10)}})()}u(aa,ab,X)}}function p(Y){if(M.ie&&M.win&&Y.readyState!=4){var X=C("div");Y.parentNode.insertBefore(X,Y);X.parentNode.replaceChild(g(Y),X);Y.style.display="none";(function(){if(Y.readyState==4){Y.parentNode.removeChild(Y)}else{setTimeout(arguments.callee,10)}})()}else{Y.parentNode.replaceChild(g(Y),Y)}}function g(ab){var aa=C("div");if(M.win&&M.ie){aa.innerHTML=ab.innerHTML}else{var Y=ab.getElementsByTagName(r)[0];if(Y){var ad=Y.childNodes;if(ad){var X=ad.length;for(var Z=0;Z<X;Z++){if(!(ad[Z].nodeType==1&&ad[Z].nodeName=="PARAM")&&!(ad[Z].nodeType==8)){aa.appendChild(ad[Z].cloneNode(true))}}}}}return aa}function u(ai,ag,Y){var X,aa=c(Y);if(M.wk&&M.wk<312){return X}if(aa){if(typeof ai.id==D){ai.id=Y}if(M.ie&&M.win){var ah="";for(var ae in ai){if(ai[ae]!=Object.prototype[ae]){if(ae.toLowerCase()=="data"){ag.movie=ai[ae]}else{if(ae.toLowerCase()=="styleclass"){ah+=' class="'+ai[ae]+'"'}else{if(ae.toLowerCase()!="classid"){ah+=" "+ae+'="'+ai[ae]+'"'}}}}}var af="";for(var ad in ag){if(ag[ad]!=Object.prototype[ad]){af+='<param name="'+ad+'" value="'+ag[ad]+'" />'}}aa.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+ah+">"+af+"</object>";N[N.length]=ai.id;X=c(ai.id)}else{var Z=C(r);Z.setAttribute("type",q);for(var ac in ai){if(ai[ac]!=Object.prototype[ac]){if(ac.toLowerCase()=="styleclass"){Z.setAttribute("class",ai[ac])}else{if(ac.toLowerCase()!="classid"){Z.setAttribute(ac,ai[ac])}}}}for(var ab in ag){if(ag[ab]!=Object.prototype[ab]&&ab.toLowerCase()!="movie"){e(Z,ab,ag[ab])}}aa.parentNode.replaceChild(Z,aa);X=Z}}return X}function e(Z,X,Y){var aa=C("param");aa.setAttribute("name",X);aa.setAttribute("value",Y);Z.appendChild(aa)}function y(Y){var X=c(Y);if(X&&X.nodeName=="OBJECT"){if(M.ie&&M.win){X.style.display="none";(function(){if(X.readyState==4){b(Y)}else{setTimeout(arguments.callee,10)}})()}else{X.parentNode.removeChild(X)}}}function b(Z){var Y=c(Z);if(Y){for(var X in Y){if(typeof Y[X]=="function"){Y[X]=null}}Y.parentNode.removeChild(Y)}}function c(Z){var X=null;try{X=j.getElementById(Z)}catch(Y){}return X}function C(X){return j.createElement(X)}function i(Z,X,Y){Z.attachEvent(X,Y);I[I.length]=[Z,X,Y]}function F(Z){var Y=M.pv,X=Z.split(".");X[0]=parseInt(X[0],10);X[1]=parseInt(X[1],10)||0;X[2]=parseInt(X[2],10)||0;return(Y[0]>X[0]||(Y[0]==X[0]&&Y[1]>X[1])||(Y[0]==X[0]&&Y[1]==X[1]&&Y[2]>=X[2]))?true:false}function v(ac,Y,ad,ab){if(M.ie&&M.mac){return}var aa=j.getElementsByTagName("head")[0];if(!aa){return}var X=(ad&&typeof ad=="string")?ad:"screen";if(ab){n=null;G=null}if(!n||G!=X){var Z=C("style");Z.setAttribute("type","text/css");Z.setAttribute("media",X);n=aa.appendChild(Z);if(M.ie&&M.win&&typeof j.styleSheets!=D&&j.styleSheets.length>0){n=j.styleSheets[j.styleSheets.length-1]}G=X}if(M.ie&&M.win){if(n&&typeof n.addRule==r){n.addRule(ac,Y)}}else{if(n&&typeof j.createTextNode!=D){n.appendChild(j.createTextNode(ac+" {"+Y+"}"))}}}function w(Z,X){if(!m){return}var Y=X?"visible":"hidden";if(J&&c(Z)){c(Z).style.visibility=Y}else{v("#"+Z,"visibility:"+Y)}}function L(Y){var Z=/[\\\"<>\.;]/;var X=Z.exec(Y)!=null;return X&&typeof encodeURIComponent!=D?encodeURIComponent(Y):Y}var d=function(){if(M.ie&&M.win){window.attachEvent("onunload",function(){var ac=I.length;for(var ab=0;ab<ac;ab++){I[ab][0].detachEvent(I[ab][1],I[ab][2])}var Z=N.length;for(var aa=0;aa<Z;aa++){y(N[aa])}for(var Y in M){M[Y]=null}M=null;for(var X in swfobject){swfobject[X]=null}swfobject=null})}}();return{registerObject:function(ab,X,aa,Z){if(M.w3&&ab&&X){var Y={};Y.id=ab;Y.swfVersion=X;Y.expressInstall=aa;Y.callbackFn=Z;o[o.length]=Y;w(ab,false)}else{if(Z){Z({success:false,id:ab})}}},getObjectById:function(X){if(M.w3){return z(X)}},embedSWF:function(ab,ah,ae,ag,Y,aa,Z,ad,af,ac){var X={success:false,id:ah};if(M.w3&&!(M.wk&&M.wk<312)&&ab&&ah&&ae&&ag&&Y){w(ah,false);K(function(){ae+="";ag+="";var aj={};if(af&&typeof af===r){for(var al in af){aj[al]=af[al]}}aj.data=ab;aj.width=ae;aj.height=ag;var am={};if(ad&&typeof ad===r){for(var ak in ad){am[ak]=ad[ak]}}if(Z&&typeof Z===r){for(var ai in Z){if(typeof am.flashvars!=D){am.flashvars+="&"+ai+"="+Z[ai]}else{am.flashvars=ai+"="+Z[ai]}}}if(F(Y)){var an=u(aj,am,ah);if(aj.id==ah){w(ah,true)}X.success=true;X.ref=an}else{if(aa&&A()){aj.data=aa;P(aj,am,ah,ac);return}else{w(ah,true)}}if(ac){ac(X)}})}else{if(ac){ac(X)}}},switchOffAutoHideShow:function(){m=false},ua:M,getFlashPlayerVersion:function(){return{major:M.pv[0],minor:M.pv[1],release:M.pv[2]}},hasFlashPlayerVersion:F,createSWF:function(Z,Y,X){if(M.w3){return u(Z,Y,X)}else{return undefined}},showExpressInstall:function(Z,aa,X,Y){if(M.w3&&A()){P(Z,aa,X,Y)}},removeSWF:function(X){if(M.w3){y(X)}},createCSS:function(aa,Z,Y,X){if(M.w3){v(aa,Z,Y,X)}},addDomLoadEvent:K,addLoadEvent:s,getQueryParamValue:function(aa){var Z=j.location.search||j.location.hash;if(Z){if(/\?/.test(Z)){Z=Z.split("?")[1]}if(aa==null){return L(Z)}var Y=Z.split("&");for(var X=0;X<Y.length;X++){if(Y[X].substring(0,Y[X].indexOf("="))==aa){return L(Y[X].substring((Y[X].indexOf("=")+1)))}}}return""},expressInstallCallback:function(){if(a){var X=c(R);if(X&&l){X.parentNode.replaceChild(l,X);if(Q){w(Q,true);if(M.ie&&M.win){l.style.display="block"}}if(E){E(B)}}a=false}}}}();
(function(){var requirejs,require,define,__inflate;(function(e){function a(e,t){var n=t&&t.split("/"),i=r.map,s=i&&i["*"]||{},o,u,a,f,l,c,h;if(e&&e.charAt(0)==="."&&t){n=n.slice(0,n.length-1),e=n.concat(e.split("/"));for(l=0;h=e[l];l++)if(h===".")e.splice(l,1),l-=1;else if(h===".."){if(l===1&&(e[2]===".."||e[0]===".."))return!0;l>0&&(e.splice(l-1,2),l-=2)}e=e.join("/")}if((n||s)&&i){o=e.split("/");for(l=o.length;l>0;l-=1){u=o.slice(0,l).join("/");if(n)for(c=n.length;c>0;c-=1){a=i[n.slice(0,c).join("/")];if(a){a=a[u];if(a){f=a;break}}}f=f||s[u];if(f){o.splice(0,l,f),e=o.join("/");break}}}return e}function f(t,n){return function(){return u.apply(e,s.call(arguments,0).concat([t,n]))}}function l(e){return function(t){return a(t,e)}}function c(e){return function(n){t[e]=n}}function h(r){if(n.hasOwnProperty(r)){var s=n[r];delete n[r],i[r]=!0,o.apply(e,s)}if(!t.hasOwnProperty(r))throw new Error("No "+r);return t[r]}function p(e,t){var n,r,i=e.indexOf("!");return i!==-1?(n=a(e.slice(0,i),t),e=e.slice(i+1),r=h(n),r&&r.normalize?e=r.normalize(e,l(t)):e=a(e,t)):e=a(e,t),{f:n?n+"!"+e:e,n:e,p:r}}function d(e){return function(){return r&&r.config&&r.config[e]||{}}}var t={},n={},r={},i={},s=[].slice,o,u;o=function(r,s,o,u){var a=[],l,v,m,g,y,b;u=u||r,typeof o=="string"&&(o=__inflate(r,o));if(typeof o=="function"){s=!s.length&&o.length?["require","exports","module"]:s;for(b=0;b<s.length;b++){y=p(s[b],u),m=y.f;if(m==="require")a[b]=f(r);else if(m==="exports")a[b]=t[r]={},l=!0;else if(m==="module")v=a[b]={id:r,uri:"",exports:t[r],config:d(r)};else if(t.hasOwnProperty(m)||n.hasOwnProperty(m))a[b]=h(m);else if(y.p)y.p.load(y.n,f(u,!0),c(m),{}),a[b]=t[m];else if(!i[m])throw new Error(r+" missing "+m)}g=o.apply(t[r],a);if(r)if(v&&v.exports!==e&&v.exports!==t[r])t[r]=v.exports;else if(g!==e||!l)t[r]=g}else r&&(t[r]=o)},requirejs=require=u=function(t,n,i,s){return typeof t=="string"?h(p(t,n).f):(t.splice||(r=t,n.splice?(t=n,n=i,i=null):t=e),n=n||function(){},s?o(e,t,n,i):setTimeout(function(){o(e,t,n,i)},15),u)},u.config=function(e){return r=e,u},define=function(e,t,r){t.splice||(r=t,t=[]),n[e]=[e,t,r]},define.amd={jQuery:!0}})(),__inflate=function(name,src){var r;return eval(["r = function(a,b,c){","\n};\n//@ sourceURL="+name+"\n"].join(src)),r},define("lib/api/events",["require","exports","module"],function(e,t,n){t.api={LOAD_PROGRESS:"loadProgres",PLAY_PROGRESS:"playProgress",PLAY:"play",PAUSE:"pause",FINISH:"finish",SEEK:"seek",READY:"ready",OPEN_SHARE_PANEL:"sharePanelOpened",SHARE:"share",CLICK_DOWNLOAD:"downloadClicked",CLICK_BUY:"buyClicked"},t.bridge={REMOVE_LISTENER:"removeEventListener",ADD_LISTENER:"addEventListener"}}),define("lib/api/getters",["require","exports","module"],function(e,t,n){n.exports={GET_VOLUME:"getVolume",GET_DURATION:"getDuration",GET_POSITION:"getPosition",GET_SOUNDS:"getSounds",GET_CURRENT_SOUND:"getCurrentSound",GET_CURRENT_SOUND_INDEX:"getCurrentSoundIndex",IS_PAUSED:"isPaused"}}),define("lib/api/setters",["require","exports","module"],function(e,t,n){n.exports={PLAY:"play",PAUSE:"pause",TOGGLE:"toggle",SEEK_TO:"seekTo",SET_VOLUME:"setVolume",NEXT:"next",PREV:"prev",SKIP:"skip"}}),define("lib/api/api",["require","exports","module","lib/api/events","lib/api/getters","lib/api/setters"],function(e,t,n){function m(e){return!!(e===""||e&&e.charCodeAt&&e.substr)}function g(e){return!!(e&&e.constructor&&e.call&&e.apply)}function y(e){return!!e&&e.nodeType===1&&e.nodeName.toUpperCase()==="IFRAME"}function b(e){var t=!1,n;for(n in i)if(i.hasOwnProperty(n)&&i[n]===e){t=!0;break}return t}function w(e){var t,n,r;for(t=0,n=f.length;t<n;t++){r=e(f[t]);if(r===!1)break}}function E(e){var t="",n,r,i;e.substr(0,2)==="//"&&(e=window.location.protocol+e),i=e.split("/");for(n=0,r=i.length;n<r;n++){if(!(n<3))break;t+=i[n],n<2&&(t+="/")}return t}function S(e){return e.contentWindow||e.contentDocument.parentWindow}function x(e){var t=[],n;for(n in e)e.hasOwnProperty(n)&&t.push(e[n]);return t}function T(e,t,n){n.callbacks[e]=n.callbacks[e]||[],n.callbacks[e].push(t)}function N(e,t){var n=!0,r;return t.callbacks[e]=[],w(function(t){r=t.callbacks[e]||[];if(r.length)return n=!1,!1}),n}function C(e,t,n){var r=S(n),i,s;if(!r.postMessage)return!1;i=n.getAttribute("src").split("?")[0],s=JSON.stringify({method:e,value:t}),i.substr(0,2)==="//"&&(i=window.location.protocol+i),i=i.replace(/http:\/\/(w|wt).soundcloud.com/,"https://$1.soundcloud.com"),r.postMessage(s,i)}function k(e){var t;return w(function(n){if(n.instance===e)return t=n,!1}),t}function L(e){var t;return w(function(n){if(S(n.element)===e)return t=n,!1}),t}function A(e,t){return function(n){var r=g(n),i=k(this),s=!r&&t?n:null,o=r&&!t?n:null;return o&&T(e,o,i),C(e,s,i.element),this}}function O(e,t,n){var r,i,s;for(r=0,i=t.length;r<i;r++)s=t[r],e[s]=A(s,n)}function M(e,t,n){return e+"?url="+t+"&"+_(n)}function _(e){var t,n,r=[];for(t in e)e.hasOwnProperty(t)&&(n=e[t],r.push(t+"="+(t==="start_track"?parseInt(n,10):n?"true":"false")));return r.join("&")}function D(e,t,n){var r=e.callbacks[t]||[],i,s;for(i=0,s=r.length;i<s;i++)r[i].apply(e.instance,n);if(b(t)||t===o.READY)e.callbacks[t]=[]}function P(e){var t,n,r,i,s;try{n=JSON.parse(e.data)}catch(u){}t=L(e.source),r=n.method,i=n.value,r===o.READY&&(t?(t.isReady=!0,D(t,l),N(l,t)):a.push(e.source)),r===o.PLAY&&!t.playEventFired&&(t.playEventFired=!0),r===o.PLAY_PROGRESS&&!t.playEventFired&&(t.playEventFired=!0,D(t,o.PLAY,[i]));if(!t||H(e.origin)!==H(t.domain))return!1;s=[],i!==undefined&&s.push(i),D(t,r,s)}function H(e){return e.replace(h,"")}var r=e("lib/api/events"),i=e("lib/api/getters"),s=e("lib/api/setters"),o=r.api,u=r.bridge,a=[],f=[],l="__LATE_BINDING__",c="http://wt.soundcloud.dev:9200/",h=/^http(?:s?)/,p,d,v;window.addEventListener?window.addEventListener("message",P,!1):window.attachEvent("onmessage",P),n.exports=v=function(e,t,n){m(e)&&(e=document.getElementById(e));if(!y(e))throw new Error("SC.Widget function should be given either iframe element or a string specifying id attribute of iframe element.");t&&(n=n||{},e.src=M(c,t,n));var r=L(S(e)),i,s;return r&&r.instance?r.instance:(i=a.indexOf(S(e))>-1,s=new p(e),f.push(new d(s,e,i)),s)},v.Events=o,window.SC=window.SC||{},window.SC.Widget=v,d=function(e,t,n){this.instance=e,this.element=t,this.domain=E(t.getAttribute("src")),this.isReady=!!n,this.callbacks={}},p=function(){},p.prototype={constructor:p,load:function(e,t){if(!e)return;t=t||{};var n=this,r=k(this),i=r.element,s=i.src,a=s.substr(0,s.indexOf("?"));r.isReady=!1,r.playEventFired=!1,i.onload=function(){n.bind(o.READY,function(){var e,n=r.callbacks;for(e in n)n.hasOwnProperty(e)&&e!==o.READY&&C(u.ADD_LISTENER,e,r.element);t.callback&&t.callback()})},i.src=M(a,e,t)},bind:function(e,t){var n=this,r=k(this);return r&&r.element&&(e===o.READY&&r.isReady?setTimeout(t,1):r.isReady?(T(e,t,r),C(u.ADD_LISTENER,e,r.element)):T(l,function(){n.bind(e,t)},r)),this},unbind:function(e){var t=k(this),n;t&&t.element&&(n=N(e,t),e!==o.READY&&n&&C(u.REMOVE_LISTENER,e,t.element))}},O(p.prototype,x(i)),O(p.prototype,x(s),!0)}),window.SC=window.SC||{},window.SC.Widget=require("lib/api/api")})()
var Recorder={swfObject:null,_callbacks:{},_events:{},_initialized:!1,options:{},initialize:function(a){this.options=a||{},this.options.flashContainer||this._setupFlashContainer(),this.bind("initialized",function(){Recorder._initialized=!0,a.initialized()}),this.bind("showFlash",this.options.onFlashSecurity||this._defaultOnShowFlash),this._loadFlash()},clear:function(){Recorder._events={}},record:function(a){a=a||{},this.clearBindings("recordingStart"),this.clearBindings("recordingProgress"),this.clearBindings("recordingCancel"),this.bind("recordingStart",this._defaultOnHideFlash),this.bind("recordingCancel",this._defaultOnHideFlash),this.bind("recordingCancel",this._loadFlash),this.bind("recordingStart",a.start),this.bind("recordingProgress",a.progress),this.bind("recordingCancel",a.cancel),this.flashInterface().record()},stop:function(){return this.flashInterface()._stop()},play:function(a){a=a||{},this.clearBindings("playingProgress"),this.bind("playingProgress",a.progress),this.bind("playingStop",a.finished),this.flashInterface()._play()},upload:function(a){a.audioParam=a.audioParam||"audio",a.params=a.params||{},this.clearBindings("uploadSuccess"),this.bind("uploadSuccess",function(b){a.success(Recorder._externalInterfaceDecode(b))}),this.flashInterface().upload(a.url,a.audioParam,a.params)},audioData:function(){return this.flashInterface().audioData().split(";")},request:function(a,b,c,d,e){var f=this.registerCallback(e);this.flashInterface().request(a,b,c,d,f)},clearBindings:function(a){Recorder._events[a]=[]},bind:function(a,b){Recorder._events[a]||(Recorder._events[a]=[]),Recorder._events[a].push(b)},triggerEvent:function(a,b,c){Recorder._executeInWindowContext(function(){for(var d in Recorder._events[a])Recorder._events[a][d]&&Recorder._events[a][d].apply(Recorder,[b,c])})},triggerCallback:function(a,b){Recorder._executeInWindowContext(function(){Recorder._callbacks[a].apply(null,b)})},registerCallback:function(a){var b="CB"+parseInt(Math.random()*999999,10);return Recorder._callbacks[b]=a,b},flashInterface:function(){if(!this.swfObject)return null;if(this.swfObject.record)return this.swfObject;if(this.swfObject.children[3].record)return this.swfObject.children[3]},_executeInWindowContext:function(a){window.setTimeout(a,1)},_setupFlashContainer:function(){this.options.flashContainer=document.createElement("div"),this.options.flashContainer.setAttribute("id","recorderFlashContainer"),this.options.flashContainer.setAttribute("style","position: fixed; left: -9999px; top: -9999px; width: 230px; height: 140px; margin-left: 10px; border-top: 6px solid rgba(128, 128, 128, 0.6); border-bottom: 6px solid rgba(128, 128, 128, 0.6); border-radius: 5px 5px; padding-bottom: 1px; padding-right: 1px;"),document.body.appendChild(this.options.flashContainer)},_clearFlash:function(){var a=this.options.flashContainer.children[0];a&&this.options.flashContainer.removeChild(a)},_loadFlash:function(){this._clearFlash();var a=document.createElement("div");a.setAttribute("id","recorderFlashObject"),this.options.flashContainer.appendChild(a),swfobject.embedSWF(this.options.swfSrc,"recorderFlashObject","231","141","10.1.0",undefined,undefined,{allowscriptaccess:"always"},undefined,function(a){a.success?(Recorder.swfObject=a.ref,Recorder._checkForFlashBlock()):Recorder._showFlashRequiredDialog()})},_defaultOnShowFlash:function(){var a=Recorder.options.flashContainer;a.style.left=(window.innerWidth||document.body.offsetWidth)/2-115+"px",a.style.top=(window.innerHeight||document.body.offsetHeight)/2-70+"px"},_defaultOnHideFlash:function(){var a=Recorder.options.flashContainer;a.style.left="-9999px",a.style.top="-9999px"},_checkForFlashBlock:function(){window.setTimeout(function(){Recorder._initialized||Recorder.triggerEvent("showFlash")},500)},_showFlashRequiredDialog:function(){Recorder.options.flashContainer.innerHTML="<p>Adobe Flash Player 10.1 or newer is required to use this feature.</p><p><a href='http://get.adobe.com/flashplayer' target='_top'>Get it on Adobe.com.</a></p>",Recorder.options.flashContainer.style.color="white",Recorder.options.flashContainer.style.backgroundColor="#777",Recorder.options.flashContainer.style.textAlign="center",Recorder.triggerEvent("showFlash")},_externalInterfaceDecode:function(a){return a.replace(/%22/g,'"').replace(/%5c/g,"\\").replace(/%26/g,"&").replace(/%25/g,"%")}};if(swfobject==undefined)var swfobject=function(){function A(){if(t)return;try{var a=i.getElementsByTagName("body")[0].appendChild(Q("span"));a.parentNode.removeChild(a)}catch(b){return}t=!0;var c=l.length;for(var d=0;d<c;d++)l[d]()}function B(a){t?a():l[l.length]=a}function C(b){if(typeof h.addEventListener!=a)h.addEventListener("load",b,!1);else if(typeof i.addEventListener!=a)i.addEventListener("load",b,!1);else if(typeof h.attachEvent!=a)R(h,"onload",b);else if(typeof h.onload=="function"){var c=h.onload;h.onload=function(){c(),b()}}else h.onload=b}function D(){k?E():F()}function E(){var c=i.getElementsByTagName("body")[0],d=Q(b);d.setAttribute("type",e);var f=c.appendChild(d);if(f){var g=0;(function(){if(typeof f.GetVariable!=a){var b=f.GetVariable("$version");b&&(b=b.split(" ")[1].split(","),y.pv=[parseInt(b[0],10),parseInt(b[1],10),parseInt(b[2],10)])}else if(g<10){g++,setTimeout(arguments.callee,10);return}c.removeChild(d),f=null,F()})()}else F()}function F(){var b=m.length;if(b>0)for(var c=0;c<b;c++){var d=m[c].id,e=m[c].callbackFn,f={success:!1,id:d};if(y.pv[0]>0){var g=P(d);if(g)if(S(m[c].swfVersion)&&!(y.wk&&y.wk<312))U(d,!0),e&&(f.success=!0,f.ref=G(d),e(f));else if(m[c].expressInstall&&H()){var h={};h.data=m[c].expressInstall,h.width=g.getAttribute("width")||"0",h.height=g.getAttribute("height")||"0",g.getAttribute("class")&&(h.styleclass=g.getAttribute("class")),g.getAttribute("align")&&(h.align=g.getAttribute("align"));var i={},j=g.getElementsByTagName("param"),k=j.length;for(var l=0;l<k;l++)j[l].getAttribute("name").toLowerCase()!="movie"&&(i[j[l].getAttribute("name")]=j[l].getAttribute("value"));I(h,i,d,e)}else J(g),e&&e(f)}else{U(d,!0);if(e){var n=G(d);n&&typeof n.SetVariable!=a&&(f.success=!0,f.ref=n),e(f)}}}}function G(c){var d=null,e=P(c);if(e&&e.nodeName=="OBJECT")if(typeof e.SetVariable!=a)d=e;else{var f=e.getElementsByTagName(b)[0];f&&(d=f)}return d}function H(){return!u&&S("6.0.65")&&(y.win||y.mac)&&!(y.wk&&y.wk<312)}function I(b,c,d,e){u=!0,r=e||null,s={success:!1,id:d};var g=P(d);if(g){g.nodeName=="OBJECT"?(p=K(g),q=null):(p=g,q=d),b.id=f;if(typeof b.width==a||!/%$/.test(b.width)&&parseInt(b.width,10)<310)b.width="310";if(typeof b.height==a||!/%$/.test(b.height)&&parseInt(b.height,10)<137)b.height="137";i.title=i.title.slice(0,47)+" - Flash Player Installation";var j=y.ie&&y.win?"ActiveX":"PlugIn",k="MMredirectURL="+encodeURI(h.location).toString().replace(/&/g,"%26")+"&MMplayerType="+j+"&MMdoctitle="+i.title;typeof c.flashvars!=a?c.flashvars+="&"+k:c.flashvars=k;if(y.ie&&y.win&&g.readyState!=4){var l=Q("div");d+="SWFObjectNew",l.setAttribute("id",d),g.parentNode.insertBefore(l,g),g.style.display="none",function(){g.readyState==4?g.parentNode.removeChild(g):setTimeout(arguments.callee,10)}()}L(b,c,d)}}function J(a){if(y.ie&&y.win&&a.readyState!=4){var b=Q("div");a.parentNode.insertBefore(b,a),b.parentNode.replaceChild(K(a),b),a.style.display="none",function(){a.readyState==4?a.parentNode.removeChild(a):setTimeout(arguments.callee,10)}()}else a.parentNode.replaceChild(K(a),a)}function K(a){var c=Q("div");if(y.win&&y.ie)c.innerHTML=a.innerHTML;else{var d=a.getElementsByTagName(b)[0];if(d){var e=d.childNodes;if(e){var f=e.length;for(var g=0;g<f;g++)(e[g].nodeType!=1||e[g].nodeName!="PARAM")&&e[g].nodeType!=8&&c.appendChild(e[g].cloneNode(!0))}}}return c}function L(c,d,f){var g,h=P(f);if(y.wk&&y.wk<312)return g;if(h){typeof c.id==a&&(c.id=f);if(y.ie&&y.win){var i="";for(var j in c)c[j]!=Object.prototype[j]&&(j.toLowerCase()=="data"?d.movie=c[j]:j.toLowerCase()=="styleclass"?i+=' class="'+c[j]+'"':j.toLowerCase()!="classid"&&(i+=" "+j+'="'+c[j]+'"'));var k="";for(var l in d)d[l]!=Object.prototype[l]&&(k+='<param name="'+l+'" value="'+d[l]+'" />');h.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+i+">"+k+"</object>",n[n.length]=c.id,g=P(c.id)}else{var m=Q(b);m.setAttribute("type",e);for(var o in c)c[o]!=Object.prototype[o]&&(o.toLowerCase()=="styleclass"?m.setAttribute("class",c[o]):o.toLowerCase()!="classid"&&m.setAttribute(o,c[o]));for(var p in d)d[p]!=Object.prototype[p]&&p.toLowerCase()!="movie"&&M(m,p,d[p]);h.parentNode.replaceChild(m,h),g=m}}return g}function M(a,b,c){var d=Q("param");d.setAttribute("name",b),d.setAttribute("value",c),a.appendChild(d)}function N(a){var b=P(a);b&&b.nodeName=="OBJECT"&&(y.ie&&y.win?(b.style.display="none",function(){b.readyState==4?O(a):setTimeout(arguments.callee,10)}()):b.parentNode.removeChild(b))}function O(a){var b=P(a);if(b){for(var c in b)typeof b[c]=="function"&&(b[c]=null);b.parentNode.removeChild(b)}}function P(a){var b=null;try{b=i.getElementById(a)}catch(c){}return b}function Q(a){return i.createElement(a)}function R(a,b,c){a.attachEvent(b,c),o[o.length]=[a,b,c]}function S(a){var b=y.pv,c=a.split(".");return c[0]=parseInt(c[0],10),c[1]=parseInt(c[1],10)||0,c[2]=parseInt(c[2],10)||0,b[0]>c[0]||b[0]==c[0]&&b[1]>c[1]||b[0]==c[0]&&b[1]==c[1]&&b[2]>=c[2]?!0:!1}function T(c,d,e,f){if(y.ie&&y.mac)return;var g=i.getElementsByTagName("head")[0];if(!g)return;var h=e&&typeof e=="string"?e:"screen";f&&(v=null,w=null);if(!v||w!=h){var j=Q("style");j.setAttribute("type","text/css"),j.setAttribute("media",h),v=g.appendChild(j),y.ie&&y.win&&typeof i.styleSheets!=a&&i.styleSheets.length>0&&(v=i.styleSheets[i.styleSheets.length-1]),w=h}y.ie&&y.win?v&&typeof v.addRule==b&&v.addRule(c,d):v&&typeof i.createTextNode!=a&&v.appendChild(i.createTextNode(c+" {"+d+"}"))}function U(a,b){if(!x)return;var c=b?"visible":"hidden";t&&P(a)?P(a).style.visibility=c:T("#"+a,"visibility:"+c)}function V(b){var c=/[\\\"<>\.;]/,d=c.exec(b)!=null;return d&&typeof encodeURIComponent!=a?encodeURIComponent(b):b}var a="undefined",b="object",c="Shockwave Flash",d="ShockwaveFlash.ShockwaveFlash",e="application/x-shockwave-flash",f="SWFObjectExprInst",g="onreadystatechange",h=window,i=document,j=navigator,k=!1,l=[D],m=[],n=[],o=[],p,q,r,s,t=!1,u=!1,v,w,x=!0,y=function(){var f=typeof i.getElementById!=a&&typeof i.getElementsByTagName!=a&&typeof i.createElement!=a,g=j.userAgent.toLowerCase(),l=j.platform.toLowerCase(),m=l?/win/.test(l):/win/.test(g),n=l?/mac/.test(l):/mac/.test(g),o=/webkit/.test(g)?parseFloat(g.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):!1,p=!1,q=[0,0,0],r=null;if(typeof j.plugins!=a&&typeof j.plugins[c]==b)r=j.plugins[c].description,r&&(typeof j.mimeTypes==a||!j.mimeTypes[e]||!!j.mimeTypes[e].enabledPlugin)&&(k=!0,p=!1,r=r.replace(/^.*\s+(\S+\s+\S+$)/,"$1"),q[0]=parseInt(r.replace(/^(.*)\..*$/,"$1"),10),q[1]=parseInt(r.replace(/^.*\.(.*)\s.*$/,"$1"),10),q[2]=/[a-zA-Z]/.test(r)?parseInt(r.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0);else if(typeof h.ActiveXObject!=a)try{var s=new ActiveXObject(d);s&&(r=s.GetVariable("$version"),r&&(p=!0,r=r.split(" ")[1].split(","),q=[parseInt(r[0],10),parseInt(r[1],10),parseInt(r[2],10)]))}catch(t){}return{w3:f,pv:q,wk:o,ie:p,win:m,mac:n}}(),z=function(){if(!y.w3)return;(typeof i.readyState!=a&&i.readyState=="complete"||typeof i.readyState==a&&(i.getElementsByTagName("body")[0]||i.body))&&A(),t||(typeof i.addEventListener!=a&&i.addEventListener("DOMContentLoaded",A,!1),y.ie&&y.win&&(i.attachEvent(g,function(){i.readyState=="complete"&&(i.detachEvent(g,arguments.callee),A())}),h==top&&function(){if(t)return;try{i.documentElement.doScroll("left")}catch(a){setTimeout(arguments.callee,0);return}A()}()),y.wk&&function(){if(t)return;if(!/loaded|complete/.test(i.readyState)){setTimeout(arguments.callee,0);return}A()}(),C(A))}(),W=function(){y.ie&&y.win&&window.attachEvent("onunload",function(){var a=o.length;for(var b=0;b<a;b++)o[b][0].detachEvent(o[b][1],o[b][2]);var c=n.length;for(var d=0;d<c;d++)N(n[d]);for(var e in y)y[e]=null;y=null;for(var f in swfobject)swfobject[f]=null;swfobject=null})}();return{registerObject:function(a,b,c,d){if(y.w3&&a&&b){var e={};e.id=a,e.swfVersion=b,e.expressInstall=c,e.callbackFn=d,m[m.length]=e,U(a,!1)}else d&&d({success:!1,id:a})},getObjectById:function(a){if(y.w3)return G(a)},embedSWF:function(c,d,e,f,g,h,i,j,k,l){var m={success:!1,id:d};y.w3&&!(y.wk&&y.wk<312)&&c&&d&&e&&f&&g?(U(d,!1),B(function(){e+="",f+="";var n={};if(k&&typeof k===b)for(var o in k)n[o]=k[o];n.data=c,n.width=e,n.height=f;var p={};if(j&&typeof j===b)for(var q in j)p[q]=j[q];if(i&&typeof i===b)for(var r in i)typeof p.flashvars!=a?p.flashvars+="&"+r+"="+i[r]:p.flashvars=r+"="+i[r];if(S(g)){var s=L(n,p,d);n.id==d&&U(d,!0),m.success=!0,m.ref=s}else{if(h&&H()){n.data=h,I(n,p,d,l);return}U(d,!0)}l&&l(m)})):l&&l(m)},switchOffAutoHideShow:function(){x=!1},ua:y,getFlashPlayerVersion:function(){return{major:y.pv[0],minor:y.pv[1],release:y.pv[2]}},hasFlashPlayerVersion:S,createSWF:function(a,b,c){return y.w3?L(a,b,c):undefined},showExpressInstall:function(a,b,c,d){y.w3&&H()&&I(a,b,c,d)},removeSWF:function(a){y.w3&&N(a)},createCSS:function(a,b,c,d){y.w3&&T(a,b,c,d)},addDomLoadEvent:B,addLoadEvent:C,getQueryParamValue:function(a){var b=i.location.search||i.location.hash;if(b){/\?/.test(b)&&(b=b.split("?")[1]);if(a==null)return V(b);var c=b.split("&");for(var d=0;d<c.length;d++)if(c[d].substring(0,c[d].indexOf("="))==a)return V(c[d].substring(c[d].indexOf("=")+1))}return""},expressInstallCallback:function(){if(u){var a=P(f);a&&p&&(a.parentNode.replaceChild(p,a),q&&(U(q,!0),y.ie&&y.win&&(p.style.display="block")),r&&r(s)),u=!1}}}}();var __hasProp=Object.prototype.hasOwnProperty;window.SC=window.SC||{},window.SC.URI=function(a,b){var c,d;return a==null&&(a=""),b==null&&(b={}),d=/^(?:([^:\/?\#]+):)?(?:\/\/([^\/?\#]*))?([^?\#]*)(?:\?([^\#]*))?(?:\#(.*))?/,c=/^(?:([^@]*)@)?([^:]*)(?::(\d*))?/,this.scheme=this.user=this.password=this.host=this.port=this.path=this.query=this.fragment=null,this.toString=function(){var a;return a="",this.isAbsolute()&&(a+=this.scheme,a+="://",this.user!=null&&(a+=this.user+":"+this.password+"@"),a+=this.host,this.port!=null&&(a+=":"+this.port)),a+=this.path,this.path===""&&(this.query!=null||this.fragment!=null)&&(a+="/"),this.query!=null&&(a+=this.encodeParamsWithPrepend(this.query,"?")),this.fragment!=null&&(a+=this.encodeParamsWithPrepend(this.fragment,"#")),a},this.isRelative=function(){return!this.isAbsolute()},this.isAbsolute=function(){return this.host!=null},this.decodeParams=function(a){var b,c,d,e,f,g,h,i;a==null&&(a=""),c={},i=a.split("&");for(g=0,h=i.length;g<h;g++)d=i[g],d!==""&&(e=d.split("="),b=decodeURIComponent(e[0]),f=decodeURIComponent(e[1]||"").replace(/\+/g," "),this.normalizeParams(c,b,f));return c},this.normalizeParams=function(a,b,c){var d,e,f,g,h,i;return c==null&&(c=NULL),h=b.match(/^[\[\]]*([^\[\]]+)\]*(.*)/),f=h[1]||"",d=h[2]||"",d===""?a[f]=c:d==="[]"?(a[f]||(a[f]=[]),a[f].push(c)):(i=d.match(/^\[\]\[([^\[\]]+)\]$/)||(i=d.match(/^\[\](.+)$/)))?(e=i[1],a[f]||(a[f]=[]),g=a[f][a[f].length-1],g!=null&&g.constructor===Object&&g[e]==null?this.normalizeParams(g,e,c):a[f].push(this.normalizeParams({},e,c))):(a[f]||(a[f]={}),a[f]=this.normalizeParams(a[f],d,c)),a},this.encodeParamsWithPrepend=function(a,b){var c;return c=this.encodeParams(a),c!==""?b+c:""},this.encodeParams=function(a){var b,c,d,e,f,g,h,i;f="";if(a.constructor===String)return f=a;b=this.flattenParams(a),d=[];for(h=0,i=b.length;h<i;h++)e=b[h],c=e[0],g=e[1],g===null?d.push(c):d.push(c+"="+encodeURIComponent(g));return f=d.join("&")},this.flattenParams=function(a,b,c){var d,e,f,g,h;b==null&&(b=""),c==null&&(c=[]);if(a==null)b!=null&&c.push([b,null]);else if(a.constructor===Object)for(d in a){if(!__hasProp.call(a,d))continue;f=a[d],b!==""?e=b+"["+d+"]":e=d,this.flattenParams(f,e,c)}else if(a.constructor===Array)for(g=0,h=a.length;g<h;g++)f=a[g],this.flattenParams(f,b+"[]",c);else b!==""&&c.push([b,a]);return c},this.parse=function(a,b){var e,f,g,h,i;a==null&&(a=""),b==null&&(b={}),g=function(a){return a===""?null:a},h=a.match(d),this.scheme=g(h[1]),e=h[2],e!=null&&(f=e.match(c),i=g(f[1]),i!=null&&(this.user=i.split(":")[0],this.password=i.split(":")[1]),this.host=g(f[2]),this.port=parseInt(f[3],10)||null),this.path=h[3],this.query=g(h[4]),b.decodeQuery&&(this.query=this.decodeParams(this.query)),this.fragment=g(h[5]);if(b.decodeFragment)return this.fragment=this.decodeParams(this.fragment)},this.parse(a.toString(),b),this},function(){var AbstractDialog,ConnectDialog,EchoDialog,PickerDialog,__hasProp={}.hasOwnProperty,__extends=function(a,b){function d(){this.constructor=a}for(var c in b)__hasProp.call(b,c)&&(a[c]=b[c]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};window.SC||(window.SC={}),SC.Helper={merge:function(a,b){var c,d,e,f,g;if(a.constructor===Array){d=Array.apply(null,a);for(f=0,g=b.length;f<g;f++)e=b[f],d.push(e);return d}d={};for(c in a){if(!__hasProp.call(a,c))continue;e=a[c],d[c]=e}for(c in b){if(!__hasProp.call(b,c))continue;e=b[c],d[c]=e}return d},groupBy:function(a,b){var c,d,e,f,g,h;c={};for(f=0,g=a.length;f<g;f++){d=a[f];if(e=d[b])c[h=d[b]]||(c[h]=[]),c[d[b]].push(d)}return c},loadJavascript:function(a,b){var c;return c=document.createElement("script"),c.async=!0,c.src=a,SC.Helper.attachLoadEvent(c,b),document.body.appendChild(c),c},extractOptionsAndCallbackArguments:function(a,b){var c;return c={},b!=null?(c.callback=b,c.options=a):typeof a=="function"?(c.callback=a,c.options={}):c.options=a||{},c},openCenteredPopup:function(a,b,c){var d;return d={},c!=null?(d.width=b,d.height=c):d=b,d=SC.Helper.merge(d,{location:1,left:window.screenX+(window.outerWidth-d.width)/2,top:window.screenY+(window.outerHeight-d.height)/2,toolbar:"no",scrollbars:"yes"}),window.open(a,d.name,this._optionsToString(d))},_optionsToString:function(a){var b,c,d;c=[];for(b in a){if(!__hasProp.call(a,b))continue;d=a[b],c.push(b+"="+d)}return c.join(", ")},attachLoadEvent:function(a,b){return a.addEventListener?a.addEventListener("load",b,!1):a.onreadystatechange=function(){if(this.readyState==="complete")return b()}},millisecondsToHMS:function(a){var b,c,d,e,f;return b={h:Math.floor(a/36e5),m:Math.floor(a/6e4%60),s:Math.floor(a/1e3%60)},f=[],b.h>0&&f.push(b.h),c=b.m,d="",e="",b.m<10&&b.h>0&&(d="0"),b.s<10&&(e="0"),f.push(d+b.m),f.push(e+b.s),f.join(".")},setFlashStatusCodeMaps:function(a){return a["_status_code_map[400]"]=200,a["_status_code_map[401]"]=200,a["_status_code_map[403]"]=200,a["_status_code_map[404]"]=200,a["_status_code_map[422]"]=200,a["_status_code_map[500]"]=200,a["_status_code_map[503]"]=200,a["_status_code_map[504]"]=200},responseHandler:function(a,b){var c,d;return d=SC.Helper.JSON.parse(a),c=null,d?d.errors&&(c={message:d.errors&&d.errors[0].error_message}):b?c={message:"HTTP Error: "+b.status}:c={message:"Unknown error"},{json:d,error:c}},FakeStorage:function(){return{_store:{},getItem:function(a){return this._store[a]||null},setItem:function(a,b){return this._store[a]=b.toString()},removeItem:function(a){return delete this._store[a]}}},JSON:{parse:function(string){return string[0]!=="{"&&string[0]!=="["?null:window.JSON!=null?window.JSON.parse(string):eval(string)}}},window.SC=SC.Helper.merge(SC||{},{_version:"1.1.5",_baseUrl:"//connect.soundcloud.com",options:{site:"soundcloud.com",baseUrl:"//connect.soundcloud.com"},connectCallbacks:{},_popupWindow:void 0,initialize:function(a){var b,c,d;a==null&&(a={}),this.accessToken(a.access_token);for(b in a){if(!__hasProp.call(a,b))continue;c=a[b],this.options[b]=c}return(d=this.options).flashXHR||(d.flashXHR=(new XMLHttpRequest).withCredentials===void 0),this},hostname:function(a){var b;return b="",a!=null&&(b+=a+"."),b+=this.options.site,b}}),window.SC=SC.Helper.merge(SC||{},{_apiRequest:function(a,b,c,d){var e,f;d==null&&(d=c,c=void 0),c||(c={}),f=SC.prepareRequestURI(b,c),f.query.format="json",SC.options.flashXHR?SC.Helper.setFlashStatusCodeMaps(f.query):f.query["_status_code_map[302]"]=200;if(a==="PUT"||a==="DELETE")f.query._method=a,a="POST";return a!=="GET"&&(e=f.encodeParams(f.query),f.query={}),this._request(a,f,"application/x-www-form-urlencoded",e,function(a,b){var c;return c=SC.Helper.responseHandler(a,b),c.json&&c.json.status==="302 - Found"?SC._apiRequest("GET",c.json.location,d):d(c.json,c.error)})},_request:function(a,b,c,d,e){return SC.options.flashXHR?this._flashRequest(a,b,c,d,e):this._xhrRequest(a,b,c,d,e)},_xhrRequest:function(a,b,c,d,e){var f;return f=new XMLHttpRequest,f.open(a,b.toString(),!0),f.setRequestHeader("Content-Type",c),f.onreadystatechange=function(a){if(a.target.readyState===4)return e(a.target.responseText,a.target)},f.send(d)},_flashRequest:function(a,b,c,d,e){return this.whenRecordingReady(function(){return Recorder.request(a,b.toString(),c,d,function(a,b){return e(Recorder._externalInterfaceDecode(a),b)})})},post:function(a,b,c){return this._apiRequest("POST",a,b,c)},put:function(a,b,c){return this._apiRequest("PUT",a,b,c)},get:function(a,b,c){return this._apiRequest("GET",a,b,c)},"delete":function(a,b){return this._apiRequest("DELETE",a,{},b)},prepareRequestURI:function(a,b){var c,d,e;b==null&&(b={}),d=new SC.URI(a,{decodeQuery:!0});for(c in b){if(!__hasProp.call(b,c))continue;e=b[c],d.query[c]=e}return d.isRelative()&&(d.host=this.hostname("api"),d.scheme="http"),this.accessToken()!=null?(d.query.oauth_token=this.accessToken(),d.scheme="https"):d.query.client_id=this.options.client_id,d},_getAll:function(a,b,c,d){return d==null&&(d=[]),c==null&&(c=b,b=void 0),b||(b={}),b.offset||(b.offset=0),b.limit||(b.limit=50),this.get(a,b,function(e,f){return e.constructor===Array&&e.length>0?(d=SC.Helper.merge(d,e),b.offset+=b.limit,SC._getAll(a,b,c,d)):c(d,null)})}}),window.SC=SC.Helper.merge(SC||{},{_connectWindow:null,connect:function(a){var b,c,d;typeof a=="function"?d={connected:a}:d=a,c={client_id:d.client_id||SC.options.client_id,redirect_uri:d.redirect_uri||SC.options.redirect_uri,response_type:"code_and_token",scope:d.scope||"non-expiring",display:"popup",window:d.window,retainWindow:d.retainWindow};if(c.client_id&&c.redirect_uri)return b=SC.dialog(SC.Dialog.CONNECT,c,function(a){if(a.error!=null)throw new Error("SC OAuth2 Error: "+a.error_description);SC.accessToken(a.access_token),d.connected!=null&&d.connected();if(d.callback!=null)return d.callback()}),this._connectWindow=b.options.window,b;throw"Options client_id and redirect_uri must be passed"},connectCallback:function(){return SC.Dialog._handleDialogReturn(SC._connectWindow)},disconnect:function(){return this.accessToken(null)},_trigger:function(a,b){if(this.connectCallbacks[a]!=null)return this.connectCallbacks[a](b)},accessToken:function(a){var b,c;return c="SC.accessToken",b=this.storage(),a===void 0?b.getItem(c):a===null?b.removeItem(c):b.setItem(c,a)},isConnected:function(){return this.accessToken()!=null}}),window.SC=SC.Helper.merge(SC||{},{_dialogsPath:"/dialogs",dialog:function(a,b,c){var d,e,f;return d=SC.Helper.extractOptionsAndCallbackArguments(b,c),f=d.options,c=d.callback,f.callback=c,f.redirect_uri=this.options.redirect_uri,e=new SC.Dialog[a+"Dialog"](f),SC.Dialog._dialogs[e.id]=e,e.open(),e},Dialog:{ECHO:"Echo",CONNECT:"Connect",PICKER:"Picker",ID_PREFIX:"SoundCloud_Dialog",_dialogs:{},_isDialogId:function(a){return(a||"").match(new RegExp("^"+this.ID_PREFIX))},_getDialogIdFromWindow:function(a){var b,c;return c=new SC.URI(a.location,{decodeQuery:!0,decodeFragment:!0}),b=c.query.state||c.fragment.state,this._isDialogId(b)?b:null},_handleDialogReturn:function(a){var b,c;c=this._getDialogIdFromWindow(a),b=this._dialogs[c];if(b!=null&&b.handleReturn())return delete this._dialogs[c]},_handleInPopupContext:function(){var a;if(this._getDialogIdFromWindow(window)&&!window.location.pathname.match(/\/dialogs\//)){a=navigator.userAgent.match(/OS 5(_\d)+ like Mac OS X/i);if(a)return window.opener.SC.Dialog._handleDialogReturn(window);if(window.opener)return window.opener.setTimeout(function(){return window.opener.SC.Dialog._handleDialogReturn(window)},1);if(window.top)return window.top.setTimeout(function(){return window.top.SC.Dialog._handleDialogReturn(window)},1)}},AbstractDialog:AbstractDialog=function(){function a(a){this.options=a!=null?a:{},this.id=this.generateId()}return a.prototype.WIDTH=456,a.prototype.HEIGHT=510,a.prototype.ID_PREFIX="SoundCloud_Dialog",a.prototype.PARAM_KEYS=["redirect_uri"],a.prototype.requiresAuthentication=!1,a.prototype.generateId=function(){return[this.ID_PREFIX,Math.ceil(Math.random()*1e6).toString(16)].join("_")},a.prototype.buildURI=function(a){var b,c,d,e;a==null&&(a=new SC.URI(SC._baseUrl)),a.scheme="http",a.path+=SC._dialogsPath+"/"+this.name+"/",a.fragment={state:this.id},this.requiresAuthentication&&(a.fragment.access_token=SC.accessToken()),e=this.PARAM_KEYS;for(c=0,d=e.length;c<d;c++)b=e[c],this.options[b]!=null&&(a.fragment[b]=this.options[b]);return a},a.prototype.open=function(){var a;return this.requiresAuthentication&&SC.accessToken()==null?this.authenticateAndOpen():(a=this.buildURI(),this.options.window!=null?this.options.window.location=a:this.options.window=SC.Helper.openCenteredPopup(a,{width:this.WIDTH,height:this.HEIGHT}))},a.prototype.authenticateAndOpen=function(){var a,b=this;return a=SC.connect({retainWindow:!0,window:this.options.window,connected:function(){return b.options.window=a.options.window,b.open()}})},a.prototype.paramsFromWindow=function(){var a,b;return b=new SC.URI(this.options.window.location,{decodeFragment:!0,decodeQuery:!0}),a=SC.Helper.merge(b.query,b.fragment)},a.prototype.handleReturn=function(){var a;return a=this.paramsFromWindow(),this.options.retainWindow||this.options.window.close(),this.options.callback(a)},a}(),EchoDialog:EchoDialog=function(a){function b(){return b.__super__.constructor.apply(this,arguments)}return __extends(b,a),b.prototype.PARAM_KEYS=["client_id","redirect_uri","hello"],b.prototype.name="echo",b}(AbstractDialog),PickerDialog:PickerDialog=function(a){function b(){return b.__super__.constructor.apply(this,arguments)}return __extends(b,a),b.prototype.PARAM_KEYS=["client_id","redirect_uri"],b.prototype.name="picker",b.prototype.requiresAuthentication=!0,b.prototype.handleReturn=function(){var a,b=this;a=this.paramsFromWindow();if(a.action==="logout")return SC.accessToken(null),this.open(),!1;if(a.track_uri!=null)return this.options.retainWindow||this.options.window.close(),SC.get(a.track_uri,function(a){return b.options.callback({track:a})}),!0},b}(AbstractDialog),ConnectDialog:ConnectDialog=function(a){function b(){return b.__super__.constructor.apply(this,arguments)}return __extends(b,a),b.prototype.PARAM_KEYS=["client_id","redirect_uri","client_secret","response_type","scope","display"],b.prototype.name="connect",b.prototype.buildURI=function(){var a;return a=b.__super__.buildURI.apply(this,arguments),a.scheme="https",a.host="soundcloud.com",a.path="/connect",a.query=a.fragment,a.fragment={},a},b}(AbstractDialog)}}),SC.Dialog._handleInPopupContext(),window.SC=SC.Helper.merge(SC||{},{Loader:{States:{UNLOADED:1,LOADING:2,READY:3},Package:function(a,b){return{name:a,callbacks:[],loadFunction:b,state:SC.Loader.States.UNLOADED,addCallback:function(a){return this.callbacks.push(a)},runCallbacks:function(){var a,b,c,d;d=this.callbacks;for(b=0,c=d.length;b<c;b++)a=d[b],a.apply(this);return this.callbacks=[]},setReady:function(){return this.state=SC.Loader.States.READY,this.runCallbacks()},load:function(){return this.state=SC.Loader.States.LOADING,this.loadFunction.apply(this)},whenReady:function(a){switch(this.state){case SC.Loader.States.UNLOADED:return this.addCallback(a),this.load();case SC.Loader.States.LOADING:return this.addCallback(a);case SC.Loader.States.READY:return a()}}}},packages:{},registerPackage:function(a){return this.packages[a.name]=a}}}),window.SC=SC.Helper.merge(SC||{},{oEmbed:function(a,b,c){var d,e,f=this;return c==null&&(c=b,b=void 0),b||(b={}),b.url=a,e=new SC.URI("http://"+SC.hostname()+"/oembed.json"),e.query=b,c.nodeType!==void 0&&c.nodeType===1&&(d=c,c=function(a){return d.innerHTML=a.html}),this._request("GET",e.toString(),null,null,function(a,b){var d;return d=SC.Helper.responseHandler(a,b),c(d.json,d.error)})}}),window.SC=SC.Helper.merge(SC||{},{_recorderSwfPath:"/recorder.js/recorder-0.9.0.swf",whenRecordingReady:function(a){return SC.Loader.packages.recording.whenReady(a)},record:function(a){return a==null&&(a={}),this.whenRecordingReady(function(){return Recorder.record(a)})},recordStop:function(a){return a==null&&(a={}),Recorder.stop()},recordPlay:function(a){return a==null&&(a={}),Recorder.play(a)},recordUpload:function(a,b){var c,d;return a==null&&(a={}),d=SC.prepareRequestURI("/tracks",a),d.query.format="json",SC.Helper.setFlashStatusCodeMaps(d.query),c=d.flattenParams(d.query),Recorder.upload({method:"POST",url:"https://"+this.hostname("api")+"/tracks",audioParam:"track[asset_data]",params:c,success:function(a){var c;return c=SC.Helper.responseHandler(a),b(c.json,c.error)}})}}),SC.Loader.registerPackage(new SC.Loader.Package("recording",function(){return Recorder.flashInterface()?SC.Loader.packages.recording.setReady():Recorder.initialize({swfSrc:SC._baseUrl+SC._recorderSwfPath+"?"+SC._version,initialized:function(){return SC.Loader.packages.recording.setReady()}})})),window.SC=SC.Helper.merge(SC||{},{storage:function(){return this._fakeStorage||(this._fakeStorage=new SC.Helper.FakeStorage)}}),window.SC=SC.Helper.merge(SC||{},{_soundmanagerPath:"/soundmanager2",_soundmanagerScriptPath:"/soundmanager2.js",whenStreamingReady:function(a){return SC.Loader.packages.streaming.whenReady(a)},_prepareStreamUrl:function(a){var b,c;return a.toString().match(/^\d.*$/)?c="/tracks/"+a:c=a,b=SC.prepareRequestURI(c),b.path.match(/\/stream/)||(b.path+="/stream"),b.toString()},_setOnPositionListenersForComments:function(a,b,c){var d,e,f,g;e=SC.Helper.groupBy(b,"timestamp"),g=[];for(f in e)d=e[f],g.push(function(b,c,d){return a.onposition(parseInt(b,10),function(){return d(c)})}(f,d,c));return g},stream:function(a,b,c){var d,e,f=this;return d=SC.Helper.extractOptionsAndCallbackArguments(b,c),e=d.options,c=d.callback,SC.whenStreamingReady(function(){var b,d;return e.id="T"+a+"-"+Math.random(),e.url=f._prepareStreamUrl(a),b=function(a){var b;return b=soundManager.createSound(a),c!=null&&c(b),b},(d=e.ontimedcomments)?(delete e.ontimedcomments,SC._getAll(e.url.replace("/stream","/comments"),function(a){var c;return c=b(e),f._setOnPositionListenersForComments(c,a,d)})):b(e)})},streamStopAll:function(){if(window.soundManager!=null)return window.soundManager.stopAll()}}),SC.Loader.registerPackage(new SC.Loader.Package("streaming",function(){var a;return window.soundManager!=null?SC.Loader.packages.streaming.setReady():(a=SC._baseUrl+SC._soundmanagerPath,window.SM2_DEFER=!0,SC.Helper.loadJavascript(a+SC._soundmanagerScriptPath,function(){return window.soundManager=new SoundManager,soundManager.url=a,soundManager.flashVersion=9,soundManager.useFlashBlock=!1,soundManager.useHTML5Audio=!1,soundManager.beginDelayedInit(),soundManager.onready(function(){return SC.Loader.packages.streaming.setReady()})}))}))}.call(this);
/*
 * jPlayer Plugin for jQuery JavaScript Library
 * http://www.jplayer.org
 *
 * Copyright (c) 2009 - 2012 Happyworm Ltd
 * Dual licensed under the MIT and GPL licenses.
 *  - http://www.opensource.org/licenses/mit-license.php
 *  - http://www.gnu.org/copyleft/gpl.html
 *
 * Author: Mark J Panaghiston
 * Version: 2.2.0
 * Date: 13th September 2012
 */

(function(b,f){b.fn.jPlayer=function(a){var c="string"===typeof a,d=Array.prototype.slice.call(arguments,1),e=this,a=!c&&d.length?b.extend.apply(null,[!0,a].concat(d)):a;if(c&&"_"===a.charAt(0))return e;c?this.each(function(){var c=b.data(this,"jPlayer"),h=c&&b.isFunction(c[a])?c[a].apply(c,d):c;if(h!==c&&h!==f)return e=h,!1}):this.each(function(){var c=b.data(this,"jPlayer");c?c.option(a||{}):b.data(this,"jPlayer",new b.jPlayer(a,this))});return e};b.jPlayer=function(a,c){if(arguments.length){this.element=
b(c);this.options=b.extend(!0,{},this.options,a);var d=this;this.element.bind("remove.jPlayer",function(){d.destroy()});this._init()}};b.jPlayer.emulateMethods="load play pause";b.jPlayer.emulateStatus="src readyState networkState currentTime duration paused ended playbackRate";b.jPlayer.emulateOptions="muted volume";b.jPlayer.reservedEvent="ready flashreset resize repeat error warning";b.jPlayer.event={ready:"jPlayer_ready",flashreset:"jPlayer_flashreset",resize:"jPlayer_resize",repeat:"jPlayer_repeat",
click:"jPlayer_click",error:"jPlayer_error",warning:"jPlayer_warning",loadstart:"jPlayer_loadstart",progress:"jPlayer_progress",suspend:"jPlayer_suspend",abort:"jPlayer_abort",emptied:"jPlayer_emptied",stalled:"jPlayer_stalled",play:"jPlayer_play",pause:"jPlayer_pause",loadedmetadata:"jPlayer_loadedmetadata",loadeddata:"jPlayer_loadeddata",waiting:"jPlayer_waiting",playing:"jPlayer_playing",canplay:"jPlayer_canplay",canplaythrough:"jPlayer_canplaythrough",seeking:"jPlayer_seeking",seeked:"jPlayer_seeked",
timeupdate:"jPlayer_timeupdate",ended:"jPlayer_ended",ratechange:"jPlayer_ratechange",durationchange:"jPlayer_durationchange",volumechange:"jPlayer_volumechange"};b.jPlayer.htmlEvent="loadstart abort emptied stalled loadedmetadata loadeddata canplay canplaythrough ratechange".split(" ");b.jPlayer.pause=function(){b.each(b.jPlayer.prototype.instances,function(a,c){c.data("jPlayer").status.srcSet&&c.jPlayer("pause")})};b.jPlayer.timeFormat={showHour:!1,showMin:!0,showSec:!0,padHour:!1,padMin:!0,padSec:!0,
sepHour:":",sepMin:":",sepSec:""};b.jPlayer.convertTime=function(a){var c=new Date(1E3*a),d=c.getUTCHours(),a=c.getUTCMinutes(),c=c.getUTCSeconds(),d=b.jPlayer.timeFormat.padHour&&10>d?"0"+d:d,a=b.jPlayer.timeFormat.padMin&&10>a?"0"+a:a,c=b.jPlayer.timeFormat.padSec&&10>c?"0"+c:c;return(b.jPlayer.timeFormat.showHour?d+b.jPlayer.timeFormat.sepHour:"")+(b.jPlayer.timeFormat.showMin?a+b.jPlayer.timeFormat.sepMin:"")+(b.jPlayer.timeFormat.showSec?c+b.jPlayer.timeFormat.sepSec:"")};b.jPlayer.uaBrowser=
function(a){var a=a.toLowerCase(),c=/(opera)(?:.*version)?[ \/]([\w.]+)/,b=/(msie) ([\w.]+)/,e=/(mozilla)(?:.*? rv:([\w.]+))?/,a=/(webkit)[ \/]([\w.]+)/.exec(a)||c.exec(a)||b.exec(a)||0>a.indexOf("compatible")&&e.exec(a)||[];return{browser:a[1]||"",version:a[2]||"0"}};b.jPlayer.uaPlatform=function(a){var b=a.toLowerCase(),d=/(android)/,e=/(mobile)/,a=/(ipad|iphone|ipod|android|blackberry|playbook|windows ce|webos)/.exec(b)||[],b=/(ipad|playbook)/.exec(b)||!e.exec(b)&&d.exec(b)||[];a[1]&&(a[1]=a[1].replace(/\s/g,
"_"));return{platform:a[1]||"",tablet:b[1]||""}};b.jPlayer.browser={};b.jPlayer.platform={};var i=b.jPlayer.uaBrowser(navigator.userAgent);i.browser&&(b.jPlayer.browser[i.browser]=!0,b.jPlayer.browser.version=i.version);i=b.jPlayer.uaPlatform(navigator.userAgent);i.platform&&(b.jPlayer.platform[i.platform]=!0,b.jPlayer.platform.mobile=!i.tablet,b.jPlayer.platform.tablet=!!i.tablet);b.jPlayer.prototype={count:0,version:{script:"2.2.0",needFlash:"2.2.0",flash:"unknown"},options:{swfPath:"js",solution:"html, flash",
supplied:"mp3",preload:"metadata",volume:0.8,muted:!1,wmode:"opaque",backgroundColor:"#000000",cssSelectorAncestor:"#jp_container_1",cssSelector:{videoPlay:".jp-video-play",play:".jp-play",pause:".jp-pause",stop:".jp-stop",seekBar:".jp-seek-bar",playBar:".jp-play-bar",mute:".jp-mute",unmute:".jp-unmute",volumeBar:".jp-volume-bar",volumeBarValue:".jp-volume-bar-value",volumeMax:".jp-volume-max",currentTime:".jp-current-time",duration:".jp-duration",fullScreen:".jp-full-screen",restoreScreen:".jp-restore-screen",
repeat:".jp-repeat",repeatOff:".jp-repeat-off",gui:".jp-gui",noSolution:".jp-no-solution"},fullScreen:!1,autohide:{restored:!1,full:!0,fadeIn:200,fadeOut:600,hold:1E3},loop:!1,repeat:function(a){a.jPlayer.options.loop?b(this).unbind(".jPlayerRepeat").bind(b.jPlayer.event.ended+".jPlayer.jPlayerRepeat",function(){b(this).jPlayer("play")}):b(this).unbind(".jPlayerRepeat")},nativeVideoControls:{},noFullScreen:{msie:/msie [0-6]/,ipad:/ipad.*?os [0-4]/,iphone:/iphone/,ipod:/ipod/,android_pad:/android [0-3](?!.*?mobile)/,
android_phone:/android.*?mobile/,blackberry:/blackberry/,windows_ce:/windows ce/,webos:/webos/},noVolume:{ipad:/ipad/,iphone:/iphone/,ipod:/ipod/,android_pad:/android(?!.*?mobile)/,android_phone:/android.*?mobile/,blackberry:/blackberry/,windows_ce:/windows ce/,webos:/webos/,playbook:/playbook/},verticalVolume:!1,idPrefix:"jp",noConflict:"jQuery",emulateHtml:!1,errorAlerts:!1,warningAlerts:!1},optionsAudio:{size:{width:"0px",height:"0px",cssClass:""},sizeFull:{width:"0px",height:"0px",cssClass:""}},
optionsVideo:{size:{width:"480px",height:"270px",cssClass:"jp-video-270p"},sizeFull:{width:"100%",height:"100%",cssClass:"jp-video-full"}},instances:{},status:{src:"",media:{},paused:!0,format:{},formatType:"",waitForPlay:!0,waitForLoad:!0,srcSet:!1,video:!1,seekPercent:0,currentPercentRelative:0,currentPercentAbsolute:0,currentTime:0,duration:0,readyState:0,networkState:0,playbackRate:1,ended:0},internal:{ready:!1},solution:{html:!0,flash:!0},format:{mp3:{codec:'audio/mpeg; codecs="mp3"',flashCanPlay:!0,
media:"audio"},m4a:{codec:'audio/mp4; codecs="mp4a.40.2"',flashCanPlay:!0,media:"audio"},oga:{codec:'audio/ogg; codecs="vorbis"',flashCanPlay:!1,media:"audio"},wav:{codec:'audio/wav; codecs="1"',flashCanPlay:!1,media:"audio"},webma:{codec:'audio/webm; codecs="vorbis"',flashCanPlay:!1,media:"audio"},fla:{codec:"audio/x-flv",flashCanPlay:!0,media:"audio"},rtmpa:{codec:'audio/rtmp; codecs="rtmp"',flashCanPlay:!0,media:"audio"},m4v:{codec:'video/mp4; codecs="avc1.42E01E, mp4a.40.2"',flashCanPlay:!0,media:"video"},
ogv:{codec:'video/ogg; codecs="theora, vorbis"',flashCanPlay:!1,media:"video"},webmv:{codec:'video/webm; codecs="vorbis, vp8"',flashCanPlay:!1,media:"video"},flv:{codec:"video/x-flv",flashCanPlay:!0,media:"video"},rtmpv:{codec:'video/rtmp; codecs="rtmp"',flashCanPlay:!0,media:"video"}},_init:function(){var a=this;this.element.empty();this.status=b.extend({},this.status);this.internal=b.extend({},this.internal);this.internal.domNode=this.element.get(0);this.formats=[];this.solutions=[];this.require=
{};this.htmlElement={};this.html={};this.html.audio={};this.html.video={};this.flash={};this.css={};this.css.cs={};this.css.jq={};this.ancestorJq=[];this.options.volume=this._limitValue(this.options.volume,0,1);b.each(this.options.supplied.toLowerCase().split(","),function(c,d){var e=d.replace(/^\s+|\s+$/g,"");if(a.format[e]){var f=false;b.each(a.formats,function(a,b){if(e===b){f=true;return false}});f||a.formats.push(e)}});b.each(this.options.solution.toLowerCase().split(","),function(c,d){var e=
d.replace(/^\s+|\s+$/g,"");if(a.solution[e]){var f=false;b.each(a.solutions,function(a,b){if(e===b){f=true;return false}});f||a.solutions.push(e)}});this.internal.instance="jp_"+this.count;this.instances[this.internal.instance]=this.element;this.element.attr("id")||this.element.attr("id",this.options.idPrefix+"_jplayer_"+this.count);this.internal.self=b.extend({},{id:this.element.attr("id"),jq:this.element});this.internal.audio=b.extend({},{id:this.options.idPrefix+"_audio_"+this.count,jq:f});this.internal.video=
b.extend({},{id:this.options.idPrefix+"_video_"+this.count,jq:f});this.internal.flash=b.extend({},{id:this.options.idPrefix+"_flash_"+this.count,jq:f,swf:this.options.swfPath+(this.options.swfPath.toLowerCase().slice(-4)!==".swf"?(this.options.swfPath&&this.options.swfPath.slice(-1)!=="/"?"/":"")+"Jplayer.swf":"")});this.internal.poster=b.extend({},{id:this.options.idPrefix+"_poster_"+this.count,jq:f});b.each(b.jPlayer.event,function(b,c){if(a.options[b]!==f){a.element.bind(c+".jPlayer",a.options[b]);
a.options[b]=f}});this.require.audio=false;this.require.video=false;b.each(this.formats,function(b,c){a.require[a.format[c].media]=true});this.options=this.require.video?b.extend(true,{},this.optionsVideo,this.options):b.extend(true,{},this.optionsAudio,this.options);this._setSize();this.status.nativeVideoControls=this._uaBlocklist(this.options.nativeVideoControls);this.status.noFullScreen=this._uaBlocklist(this.options.noFullScreen);this.status.noVolume=this._uaBlocklist(this.options.noVolume);this._restrictNativeVideoControls();
this.htmlElement.poster=document.createElement("img");this.htmlElement.poster.id=this.internal.poster.id;this.htmlElement.poster.onload=function(){(!a.status.video||a.status.waitForPlay)&&a.internal.poster.jq.show()};this.element.append(this.htmlElement.poster);this.internal.poster.jq=b("#"+this.internal.poster.id);this.internal.poster.jq.css({width:this.status.width,height:this.status.height});this.internal.poster.jq.hide();this.internal.poster.jq.bind("click.jPlayer",function(){a._trigger(b.jPlayer.event.click)});
this.html.audio.available=false;if(this.require.audio){this.htmlElement.audio=document.createElement("audio");this.htmlElement.audio.id=this.internal.audio.id;this.html.audio.available=!!this.htmlElement.audio.canPlayType&&this._testCanPlayType(this.htmlElement.audio)}this.html.video.available=false;if(this.require.video){this.htmlElement.video=document.createElement("video");this.htmlElement.video.id=this.internal.video.id;this.html.video.available=!!this.htmlElement.video.canPlayType&&this._testCanPlayType(this.htmlElement.video)}this.flash.available=
this._checkForFlash(10);this.html.canPlay={};this.flash.canPlay={};b.each(this.formats,function(b,c){a.html.canPlay[c]=a.html[a.format[c].media].available&&""!==a.htmlElement[a.format[c].media].canPlayType(a.format[c].codec);a.flash.canPlay[c]=a.format[c].flashCanPlay&&a.flash.available});this.html.desired=false;this.flash.desired=false;b.each(this.solutions,function(c,d){if(c===0)a[d].desired=true;else{var e=false,f=false;b.each(a.formats,function(b,c){a[a.solutions[0]].canPlay[c]&&(a.format[c].media===
"video"?f=true:e=true)});a[d].desired=a.require.audio&&!e||a.require.video&&!f}});this.html.support={};this.flash.support={};b.each(this.formats,function(b,c){a.html.support[c]=a.html.canPlay[c]&&a.html.desired;a.flash.support[c]=a.flash.canPlay[c]&&a.flash.desired});this.html.used=false;this.flash.used=false;b.each(this.solutions,function(c,d){b.each(a.formats,function(b,c){if(a[d].support[c]){a[d].used=true;return false}})});this._resetActive();this._resetGate();this._cssSelectorAncestor(this.options.cssSelectorAncestor);
if(!this.html.used&&!this.flash.used){this._error({type:b.jPlayer.error.NO_SOLUTION,context:"{solution:'"+this.options.solution+"', supplied:'"+this.options.supplied+"'}",message:b.jPlayer.errorMsg.NO_SOLUTION,hint:b.jPlayer.errorHint.NO_SOLUTION});this.css.jq.noSolution.length&&this.css.jq.noSolution.show()}else this.css.jq.noSolution.length&&this.css.jq.noSolution.hide();if(this.flash.used){var c,d="jQuery="+encodeURI(this.options.noConflict)+"&id="+encodeURI(this.internal.self.id)+"&vol="+this.options.volume+
"&muted="+this.options.muted;if(b.jPlayer.browser.msie&&Number(b.jPlayer.browser.version)<=8){d=['<param name="movie" value="'+this.internal.flash.swf+'" />','<param name="FlashVars" value="'+d+'" />','<param name="allowScriptAccess" value="always" />','<param name="bgcolor" value="'+this.options.backgroundColor+'" />','<param name="wmode" value="'+this.options.wmode+'" />'];c=document.createElement('<object id="'+this.internal.flash.id+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="0" height="0"></object>');
for(var e=0;e<d.length;e++)c.appendChild(document.createElement(d[e]))}else{e=function(a,b,c){var d=document.createElement("param");d.setAttribute("name",b);d.setAttribute("value",c);a.appendChild(d)};c=document.createElement("object");c.setAttribute("id",this.internal.flash.id);c.setAttribute("data",this.internal.flash.swf);c.setAttribute("type","application/x-shockwave-flash");c.setAttribute("width","1");c.setAttribute("height","1");e(c,"flashvars",d);e(c,"allowscriptaccess","always");e(c,"bgcolor",
this.options.backgroundColor);e(c,"wmode",this.options.wmode)}this.element.append(c);this.internal.flash.jq=b(c)}if(this.html.used){if(this.html.audio.available){this._addHtmlEventListeners(this.htmlElement.audio,this.html.audio);this.element.append(this.htmlElement.audio);this.internal.audio.jq=b("#"+this.internal.audio.id)}if(this.html.video.available){this._addHtmlEventListeners(this.htmlElement.video,this.html.video);this.element.append(this.htmlElement.video);this.internal.video.jq=b("#"+this.internal.video.id);
this.status.nativeVideoControls?this.internal.video.jq.css({width:this.status.width,height:this.status.height}):this.internal.video.jq.css({width:"0px",height:"0px"});this.internal.video.jq.bind("click.jPlayer",function(){a._trigger(b.jPlayer.event.click)})}}this.options.emulateHtml&&this._emulateHtmlBridge();this.html.used&&!this.flash.used&&setTimeout(function(){a.internal.ready=true;a.version.flash="n/a";a._trigger(b.jPlayer.event.repeat);a._trigger(b.jPlayer.event.ready)},100);this._updateNativeVideoControls();
this._updateInterface();this._updateButtons(false);this._updateAutohide();this._updateVolume(this.options.volume);this._updateMute(this.options.muted);this.css.jq.videoPlay.length&&this.css.jq.videoPlay.hide();b.jPlayer.prototype.count++},destroy:function(){this.clearMedia();this._removeUiClass();this.css.jq.currentTime.length&&this.css.jq.currentTime.text("");this.css.jq.duration.length&&this.css.jq.duration.text("");b.each(this.css.jq,function(a,b){b.length&&b.unbind(".jPlayer")});this.internal.poster.jq.unbind(".jPlayer");
this.internal.video.jq&&this.internal.video.jq.unbind(".jPlayer");this.options.emulateHtml&&this._destroyHtmlBridge();this.element.removeData("jPlayer");this.element.unbind(".jPlayer");this.element.empty();delete this.instances[this.internal.instance]},enable:function(){},disable:function(){},_testCanPlayType:function(a){try{a.canPlayType(this.format.mp3.codec);return true}catch(b){return false}},_uaBlocklist:function(a){var c=navigator.userAgent.toLowerCase(),d=false;b.each(a,function(a,b){if(b&&
b.test(c)){d=true;return false}});return d},_restrictNativeVideoControls:function(){if(this.require.audio&&this.status.nativeVideoControls){this.status.nativeVideoControls=false;this.status.noFullScreen=true}},_updateNativeVideoControls:function(){if(this.html.video.available&&this.html.used){this.htmlElement.video.controls=this.status.nativeVideoControls;this._updateAutohide();if(this.status.nativeVideoControls&&this.require.video){this.internal.poster.jq.hide();this.internal.video.jq.css({width:this.status.width,
height:this.status.height})}else if(this.status.waitForPlay&&this.status.video){this.internal.poster.jq.show();this.internal.video.jq.css({width:"0px",height:"0px"})}}},_addHtmlEventListeners:function(a,c){var d=this;a.preload=this.options.preload;a.muted=this.options.muted;a.volume=this.options.volume;a.addEventListener("progress",function(){if(c.gate){d._getHtmlStatus(a);d._updateInterface();d._trigger(b.jPlayer.event.progress)}},false);a.addEventListener("timeupdate",function(){if(c.gate){d._getHtmlStatus(a);
d._updateInterface();d._trigger(b.jPlayer.event.timeupdate)}},false);a.addEventListener("durationchange",function(){if(c.gate){d._getHtmlStatus(a);d._updateInterface();d._trigger(b.jPlayer.event.durationchange)}},false);a.addEventListener("play",function(){if(c.gate){d._updateButtons(true);d._html_checkWaitForPlay();d._trigger(b.jPlayer.event.play)}},false);a.addEventListener("playing",function(){if(c.gate){d._updateButtons(true);d._seeked();d._trigger(b.jPlayer.event.playing)}},false);a.addEventListener("pause",
function(){if(c.gate){d._updateButtons(false);d._trigger(b.jPlayer.event.pause)}},false);a.addEventListener("waiting",function(){if(c.gate){d._seeking();d._trigger(b.jPlayer.event.waiting)}},false);a.addEventListener("seeking",function(){if(c.gate){d._seeking();d._trigger(b.jPlayer.event.seeking)}},false);a.addEventListener("seeked",function(){if(c.gate){d._seeked();d._trigger(b.jPlayer.event.seeked)}},false);a.addEventListener("volumechange",function(){if(c.gate){d.options.volume=a.volume;d.options.muted=
a.muted;d._updateMute();d._updateVolume();d._trigger(b.jPlayer.event.volumechange)}},false);a.addEventListener("suspend",function(){if(c.gate){d._seeked();d._trigger(b.jPlayer.event.suspend)}},false);a.addEventListener("ended",function(){if(c.gate){if(!b.jPlayer.browser.webkit)d.htmlElement.media.currentTime=0;d.htmlElement.media.pause();d._updateButtons(false);d._getHtmlStatus(a,true);d._updateInterface();d._trigger(b.jPlayer.event.ended)}},false);a.addEventListener("error",function(){if(c.gate){d._updateButtons(false);
d._seeked();if(d.status.srcSet){clearTimeout(d.internal.htmlDlyCmdId);d.status.waitForLoad=true;d.status.waitForPlay=true;d.status.video&&!d.status.nativeVideoControls&&d.internal.video.jq.css({width:"0px",height:"0px"});d._validString(d.status.media.poster)&&!d.status.nativeVideoControls&&d.internal.poster.jq.show();d.css.jq.videoPlay.length&&d.css.jq.videoPlay.show();d._error({type:b.jPlayer.error.URL,context:d.status.src,message:b.jPlayer.errorMsg.URL,hint:b.jPlayer.errorHint.URL})}}},false);b.each(b.jPlayer.htmlEvent,
function(e,g){a.addEventListener(this,function(){c.gate&&d._trigger(b.jPlayer.event[g])},false)})},_getHtmlStatus:function(a,b){var d=0,e=0,g=0,f=0;if(isFinite(a.duration))this.status.duration=a.duration;d=a.currentTime;e=this.status.duration>0?100*d/this.status.duration:0;if(typeof a.seekable==="object"&&a.seekable.length>0){g=this.status.duration>0?100*a.seekable.end(a.seekable.length-1)/this.status.duration:100;f=this.status.duration>0?100*a.currentTime/a.seekable.end(a.seekable.length-1):0}else{g=
100;f=e}if(b)e=f=d=0;this.status.seekPercent=g;this.status.currentPercentRelative=f;this.status.currentPercentAbsolute=e;this.status.currentTime=d;this.status.readyState=a.readyState;this.status.networkState=a.networkState;this.status.playbackRate=a.playbackRate;this.status.ended=a.ended},_resetStatus:function(){this.status=b.extend({},this.status,b.jPlayer.prototype.status)},_trigger:function(a,c,d){a=b.Event(a);a.jPlayer={};a.jPlayer.version=b.extend({},this.version);a.jPlayer.options=b.extend(true,
{},this.options);a.jPlayer.status=b.extend(true,{},this.status);a.jPlayer.html=b.extend(true,{},this.html);a.jPlayer.flash=b.extend(true,{},this.flash);if(c)a.jPlayer.error=b.extend({},c);if(d)a.jPlayer.warning=b.extend({},d);this.element.trigger(a)},jPlayerFlashEvent:function(a,c){if(a===b.jPlayer.event.ready)if(this.internal.ready){if(this.flash.gate){if(this.status.srcSet){var d=this.status.currentTime,e=this.status.paused;this.setMedia(this.status.media);d>0&&(e?this.pause(d):this.play(d))}this._trigger(b.jPlayer.event.flashreset)}}else{this.internal.ready=
true;this.internal.flash.jq.css({width:"0px",height:"0px"});this.version.flash=c.version;this.version.needFlash!==this.version.flash&&this._error({type:b.jPlayer.error.VERSION,context:this.version.flash,message:b.jPlayer.errorMsg.VERSION+this.version.flash,hint:b.jPlayer.errorHint.VERSION});this._trigger(b.jPlayer.event.repeat);this._trigger(a)}if(this.flash.gate)switch(a){case b.jPlayer.event.progress:this._getFlashStatus(c);this._updateInterface();this._trigger(a);break;case b.jPlayer.event.timeupdate:this._getFlashStatus(c);
this._updateInterface();this._trigger(a);break;case b.jPlayer.event.play:this._seeked();this._updateButtons(true);this._trigger(a);break;case b.jPlayer.event.pause:this._updateButtons(false);this._trigger(a);break;case b.jPlayer.event.ended:this._updateButtons(false);this._trigger(a);break;case b.jPlayer.event.click:this._trigger(a);break;case b.jPlayer.event.error:this.status.waitForLoad=true;this.status.waitForPlay=true;this.status.video&&this.internal.flash.jq.css({width:"0px",height:"0px"});this._validString(this.status.media.poster)&&
this.internal.poster.jq.show();this.css.jq.videoPlay.length&&this.status.video&&this.css.jq.videoPlay.show();this.status.video?this._flash_setVideo(this.status.media):this._flash_setAudio(this.status.media);this._updateButtons(false);this._error({type:b.jPlayer.error.URL,context:c.src,message:b.jPlayer.errorMsg.URL,hint:b.jPlayer.errorHint.URL});break;case b.jPlayer.event.seeking:this._seeking();this._trigger(a);break;case b.jPlayer.event.seeked:this._seeked();this._trigger(a);break;case b.jPlayer.event.ready:break;
default:this._trigger(a)}return false},_getFlashStatus:function(a){this.status.seekPercent=a.seekPercent;this.status.currentPercentRelative=a.currentPercentRelative;this.status.currentPercentAbsolute=a.currentPercentAbsolute;this.status.currentTime=a.currentTime;this.status.duration=a.duration;this.status.readyState=4;this.status.networkState=0;this.status.playbackRate=1;this.status.ended=false},_updateButtons:function(a){if(a!==f){this.status.paused=!a;if(this.css.jq.play.length&&this.css.jq.pause.length)if(a){this.css.jq.play.hide();
this.css.jq.pause.show()}else{this.css.jq.play.show();this.css.jq.pause.hide()}}if(this.css.jq.restoreScreen.length&&this.css.jq.fullScreen.length)if(this.status.noFullScreen){this.css.jq.fullScreen.hide();this.css.jq.restoreScreen.hide()}else if(this.options.fullScreen){this.css.jq.fullScreen.hide();this.css.jq.restoreScreen.show()}else{this.css.jq.fullScreen.show();this.css.jq.restoreScreen.hide()}if(this.css.jq.repeat.length&&this.css.jq.repeatOff.length)if(this.options.loop){this.css.jq.repeat.hide();
this.css.jq.repeatOff.show()}else{this.css.jq.repeat.show();this.css.jq.repeatOff.hide()}},_updateInterface:function(){this.css.jq.seekBar.length&&this.css.jq.seekBar.width(this.status.seekPercent+"%");this.css.jq.playBar.length&&this.css.jq.playBar.width(this.status.currentPercentRelative+"%");this.css.jq.currentTime.length&&this.css.jq.currentTime.text(b.jPlayer.convertTime(this.status.currentTime));this.css.jq.duration.length&&this.css.jq.duration.text(b.jPlayer.convertTime(this.status.duration))},
_seeking:function(){this.css.jq.seekBar.length&&this.css.jq.seekBar.addClass("jp-seeking-bg")},_seeked:function(){this.css.jq.seekBar.length&&this.css.jq.seekBar.removeClass("jp-seeking-bg")},_resetGate:function(){this.html.audio.gate=false;this.html.video.gate=false;this.flash.gate=false},_resetActive:function(){this.html.active=false;this.flash.active=false},setMedia:function(a){var c=this,d=false,e=this.status.media.poster!==a.poster;this._resetMedia();this._resetGate();this._resetActive();b.each(this.formats,
function(e,f){var i=c.format[f].media==="video";b.each(c.solutions,function(b,e){if(c[e].support[f]&&c._validString(a[f])){var g=e==="html";if(i){if(g){c.html.video.gate=true;c._html_setVideo(a);c.html.active=true}else{c.flash.gate=true;c._flash_setVideo(a);c.flash.active=true}c.css.jq.videoPlay.length&&c.css.jq.videoPlay.show();c.status.video=true}else{if(g){c.html.audio.gate=true;c._html_setAudio(a);c.html.active=true}else{c.flash.gate=true;c._flash_setAudio(a);c.flash.active=true}c.css.jq.videoPlay.length&&
c.css.jq.videoPlay.hide();c.status.video=false}d=true;return false}});if(d)return false});if(d){if((!this.status.nativeVideoControls||!this.html.video.gate)&&this._validString(a.poster))e?this.htmlElement.poster.src=a.poster:this.internal.poster.jq.show();this.status.srcSet=true;this.status.media=b.extend({},a);this._updateButtons(false);this._updateInterface()}else this._error({type:b.jPlayer.error.NO_SUPPORT,context:"{supplied:'"+this.options.supplied+"'}",message:b.jPlayer.errorMsg.NO_SUPPORT,
hint:b.jPlayer.errorHint.NO_SUPPORT})},_resetMedia:function(){this._resetStatus();this._updateButtons(false);this._updateInterface();this._seeked();this.internal.poster.jq.hide();clearTimeout(this.internal.htmlDlyCmdId);this.html.active?this._html_resetMedia():this.flash.active&&this._flash_resetMedia()},clearMedia:function(){this._resetMedia();this.html.active?this._html_clearMedia():this.flash.active&&this._flash_clearMedia();this._resetGate();this._resetActive()},load:function(){this.status.srcSet?
this.html.active?this._html_load():this.flash.active&&this._flash_load():this._urlNotSetError("load")},play:function(a){a=typeof a==="number"?a:NaN;this.status.srcSet?this.html.active?this._html_play(a):this.flash.active&&this._flash_play(a):this._urlNotSetError("play")},videoPlay:function(){this.play()},pause:function(a){a=typeof a==="number"?a:NaN;this.status.srcSet?this.html.active?this._html_pause(a):this.flash.active&&this._flash_pause(a):this._urlNotSetError("pause")},pauseOthers:function(){var a=
this;b.each(this.instances,function(b,d){a.element!==d&&d.data("jPlayer").status.srcSet&&d.jPlayer("pause")})},stop:function(){this.status.srcSet?this.html.active?this._html_pause(0):this.flash.active&&this._flash_pause(0):this._urlNotSetError("stop")},playHead:function(a){a=this._limitValue(a,0,100);this.status.srcSet?this.html.active?this._html_playHead(a):this.flash.active&&this._flash_playHead(a):this._urlNotSetError("playHead")},_muted:function(a){this.options.muted=a;this.html.used&&this._html_mute(a);
this.flash.used&&this._flash_mute(a);if(!this.html.video.gate&&!this.html.audio.gate){this._updateMute(a);this._updateVolume(this.options.volume);this._trigger(b.jPlayer.event.volumechange)}},mute:function(a){a=a===f?true:!!a;this._muted(a)},unmute:function(a){a=a===f?true:!!a;this._muted(!a)},_updateMute:function(a){if(a===f)a=this.options.muted;if(this.css.jq.mute.length&&this.css.jq.unmute.length)if(this.status.noVolume){this.css.jq.mute.hide();this.css.jq.unmute.hide()}else if(a){this.css.jq.mute.hide();
this.css.jq.unmute.show()}else{this.css.jq.mute.show();this.css.jq.unmute.hide()}},volume:function(a){a=this._limitValue(a,0,1);this.options.volume=a;this.html.used&&this._html_volume(a);this.flash.used&&this._flash_volume(a);if(!this.html.video.gate&&!this.html.audio.gate){this._updateVolume(a);this._trigger(b.jPlayer.event.volumechange)}},volumeBar:function(a){if(this.css.jq.volumeBar.length){var b=this.css.jq.volumeBar.offset(),d=a.pageX-b.left,e=this.css.jq.volumeBar.width(),a=this.css.jq.volumeBar.height()-
a.pageY+b.top,b=this.css.jq.volumeBar.height();this.options.verticalVolume?this.volume(a/b):this.volume(d/e)}this.options.muted&&this._muted(false)},volumeBarValue:function(a){this.volumeBar(a)},_updateVolume:function(a){if(a===f)a=this.options.volume;a=this.options.muted?0:a;if(this.status.noVolume){this.css.jq.volumeBar.length&&this.css.jq.volumeBar.hide();this.css.jq.volumeBarValue.length&&this.css.jq.volumeBarValue.hide();this.css.jq.volumeMax.length&&this.css.jq.volumeMax.hide()}else{this.css.jq.volumeBar.length&&
this.css.jq.volumeBar.show();if(this.css.jq.volumeBarValue.length){this.css.jq.volumeBarValue.show();this.css.jq.volumeBarValue[this.options.verticalVolume?"height":"width"](a*100+"%")}this.css.jq.volumeMax.length&&this.css.jq.volumeMax.show()}},volumeMax:function(){this.volume(1);this.options.muted&&this._muted(false)},_cssSelectorAncestor:function(a){var c=this;this.options.cssSelectorAncestor=a;this._removeUiClass();this.ancestorJq=a?b(a):[];a&&this.ancestorJq.length!==1&&this._warning({type:b.jPlayer.warning.CSS_SELECTOR_COUNT,
context:a,message:b.jPlayer.warningMsg.CSS_SELECTOR_COUNT+this.ancestorJq.length+" found for cssSelectorAncestor.",hint:b.jPlayer.warningHint.CSS_SELECTOR_COUNT});this._addUiClass();b.each(this.options.cssSelector,function(a,b){c._cssSelector(a,b)})},_cssSelector:function(a,c){var d=this;if(typeof c==="string")if(b.jPlayer.prototype.options.cssSelector[a]){this.css.jq[a]&&this.css.jq[a].length&&this.css.jq[a].unbind(".jPlayer");this.options.cssSelector[a]=c;this.css.cs[a]=this.options.cssSelectorAncestor+
" "+c;this.css.jq[a]=c?b(this.css.cs[a]):[];this.css.jq[a].length&&this.css.jq[a].bind("click.jPlayer",function(c){d[a](c);b(this).blur();return false});c&&this.css.jq[a].length!==1&&this._warning({type:b.jPlayer.warning.CSS_SELECTOR_COUNT,context:this.css.cs[a],message:b.jPlayer.warningMsg.CSS_SELECTOR_COUNT+this.css.jq[a].length+" found for "+a+" method.",hint:b.jPlayer.warningHint.CSS_SELECTOR_COUNT})}else this._warning({type:b.jPlayer.warning.CSS_SELECTOR_METHOD,context:a,message:b.jPlayer.warningMsg.CSS_SELECTOR_METHOD,
hint:b.jPlayer.warningHint.CSS_SELECTOR_METHOD});else this._warning({type:b.jPlayer.warning.CSS_SELECTOR_STRING,context:c,message:b.jPlayer.warningMsg.CSS_SELECTOR_STRING,hint:b.jPlayer.warningHint.CSS_SELECTOR_STRING})},seekBar:function(a){if(this.css.jq.seekBar){var b=this.css.jq.seekBar.offset(),a=a.pageX-b.left,b=this.css.jq.seekBar.width();this.playHead(100*a/b)}},playBar:function(a){this.seekBar(a)},repeat:function(){this._loop(true)},repeatOff:function(){this._loop(false)},_loop:function(a){if(this.options.loop!==
a){this.options.loop=a;this._updateButtons();this._trigger(b.jPlayer.event.repeat)}},currentTime:function(){},duration:function(){},gui:function(){},noSolution:function(){},option:function(a,c){var d=a;if(arguments.length===0)return b.extend(true,{},this.options);if(typeof a==="string"){var e=a.split(".");if(c===f){for(var d=b.extend(true,{},this.options),g=0;g<e.length;g++)if(d[e[g]]!==f)d=d[e[g]];else{this._warning({type:b.jPlayer.warning.OPTION_KEY,context:a,message:b.jPlayer.warningMsg.OPTION_KEY,
hint:b.jPlayer.warningHint.OPTION_KEY});return f}return d}for(var g=d={},h=0;h<e.length;h++)if(h<e.length-1){g[e[h]]={};g=g[e[h]]}else g[e[h]]=c}this._setOptions(d);return this},_setOptions:function(a){var c=this;b.each(a,function(a,b){c._setOption(a,b)});return this},_setOption:function(a,c){var d=this;switch(a){case "volume":this.volume(c);break;case "muted":this._muted(c);break;case "cssSelectorAncestor":this._cssSelectorAncestor(c);break;case "cssSelector":b.each(c,function(a,b){d._cssSelector(a,
b)});break;case "fullScreen":if(this.options[a]!==c){this._removeUiClass();this.options[a]=c;this._refreshSize()}break;case "size":!this.options.fullScreen&&this.options[a].cssClass!==c.cssClass&&this._removeUiClass();this.options[a]=b.extend({},this.options[a],c);this._refreshSize();break;case "sizeFull":this.options.fullScreen&&this.options[a].cssClass!==c.cssClass&&this._removeUiClass();this.options[a]=b.extend({},this.options[a],c);this._refreshSize();break;case "autohide":this.options[a]=b.extend({},
this.options[a],c);this._updateAutohide();break;case "loop":this._loop(c);break;case "nativeVideoControls":this.options[a]=b.extend({},this.options[a],c);this.status.nativeVideoControls=this._uaBlocklist(this.options.nativeVideoControls);this._restrictNativeVideoControls();this._updateNativeVideoControls();break;case "noFullScreen":this.options[a]=b.extend({},this.options[a],c);this.status.nativeVideoControls=this._uaBlocklist(this.options.nativeVideoControls);this.status.noFullScreen=this._uaBlocklist(this.options.noFullScreen);
this._restrictNativeVideoControls();this._updateButtons();break;case "noVolume":this.options[a]=b.extend({},this.options[a],c);this.status.noVolume=this._uaBlocklist(this.options.noVolume);this._updateVolume();this._updateMute();break;case "emulateHtml":if(this.options[a]!==c)(this.options[a]=c)?this._emulateHtmlBridge():this._destroyHtmlBridge()}return this},_refreshSize:function(){this._setSize();this._addUiClass();this._updateSize();this._updateButtons();this._updateAutohide();this._trigger(b.jPlayer.event.resize)},
_setSize:function(){if(this.options.fullScreen){this.status.width=this.options.sizeFull.width;this.status.height=this.options.sizeFull.height;this.status.cssClass=this.options.sizeFull.cssClass}else{this.status.width=this.options.size.width;this.status.height=this.options.size.height;this.status.cssClass=this.options.size.cssClass}this.element.css({width:this.status.width,height:this.status.height})},_addUiClass:function(){this.ancestorJq.length&&this.ancestorJq.addClass(this.status.cssClass)},_removeUiClass:function(){this.ancestorJq.length&&
this.ancestorJq.removeClass(this.status.cssClass)},_updateSize:function(){this.internal.poster.jq.css({width:this.status.width,height:this.status.height});!this.status.waitForPlay&&this.html.active&&this.status.video||this.html.video.available&&this.html.used&&this.status.nativeVideoControls?this.internal.video.jq.css({width:this.status.width,height:this.status.height}):!this.status.waitForPlay&&(this.flash.active&&this.status.video)&&this.internal.flash.jq.css({width:this.status.width,height:this.status.height})},
_updateAutohide:function(){var a=this,b=function(){a.css.jq.gui.fadeIn(a.options.autohide.fadeIn,function(){clearTimeout(a.internal.autohideId);a.internal.autohideId=setTimeout(function(){a.css.jq.gui.fadeOut(a.options.autohide.fadeOut)},a.options.autohide.hold)})};if(this.css.jq.gui.length){this.css.jq.gui.stop(true,true);clearTimeout(this.internal.autohideId);this.element.unbind(".jPlayerAutohide");this.css.jq.gui.unbind(".jPlayerAutohide");if(this.status.nativeVideoControls)this.css.jq.gui.hide();
else if(this.options.fullScreen&&this.options.autohide.full||!this.options.fullScreen&&this.options.autohide.restored){this.element.bind("mousemove.jPlayer.jPlayerAutohide",b);this.css.jq.gui.bind("mousemove.jPlayer.jPlayerAutohide",b);this.css.jq.gui.hide()}else this.css.jq.gui.show()}},fullScreen:function(){this._setOption("fullScreen",true)},restoreScreen:function(){this._setOption("fullScreen",false)},_html_initMedia:function(){this.htmlElement.media.src=this.status.src;this.options.preload!==
"none"&&this._html_load();this._trigger(b.jPlayer.event.timeupdate)},_html_setAudio:function(a){var c=this;b.each(this.formats,function(b,e){if(c.html.support[e]&&a[e]){c.status.src=a[e];c.status.format[e]=true;c.status.formatType=e;return false}});this.htmlElement.media=this.htmlElement.audio;this._html_initMedia()},_html_setVideo:function(a){var c=this;b.each(this.formats,function(b,e){if(c.html.support[e]&&a[e]){c.status.src=a[e];c.status.format[e]=true;c.status.formatType=e;return false}});if(this.status.nativeVideoControls)this.htmlElement.video.poster=
this._validString(a.poster)?a.poster:"";this.htmlElement.media=this.htmlElement.video;this._html_initMedia()},_html_resetMedia:function(){if(this.htmlElement.media){this.htmlElement.media.id===this.internal.video.id&&!this.status.nativeVideoControls&&this.internal.video.jq.css({width:"0px",height:"0px"});this.htmlElement.media.pause()}},_html_clearMedia:function(){if(this.htmlElement.media){this.htmlElement.media.src="";this.htmlElement.media.load()}},_html_load:function(){if(this.status.waitForLoad){this.status.waitForLoad=
false;this.htmlElement.media.load()}clearTimeout(this.internal.htmlDlyCmdId)},_html_play:function(a){var b=this;this._html_load();this.htmlElement.media.play();if(!isNaN(a))try{this.htmlElement.media.currentTime=a}catch(d){this.internal.htmlDlyCmdId=setTimeout(function(){b.play(a)},100);return}this._html_checkWaitForPlay()},_html_pause:function(a){var b=this;a>0?this._html_load():clearTimeout(this.internal.htmlDlyCmdId);this.htmlElement.media.pause();if(!isNaN(a))try{this.htmlElement.media.currentTime=
a}catch(d){this.internal.htmlDlyCmdId=setTimeout(function(){b.pause(a)},100);return}a>0&&this._html_checkWaitForPlay()},_html_playHead:function(a){var b=this;this._html_load();try{if(typeof this.htmlElement.media.seekable==="object"&&this.htmlElement.media.seekable.length>0)this.htmlElement.media.currentTime=a*this.htmlElement.media.seekable.end(this.htmlElement.media.seekable.length-1)/100;else if(this.htmlElement.media.duration>0&&!isNaN(this.htmlElement.media.duration))this.htmlElement.media.currentTime=
a*this.htmlElement.media.duration/100;else throw"e";}catch(d){this.internal.htmlDlyCmdId=setTimeout(function(){b.playHead(a)},100);return}this.status.waitForLoad||this._html_checkWaitForPlay()},_html_checkWaitForPlay:function(){if(this.status.waitForPlay){this.status.waitForPlay=false;this.css.jq.videoPlay.length&&this.css.jq.videoPlay.hide();if(this.status.video){this.internal.poster.jq.hide();this.internal.video.jq.css({width:this.status.width,height:this.status.height})}}},_html_volume:function(a){if(this.html.audio.available)this.htmlElement.audio.volume=
a;if(this.html.video.available)this.htmlElement.video.volume=a},_html_mute:function(a){if(this.html.audio.available)this.htmlElement.audio.muted=a;if(this.html.video.available)this.htmlElement.video.muted=a},_flash_setAudio:function(a){var c=this;try{b.each(this.formats,function(b,d){if(c.flash.support[d]&&a[d]){switch(d){case "m4a":case "fla":c._getMovie().fl_setAudio_m4a(a[d]);break;case "mp3":c._getMovie().fl_setAudio_mp3(a[d]);break;case "rtmpa":c._getMovie().fl_setAudio_rtmp(a[d])}c.status.src=
a[d];c.status.format[d]=true;c.status.formatType=d;return false}});if(this.options.preload==="auto"){this._flash_load();this.status.waitForLoad=false}}catch(d){this._flashError(d)}},_flash_setVideo:function(a){var c=this;try{b.each(this.formats,function(b,d){if(c.flash.support[d]&&a[d]){switch(d){case "m4v":case "flv":c._getMovie().fl_setVideo_m4v(a[d]);break;case "rtmpv":c._getMovie().fl_setVideo_rtmp(a[d])}c.status.src=a[d];c.status.format[d]=true;c.status.formatType=d;return false}});if(this.options.preload===
"auto"){this._flash_load();this.status.waitForLoad=false}}catch(d){this._flashError(d)}},_flash_resetMedia:function(){this.internal.flash.jq.css({width:"0px",height:"0px"});this._flash_pause(NaN)},_flash_clearMedia:function(){try{this._getMovie().fl_clearMedia()}catch(a){this._flashError(a)}},_flash_load:function(){try{this._getMovie().fl_load()}catch(a){this._flashError(a)}this.status.waitForLoad=false},_flash_play:function(a){try{this._getMovie().fl_play(a)}catch(b){this._flashError(b)}this.status.waitForLoad=
false;this._flash_checkWaitForPlay()},_flash_pause:function(a){try{this._getMovie().fl_pause(a)}catch(b){this._flashError(b)}if(a>0){this.status.waitForLoad=false;this._flash_checkWaitForPlay()}},_flash_playHead:function(a){try{this._getMovie().fl_play_head(a)}catch(b){this._flashError(b)}this.status.waitForLoad||this._flash_checkWaitForPlay()},_flash_checkWaitForPlay:function(){if(this.status.waitForPlay){this.status.waitForPlay=false;this.css.jq.videoPlay.length&&this.css.jq.videoPlay.hide();if(this.status.video){this.internal.poster.jq.hide();
this.internal.flash.jq.css({width:this.status.width,height:this.status.height})}}},_flash_volume:function(a){try{this._getMovie().fl_volume(a)}catch(b){this._flashError(b)}},_flash_mute:function(a){try{this._getMovie().fl_mute(a)}catch(b){this._flashError(b)}},_getMovie:function(){return document[this.internal.flash.id]},_checkForFlash:function(a){var b=false,d;if(window.ActiveXObject)try{new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+a);b=true}catch(e){}else if(navigator.plugins&&navigator.mimeTypes.length>
0)(d=navigator.plugins["Shockwave Flash"])&&navigator.plugins["Shockwave Flash"].description.replace(/.*\s(\d+\.\d+).*/,"$1")>=a&&(b=true);return b},_validString:function(a){return a&&typeof a==="string"},_limitValue:function(a,b,d){return a<b?b:a>d?d:a},_urlNotSetError:function(a){this._error({type:b.jPlayer.error.URL_NOT_SET,context:a,message:b.jPlayer.errorMsg.URL_NOT_SET,hint:b.jPlayer.errorHint.URL_NOT_SET})},_flashError:function(a){var c;c=this.internal.ready?"FLASH_DISABLED":"FLASH";this._error({type:b.jPlayer.error[c],
context:this.internal.flash.swf,message:b.jPlayer.errorMsg[c]+a.message,hint:b.jPlayer.errorHint[c]});this.internal.flash.jq.css({width:"1px",height:"1px"})},_error:function(a){this._trigger(b.jPlayer.event.error,a);this.options.errorAlerts&&this._alert("Error!"+(a.message?"\n\n"+a.message:"")+(a.hint?"\n\n"+a.hint:"")+"\n\nContext: "+a.context)},_warning:function(a){this._trigger(b.jPlayer.event.warning,f,a);this.options.warningAlerts&&this._alert("Warning!"+(a.message?"\n\n"+a.message:"")+(a.hint?
"\n\n"+a.hint:"")+"\n\nContext: "+a.context)},_alert:function(a){alert("jPlayer "+this.version.script+" : id='"+this.internal.self.id+"' : "+a)},_emulateHtmlBridge:function(){var a=this;b.each(b.jPlayer.emulateMethods.split(/\s+/g),function(b,d){a.internal.domNode[d]=function(b){a[d](b)}});b.each(b.jPlayer.event,function(c,d){var e=true;b.each(b.jPlayer.reservedEvent.split(/\s+/g),function(a,b){if(b===c)return e=false});e&&a.element.bind(d+".jPlayer.jPlayerHtml",function(){a._emulateHtmlUpdate();
var b=document.createEvent("Event");b.initEvent(c,false,true);a.internal.domNode.dispatchEvent(b)})})},_emulateHtmlUpdate:function(){var a=this;b.each(b.jPlayer.emulateStatus.split(/\s+/g),function(b,d){a.internal.domNode[d]=a.status[d]});b.each(b.jPlayer.emulateOptions.split(/\s+/g),function(b,d){a.internal.domNode[d]=a.options[d]})},_destroyHtmlBridge:function(){var a=this;this.element.unbind(".jPlayerHtml");b.each((b.jPlayer.emulateMethods+" "+b.jPlayer.emulateStatus+" "+b.jPlayer.emulateOptions).split(/\s+/g),
function(b,d){delete a.internal.domNode[d]})}};b.jPlayer.error={FLASH:"e_flash",FLASH_DISABLED:"e_flash_disabled",NO_SOLUTION:"e_no_solution",NO_SUPPORT:"e_no_support",URL:"e_url",URL_NOT_SET:"e_url_not_set",VERSION:"e_version"};b.jPlayer.errorMsg={FLASH:"jPlayer's Flash fallback is not configured correctly, or a command was issued before the jPlayer Ready event. Details: ",FLASH_DISABLED:"jPlayer's Flash fallback has been disabled by the browser due to the CSS rules you have used. Details: ",NO_SOLUTION:"No solution can be found by jPlayer in this browser. Neither HTML nor Flash can be used.",
NO_SUPPORT:"It is not possible to play any media format provided in setMedia() on this browser using your current options.",URL:"Media URL could not be loaded.",URL_NOT_SET:"Attempt to issue media playback commands, while no media url is set.",VERSION:"jPlayer "+b.jPlayer.prototype.version.script+" needs Jplayer.swf version "+b.jPlayer.prototype.version.needFlash+" but found "};b.jPlayer.errorHint={FLASH:"Check your swfPath option and that Jplayer.swf is there.",FLASH_DISABLED:"Check that you have not display:none; the jPlayer entity or any ancestor.",
NO_SOLUTION:"Review the jPlayer options: support and supplied.",NO_SUPPORT:"Video or audio formats defined in the supplied option are missing.",URL:"Check media URL is valid.",URL_NOT_SET:"Use setMedia() to set the media URL.",VERSION:"Update jPlayer files."};b.jPlayer.warning={CSS_SELECTOR_COUNT:"e_css_selector_count",CSS_SELECTOR_METHOD:"e_css_selector_method",CSS_SELECTOR_STRING:"e_css_selector_string",OPTION_KEY:"e_option_key"};b.jPlayer.warningMsg={CSS_SELECTOR_COUNT:"The number of css selectors found did not equal one: ",
CSS_SELECTOR_METHOD:"The methodName given in jPlayer('cssSelector') is not a valid jPlayer method.",CSS_SELECTOR_STRING:"The methodCssSelector given in jPlayer('cssSelector') is not a String or is empty.",OPTION_KEY:"The option requested in jPlayer('option') is undefined."};b.jPlayer.warningHint={CSS_SELECTOR_COUNT:"Check your css selector and the ancestor.",CSS_SELECTOR_METHOD:"Check your method name.",CSS_SELECTOR_STRING:"Check your css selector is a string.",OPTION_KEY:"Check your option name."}})(jQuery);
/*
 * Playlist Object for the jPlayer Plugin
 * http://www.jplayer.org
 *
 * Copyright (c) 2009 - 2011 Happyworm Ltd
 * Dual licensed under the MIT and GPL licenses.
 *  - http://www.opensource.org/licenses/mit-license.php
 *  - http://www.gnu.org/copyleft/gpl.html
 *
 * Author: Mark J Panaghiston
 * Version: 2.1.0 (jPlayer 2.1.0)
 * Date: 1st September 2011
 */

(function(b,f){jPlayerPlaylist=function(a,c,d){var e=this;this.current=0;this.removing=this.shuffled=this.loop=!1;this.cssSelector=b.extend({},this._cssSelector,a);this.options=b.extend(!0,{},this._options,d);this.playlist=[];this.original=[];this._initPlaylist(c);this.cssSelector.title=this.cssSelector.cssSelectorAncestor+" .jp-title";this.cssSelector.playlist=this.cssSelector.cssSelectorAncestor+" .jp-playlist";this.cssSelector.next=this.cssSelector.cssSelectorAncestor+" .jp-next";this.cssSelector.previous=
this.cssSelector.cssSelectorAncestor+" .jp-previous";this.cssSelector.shuffle=this.cssSelector.cssSelectorAncestor+" .jp-shuffle";this.cssSelector.shuffleOff=this.cssSelector.cssSelectorAncestor+" .jp-shuffle-off";this.options.cssSelectorAncestor=this.cssSelector.cssSelectorAncestor;this.options.repeat=function(a){e.loop=a.jPlayer.options.loop};b(this.cssSelector.jPlayer).bind(b.jPlayer.event.ready,function(){e._init()});b(this.cssSelector.jPlayer).bind(b.jPlayer.event.ended,function(){e.next()});
b(this.cssSelector.jPlayer).bind(b.jPlayer.event.play,function(){b(this).jPlayer("pauseOthers")});b(this.cssSelector.jPlayer).bind(b.jPlayer.event.resize,function(a){a.jPlayer.options.fullScreen?b(e.cssSelector.title).show():b(e.cssSelector.title).hide()});b(this.cssSelector.previous).click(function(){e.previous();b(this).blur();return!1});b(this.cssSelector.next).click(function(){e.next();b(this).blur();return!1});b(this.cssSelector.shuffle).click(function(){e.shuffle(!0);return!1});b(this.cssSelector.shuffleOff).click(function(){e.shuffle(!1);
return!1}).hide();this.options.fullScreen||b(this.cssSelector.title).hide();b(this.cssSelector.playlist+" ul").empty();this._createItemHandlers();b(this.cssSelector.jPlayer).jPlayer(this.options)};jPlayerPlaylist.prototype={_cssSelector:{jPlayer:"#jquery_jplayer_1",cssSelectorAncestor:"#jp_container_1"},_options:{playlistOptions:{autoPlay:!1,loopOnPrevious:!1,shuffleOnLoop:!0,enableRemoveControls:!1,displayTime:"slow",addTime:"fast",removeTime:"fast",shuffleTime:"slow",itemClass:"jp-playlist-item",
freeGroupClass:"jp-free-media",freeItemClass:"jp-playlist-item-free",removeItemClass:"jp-playlist-item-remove"}},option:function(a,b){if(b===f)return this.options.playlistOptions[a];this.options.playlistOptions[a]=b;switch(a){case "enableRemoveControls":this._updateControls();break;case "itemClass":case "freeGroupClass":case "freeItemClass":case "removeItemClass":this._refresh(!0),this._createItemHandlers()}return this},_init:function(){var a=this;this._refresh(function(){a.options.playlistOptions.autoPlay?
a.play(a.current):a.select(a.current)})},_initPlaylist:function(a){this.current=0;this.removing=this.shuffled=!1;this.original=b.extend(!0,[],a);this._originalPlaylist()},_originalPlaylist:function(){var a=this;this.playlist=[];b.each(this.original,function(b){a.playlist[b]=a.original[b]})},_refresh:function(a){var c=this;if(a&&!b.isFunction(a))b(this.cssSelector.playlist+" ul").empty(),b.each(this.playlist,function(a){b(c.cssSelector.playlist+" ul").append(c._createListItem(c.playlist[a]))}),this._updateControls();
else{var d=b(this.cssSelector.playlist+" ul").children().length?this.options.playlistOptions.displayTime:0;b(this.cssSelector.playlist+" ul").slideUp(d,function(){var d=b(this);b(this).empty();b.each(c.playlist,function(a){d.append(c._createListItem(c.playlist[a]))});c._updateControls();b.isFunction(a)&&a();c.playlist.length?b(this).slideDown(c.options.playlistOptions.displayTime):b(this).show()})}},_createListItem:function(a){var c=this,d="<li><div>";d+="<a href='javascript:;' class='"+this.options.playlistOptions.removeItemClass+
"'>&times;</a>";if(a.free){var e=!0;d+="<span class='"+this.options.playlistOptions.freeGroupClass+"'>(";b.each(a,function(a,f){b.jPlayer.prototype.format[a]&&(e?e=!1:d+=" | ",d+="<a class='"+c.options.playlistOptions.freeItemClass+"' href='"+f+"' tabindex='1'>"+a+"</a>")});d+=")</span>"}d+="<a href='javascript:;' class='"+this.options.playlistOptions.itemClass+"' tabindex='1'>"+a.title+(a.artist?" <span class='jp-artist'>by "+a.artist+"</span>":"")+"</a>";d+="</div></li>";return d},_createItemHandlers:function(){var a=
this;b(this.cssSelector.playlist+" a."+this.options.playlistOptions.itemClass).die("click").live("click",function(){var c=b(this).parent().parent().index();a.current!==c?a.play(c):b(a.cssSelector.jPlayer).jPlayer("play");b(this).blur();return!1});b(a.cssSelector.playlist+" a."+this.options.playlistOptions.freeItemClass).die("click").live("click",function(){b(this).parent().parent().find("."+a.options.playlistOptions.itemClass).click();b(this).blur();return!1});b(a.cssSelector.playlist+" a."+this.options.playlistOptions.removeItemClass).die("click").live("click",
function(){var c=b(this).parent().parent().index();a.remove(c);b(this).blur();return!1})},_updateControls:function(){this.options.playlistOptions.enableRemoveControls?b(this.cssSelector.playlist+" ."+this.options.playlistOptions.removeItemClass).show():b(this.cssSelector.playlist+" ."+this.options.playlistOptions.removeItemClass).hide();this.shuffled?(b(this.cssSelector.shuffleOff).show(),b(this.cssSelector.shuffle).hide()):(b(this.cssSelector.shuffleOff).hide(),b(this.cssSelector.shuffle).show())},
_highlight:function(a){this.playlist.length&&a!==f&&(b(this.cssSelector.playlist+" .jp-playlist-current").removeClass("jp-playlist-current"),b(this.cssSelector.playlist+" li:nth-child("+(a+1)+")").addClass("jp-playlist-current").find(".jp-playlist-item").addClass("jp-playlist-current"),b(this.cssSelector.title+" li").html(this.playlist[a].title+(this.playlist[a].artist?" <span class='jp-artist'>by "+this.playlist[a].artist+"</span>":"")))},setPlaylist:function(a){this._initPlaylist(a);this._init()},
add:function(a,c){b(this.cssSelector.playlist+" ul").append(this._createListItem(a)).find("li:last-child").hide().slideDown(this.options.playlistOptions.addTime);this._updateControls();this.original.push(a);this.playlist.push(a);c?this.play(this.playlist.length-1):this.original.length===1&&this.select(0)},remove:function(a){var c=this;if(a===f)return this._initPlaylist([]),this._refresh(function(){b(c.cssSelector.jPlayer).jPlayer("clearMedia")}),!0;else if(this.removing)return!1;else{a=a<0?c.original.length+
a:a;if(0<=a&&a<this.playlist.length)this.removing=!0,b(this.cssSelector.playlist+" li:nth-child("+(a+1)+")").slideUp(this.options.playlistOptions.removeTime,function(){b(this).remove();if(c.shuffled){var d=c.playlist[a];b.each(c.original,function(a){if(c.original[a]===d)return c.original.splice(a,1),!1})}else c.original.splice(a,1);c.playlist.splice(a,1);c.original.length?a===c.current?(c.current=a<c.original.length?c.current:c.original.length-1,c.select(c.current)):a<c.current&&c.current--:(b(c.cssSelector.jPlayer).jPlayer("clearMedia"),
c.current=0,c.shuffled=!1,c._updateControls());c.removing=!1});return!0}},select:function(a){a=a<0?this.original.length+a:a;0<=a&&a<this.playlist.length?(this.current=a,this._highlight(a),b(this.cssSelector.jPlayer).jPlayer("setMedia",this.playlist[this.current])):this.current=0},play:function(a){a=a<0?this.original.length+a:a;0<=a&&a<this.playlist.length?this.playlist.length&&(this.select(a),b(this.cssSelector.jPlayer).jPlayer("play")):a===f&&b(this.cssSelector.jPlayer).jPlayer("play")},pause:function(){b(this.cssSelector.jPlayer).jPlayer("pause")},
next:function(){var a=this.current+1<this.playlist.length?this.current+1:0;this.loop?a===0&&this.shuffled&&this.options.playlistOptions.shuffleOnLoop&&this.playlist.length>1?this.shuffle(!0,!0):this.play(a):a>0&&this.play(a)},previous:function(){var a=this.current-1>=0?this.current-1:this.playlist.length-1;(this.loop&&this.options.playlistOptions.loopOnPrevious||a<this.playlist.length-1)&&this.play(a)},shuffle:function(a,c){var d=this;a===f&&(a=!this.shuffled);(a||a!==this.shuffled)&&b(this.cssSelector.playlist+
" ul").slideUp(this.options.playlistOptions.shuffleTime,function(){(d.shuffled=a)?d.playlist.sort(function(){return 0.5-Math.random()}):d._originalPlaylist();d._refresh(!0);c||!b(d.cssSelector.jPlayer).data("jPlayer").status.paused?d.play(0):d.select(0);b(this).slideDown(d.options.playlistOptions.shuffleTime)})}}})(jQuery);
/*!
 * jQuery Form Plugin
 * version: 2.94 (13-DEC-2011)
 * @requires jQuery v1.3.2 or later
 *
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Dual licensed under the MIT and GPL licenses:
 *	http://www.opensource.org/licenses/mit-license.php
 *	http://www.gnu.org/licenses/gpl.html
 */
;(function($) {

/*
	Usage Note:
	-----------
	Do not use both ajaxSubmit and ajaxForm on the same form.  These
	functions are intended to be exclusive.  Use ajaxSubmit if you want
	to bind your own submit handler to the form.  For example,

	$(document).ready(function() {
		$('#myForm').bind('submit', function(e) {
			e.preventDefault(); // <-- important
			$(this).ajaxSubmit({
				target: '#output'
			});
		});
	});

	Use ajaxForm when you want the plugin to manage all the event binding
	for you.  For example,

	$(document).ready(function() {
		$('#myForm').ajaxForm({
			target: '#output'
		});
	});

	When using ajaxForm, the ajaxSubmit function will be invoked for you
	at the appropriate time.
*/

/**
 * ajaxSubmit() provides a mechanism for immediately submitting
 * an HTML form using AJAX.
 */
$.fn.ajaxSubmit = function(options) {
	// fast fail if nothing selected (http://dev.jquery.com/ticket/2752)
	if (!this.length) {
		log('ajaxSubmit: skipping submit process - no element selected');
		return this;
	}
	
	var method, action, url, $form = this;

	if (typeof options == 'function') {
		options = { success: options };
	}

	method = this.attr('method');
	action = this.attr('action');
	url = (typeof action === 'string') ? $.trim(action) : '';
	url = url || window.location.href || '';
	if (url) {
		// clean url (don't include hash vaue)
		url = (url.match(/^([^#]+)/)||[])[1];
	}

	options = $.extend(true, {
		url:  url,
		success: $.ajaxSettings.success,
		type: method || 'GET',
		iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank'
	}, options);

	// hook for manipulating the form data before it is extracted;
	// convenient for use with rich editors like tinyMCE or FCKEditor
	var veto = {};
	this.trigger('form-pre-serialize', [this, options, veto]);
	if (veto.veto) {
		log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');
		return this;
	}

	// provide opportunity to alter form data before it is serialized
	if (options.beforeSerialize && options.beforeSerialize(this, options) === false) {
		log('ajaxSubmit: submit aborted via beforeSerialize callback');
		return this;
	}

	var traditional = options.traditional;
	if ( traditional === undefined ) {
		traditional = $.ajaxSettings.traditional;
	}
	
	var qx,n,v,a = this.formToArray(options.semantic);
	if (options.data) {
		options.extraData = options.data;
		qx = $.param(options.data, traditional);
	}

	// give pre-submit callback an opportunity to abort the submit
	if (options.beforeSubmit && options.beforeSubmit(a, this, options) === false) {
		log('ajaxSubmit: submit aborted via beforeSubmit callback');
		return this;
	}

	// fire vetoable 'validate' event
	this.trigger('form-submit-validate', [a, this, options, veto]);
	if (veto.veto) {
		log('ajaxSubmit: submit vetoed via form-submit-validate trigger');
		return this;
	}

	var q = $.param(a, traditional);
	if (qx) {
		q = ( q ? (q + '&' + qx) : qx );
	}	
	if (options.type.toUpperCase() == 'GET') {
		options.url += (options.url.indexOf('?') >= 0 ? '&' : '?') + q;
		options.data = null;  // data is null for 'get'
	}
	else {
		options.data = q; // data is the query string for 'post'
	}

	var callbacks = [];
	if (options.resetForm) {
		callbacks.push(function() { $form.resetForm(); });
	}
	if (options.clearForm) {
		callbacks.push(function() { $form.clearForm(options.includeHidden); });
	}

	// perform a load on the target only if dataType is not provided
	if (!options.dataType && options.target) {
		var oldSuccess = options.success || function(){};
		callbacks.push(function(data) {
			var fn = options.replaceTarget ? 'replaceWith' : 'html';
			$(options.target)[fn](data).each(oldSuccess, arguments);
		});
	}
	else if (options.success) {
		callbacks.push(options.success);
	}

	options.success = function(data, status, xhr) { // jQuery 1.4+ passes xhr as 3rd arg
		var context = options.context || options;	// jQuery 1.4+ supports scope context 
		for (var i=0, max=callbacks.length; i < max; i++) {
			callbacks[i].apply(context, [data, status, xhr || $form, $form]);
		}
	};

	// are there files to upload?
	var fileInputs = $('input:file:enabled[value]', this); // [value] (issue #113)
	var hasFileInputs = fileInputs.length > 0;
	var mp = 'multipart/form-data';
	var multipart = ($form.attr('enctype') == mp || $form.attr('encoding') == mp);

	var fileAPI = !!(hasFileInputs && fileInputs.get(0).files && window.FormData);
	log("fileAPI :" + fileAPI);
	var shouldUseFrame = (hasFileInputs || multipart) && !fileAPI;

	// options.iframe allows user to force iframe mode
	// 06-NOV-09: now defaulting to iframe mode if file input is detected
	if (options.iframe !== false && (options.iframe || shouldUseFrame)) {
		// hack to fix Safari hang (thanks to Tim Molendijk for this)
		// see:  http://groups.google.com/group/jquery-dev/browse_thread/thread/36395b7ab510dd5d
		if (options.closeKeepAlive) {
			$.get(options.closeKeepAlive, function() {
				fileUploadIframe(a);
			});
		}
  		else {
			fileUploadIframe(a);
  		}
	}
	else if ((hasFileInputs || multipart) && fileAPI) {
		options.progress = options.progress || $.noop;
		fileUploadXhr(a);
	}
	else {
		$.ajax(options);
	}

	 // fire 'notify' event
	 this.trigger('form-submit-notify', [this, options]);
	 return this;

	 // XMLHttpRequest Level 2 file uploads (big hat tip to francois2metz)
	function fileUploadXhr(a) {
		var formdata = new FormData();

		for (var i=0; i < a.length; i++) {
			if (a[i].type == 'file')
				continue;
			formdata.append(a[i].name, a[i].value);
		}

		$form.find('input:file:enabled').each(function(){
			var name = $(this).attr('name'), files = this.files;
			if (name) {
				for (var i=0; i < files.length; i++)
					formdata.append(name, files[i]);
			}
		});

		if (options.extraData) {
			for (var k in options.extraData)
				formdata.append(k, options.extraData[k])
		}

		options.data = null;

		var s = $.extend(true, {}, $.ajaxSettings, options, {
			contentType: false,
			processData: false,
			cache: false,
			type: 'POST'
		});

      s.context = s.context || s;

      s.data = null;
      var beforeSend = s.beforeSend;
      s.beforeSend = function(xhr, o) {
          o.data = formdata;
          if(xhr.upload) { // unfortunately, jQuery doesn't expose this prop (http://bugs.jquery.com/ticket/10190)
              xhr.upload.onprogress = function(event) {
                  o.progress(event.position, event.total);
              };
          }
          if(beforeSend)
              beforeSend.call(o, xhr, options);
      };
      $.ajax(s);
   }

	// private function for handling file uploads (hat tip to YAHOO!)
	function fileUploadIframe(a) {
		var form = $form[0], el, i, s, g, id, $io, io, xhr, sub, n, timedOut, timeoutHandle;
		var useProp = !!$.fn.prop;

		if (a) {
			if ( useProp ) {
				// ensure that every serialized input is still enabled
				for (i=0; i < a.length; i++) {
					el = $(form[a[i].name]);
					el.prop('disabled', false);
				}
			} else {
				for (i=0; i < a.length; i++) {
					el = $(form[a[i].name]);
					el.removeAttr('disabled');
				}
			};
		}

		if ($(':input[name=submit],:input[id=submit]', form).length) {
			// if there is an input with a name or id of 'submit' then we won't be
			// able to invoke the submit fn on the form (at least not x-browser)
			alert('Error: Form elements must not have name or id of "submit".');
			return;
		}
		
		s = $.extend(true, {}, $.ajaxSettings, options);
		s.context = s.context || s;
		id = 'jqFormIO' + (new Date().getTime());
		if (s.iframeTarget) {
			$io = $(s.iframeTarget);
			n = $io.attr('name');
			if (n == null)
			 	$io.attr('name', id);
			else
				id = n;
		}
		else {
			$io = $('<iframe name="' + id + '" src="'+ s.iframeSrc +'" />');
			$io.css({ position: 'absolute', top: '-1000px', left: '-1000px' });
		}
		io = $io[0];


		xhr = { // mock object
			aborted: 0,
			responseText: null,
			responseXML: null,
			status: 0,
			statusText: 'n/a',
			getAllResponseHeaders: function() {},
			getResponseHeader: function() {},
			setRequestHeader: function() {},
			abort: function(status) {
				var e = (status === 'timeout' ? 'timeout' : 'aborted');
				log('aborting upload... ' + e);
				this.aborted = 1;
				$io.attr('src', s.iframeSrc); // abort op in progress
				xhr.error = e;
				s.error && s.error.call(s.context, xhr, e, status);
				g && $.event.trigger("ajaxError", [xhr, s, e]);
				s.complete && s.complete.call(s.context, xhr, e);
			}
		};

		g = s.global;
		// trigger ajax global events so that activity/block indicators work like normal
		if (g && ! $.active++) {
			$.event.trigger("ajaxStart");
		}
		if (g) {
			$.event.trigger("ajaxSend", [xhr, s]);
		}

		if (s.beforeSend && s.beforeSend.call(s.context, xhr, s) === false) {
			if (s.global) {
				$.active--;
			}
			return;
		}
		if (xhr.aborted) {
			return;
		}

		// add submitting element to data if we know it
		sub = form.clk;
		if (sub) {
			n = sub.name;
			if (n && !sub.disabled) {
				s.extraData = s.extraData || {};
				s.extraData[n] = sub.value;
				if (sub.type == "image") {
					s.extraData[n+'.x'] = form.clk_x;
					s.extraData[n+'.y'] = form.clk_y;
				}
			}
		}
		
		var CLIENT_TIMEOUT_ABORT = 1;
		var SERVER_ABORT = 2;

		function getDoc(frame) {
			var doc = frame.contentWindow ? frame.contentWindow.document : frame.contentDocument ? frame.contentDocument : frame.document;
			return doc;
		}
		
		// Rails CSRF hack (thanks to Yvan Barthelemy)
		var csrf_token = $('meta[name=csrf-token]').attr('content');
		var csrf_param = $('meta[name=csrf-param]').attr('content');
		if (csrf_param && csrf_token) {
			s.extraData = s.extraData || {};
			s.extraData[csrf_param] = csrf_token;
		}

		// take a breath so that pending repaints get some cpu time before the upload starts
		function doSubmit() {
			// make sure form attrs are set
			var t = $form.attr('target'), a = $form.attr('action');

			// update form attrs in IE friendly way
			form.setAttribute('target',id);
			if (!method) {
				form.setAttribute('method', 'POST');
			}
			if (a != s.url) {
				form.setAttribute('action', s.url);
			}

			// ie borks in some cases when setting encoding
			if (! s.skipEncodingOverride && (!method || /post/i.test(method))) {
				$form.attr({
					encoding: 'multipart/form-data',
					enctype:  'multipart/form-data'
				});
			}

			// support timout
			if (s.timeout) {
				timeoutHandle = setTimeout(function() { timedOut = true; cb(CLIENT_TIMEOUT_ABORT); }, s.timeout);
			}
			
			// look for server aborts
			function checkState() {
				try {
					var state = getDoc(io).readyState;
					log('state = ' + state);
					if (state.toLowerCase() == 'uninitialized')
						setTimeout(checkState,50);
				}
				catch(e) {
					log('Server abort: ' , e, ' (', e.name, ')');
					cb(SERVER_ABORT);
					timeoutHandle && clearTimeout(timeoutHandle);
					timeoutHandle = undefined;
				}
			}

			// add "extra" data to form if provided in options
			var extraInputs = [];
			try {
				if (s.extraData) {
					for (var n in s.extraData) {
						extraInputs.push(
							$('<input type="hidden" name="'+n+'">').attr('value',s.extraData[n])
								.appendTo(form)[0]);
					}
				}

				if (!s.iframeTarget) {
					// add iframe to doc and submit the form
					$io.appendTo('body');
					io.attachEvent ? io.attachEvent('onload', cb) : io.addEventListener('load', cb, false);
				}
				setTimeout(checkState,15);
				form.submit();
			}
			finally {
				// reset attrs and remove "extra" input elements
				form.setAttribute('action',a);
				if(t) {
					form.setAttribute('target', t);
				} else {
					$form.removeAttr('target');
				}
				$(extraInputs).remove();
			}
		}

		if (s.forceSync) {
			doSubmit();
		}
		else {
			setTimeout(doSubmit, 10); // this lets dom updates render
		}

		var data, doc, domCheckCount = 50, callbackProcessed;

		function cb(e) {
			if (xhr.aborted || callbackProcessed) {
				return;
			}
			try {
				doc = getDoc(io);
			}
			catch(ex) {
				log('cannot access response document: ', ex);
				e = SERVER_ABORT;
			}
			if (e === CLIENT_TIMEOUT_ABORT && xhr) {
				xhr.abort('timeout');
				return;
			}
			else if (e == SERVER_ABORT && xhr) {
				xhr.abort('server abort');
				return;
			}

			if (!doc || doc.location.href == s.iframeSrc) {
				// response not received yet
				if (!timedOut)
					return;
			}
			io.detachEvent ? io.detachEvent('onload', cb) : io.removeEventListener('load', cb, false);

			var status = 'success', errMsg;
			try {
				if (timedOut) {
					throw 'timeout';
				}

				var isXml = s.dataType == 'xml' || doc.XMLDocument || $.isXMLDoc(doc);
				log('isXml='+isXml);
				if (!isXml && window.opera && (doc.body == null || doc.body.innerHTML == '')) {
					if (--domCheckCount) {
						// in some browsers (Opera) the iframe DOM is not always traversable when
						// the onload callback fires, so we loop a bit to accommodate
						log('requeing onLoad callback, DOM not available');
						setTimeout(cb, 250);
						return;
					}
					// let this fall through because server response could be an empty document
					//log('Could not access iframe DOM after mutiple tries.');
					//throw 'DOMException: not available';
				}

				//log('response detected');
				var docRoot = doc.body ? doc.body : doc.documentElement;
				xhr.responseText = docRoot ? docRoot.innerHTML : null;
				xhr.responseXML = doc.XMLDocument ? doc.XMLDocument : doc;
				if (isXml)
					s.dataType = 'xml';
				xhr.getResponseHeader = function(header){
					var headers = {'content-type': s.dataType};
					return headers[header];
				};
				// support for XHR 'status' & 'statusText' emulation :
				if (docRoot) {
					xhr.status = Number( docRoot.getAttribute('status') ) || xhr.status;
					xhr.statusText = docRoot.getAttribute('statusText') || xhr.statusText;
				}

				var dt = (s.dataType || '').toLowerCase();
				var scr = /(json|script|text)/.test(dt);
				if (scr || s.textarea) {
					// see if user embedded response in textarea
					var ta = doc.getElementsByTagName('textarea')[0];
					if (ta) {
						xhr.responseText = ta.value;
						// support for XHR 'status' & 'statusText' emulation :
						xhr.status = Number( ta.getAttribute('status') ) || xhr.status;
						xhr.statusText = ta.getAttribute('statusText') || xhr.statusText;
					}
					else if (scr) {
						// account for browsers injecting pre around json response
						var pre = doc.getElementsByTagName('pre')[0];
						var b = doc.getElementsByTagName('body')[0];
						if (pre) {
							xhr.responseText = pre.textContent ? pre.textContent : pre.innerText;
						}
						else if (b) {
							xhr.responseText = b.textContent ? b.textContent : b.innerText;
						}
					}
				}
				else if (dt == 'xml' && !xhr.responseXML && xhr.responseText != null) {
					xhr.responseXML = toXml(xhr.responseText);
				}

				try {
					data = httpData(xhr, dt, s);
				}
				catch (e) {
					status = 'parsererror';
					xhr.error = errMsg = (e || status);
				}
			}
			catch (e) {
				log('error caught: ',e);
				status = 'error';
				xhr.error = errMsg = (e || status);
			}

			if (xhr.aborted) {
				log('upload aborted');
				status = null;
			}

			if (xhr.status) { // we've set xhr.status
				status = (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) ? 'success' : 'error';
			}

			// ordering of these callbacks/triggers is odd, but that's how $.ajax does it
			if (status === 'success') {
				s.success && s.success.call(s.context, data, 'success', xhr);
				g && $.event.trigger("ajaxSuccess", [xhr, s]);
			}
			else if (status) {
				if (errMsg == undefined)
					errMsg = xhr.statusText;
				s.error && s.error.call(s.context, xhr, status, errMsg);
				g && $.event.trigger("ajaxError", [xhr, s, errMsg]);
			}

			g && $.event.trigger("ajaxComplete", [xhr, s]);

			if (g && ! --$.active) {
				$.event.trigger("ajaxStop");
			}

			s.complete && s.complete.call(s.context, xhr, status);

			callbackProcessed = true;
			if (s.timeout)
				clearTimeout(timeoutHandle);

			// clean up
			setTimeout(function() {
				if (!s.iframeTarget)
					$io.remove();
				xhr.responseXML = null;
			}, 100);
		}

		var toXml = $.parseXML || function(s, doc) { // use parseXML if available (jQuery 1.5+)
			if (window.ActiveXObject) {
				doc = new ActiveXObject('Microsoft.XMLDOM');
				doc.async = 'false';
				doc.loadXML(s);
			}
			else {
				doc = (new DOMParser()).parseFromString(s, 'text/xml');
			}
			return (doc && doc.documentElement && doc.documentElement.nodeName != 'parsererror') ? doc : null;
		};
		var parseJSON = $.parseJSON || function(s) {
			return window['eval']('(' + s + ')');
		};

		var httpData = function( xhr, type, s ) { // mostly lifted from jq1.4.4

			var ct = xhr.getResponseHeader('content-type') || '',
				xml = type === 'xml' || !type && ct.indexOf('xml') >= 0,
				data = xml ? xhr.responseXML : xhr.responseText;

			if (xml && data.documentElement.nodeName === 'parsererror') {
				$.error && $.error('parsererror');
			}
			if (s && s.dataFilter) {
				data = s.dataFilter(data, type);
			}
			if (typeof data === 'string') {
				if (type === 'json' || !type && ct.indexOf('json') >= 0) {
					data = parseJSON(data);
				} else if (type === "script" || !type && ct.indexOf("javascript") >= 0) {
					$.globalEval(data);
				}
			}
			return data;
		};
	}
};

/**
 * ajaxForm() provides a mechanism for fully automating form submission.
 *
 * The advantages of using this method instead of ajaxSubmit() are:
 *
 * 1: This method will include coordinates for <input type="image" /> elements (if the element
 *	is used to submit the form).
 * 2. This method will include the submit element's name/value data (for the element that was
 *	used to submit the form).
 * 3. This method binds the submit() method to the form for you.
 *
 * The options argument for ajaxForm works exactly as it does for ajaxSubmit.  ajaxForm merely
 * passes the options argument along after properly binding events for submit elements and
 * the form itself.
 */
$.fn.ajaxForm = function(options) {
	// in jQuery 1.3+ we can fix mistakes with the ready state
	if (this.length === 0) {
		var o = { s: this.selector, c: this.context };
		if (!$.isReady && o.s) {
			log('DOM not ready, queuing ajaxForm');
			$(function() {
				$(o.s,o.c).ajaxForm(options);
			});
			return this;
		}
		// is your DOM ready?  http://docs.jquery.com/Tutorials:Introducing_$(document).ready()
		log('terminating; zero elements found by selector' + ($.isReady ? '' : ' (DOM not ready)'));
		return this;
	}

	return this.ajaxFormUnbind().bind('submit.form-plugin', function(e) {
		if (!e.isDefaultPrevented()) { // if event has been canceled, don't proceed
			e.preventDefault();
			$(this).ajaxSubmit(options);
		}
	}).bind('click.form-plugin', function(e) {
		var target = e.target;
		var $el = $(target);
		if (!($el.is(":submit,input:image"))) {
			// is this a child element of the submit el?  (ex: a span within a button)
			var t = $el.closest(':submit');
			if (t.length == 0) {
				return;
			}
			target = t[0];
		}
		var form = this;
		form.clk = target;
		if (target.type == 'image') {
			if (e.offsetX != undefined) {
				form.clk_x = e.offsetX;
				form.clk_y = e.offsetY;
			} else if (typeof $.fn.offset == 'function') { // try to use dimensions plugin
				var offset = $el.offset();
				form.clk_x = e.pageX - offset.left;
				form.clk_y = e.pageY - offset.top;
			} else {
				form.clk_x = e.pageX - target.offsetLeft;
				form.clk_y = e.pageY - target.offsetTop;
			}
		}
		// clear form vars
		setTimeout(function() { form.clk = form.clk_x = form.clk_y = null; }, 100);
	});
};

// ajaxFormUnbind unbinds the event handlers that were bound by ajaxForm
$.fn.ajaxFormUnbind = function() {
	return this.unbind('submit.form-plugin click.form-plugin');
};

/**
 * formToArray() gathers form element data into an array of objects that can
 * be passed to any of the following ajax functions: $.get, $.post, or load.
 * Each object in the array has both a 'name' and 'value' property.  An example of
 * an array for a simple login form might be:
 *
 * [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]
 *
 * It is this array that is passed to pre-submit callback functions provided to the
 * ajaxSubmit() and ajaxForm() methods.
 */
$.fn.formToArray = function(semantic) {
	var a = [];
	if (this.length === 0) {
		return a;
	}

	var form = this[0];
	var els = semantic ? form.getElementsByTagName('*') : form.elements;
	if (!els) {
		return a;
	}

	var i,j,n,v,el,max,jmax;
	for(i=0, max=els.length; i < max; i++) {
		el = els[i];
		n = el.name;
		if (!n) {
			continue;
		}

		if (semantic && form.clk && el.type == "image") {
			// handle image inputs on the fly when semantic == true
			if(!el.disabled && form.clk == el) {
				a.push({name: n, value: $(el).val(), type: el.type });
				a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
			}
			continue;
		}

		v = $.fieldValue(el, true);
		if (v && v.constructor == Array) {
			for(j=0, jmax=v.length; j < jmax; j++) {
				a.push({name: n, value: v[j]});
			}
		}
		else if (v !== null && typeof v != 'undefined') {
			a.push({name: n, value: v, type: el.type});
		}
	}

	if (!semantic && form.clk) {
		// input type=='image' are not found in elements array! handle it here
		var $input = $(form.clk), input = $input[0];
		n = input.name;
		if (n && !input.disabled && input.type == 'image') {
			a.push({name: n, value: $input.val()});
			a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
		}
	}
	return a;
};

/**
 * Serializes form data into a 'submittable' string. This method will return a string
 * in the format: name1=value1&amp;name2=value2
 */
$.fn.formSerialize = function(semantic) {
	//hand off to jQuery.param for proper encoding
	return $.param(this.formToArray(semantic));
};

/**
 * Serializes all field elements in the jQuery object into a query string.
 * This method will return a string in the format: name1=value1&amp;name2=value2
 */
$.fn.fieldSerialize = function(successful) {
	var a = [];
	this.each(function() {
		var n = this.name;
		if (!n) {
			return;
		}
		var v = $.fieldValue(this, successful);
		if (v && v.constructor == Array) {
			for (var i=0,max=v.length; i < max; i++) {
				a.push({name: n, value: v[i]});
			}
		}
		else if (v !== null && typeof v != 'undefined') {
			a.push({name: this.name, value: v});
		}
	});
	//hand off to jQuery.param for proper encoding
	return $.param(a);
};

/**
 * Returns the value(s) of the element in the matched set.  For example, consider the following form:
 *
 *  <form><fieldset>
 *	  <input name="A" type="text" />
 *	  <input name="A" type="text" />
 *	  <input name="B" type="checkbox" value="B1" />
 *	  <input name="B" type="checkbox" value="B2"/>
 *	  <input name="C" type="radio" value="C1" />
 *	  <input name="C" type="radio" value="C2" />
 *  </fieldset></form>
 *
 *  var v = $(':text').fieldValue();
 *  // if no values are entered into the text inputs
 *  v == ['','']
 *  // if values entered into the text inputs are 'foo' and 'bar'
 *  v == ['foo','bar']
 *
 *  var v = $(':checkbox').fieldValue();
 *  // if neither checkbox is checked
 *  v === undefined
 *  // if both checkboxes are checked
 *  v == ['B1', 'B2']
 *
 *  var v = $(':radio').fieldValue();
 *  // if neither radio is checked
 *  v === undefined
 *  // if first radio is checked
 *  v == ['C1']
 *
 * The successful argument controls whether or not the field element must be 'successful'
 * (per http://www.w3.org/TR/html4/interact/forms.html#successful-controls).
 * The default value of the successful argument is true.  If this value is false the value(s)
 * for each element is returned.
 *
 * Note: This method *always* returns an array.  If no valid value can be determined the
 *	array will be empty, otherwise it will contain one or more values.
 */
$.fn.fieldValue = function(successful) {
	for (var val=[], i=0, max=this.length; i < max; i++) {
		var el = this[i];
		var v = $.fieldValue(el, successful);
		if (v === null || typeof v == 'undefined' || (v.constructor == Array && !v.length)) {
			continue;
		}
		v.constructor == Array ? $.merge(val, v) : val.push(v);
	}
	return val;
};

/**
 * Returns the value of the field element.
 */
$.fieldValue = function(el, successful) {
	var n = el.name, t = el.type, tag = el.tagName.toLowerCase();
	if (successful === undefined) {
		successful = true;
	}

	if (successful && (!n || el.disabled || t == 'reset' || t == 'button' ||
		(t == 'checkbox' || t == 'radio') && !el.checked ||
		(t == 'submit' || t == 'image') && el.form && el.form.clk != el ||
		tag == 'select' && el.selectedIndex == -1)) {
			return null;
	}

	if (tag == 'select') {
		var index = el.selectedIndex;
		if (index < 0) {
			return null;
		}
		var a = [], ops = el.options;
		var one = (t == 'select-one');
		var max = (one ? index+1 : ops.length);
		for(var i=(one ? index : 0); i < max; i++) {
			var op = ops[i];
			if (op.selected) {
				var v = op.value;
				if (!v) { // extra pain for IE...
					v = (op.attributes && op.attributes['value'] && !(op.attributes['value'].specified)) ? op.text : op.value;
				}
				if (one) {
					return v;
				}
				a.push(v);
			}
		}
		return a;
	}
	return $(el).val();
};

/**
 * Clears the form data.  Takes the following actions on the form's input fields:
 *  - input text fields will have their 'value' property set to the empty string
 *  - select elements will have their 'selectedIndex' property set to -1
 *  - checkbox and radio inputs will have their 'checked' property set to false
 *  - inputs of type submit, button, reset, and hidden will *not* be effected
 *  - button elements will *not* be effected
 */
$.fn.clearForm = function(includeHidden) {
	return this.each(function() {
		$('input,select,textarea', this).clearFields(includeHidden);
	});
};

/**
 * Clears the selected form elements.
 */
$.fn.clearFields = $.fn.clearInputs = function(includeHidden) {
	var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
	return this.each(function() {
		var t = this.type, tag = this.tagName.toLowerCase();
		if (re.test(t) || tag == 'textarea' || (includeHidden && /hidden/.test(t)) ) {
			this.value = '';
		}
		else if (t == 'checkbox' || t == 'radio') {
			this.checked = false;
		}
		else if (tag == 'select') {
			this.selectedIndex = -1;
		}
	});
};

/**
 * Resets the form data.  Causes all form elements to be reset to their original value.
 */
$.fn.resetForm = function() {
	return this.each(function() {
		// guard against an input with the name of 'reset'
		// note that IE reports the reset function as an 'object'
		if (typeof this.reset == 'function' || (typeof this.reset == 'object' && !this.reset.nodeType)) {
			this.reset();
		}
	});
};

/**
 * Enables or disables any matching elements.
 */
$.fn.enable = function(b) {
	if (b === undefined) {
		b = true;
	}
	return this.each(function() {
		this.disabled = !b;
	});
};

/**
 * Checks/unchecks any matching checkboxes or radio buttons and
 * selects/deselects and matching option elements.
 */
$.fn.selected = function(select) {
	if (select === undefined) {
		select = true;
	}
	return this.each(function() {
		var t = this.type;
		if (t == 'checkbox' || t == 'radio') {
			this.checked = select;
		}
		else if (this.tagName.toLowerCase() == 'option') {
			var $sel = $(this).parent('select');
			if (select && $sel[0] && $sel[0].type == 'select-one') {
				// deselect all other options
				$sel.find('option').selected(false);
			}
			this.selected = select;
		}
	});
};

// expose debug var
$.fn.ajaxSubmit.debug = false;

// helper fn for console logging
function log() {
	if (!$.fn.ajaxSubmit.debug) 
		return;
	var msg = '[jquery.form] ' + Array.prototype.join.call(arguments,'');
	if (window.console && window.console.log) {
		window.console.log(msg);
	}
	else if (window.opera && window.opera.postError) {
		window.opera.postError(msg);
	}
};

})(jQuery);

/*
 * jQuery Plugin : jConfirmAction
 * 
 * by Hidayat Sagita, modified by Sevajol Bastien (http://blog.bux.fr/tag/jconfirmaction/)
 * http://www.webstuffshare.com
 * Licensed Under GPL version 2 license.
 *
 */
(function($){

	jQuery.fn.jConfirmAction = function (options) {
		
		// Some jConfirmAction options (limited to customize language) :
		// question : a text for your question.
		// yesAnswer : a text for Yes answer.
		// cancelAnswer : a text for Cancel/No answer.
		var theOptions = jQuery.extend ({
			question: "Are You Sure ?",
			yesAnswer: "Yes",
			cancelAnswer: "Cancel",
      onYes: function(){},
      onOpen: function(){},
      onClose: function(){},
      justOneAtTime: true
		}, options);
					
			$(this).live('click', function(e) {

        if (theOptions.justOneAtTime)
        {
          $('div.question').remove();
        }

        theOptions.onOpen($(this));

				e.preventDefault();
				
				if($(this).next('.question').length <= 0)
					$(this).after(
            '<div class="question">'+theOptions.question
            +'<br/> <span class="yes">'+theOptions.yesAnswer
            +'</span><span class="cancel">'+theOptions.cancelAnswer
            +'</span></div>'
          );
				
				$(this).next('.question').animate({opacity: 1}, 300);
				
				$(this).next('.question').find('.yes').bind('click', function(){
					theOptions.onYes($(this).parents('div.question').prev('a'));
				});
		
				$(this).next('.question').find('.cancel').bind('click', function(){
					$(this).parents('.question').fadeOut(300, function() {
            theOptions.onClose($(this).parents('div.question').prev('a'));
					});
				});
				
			});
	}
	
})(jQuery);
/*
 * jQuery Foundation Joyride Plugin 2.0.3
 * http://foundation.zurb.com
 * Copyright 2012, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/

/*jslint unparam: true, browser: true, indent: 2 */

;(function ($, window, undefined) {
  'use strict';

  var defaults = {
      'version'              : '2.0.3',
      'tipLocation'          : 'bottom',  // 'top' or 'bottom' in relation to parent
      'nubPosition'          : 'auto',    // override on a per tooltip bases
      'scrollSpeed'          : 300,       // Page scrolling speed in milliseconds
      'timer'                : 0,         // 0 = no timer , all other numbers = timer in milliseconds
      'startTimerOnClick'    : true,      // true or false - true requires clicking the first button start the timer
      'startOffset'          : 0,         // the index of the tooltip you want to start on (index of the li)
      'nextButton'           : true,      // true or false to control whether a next button is used
      'tipAnimation'         : 'fade',    // 'pop' or 'fade' in each tip
      'pauseAfter'           : [],        // array of indexes where to pause the tour after
      'tipAnimationFadeSpeed': 300,       // when tipAnimation = 'fade' this is speed in milliseconds for the transition
      'cookieMonster'        : false,     // true or false to control whether cookies are used
      'cookieName'           : 'joyride', // Name the cookie you'll use
      'cookieDomain'         : false,     // Will this cookie be attached to a domain, ie. '.notableapp.com'
      'tipContainer'         : 'body',    // Where will the tip be attached
      'postRideCallback'     : $.noop,    // A method to call once the tour closes (canceled or complete)
      'postStepCallback'     : $.noop,    // A method to call after each step
      'template' : { // HTML segments for tip layout
        'link'    : '<a href="#close" class="joyride-close-tip">X</a>',
        'timer'   : '<div class="joyride-timer-indicator-wrap"><span class="joyride-timer-indicator"></span></div>',
        'tip'     : '<div class="joyride-tip-guide"><span class="joyride-nub"></span></div>',
        'wrapper' : '<div class="joyride-content-wrapper"></div>',
        'button'  : '<a href="#" class="joyride-next-tip"></a>'
      }
    },

    Modernizr = Modernizr || false,

    settings = {},

    methods = {

      init : function (opts) {
        return this.each(function () {

          if ($.isEmptyObject(settings)) {
            settings = $.extend(true, defaults, opts);

            // non configurable settings
            settings.document = window.document;
            settings.$document = $(settings.document);
            settings.$window = $(window);
            settings.$content_el = $(this);
            settings.body_offset = $(settings.tipContainer).position();
            settings.$tip_content = $('> li', settings.$content_el);
            settings.paused = false;
            settings.attempts = 0;

            settings.tipLocationPatterns = {
              top: ['bottom'],
              bottom: [], // bottom should not need to be repositioned
              left: ['right', 'top', 'bottom'],
              right: ['left', 'top', 'bottom']
            };

            // are we using jQuery 1.7+
            methods.jquery_check();

            // can we create cookies?
            if (!$.isFunction($.cookie)) {
              settings.cookieMonster = false;
            }

            // generate the tips and insert into dom.
            if (!settings.cookieMonster || !$.cookie(settings.cookieName)) {

              settings.$tip_content.each(function (index) {
                methods.create({$li : $(this), index : index});
              });

              // show first tip
              if (!settings.startTimerOnClick && settings.timer > 0) {
                methods.show('init');
                methods.startTimer();
              } else {
                methods.show('init');
              }

            }

            settings.$document.on('click.joyride', '.joyride-next-tip, .joyride-modal-bg', function (e) {
              e.preventDefault();

              if (settings.$li.next().length < 1) {
                methods.end();
              } else if (settings.timer > 0) {
                clearTimeout(settings.automate);
                methods.hide();
                methods.show();
                methods.startTimer();
              } else {
                methods.hide();
                methods.show();
              }

            });

            settings.$document.on('click.joyride', '.joyride-close-tip', function (e) {
              e.preventDefault();
              methods.end();
            });

            settings.$window.bind('resize.joyride', function (e) {
              if (methods.is_phone()) {
                methods.pos_phone();
              } else {
                methods.pos_default();
              }
            });
          } else {
            methods.restart();
          }

        });
      },

      // call this method when you want to resume the tour
      resume : function () {
        methods.set_li();
        methods.show();
      },

      nextTip: function(){
            if (settings.$li.next().length < 1) {
            methods.end();
            } else if (settings.timer > 0) {
            clearTimeout(settings.automate);
            methods.hide();
            methods.show();
            methods.startTimer();
            } else {
            methods.hide();
            methods.show();
            }
      }, 

      tip_template : function (opts) {
        var $blank, content;

        opts.tip_class = opts.tip_class || '';

        $blank = $(settings.template.tip).addClass(opts.tip_class);
        content = $.trim($(opts.li).html()) +
          methods.button_text(opts.button_text) +
          settings.template.link +
          methods.timer_instance(opts.index);

        $blank.append($(settings.template.wrapper));
        $blank.first().attr('data-index', opts.index);
        $('.joyride-content-wrapper', $blank).append(content);

        return $blank[0];
      },

      timer_instance : function (index) {
        var txt;

        if ((index === 0 && settings.startTimerOnClick && settings.timer > 0) || settings.timer === 0) {
          txt = '';
        } else {
          txt = methods.outerHTML($(settings.template.timer)[0]);
        }
        return txt;
      },

      button_text : function (txt) {
        if (settings.nextButton) {
          txt = $.trim(txt) || 'Next';
          txt = methods.outerHTML($(settings.template.button).append(txt)[0]);
        } else {
          txt = '';
        }
        return txt;
      },

      create : function (opts) {
        // backwards compatibility with data-text attribute
        var buttonText = opts.$li.attr('data-button') || opts.$li.attr('data-text'),
          tipClass = opts.$li.attr('class'),
          $tip_content = $(methods.tip_template({
            tip_class : tipClass,
            index : opts.index,
            button_text : buttonText,
            li : opts.$li
          }));

        $(settings.tipContainer).append($tip_content);
      },

      show : function (init) {
        var opts = {}, ii, opts_arr = [], opts_len = 0, p,
            $timer = null;

        // are we paused?
        if (settings.$li === undefined || ($.inArray(settings.$li.index(), settings.pauseAfter) === -1)) {

          // don't go to the next li if the tour was paused
          if (settings.paused) {
            settings.paused = false;
          } else {
            methods.set_li(init);
          }

          settings.attempts = 0;

          if (settings.$li.length && settings.$target.length > 0) {
            opts_arr = (settings.$li.data('options') || ':').split(';');
            opts_len = opts_arr.length;

            // parse options
            for (ii = opts_len - 1; ii >= 0; ii--) {
              p = opts_arr[ii].split(':');

              if (p.length === 2) {
                opts[$.trim(p[0])] = $.trim(p[1]);
              }
            }

            settings.tipSettings = $.extend({}, settings, opts);

            settings.tipSettings.tipLocationPattern = settings.tipLocationPatterns[settings.tipSettings.tipLocation];

            // scroll if not modal
            if (!/body/i.test(settings.$target.selector)) {
              methods.scroll_to();
            }

            if (methods.is_phone()) {
              methods.pos_phone(true);
            } else {
              methods.pos_default(true);
            }

            $timer = $('.joyride-timer-indicator', settings.$next_tip);

            if (/pop/i.test(settings.tipAnimation)) {

              $timer.outerWidth(0);

              if (settings.timer > 0) {

                settings.$next_tip.show();
                $timer.animate({
                  width: $('.joyride-timer-indicator-wrap', settings.$next_tip).outerWidth()
                }, settings.timer);

              } else {

                settings.$next_tip.show();

              }


            } else if (/fade/i.test(settings.tipAnimation)) {

              $timer.outerWidth(0);

              if (settings.timer > 0) {

                settings.$next_tip.fadeIn(settings.tipAnimationFadeSpeed);

                settings.$next_tip.show();
                $timer.animate({
                  width: $('.joyride-timer-indicator-wrap', settings.$next_tip).outerWidth()
                }, settings.timer);

              } else {

                settings.$next_tip.fadeIn(settings.tipAnimationFadeSpeed);

              }
            }

            settings.$current_tip = settings.$next_tip;

          // skip non-existent targets
          } else if (settings.$li && settings.$target.length < 1) {

            methods.show();

          } else {

            methods.end();

          }
        } else {

          settings.paused = true;

        }

      },

      // detect phones with media queries if supported.
      is_phone : function () {
        if (Modernizr) {
          return Modernizr.mq('only screen and (max-width: 767px)');
        }

        return (settings.$window.width() < 767) ? true : false;
      },

      hide : function () {
        settings.postStepCallback(settings.$li.index(), settings.$current_tip);
        $('.joyride-modal-bg').hide();
        settings.$current_tip.hide();
      },

      set_li : function (init) {
        if (init) {
          settings.$li = settings.$tip_content.eq(settings.startOffset);
          methods.set_next_tip();
          settings.$current_tip = settings.$next_tip;
        } else {
          settings.$li = settings.$li.next();
          methods.set_next_tip();
        }

        methods.set_target();
      },

      set_next_tip : function () {
        settings.$next_tip = $('.joyride-tip-guide[data-index=' + settings.$li.index() + ']');
      },

      set_target : function () {
        var cl = settings.$li.attr('data-class'),
            id = settings.$li.attr('data-id'),
            $sel = function () {
              if (id) {
                return $(settings.document.getElementById(id));
              } else if (cl) {
                return $('.' + cl).first();
              } else {
                return $('body');
              }
            };

        settings.$target = $sel();
      },

      scroll_to : function () {
        var window_half, tipOffset;

        window_half = settings.$window.height() / 2;
        tipOffset = Math.ceil(settings.$target.offset().top - window_half + settings.$next_tip.outerHeight());

        $("html, body").stop().animate({
          scrollTop: tipOffset
        }, settings.scrollSpeed);
      },

      paused : function () {
        if (($.inArray((settings.$li.index() + 1), settings.pauseAfter) === -1)) {
          return true;
        }

        return false;
      },

      destroy : function () {
        settings.$document.off('.joyride');
        $(window).off('.joyride');
        $('.joyride-close-tip, .joyride-next-tip, .joyride-modal-bg').off('.joyride');
        $('.joyride-tip-guide, .joyride-modal-bg').remove();
        clearTimeout(settings.automate);
        settings = {};
      },

      restart : function () {
        methods.hide();
        settings.$li = undefined;
        methods.show('init');
      },

      pos_default : function (init) {
        var half_fold = Math.ceil(settings.$window.height() / 2),
            tip_position = settings.$next_tip.offset(),
            $nub = $('.joyride-nub', settings.$next_tip),
            nub_height = Math.ceil($nub.outerHeight() / 2),
            toggle = init || false;

        // tip must not be "display: none" to calculate position
        if (toggle) {
          settings.$next_tip.css('visibility', 'hidden');
          settings.$next_tip.show();
        }

        if (!/body/i.test(settings.$target.selector)) {

            if (methods.bottom()) {
              settings.$next_tip.css({
                top: (settings.$target.offset().top + nub_height + settings.$target.outerHeight()),
                left: settings.$target.offset().left});

              methods.nub_position($nub, settings.tipSettings.nubPosition, 'top');

            } else if (methods.top()) {

              settings.$next_tip.css({
                top: (settings.$target.offset().top - settings.$next_tip.outerHeight() - nub_height),
                left: settings.$target.offset().left});

              methods.nub_position($nub, settings.tipSettings.nubPosition, 'bottom');

            } else if (methods.right()) {

              settings.$next_tip.css({
                top: settings.$target.offset().top,
                left: (settings.$target.outerWidth() + settings.$target.offset().left)});

              methods.nub_position($nub, settings.tipSettings.nubPosition, 'left');

            } else if (methods.left()) {

              settings.$next_tip.css({
                top: settings.$target.offset().top,
                left: (settings.$target.offset().left - settings.$next_tip.outerWidth() - nub_height)});

              methods.nub_position($nub, settings.tipSettings.nubPosition, 'right');

            }

            if (!methods.visible(methods.corners(settings.$next_tip)) && settings.attempts < settings.tipSettings.tipLocationPattern.length) {

              $nub.removeClass('bottom')
                .removeClass('top')
                .removeClass('right')
                .removeClass('left');

              settings.tipSettings.tipLocation = settings.tipSettings.tipLocationPattern[settings.attempts];

              settings.attempts++;

              methods.pos_default(true);

            }

        } else if (settings.$li.length) {

          methods.pos_modal($nub);

        }

        if (toggle) {
          settings.$next_tip.hide();
          settings.$next_tip.css('visibility', 'visible');
        }

      },

      pos_phone : function (init) {
        var tip_height = settings.$next_tip.outerHeight(),
            tip_offset = settings.$next_tip.offset(),
            target_height = settings.$target.outerHeight(),
            $nub = $('.joyride-nub', settings.$next_tip),
            nub_height = Math.ceil($nub.outerHeight() / 2),
            toggle = init || false;

        $nub.removeClass('bottom')
          .removeClass('top')
          .removeClass('right')
          .removeClass('left');

        if (toggle) {
          settings.$next_tip.css('visibility', 'hidden');
          settings.$next_tip.show();
        }

        if (!/body/i.test(settings.$target.selector)) {

          if (methods.top()) {

              settings.$next_tip.offset({top: settings.$target.offset().top - tip_height - nub_height});
              $nub.addClass('bottom');

          } else {

            settings.$next_tip.offset({top: settings.$target.offset().top + target_height + nub_height});
            $nub.addClass('top');

          }

        } else if (settings.$li.length) {

          methods.pos_modal($nub);

        }

        if (toggle) {
          settings.$next_tip.hide();
          settings.$next_tip.css('visibility', 'visible');
        }
      },

      pos_modal : function ($nub) {
        methods.center();
        $nub.hide();

        if ($('.joyride-modal-bg').length < 1) {
          $('body').append('<div class="joyride-modal-bg">').show();
        }

        if (/pop/i.test(settings.tipAnimation)) {
          $('.joyride-modal-bg').show();
        } else {
          $('.joyride-modal-bg').fadeIn(settings.tipAnimationFadeSpeed);
        }
      },

      center : function () {
        var $w = settings.$window;

        settings.$next_tip.css({
          top : ((($w.height() - settings.$next_tip.outerHeight()) / 2) + $w.scrollTop()),
          left : ((($w.width() - settings.$next_tip.outerWidth()) / 2) + $w.scrollLeft())
        });

        return true;
      },

      bottom : function () {
        return /bottom/i.test(settings.tipSettings.tipLocation);
      },

      top : function () {
        return /top/i.test(settings.tipSettings.tipLocation);
      },

      right : function () {
        return /right/i.test(settings.tipSettings.tipLocation);
      },

      left : function () {
        return /left/i.test(settings.tipSettings.tipLocation);
      },

      corners : function (el) {
        var w = settings.$window,
            right = w.width() + w.scrollLeft(),
            bottom = w.width() + w.scrollTop();

        return [
          el.offset().top <= w.scrollTop(),
          right <= el.offset().left + el.outerWidth(),
          bottom <= el.offset().top + el.outerHeight(),
          w.scrollLeft() >= el.offset().left
        ];
      },

      visible : function (hidden_corners) {
        var i = hidden_corners.length;

        while (i--) {
          if (hidden_corners[i]) return false;
        }

        return true;
      },

      nub_position : function (nub, pos, def) {
        if (pos === 'auto') {
          nub.addClass(def);
        } else {
          nub.addClass(pos);
        }
      },

      startTimer : function () {
        if (settings.$li.length) {
          settings.automate = setTimeout(function () {
            methods.hide();
            methods.show();
            methods.startTimer();
          }, settings.timer);
        } else {
          clearTimeout(settings.automate);
        }
      },

      end : function () {
        if (settings.cookieMonster) {
          $.cookie(settings.cookieName, 'ridden', { expires: 365, domain: settings.cookieDomain });
        }

        if (settings.timer > 0) {
          clearTimeout(settings.automate);
        }

        $('.joyride-modal-bg').hide();
        settings.$current_tip.hide();
        settings.postStepCallback(settings.$li.index(), settings.$current_tip);
        settings.postRideCallback(settings.$li.index(), settings.$current_tip);
      },

      jquery_check : function () {
        // define on() and off() for older jQuery
        if (!$.isFunction($.fn.on)) {

          $.fn.on = function (types, sel, fn) {

            return this.delegate(sel, types, fn);

          };

          $.fn.off = function (types, sel, fn) {

            return this.undelegate(sel, types, fn);

          };

          return false;
        }

        return true;
      },

      outerHTML : function (el) {
        // support FireFox < 11
        return el.outerHTML || new XMLSerializer().serializeToString(el);
      },

      version : function () {
        return settings.version;
      }

    };

  $.fn.joyride = function (method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' +  method + ' does not exist on jQuery.joyride');
    }
  };

}(jQuery, this));

function TagPrompt(select_tag_callback, tag_prompt_connector)
{
  // En plus je change une ligne !
  /* @var tags_selected array of Tag */
  var tags_selected = [];
  /* @var tags_proposed array of Tag */
  var tags_proposed = [];
  var _select_tag_callback = select_tag_callback;
  var _tag_prompt_connector = tag_prompt_connector;
  
  this.getProposedTagsForString = function(search_string, callback_success, callback_error)
  {
    tags_proposed = [];
    JQueryJson(url_search_tag, {'string_search': search_string}, function(response){
      if (response.status == 'error')
      {
        callback_error(response.error);
      }
      else if (response.status == 'success')
      {
        for (i in response.data)
        {
          var tag = new Tag(
            response.data[i].id,
            response.data[i].name
          );
          tags_proposed.push(tag);
        }
        callback_success(tags_proposed, search_string, response.message, response.same_found);
      }
    });
  }
  
  this.selectProposedTag = function (tag_id, tag_name)
  {
    if (!tag_id)
    {
      openTagSubmission(tag_name);
    }
    else
    {
      addTagToSelectedTags(findTagInProposedList(tag_id));
      _select_tag_callback(tags_selected);
    }
  }
  
  var openTagSubmission = function (tag_name)
  {
    // TODO : Cette partie du code n'est pas encore refactorisé
    
    // Effet fade-in du fond opaque
    $('body').append($('<div>').attr('id', 'fade')); 
    //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
    $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
    
    // En premier lieux on fait apparaître la fenêtre de confirmation
    var popup = $('<div>')
    .attr('id', 'add_tag')
    .addClass('popin_block')
    .css('width', '400px')
      //.append($('<h2>').append(string_tag_add_title))
    .append($('<form>')
      .attr('action', url_add_tag)
      .attr('method', 'post')
      .attr('name', 'add_tag')
      .ajaxForm(function(response) {
        /*
        *
        */
  
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
  
        if (response.status == 'success')
        {
          var tag = new Tag(response.tag_id, response.tag_name);
          addTagToProposedTags(tag);
          addTagToSelectedTags(tag);
          _tag_prompt_connector.updateOutput(tags_selected);
  
          $('#fade').fadeOut(400, function(){$('#fade').remove();});
          $('#add_tag').remove();
        }
  
        if (response.status == 'error')
        {
          $('form[name="add_tag"]').find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
  
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
  
          $('form[name="add_tag"]').prepend(ul_errors);
        }
  
        return false;
      })
  
      .append($('<div>').addClass('tag')
        .append($('<ul>')
          .append($('<li>').addClass('button')
            .append(tag_name))))
      .append($('<p>').append(string_tag_add_text))
      .append($('<p>').append(string_tag_add_argument))
      .append($('<textarea>').attr('name', 'argument'))
      .append($('<div>').addClass('inputs')
        .append($('<input>')
          .attr('type', 'button')
          .attr('value', string_tag_add_inputs_cancel)
          .addClass('button')
          .click(function(){
            $('#fade').fadeOut(1000, function(){$('#fade').remove();});
            $('#add_tag').remove();
  
            return false;
          })
        )
        .append($('<input>')
          .attr('type', 'submit')
          .attr('value', string_tag_add_inputs_submit)
          .addClass('button')
          .click(function(){
  
            // TODO: loader gif
  
          })
        )
        .append($('<input>').attr('type', 'hidden').attr('name', 'tag_name').val(tag_name))
      ))
    ;
  
    // Il faut ajouter le popup au dom avant de le positionner en css
    // Sinon la valeur height n'est pas encore calculable
    $('body').prepend(popup);
  
    //Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
    var popMargTop = (popup.height() + 50) / 2;
    var popMargLeft = (popup.width() + 50) / 2;
  
    //On affecte le margin
    $(popup).css({
      'margin-top' : -popMargTop,
      'margin-left' : -popMargLeft
    });
  
    return false;
  }
  
  this.openTagSubmission = function (tag_name)
  {
    // TODO : Cette partie du code n'est pas encore refactorisé
    
    // Effet fade-in du fond opaque
    $('body').append($('<div>').attr('id', 'fade')); 
    //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
    $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
    
    // En premier lieux on fait apparaître la fenêtre de confirmation
    var popup = $('<div>')
    .attr('id', 'add_tag')
    .addClass('popin_block')
    .css('width', '400px')
      //.append($('<h2>').append(string_tag_add_title))
    .append($('<form>')
      .attr('action', url_add_tag)
      .attr('method', 'post')
      .attr('name', 'add_tag')
      .ajaxForm(function(response) {
        /*
        *
        */
  
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
  
        if (response.status == 'success')
        {
          var tag = new Tag(response.tag_id, response.tag_name);
          addTagToProposedTags(tag);
          addTagToSelectedTags(tag);
          _tag_prompt_connector.updateOutput(tags_selected);
  
          $('#fade').fadeOut(400, function(){$('#fade').remove();});
          $('#add_tag').remove();
        }
  
        if (response.status == 'error')
        {
          $('form[name="add_tag"]').find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
  
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
  
          $('form[name="add_tag"]').prepend(ul_errors);
        }
  
        return false;
      })
  
      .append($('<div>').addClass('tag')
        .append($('<ul>')
          .append($('<li>').addClass('button')
            .append(tag_name))))
      .append($('<p>').append(string_tag_add_text))
      .append($('<p>').append(string_tag_add_argument))
      .append($('<textarea>').attr('name', 'argument'))
      .append($('<div>').addClass('inputs')
        .append($('<input>')
          .attr('type', 'button')
          .attr('value', string_tag_add_inputs_cancel)
          .addClass('button')
          .click(function(){
            $('#fade').fadeOut(1000, function(){$('#fade').remove();});
            $('#add_tag').remove();
  
            return false;
          })
        )
        .append($('<input>')
          .attr('type', 'submit')
          .attr('value', string_tag_add_inputs_submit)
          .addClass('button')
          .click(function(){
  
            // TODO: loader gif
  
          })
        )
        .append($('<input>').attr('type', 'hidden').attr('name', 'tag_name').val(tag_name))
      ))
    ;
  
    // Il faut ajouter le popup au dom avant de le positionner en css
    // Sinon la valeur height n'est pas encore calculable
    $('body').prepend(popup);
  
    //Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
    var popMargTop = (popup.height() + 50) / 2;
    var popMargLeft = (popup.width() + 50) / 2;
  
    //On affecte le margin
    $(popup).css({
      'margin-top' : -popMargTop,
      'margin-left' : -popMargLeft
    });
  
    return false;
  }
  
  var addTagToSelectedTags = function(tag)
  {
    var found = false;
    for (i in tags_selected)
    {
      if (tags_selected[i].id == tag.id)
      {
        found = true;
      }
    }
    if (!found)
    {
      tags_selected.push(tag);
    }
  }
  
  var addTagToProposedTags = function(tag)
  {
    var found = false;
    for (i in tags_proposed)
    {
      if (tags_proposed[i].id == tag.id)
      {
        found = true;
      }
    }
    if (!found)
    {
      tags_proposed.push(tag);
    }
  }
  
  this.addTag = function(tag)
  {
    addTagToSelectedTags(tag);
    addTagToProposedTags(tag);
  }
  
  var findTagInProposedList = function(tag_id)
  {
    for (i in tags_proposed)
    {
      if (tags_proposed[i].id == tag_id)
      {
        return tags_proposed[i];
      }
    }
    throw new Error("Unable to find the tag !")
  }
  
  this.removeSelectedTag = function(tag_id)
  {
    var new_tags_selected = [];
    for (i in tags_selected)
    {
      if (tags_selected[i].id != tag_id)
      {
        new_tags_selected.push(tags_selected[i]);
      }
    }
    tags_selected = new_tags_selected;
  }
  
  this.getSelectedTags = function()
  {
    return tags_selected;
  }
  
  this.setSelectedTags = function(tags)
  {
    tags_selected = tags;
  }
  
}

function TagPromptConnector(input, output, proposition_list, tag_box, prompt_loader)
{
  var _input = input;
  var _output = output;
  var _tag_box_manager = new TagBoxManager(tag_box, this);
  var _prompt_loader = prompt_loader;
  
  this.updateOutput = function(tags)
  {
    _output.val(array2json(tagsToArrayIds(tags)));
    _tag_proposition_list.hide();
    _tag_box_manager.update(tags);
    cleanInput();
  }
  
  var _tag_prompt = new TagPrompt(this.updateOutput, this);
  var _tag_proposition_list = new TagPromptPropositionList(proposition_list, _tag_prompt.selectProposedTag, this);
  
  var cleanInput = function()
  {
    // hack pour ie < 10 ne supportant pas le placeholder
    if ($.browser.version < 10 && $.browser.msie)
    {
      _input.addClass('placeholder');
      _input.val(_input.attr('placeholder'));
    }
    else
    {
      _input.val('');
    }
  }
  
  var showPromptLoader = function()
  {
    _prompt_loader.show();
  }
  
  this.hidePromptLoader = function()
  {
    _prompt_loader.hide();
  }
  
  var launchSearchTagsIdLastKeystroke = function(search_string)
  {
    if (search_string == _input.val())
    {
      displayTagsProposedSearchTags();
    }
  }
  
  var displayTagsProposedSearchTags = function()
  {
    var string_search = _input.val();
    _tag_prompt.getProposedTagsForString(
      string_search,
      _tag_proposition_list.displayTagsPropositions,
      _tag_proposition_list.displayError
    );
  }
  
  $(_input).bind('keyup', function() {
    if ($(this).val().length > 0)
    {
      showPromptLoader();
      var input_value = _input.val();
      window.setTimeout(function(){
        launchSearchTagsIdLastKeystroke(input_value);
      }, 1000);
    }
  });
  
  var tagsToArrayIds = function(tags)
  {
    var tags_ids = [];
    for (i in tags)
    {
      tags_ids.push(tags[i].id);
    }
    return tags_ids;
  }
  
  this.removeSelectedTag = function(tag_id)
  {
    _tag_prompt.removeSelectedTag(tag_id);
    this.updateOutput(_tag_prompt.getSelectedTags());
  }
  
  this.initializeTags = function(tags)
  {
    _tag_prompt.setSelectedTags(tags);
    this.updateOutput(_tag_prompt.getSelectedTags());
  }
  
  this.addTagToTagPrompt = function(tag)
  {
    _tag_prompt.addTag(tag);
    this.updateOutput(_tag_prompt.getSelectedTags());
  }
  
  this.openTagSubmission = function(tag_name)
  {
    _tag_prompt.openTagSubmission(tag_name);
  }
  
}

function TagBoxManager(tag_box, tag_prompt_connector)
{
  var _tag_prompt_connector = tag_prompt_connector;
  var _tag_box = tag_box;
  
  this.update = function(tags)
  {
    _tag_box.find('li').remove();
    for (i in tags)
    {
      _tag_box.append(getTagLine(tags[i]));
    }
  }
  
  var getTagLine = function (tag)
  {
    var line = $('<li>');
    line.addClass('tag');
    line.text(tag.name);
    line.append(getCloseLink(tag));
    
    return line;
  }
  
  var getCloseLink = function(tag)
  {
    var close_link = $('<a>');
    close_link.addClass('close');
    close_link.attr('href', '#');
    close_link.data('tag_id', tag.id);
    close_link.data('tag_name', tag.name);
    close_link.text('close');
    close_link.bind('click', function(){
      _tag_prompt_connector.removeSelectedTag($(this).data('tag_id'));
      return false;
    });
    
    return close_link;
  }
  
}

function TagPromptPropositionList(proposition_list, click_tag_callback, tag_prompt_connector)
{
  var _proposition_list = proposition_list;
  var _list;
  var _limit_display_tags = 30;
  var _click_tag_callback = click_tag_callback;
  var _tag_prompt_connector = tag_prompt_connector;
  
  this.displayError = function(error_string)
  {
    initializeList();
    var span_info = _proposition_list.find('span.info');
    span_info.text(error_string);
  }
  
  this.displayTagsPropositions = function(tags, search_string, message, same_found)
  {
    initializeList();
    displayMessage(message);
    for (i in tags)
    {
      addTagToList(tags[i], search_string);
    }
    if (!same_found)
    {
      addTagPropositionToList(new Tag(null, search_string));
    }
  }
  
  var initializeList = function()
  {
    _tag_prompt_connector.hidePromptLoader();
    $(_proposition_list).show();
    _list = _proposition_list.find('ul.search_tag_list');
    _list.find('li').remove();
    _proposition_list.find('a.more').hide();
  }
  
  var displayMessage = function(message)
  {
    var span_info = _proposition_list.find('span.info');
    span_info.text(message);
  }
  
  var addTagToList = function(tag, search_string)
  {
    var line = '';
    if (_list.find('li').length > _limit_display_tags)
    {
      line = getListLine(tag, true);
      _proposition_list.find('a.more').show();
    }
    else
    {
      line = getListLine(tag, false);
    }
    
    line = strongifySearchedLetters(line, search_string);
    _list.append(line);
  }
  
  var getListLine = function(tag, hide)
  {
    if (hide)
    {
      var line = $('<li style="display: none;">');
    }
    else
    {
      var line = $('<li>');
    }
    
    return line.append(getTagLink(tag));
  }
  
  var getTagLink = function(tag)
  {
    link = $('<a>');
    link.attr('href', '#');
    link.data('tag_id', tag.id);
    link.data('tag_name', tag.name);
    link.text(tag.name);
    link.bind('click', function(){
      _click_tag_callback($(this).data('tag_id'), $(this).data('tag_name'));
      return false;
    });
    return link;
  }
  
  var strongifySearchedLetters = function(line, search_string)
  {
    var name = line.find('a').text();
    line.find('a').html(name.replace(new RegExp(search_string, "i"), "<strong>" + search_string + "</strong>"));
    return line;
  }
  
  var addTagPropositionToList = function(tag)
  {
    var line = getListLine(tag);
    line.addClass('new');
    _list.append(line);
  }
  
  this.hide = function()
  {
    _proposition_list.hide();
  }
  
}

function Tag(id, name)
{
  /* @var _id int */
  this.id = id;
  /* @var _name string */
  this.name = name;
}

Tag.prototype =
{
  isKnew: function()
  {
    if (this.id)
    {
      return true;
    }
    return false;
  }
}

$(document).ready(function(){
  // Ce code permet la fermeture de la propositions de tags lors d'un click sur la page
  $('html').click(function() {
    if ($("div.search_tag_list").is(':visible'))
    {
      $("div.search_tag_list").hide();
    }
  });
  $("div.search_tag_list, div.search_tag_list a.more").live('click', function(event){
    event.stopPropagation();
    $("div.search_tag_list").show();
  });
  
  $('div.search_tag_list a.more').live('click', function(){
    $(this).parents('div.search_tag_list ').find('ul.search_tag_list li').show();
    $(this).hide();
    return false;
  });
  
});

// loaders
function GenericPlayer(ref_id, object_for_player)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  
  this.play = function()
  {
    JQueryJson(url_get_embed_for_element+'/'+_ref_id, {}, function(response){
      if (response.status == 'success')
      {
        object_for_player.html(response.data);
      }
    });
  }
  
  this.stop = function()
  {
    _object_for_player.html('');
  }
  
  this.close = function()
  {
    this.stop();
  }
}
function GenericStreamingPlayer(ref_id, object_for_player,
  callback_event_play,
  callback_event_end,
  callback_event_error,
  callback_event_finish_playlist
)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _playlist = new Array();
  var _player = null;
  var _player_dom = null;
  
  var _callback_event_play = callback_event_play;
  var _callback_event_end = callback_event_end;
  var _callback_event_error = callback_event_error;
  var _callback_event_finish_playlist = callback_event_finish_playlist;
  
  var _current_index = 0;
  
  this.create_player = function()
  {
    var jplayer_player  = $('#jquery_jplayer_1').clone();
    var jplayer_content = $('#jp_container_1').clone();
    
    jplayer_player.attr ('id', 'jplayer_player_element_'+ref_id);
    jplayer_content.attr('id', 'jplayer_content_element_'+ref_id);
    
    _object_for_player.html('');
    _object_for_player.append(jplayer_player);
    _object_for_player.append(jplayer_content);
    
    JQueryJson(url_element_get_stream_data+'/'+ref_id, {}, function(response){
      if (response.status == 'success')
      {
        if (response.data)
        {
          for(var i = 0; i < response.data.length; i++)
          {
            var song = new GenericSong(response.data[i].name, response.data[i].url);
            _playlist[i] = song;
          }
          
          _player = new jPlayerPlaylist
          (
            {
              jPlayer: '#jplayer_player_element_'+ref_id,
              cssSelectorAncestor: '#jplayer_content_element_'+ref_id
            },
            _playlist,
            {
              playlistOptions:
              {
                autoPlay: true,
                enableRemoveControls: true
              },
              swfPath: "/jplayer/js",
              supplied: "mp3",
              wmode: "window"
            }
          );
          
          var _player_dom = $('#jplayer_player_element_'+ref_id);
          _player_dom.bind($.jPlayer.event.play, event_play);
          _player_dom.bind($.jPlayer.event.ended, event_end);
          _player_dom.bind($.jPlayer.event.error, event_error);
        }
        else
        {
          _callback_event_finish_playlist();
        }
      }
      else
      {
        _callback_event_finish_playlist();
      }
    });
  }
  
  var event_play = function(event)
  {
    _current_index = _player.current;
  
    _callback_event_play(event);
  }
  
  var event_end = function(event)
  {
    _callback_event_end(event);
    if (_current_index+1 == _playlist.length)
    {
      event_finish_playlist(event);
    }
  }
  
  var event_error = function(event)
  {
    _callback_event_error(event);
  }
  
  var event_finish_playlist = function(event)
  {
    _callback_event_finish_playlist(event);
  }
  
  this.play = function()
  {
    _player_dom.jPlayer("play");
  }
  
  this.stop = function()
  {
    //_player_dom.jPlayer("stop");
  }
  
  this.pause = function()
  {
    _player_dom.jPlayer("pause");
  }
  
  this.destroy = function()
  {
    _object_for_player.html('');
  }
}

function GenericSong(title, mp3)
{
  this.title = title;
  this.mp3   = mp3;
}
function JamendoPlayer(ref_id, object_for_player, finish_callback)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _player = null;
  var _finish_callback = finish_callback;
  
  this.play = function()
  {
    _player = new GenericStreamingPlayer(_ref_id, _object_for_player,
      event_play,
      event_end,
      event_error,
      event_finish_playlist);
    _player.create_player();
  }
  
  var event_play = function()
  {
    
  }
  
  var event_end = function()
  {
    
  }
  
  var event_error = function()
  {
    _finish_callback();
  }
  
  var event_finish_playlist = function()
  {
    _finish_callback();
  }
  
  this.stop = function()
  {
    _player.stop();
  }
  
  this.pause = function()
  {
    _player.pause();
  }
  
  this.destroy = function()
  {
     _player.destroy();
  }
  
  this.stopAndDestroy = function()
  {
    this.stop();
    this.destroy();
  }
  
  this.close = function()
  {
    this.stopAndDestroy();
  }
}
function SoundCloudPlayer(ref_id, object_for_player, finish_callback, autoplay)
{
  autoplay = typeof autoplay !== 'undefined' ? autoplay : false;
  var _autoplay = autoplay;
  var _iframe_id = '';
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _player = null;
  var _sounds_count = 0;
  var _current_sound_index = 0;
  var _finish_callback = finish_callback;
  
  this.play = function()
  {
    createPlayer(
      event_ready,
      event_play,
      event_finish
    );
  }
  
  var createPlayer = function(callback_ready, callback_play, callback_finish)
  {
    _iframe_id = 'soundcloud_player_'+new Date().getTime();
    if (!_autoplay)
    {
      _object_for_player.html('<iframe id="'+_iframe_id+'" src="http://w.soundcloud.com/player/?url='
            +_ref_id+'&show_artwork=false&auto_play=true" width="100%" '
            +'height="350" scrolling="no" frameborder="no"></iframe>');
    }
    else
    {
      // On gère la lecture auto difframment: rappel: si on supprime une iframe l'api SC ne la trouve plus et bug
      $('#autoplay').append('<iframe id="'+_iframe_id+'" src="http://w.soundcloud.com/player/?url='
        +_ref_id+'&show_artwork=false&auto_play=true" width="100%" '
        +'height="350" scrolling="no" frameborder="no"></iframe>');
    }
    
    SC.initialize({
      client_id: '39946ea18e3d78d64c0ac95a025794e1'
    });
    SC.get(_ref_id, function(track, error)
    {
      if (!error)
      {
        _player = SC.Widget(document.getElementById(_iframe_id));
        _player.bind(SC.Widget.Events.READY, callback_ready);
        _player.bind(SC.Widget.Events.PLAY, callback_play);
        _player.bind(SC.Widget.Events.FINISH, callback_finish);
      }
      else
      {
        _finish_callback();
      }
    });
    
  }
  
  var event_ready = function()
  {
    _player.getSounds(function(value){
      _sounds_count = value.length;
    });
  }
  
  var event_play = function()
  {
    _player.getSounds(function(value){
      _sounds_count = value.length;
    });
    _player.getCurrentSoundIndex(function(value){
      _current_sound_index = value;
    });
  }
  
  var event_finish = function()
  {
    if (_sounds_count == _current_sound_index+1)
    {
      event_finish_playlist();
    }
  }
  
  var event_finish_playlist = function()
  {
    _finish_callback();
  }
  
  this.stop = function()
  {
    
  }
  
  this.destroy = function(force)
  {
    if (_player)
    {
      _player.pause();
    }
    if (_autoplay || force)
    {
      $('#'+_iframe_id).hide();
    }
  }
  
  this.stopAndDestroy = function()
  {
    //this.stop();
    this.destroy(false);
  }
  
  this.close = function()
  {
    this.destroy(true);
  }
}
function YoutubePlayer(ref_id, object_for_player, finish_callback)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _yt_player;
  var _finish_callback = finish_callback;
  
  this.play = function()
  { 
    create_player();
  }
  
  var create_player = function()
  {
    var div_for_iframe = $('<div>').attr('id', _object_for_player.attr('id')+'_iframe');
    _object_for_player.append(div_for_iframe);
    
    _yt_player = new YT.Player(_object_for_player.attr('id')+'_iframe', {
      height  : config_player_youtube_height,
      width   : '100%',
      videoId : _ref_id,
      events  : {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange,
        'onError': onError
      }
    });
  }
  
  var onPlayerReady = function(event)
  {
    _yt_player.playVideo();
  }
  
  var onError = function(event)
  {
    _finish_callback();
  }
  
  var onPlayerStateChange = function(event)
  {
    if (event.data == YT.PlayerState.PLAYING)
    {
      
    }
    if (event.data == YT.PlayerState.ENDED)
    {
      _finish_callback();
    }
    if (event.data == YT.PlayerState.PAUSED)
    {
      
    }
    if (event.data == YT.PlayerState.BUFFERING)
    {
      
    }
    if (event.data == YT.PlayerState.CUED)
    {
      
    }
  }
  
  this.pause = function()
  {
    if (_yt_player)
    {
      if(typeof(_yt_player.pauseVideo)!=='undefined')
      {
        _yt_player.pauseVideo();
      }
    }
  }
  
  this.stop = function()
  {
    if (_yt_player)
    {
      if(typeof(_yt_player.stopVideo)!=='undefined')
      {
        _yt_player.stopVideo();
      }
    }
  }
  
  this.destroy = function()
  {
    _object_for_player.html('');
  }
  
  this.stopAndDestroy = function()
  {
    this.stop();
    this.destroy();
  }
  
  this.close = function()
  {
    this.stopAndDestroy();
  }
}
function DynamicPlayer()
{
  
  this.play = function(object_for_player, player_type, ref_id, element_id, autoplay, finish_callback)
  {
    autoplay = typeof autoplay !== 'undefined' ? autoplay : false;
    finish_callback = typeof finish_callback !== 'undefined' ? finish_callback : $.noop;
    if ((player = getPlayerObjectForElementType(object_for_player, player_type, ref_id, element_id, autoplay, finish_callback)))
    {
      player.play();
      return player;
    }
    
    return false;
  }
  
  var getPlayerObjectForElementType = function(object_for_player, player_type, ref_id, element_id, autoplay, finish_callback)
  {
    if (player_type == 'youtube.com' || player_type == 'youtu.be')
    {
      return new YoutubePlayer(ref_id, object_for_player, finish_callback);
    }
    if (player_type == 'soundcloud.com')
    {
      return new SoundCloudPlayer(ref_id, object_for_player, finish_callback, autoplay);
    }
    if (player_type == 'jamendo.com')
    {
      return new JamendoPlayer(element_id, object_for_player, finish_callback);
    }
    
    if (!autoplay && element_id)
    {
      return new GenericPlayer(element_id, object_for_player);
    }
    
    return false;
  }
  
}

function PlayersManager()
{
  var _players = new Array();
  
  this.add = function(player, key)
  {
    _players[key] = player;
  }
  
  this.get = function(key)
  {
    return _players[key];
  }
  
  this.remove = function(key)
  {
    delete _players[key];
  }
  
  this.getAll = function()
  {
    return _players;
  }
}

$(document).ready(function() {
  window.dynamic_player = new DynamicPlayer();
  window.players_manager = new PlayersManager();
});
function Autoplay()
{
  
  var _playlist = new Array();
  var _player = null;
  var _current_index = 0;
  var _link = null;
  
  this.start = function(link)
  {
    _link = link;
    open_popin_dialog('autoplay');
    initializePlaylist(this.play);
  }
  
  var initializePlaylist = function(callback)
  {
    JQueryJson(_link.attr('href'), {}, function(response){
      if (response.status == 'success')
      {
        if (response.data.length)
        {
          _playlist = response.data;
        }
      }
      callback(0);
    });
  }
  
  this.play = function(index_to_play, timed)
  {
    window.autoplay.stopAndClearAllPlayers();
    if (array_key_exists(index_to_play, _playlist))
    {
      if (!timed)
      {
        _current_index = index_to_play;
        $('#autoplay_element_loader').show();
        window.setTimeout(function(){
          window.autoplay.play(index_to_play, true);
        });
      }
      else if (_current_index == index_to_play)
      {
        loadAndDisplayInfos(_playlist[index_to_play].element_id);
        if (!createPlayer(_playlist[index_to_play], window.autoplay.playNext))
        {
          this.play(index_to_play+1);
        }
        else
        {
          _current_index = index_to_play;
        }
      }
    }
    else
    {
      window.autoplay.nothingToPlay();
    }
  }
  
  this.stopAndClearAllPlayers = function()
  {
    players = window.players_manager.getAll();
    for (var i in players)
    {
      players[i].stopAndDestroy();
      window.players_manager.remove(i);
    }
  }
  
  var loadAndDisplayInfos = function(element_id)
  {
    $('#autoplay_element_loader').show();
    JQueryJson(url_element_dom_get_one_autoplay+'/'+element_id, {}, function(response){
      if (response.status == 'success')
      {
        $('li#autoplay_element_container').html(response.data);
        $('#autoplay_element_loader').hide();
      }
    });
  }
  
  var createPlayer = function(play_data, finish_callback)
  {
    $('#autoplay_loader').show();
    if ((player = window.dynamic_player.play(
      $('#autoplay_player'),
      play_data.element_type,
      play_data.element_ref_id,
      play_data.element_id,
      true,
      finish_callback
    )))
    {
      $('#autoplay_loader').hide();
      window.players_manager.add(player, 'autoplay_'+play_data.element_id);
      return true;
    }
    else
    {
      return false;
    }
  }
  
  this.playNext = function()
  {
    window.autoplay.play(_current_index+1);
  }
  
  this.playPrevious = function()
  {
    if (array_key_exists(_current_index-1, _playlist))
    {
      window.autoplay.play(_current_index-1);
    }
    return false;
  }
  
  this.nothingToPlay = function()
  {
    this.stopAndClearAllPlayers();
    $('#autoplay_noelements_text').show();
    $('div#autoplay_player_container').html('<div id="autoplay_player"></div>');
    $('li#autoplay_element_container').html('');
    $('#autoplay iframe').hide();
    $('#autoplay img[alt="loader"]').hide();
  }
  
}

$(document).ready(function() {
  
  window.autoplay = new Autoplay();
  
  $('a.autoplay_link').live('click', function(){
    window.autoplay.start($(this));
    $('html, body').animate({ scrollTop: 0 }, 'fast');
    return false;
  });
  
  $('a#autoplay_previous').click(function(){window.autoplay.playPrevious()});
  
  $('a#autoplay_next').click(function(){window.autoplay.playNext()});
  
  $('a#autoplay_close').click(function(){
    // Fond gris
    $('#fade').fadeOut(1000, function(){$('#fade').remove();});
    // On cache le lecteur
    $('#autoplay').hide();
    window.autoplay.stopAndClearAllPlayers();
  });
  
});
/*
 * Scripts de Muzi.ch
 * Rédigé et propriété de Sevajol Bastien (http://www.bux.fr) sauf si mention
 * contraire sur la fonction.
 * 
 */

// Messages flashs
var myMessages = ['info','warning','error','success']; // define the messages types	

function hideAllMessages()
{
  var messagesHeights = new Array(); // this array will store height for each
  
 for (i=0; i<myMessages.length; i++)
 {
    messagesHeights[i] = $('.' + myMessages[i]).outerHeight();
    $('.' + myMessages[i]).css('top', -messagesHeights[i]); //move element outside viewport	  
 }
}

$(document).ready(function(){
		 
  // Initially, hide them all
  hideAllMessages();

  $('.message').animate({top:"0"}, 500);

  // When message is clicked, hide it
  $('.message a.message-close').click(function(){			  
    $('.message').hide();
    return false;
  });		 
		 
});   

function findKeyWithValue(arrayt, value)
{
  for(i in arrayt)
  {
    if (arrayt[i] == value)
    {
      return i;
    }
  }
  return "";
}

function array_key_exists (key, search) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Felix Geisendoerfer (http://www.debuggable.com/felix)
  // *     example 1: array_key_exists('kevin', {'kevin': 'van Zonneveld'});
  // *     returns 1: true
  // input sanitation
  if (!search || (search.constructor !== Array && search.constructor !== Object)) {
    return false;
  }

  return key in search;
}


function json_to_array(json_string)
{
  if (json_string.length)
  {
    return eval("(" + json_string + ")");
  }
  return new Array();
}

function strpos (haystack, needle, offset) {
    // Finds position of first occurrence of a string within another  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/strpos    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Onno Marsman    
    // +   bugfixed by: Daniel Esteban
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: strpos('Kevin van Zonneveld', 'e', 5);    // *     returns 1: 14
    var i = (haystack + '').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}

/**
 * Converts the given data structure to a JSON string.
 * Argument: arr - The data structure that must be converted to JSON
 * Example: var json_string = array2json(['e', {pluribus: 'unum'}]);
 * 			var json = array2json({"success":"Sweet","failure":false,"empty_array":[],"numbers":[1,2,3],"info":{"name":"Binny","site":"http:\/\/www.openjs.com\/"}});
 * http://www.openjs.com/scripts/data/json_encode.php
 */
function array2json(arr) {
    var parts = [];
    var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

    for(var key in arr) {
    	var value = arr[key];
        if(typeof value == "object") { //Custom handling for arrays
            if(is_list) parts.push(array2json(value)); /* :RECURSION: */
            else parts[key] = array2json(value); /* :RECURSION: */
        } else {
            var str = "";
            if(!is_list) str = '"' + key + '":';

            //Custom handling for multiple data types
            if(typeof value == "number") str += value; //Numbers
            else if(value === false) str += 'false'; //The booleans
            else if(value === true) str += 'true';
            else str += '"' + value + '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Functions?)

            parts.push(str);
        }
    }
    var json = parts.join(",");
    
    if(is_list) return '[' + json + ']';//Return numerical JSON
    return '{' + json + '}';//Return associative JSON
}

function isInteger(s) {
  return (s.toString().search(/^-?[0-9]+$/) == 0);
}

function inArray(array, p_val) {
    var l = array.length;
    for(var i = 0; i < l; i++) {
        if(array[i] == p_val) {
            return true;
        }
    }
    return false;
}

if(typeof(String.prototype.trim) === "undefined")
{
    String.prototype.trim = function() 
    {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}

function str_replace (search, replace, subject, count) {
    // Replaces all occurrences of search in haystack with replace  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/str_replace    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = Object.prototype.toString.call(r) === '[object Array]',        sa = Object.prototype.toString.call(s) === '[object Array]';
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    } 
    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];
}

function explode (delimiter, string, limit) {
    // Splits a string on string separator and return array of components. If limit is positive only limit number of components is returned. If limit is negative all components except the last abs(limit) are returned.  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/explode    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
    var emptyArray = {0: ''
    };
 
    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;}
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    } 
    if (delimiter === true) {
        delimiter = '1';
    }
     if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    // support for limit argument
    var splitted = string.toString().split(delimiter.toString());var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
}

// fonction de nettoyage des tags
function remove_tags(form_name)
{
  //tagsAddeds[form_name] = new Array();
  //$('form[name="'+form_name+'"] ul.tagbox li.tag').remove();
  //$('form[name="'+form_name+'"] input.tagBox_tags_ids').val('');
  
}

function JQueryJson(url, data, callback_success)
{
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: data,
    success: function(response)
    {
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      callback_success(response);
    }
  });
}

$(document).ready(function(){  
    
  // Controle du focus sur la page
  function onBlur() {
    document.body.className = 'blurred';
  }

  function onFocus(){
      document.body.className = 'focused';
      do_action_body_focused();
  }

  if (/*@cc_on!@*/false) { // check for Internet Explorer
      document.onfocusin = onFocus;
      document.onfocusout = onBlur;
  } else {
      window.onfocus = onFocus;
      window.onblur = onBlur;
  }
  
  // Bouton de personalisation du filtre
  // Aucun tags
  $('#tabs_tag_search_no_tags, a.filter_clear_url').live("click", function(){
    $('img.elements_more_loader').show();
    $('ul.elements').html('');
    
    // COde: c tout pouris ce code 
    if ($(this).hasClass('filter_clear_url'))
    {
      $('ul#tabs_tag_search_buttons').find('li').removeClass('selected');
      $('li#tab_li_tag_search_no_tags').addClass('selected');
      $('input#element_search_form_tag_strict').attr('checked', false);
    }
    else
    {
      $(this).parents('ul#tabs_tag_search_buttons').find('li').removeClass('selected');
      $(this).parent('li').addClass('selected');
    }
    
    if ($('div.top_tools:visible').length)
    {
      $('div.top_tools').slideUp();
    }
    
    // On initialise la liste de tags déjà ajouté
    window.search_tag_prompt_connector.initializeTags([]);
    $('div.no_elements').hide();
    //tagsAddeds['search'] = new Array;
    var form = $('form[name="search"]');
    //remove_tags(form.attr('name'));
    form.submit();
  });
  
  // tags préférés
  $('#tabs_tag_search_with_tags').live("click", function(){
    
    $('img.elements_more_loader').show();
    $('ul.elements').html('');
    $(this).parents('ul#tabs_tag_search_buttons').find('li').removeClass('selected');
    $(this).parent('li').addClass('selected');
    
    if ($('div.top_tools:visible').length == 0)
    {
      $('div.top_tools').slideDown();
    }
    
    var form = $('form[name="search"]');
    
    $.getJSON(url_get_favorites_tags, function(response) {
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
        var tags = [];
        for (i in response.tags)
        {
          var tag = new Tag(i, response.tags[i]);
          tags.push(tag);
        }
        
        window.search_tag_prompt_connector.initializeTags(tags);
        form.submit();
    });
  });
  
  // Tag cliqué dans la liste d'éléments
  $('ul.element_tags li a.element_tag').live('click', function(){
    // Si il y a une liste de tags (comme sur la page favoris, profil)
    var id;
    
    if ($('ul#favorite_tags').length)
    {
      id = str_replace('element_tag_', '', $(this).attr('id'));
      var link = $('a#filtering_tag_'+id);
      list_tag_clicked(link, true);
    }
    
    if ($('form[name="search"]').length)
    {
      if ($('li#tab_li_tag_search_no_tags').hasClass('selected'))
      {
        $('ul#tabs_tag_search_buttons').find('li').removeClass('selected');
        $('li#tab_li_tag_search_with_tags').addClass('selected');
        if (!$('div.top_tools:visible').length)
        {
          $('div.top_tools').slideDown();
        }
      }
      
      
      $('img.elements_more_loader').show();
      $('ul.elements').html('');
      
      var form = $('form[name="search"]');
      id = str_replace('element_tag_', '', $(this).attr('id'));
      var tag = new Tag(id, $(this).text());
      
      window.search_tag_prompt_connector.initializeTags([tag]);
      
      form.submit();
      
    }
    
    $('html, body').animate({ scrollTop: 0 }, 'fast');
    return false;
  });
  
  function element_last_opened(li)
  {
    $('li.element').removeClass('shadows');
    li.addClass('shadows');
  }

  // Affichage un/des embed
  // 1328283150_media-playback-start.png
  // 1328283201_emblem-symbolic-link.png
  $('a.element_embed_open_link, a.element_name_embed_open_link').live("click", function(){
    
     var li = $(this).parents('li.element');
     
     element_last_opened(li);
     li.find('a.element_embed_close_link').show();
     li.find('a.element_embed_open_link_text').hide();
     li.find('div.element_embed').show();
     
    if ((player = window.dynamic_player.play(
      li.find('div.element_embed'),
      li.data('type'),
      li.data('refid'),
      li.data('elementid'),
      false
    )))
    {
      window.players_manager.add(player, li.attr('id'));
    }
     
     return false;
  });
  
  //$('a.element_name_embed_open_link').live("click", function(){
  //  
  //   var li = $(this).parents('li.element');
  //   
  //   element_last_opened(li);
  //   li.find('a.element_embed_close_link').show();
  //   li.find('a.element_embed_open_link_text').hide();
  //   li.find('div.element_embed').show();
  //   
  //   return false;
  //});

  // Fermeture du embed si demandé
  $('a.element_embed_close_link').live("click", function(){
    
     var li = $(this).parents('li.element');
    
     li.removeClass('shadows');
     li.find('div.element_embed').hide();
     li.find('a.element_embed_open_link_text').show();
     $(this).hide();
     
     var player = window.players_manager.get(li.attr('id'));
     if (player)
     {
       player.close();
     }
     else
     {
        // On a eu un soucis a la creation du player on dirais
     }
     
     return false;
  });
  
  // Affichage du "play" ou du "open" (image png)
  $('li.element a.a_thumbnail, li.element img.open, li.element img.play').live({
    mouseenter:
      function()
      {
        var td = $(this).parent('td');
        var a = td.find('a.a_thumbnail');
        if (a.hasClass('embed'))
        {
          td.find('img.play').show();
        }
        else
        {
          td.find('img.open').show();
        }
      },
    mouseleave:
      function()
      {
        var td = $(this).parent('td');
        var a = td.find('a.a_thumbnail');
        if (a.hasClass('embed'))
        {
          td.find('img.play').hide();
        }
        else
        {
          td.find('img.open').hide();
        }
      }
    }
  );

  // Mise en favoris
  $('a.favorite_link').live("click", function(){
    var link = $(this);
    
    // Pour ne pas attendre la fin du chargement ajax:
    var img = link.find('img');
    if (!link.hasClass('loading'))
    {
      if (img.attr('src') == '/img/icon_star_2.png')
      {
        img.attr('src', '/img/icon_star_2_red.png');
      }
      else
      {
        img.attr('src', '/img/icon_star_2.png');
      }
    }
    
    link.addClass('loading');
    
    $.getJSON($(this).attr('href'), function(response) {
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      var img = link.find('img');
      link.attr('href', response.link_new_url);
      img.attr('src', response.img_new_src);
      img.attr('title', response.img_new_title);
      link.removeClass('loading');
    });
    return false;
  });
    
//  // Affichage du bouton Modifier et Supprimer
//  $('ul.elements li.element').live({
//    mouseenter:
//      function()
//      {
//        $(this).find('a.element_edit_link').show();
//        $(this).find('a.element_remove_link').show();
//      },
//    mouseleave:
//      function()
//      {
//        if (!$(this).find('a.element_edit_link').hasClass('mustBeDisplayed'))
//        {
//          $(this).find('a.element_edit_link').hide();
//        }
//        if (!$(this).find('a.element_remove_link').hasClass('mustBeDisplayed'))
//        {
//          $(this).find('a.element_remove_link').hide();
//        }
//      }
//    }
//  );
    
   // Plus d'éléments
   var last_id = null;
   $('a.elements_more').click(function(){
    
    $('img.elements_more_loader').show();
    // On fait un cas isolé (pour l'instant!!)
    if (!$(this).hasClass('event_view'))
    {
      var link = $(this);
      var last_element = $('ul.elements li.element:last');
      var id_last = str_replace('element_', '', last_element.attr('id'));
      
      var url = link.attr('href')+'/'+id_last;
      // Cas exeptionel si on se trouve sur la global_search
      if ($('div#results_search_form').length)
      {
        url = link.attr('href')+id_last+'/'+$('div#results_search_form form input[type="text"]').val();
      }
      
      var old_form_action = $('form[name="search"]').attr('action');
      $('form[name="search"]').attr('action', url);
      
      var data = $('form[name="search"]').serialize();
      var type = 'POST';
    }
    else
    {
      var link = $(this);
      var url = $(this).attr('href');
      var data = {};
      var type = 'GET';
    }
         
     $.ajax({
       type: type,
       url: url,
       data: data,
       success: function(response) {
          if (response.status == 'mustbeconnected')
           {
             $(location).attr('href', url_index);
           }

          if (response.count)
          {
            $('ul.elements').append(response.html);
            $('img.elements_more_loader').hide();
            recolorize_element_list();
            
            if (link.hasClass('event_view'))
            {
              link.attr('href', response.data.more_link_href);
            }
          }
          
          if (response.end || response.count < 1)
          {
            $('img.elements_more_loader').hide();
            $('ul.elements').after('<div class="no_elements"><p class="no-elements">'+
              response.message+'</p></div>');
            link.hide();
          }
        },
       dataType: "json"
     });
     
     if (!$(this).hasClass('event_view'))
    {
      $('form[name="search"]').attr('action', old_form_action);
    }
    return false;
   });
   
  tag_box_input_value = $('ul.tagbox input[type="text"]').val();
   
  // Filtre et affichage éléments ajax
  $('form[name="search"] input[type="submit"]').click(function(){
    $('ul.elements').html('');
    $('div.no_elements').hide();
    $('img.elements_more_loader').show();
  });
  
  $('form[name="search"]').ajaxForm(function(response) { 
    
    if (response.status == 'mustbeconnected')
    {
      $(location).attr('href', url_index);
    }
    
    $('ul.elements').html(response.html);
    
    if (response.count)
     {
       $('img.elements_more_loader').hide();
       $('span.elements_more').show();
       $('a.elements_more').show();
     }

     if (response.count < 1)
     {
       $('img.elements_more_loader').hide();
       $('ul.elements').after('<div class="no_elements"><p class="no-elements">'+
         response.message+'</p></div>');
         $('a.elements_more').hide()
       ;
     }
     
     $('ul.tagbox input[type="text"]').val($('ul.tagbox input[type="text"]').val());
    
  }); 
  
  // Suppression d'un element
  $('a.element_remove_link').jConfirmAction({
    question : string_element_delete_confirm_sentence, 
    yesAnswer : string_element_delete_confirm_yes, 
    cancelAnswer : string_element_delete_confirm_no,
    onYes: function(link){
      
      var li = link.parents('li.element');
      li.find('img.element_loader').show();
      $.getJSON(link.attr('href'), function(response){
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        if (response.status == 'success')
        {
          li.remove();
        }
        else
        {
          li.find('img.element_loader').hide();
        }
      });

      return false;
    },
    onOpen: function(link){
      var li = link.parents('li.element');
      li.find('a.element_edit_link').addClass('mustBeDisplayed');
      li.find('a.element_remove_link').addClass('mustBeDisplayed');
    },
    onClose: function(link){
      var li = link.parents('li.element');
      li.find('a.element_edit_link').removeClass('mustBeDisplayed');
      li.find('a.element_remove_link').removeClass('mustBeDisplayed');
      li.find('a.element_edit_link').hide();
      li.find('a.element_remove_link').hide();
    }
    
    
  });
  
  // Retrait d'un element d'un groupe
  $('a.element_remove_from_group_link').jConfirmAction({
    question : string_removefromgroup_sentence, 
    yesAnswer : string_removefromgroup_confirm_yes, 
    cancelAnswer : string_removefromgroup_confirm_no,
    onYes: function(link){
      
      var li = link.parents('li.element');
      li.find('img.element_loader').show();
      $.getJSON(link.attr('href'), function(response){
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        if (response.status == 'success')
        {
          li.remove();
        }
        else
        {
          li.find('img.element_loader').hide();
        }
      });

      return false;
    }
  });

 var elements_edited = new Array();
 // Ouverture du formulaire de modification
  $('a.element_edit_link').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    li.addClass('selected');
    // On garde en mémoire l'élément édité en cas d'annulation
    elements_edited[li.attr('id')] = li.html();
    var div_loader = li.find('div.loader');
    li.html(div_loader);
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      // On prépare le tagBox
      li.html(response.html);
      // Pour le click sur l'input de saisie de tag
      //li.find('ul.tagbox li.input input[type="text"]').formDefaults();
     
      var options = new Array();
      options.form_name  = response.form_name;
      options.tag_init   = response.tags;

      ajax_query_timestamp = null;
      
      //$("#tags_prompt_list_"+response.form_name).tagBox(options);
      
      // On rend ce formulaire ajaxFormable
      $('form[name="'+response.form_name+'"] input[type="submit"]').live('click', function(){
        var li = $(this).parents('li.element');
        li.prepend(div_loader);
        li.find('img.element_loader').show();
      });
      $('form[name="'+response.form_name+'"]').ajaxForm(function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        var li = $('li#'+response.dom_id);
        
        if (response.status == 'success')
        {
          li.html(response.html);
          li.removeClass('selected');
          delete(elements_edited[li.attr('id')]);
        }
        else if (response.status == 'error')
        {
          li.find('img.element_loader').hide();
          li.find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
          
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
          
          li.prepend(ul_errors);
        }
      });
      
    });
    return false;
  });
  
  // Annulation d'un formulaire de modification d'élément
  $('form.edit_element input.cancel_edit').live('click', function(){
    var li = $(this).parents('li.element');
    li.removeClass('selected');
    li.html(elements_edited[li.attr('id')]);
    delete(elements_edited[li.attr('id')]);
  });
 
  ////////////////// TAG PROMPT ///////////////
  //
  //var ajax_query_timestamp = null;
  //
  //// Les deux clicks ci-dessous permettent de faire disparaitre
  //// la div de tags lorsque l'on clique ailleurs
  //$('html').click(function() {
  //  if ($("div.search_tag_list").is(':visible'))
  //  {
  //    $("div.search_tag_list").hide();
  //  }
  //});
  //
  //$("div.search_tag_list, div.search_tag_list a.more").live('click', function(event){
  //  event.stopPropagation();
  //  $("div.search_tag_list").show();
  //});
  //
  //function autocomplete_tag(input, form_name)
  //{
  //  // Il doit y avoir au moin un caractère
  //  if (input.val().length > 0) 
  //  {
  //
  //    // on met en variable l'input
  //    var inputTag = input;
  //    
  //    // On récupére la div de tags
  //    var divtags = $("#search_tag_"+form_name);
  //
  //    // Si la fenêtre de tags est caché
  //    if (!divtags.is(':visible'))
  //    {
  //      // On la replace
  //      var position = input.position();
  //      divtags.css('left', Math.round(position.left) + 5);
  //      divtags.css('top', Math.round(position.top) + 28);
  //      // Et on l'affiche
  //      divtags.show();
  //    }
  //    // On affiche le loader
  //    $('#tag_loader_'+form_name).show();
  //    // On cache la liste de tags
  //    var search_tag_list = divtags.find('ul.search_tag_list');
  //    // On supprime les anciens li
  //    search_tag_list.find('li').remove();
  //    search_tag_list.hide();
  //    // Et on affiche une info
  //    var span_info = divtags.find('span.info');
  //    span_info.show();
  //    // TODO: multilingue !
  //    span_info.text(str_replace('%string_search%', input.val(), string_search_tag_title));
  //
  //    // C'est en fonction du nb de resultats qu'il sera affiché
  //    divtags.find('a.more').hide();
  //
  //    // On récupère le timestamp pour reconnaitre la dernière requête effectué
  //    ajax_query_timestamp = new Date().getTime();
  //    
  //    // Récupération des tags correspondants
  //    $.ajax({
  //      type: 'POST',
  //      url: url_search_tag+'/'+ajax_query_timestamp,
  //      dataType: 'json',
  //      data: {'string_search':input.val()},
  //      success: function(data) {
  //      if (data.status == 'mustbeconnected')
  //      {
  //        $(location).attr('href', url_index);
  //      }
  //      
  //      // Ce contrôle permet de ne pas continuer si une requete
  //      // ajax a été faite depuis.
  //      if (data.timestamp == ajax_query_timestamp)
  //      {
  //        var status = data.status;
  //        var tags   = data.data;
  //
  //        // Si on spécifie une erreur
  //        if (status == 'error')
  //        {
  //          // On l'affiche a l'utilisateur
  //          span_info.text(data.error);
  //        }
  //        // Si c'est un succés
  //        else if (status == 'success')
  //        {
  //          if (tags.length > 0)
  //          {
  //            var more = false;
  //            // Pour chaque tags retournés
  //            for (i in tags)
  //            {
  //              var tag_name = tags[i]['name'];
  //              var tag_id = tags[i]['id'];
  //              var t_string = tag_name
  //              // On construit un li
  //              
  //              var r_string = $.trim(input.val());
  //              var re = new RegExp(r_string, "i");
  //              t_string = t_string.replace(re,"<strong>" + r_string + "</strong>");
  //              
  //                              
  //              var li_tag = 
  //                $('<li>').append(
  //                  $('<a>').attr('id','searched_tag_'+tag_id)
  //                    .attr('href', '#')
  //                  // qui réagit quand on clique dessus
  //                  .click(function(e){
  //                    
  //                    var id = str_replace('searched_tag_', '', $(this).attr('id'));
  //                    var name = $('span#tag_prompt_tag_'+id+'_name').html();
  //                                          
  //                    $('input#tags_selected_tag_'+form_name).val(id);
  //                    inputTag.val(name);
  //                    // Et on execute l'évènement selectTag de l'input
  //                    inputTag.trigger("selectTag");
  //                    // On cache la liste puisque le choix vient d'être fait
  //                    divtags.hide();
  //                    // On vide le champs de saisie du tag
  //                    $('input.form-default-value-processed').val('');
  //                    return false;
  //                  })
  //                  .append(t_string)
  //              ).append($('<span style="display: none;" id="tag_prompt_tag_'+tag_id+'_name">'+tag_name+'</span>'));
  //
  //              // Si on depasse les 30 tags
  //              if (i > 30)
  //              {
  //                more = true;
  //                // On le cache
  //                li_tag.hide();
  //              }
  //
  //              // On ajout ce li a la liste
  //              search_tag_list.append(li_tag);
  //            } 
  //
  //            if (more)
  //            {
  //              divtags.find('a.more').show();
  //            }
  //            
  //            span_info.show();
  //            span_info.text(data.message);
  //            // Et on affiche la liste
  //            search_tag_list.show();
  //          }
  //          else
  //          {
  //            span_info.show();
  //            span_info.text(data.message);
  //            search_tag_list.show();
  //            
  //            // Dans ce cas ou aucun tag n'a été trouvé, la proposition 
  //            // d'ajout s'affichecf en dessous
  //            
  //            //span_info.text("Aucun tag de trouvé pour \""+inputTag.val()+"\"");
  //          }
  //          
  //          // Si le tag ne semble pas connu en base
  //          if (!data.same_found)
  //          {
  //            li_tag = 
  //              $('<li>').addClass('new').append(
  //                $('<a>').attr('href','#new#'+$.trim(input.val()))
  //                // qui réagit quand on clique dessus
  //                .click({
  //                  inputTag: inputTag,
  //                  form_name: form_name,
  //                  divtags: divtags
  //                }, event_click_new_tag_proposition)
  //                .append($.trim(input.val()))
  //            );
  //            search_tag_list.append(li_tag);
  //          }
  //          
  //        }
  //
  //        // On cache le loader
  //        $('#tag_loader_'+form_name).hide();
  //      }
  //    }
  //    });
  //    
  //    
  //    //$.getJSON(url_search_tag+'/'+input.val()+'/'+ajax_query_timestamp, );
  //    
  //  }
  //}
  //
  //function event_click_new_tag_proposition(event)
  //{
  //  form_add_open_dialog_for_new_tag($(event.target), event.data.inputTag, event.data.form_name, event.data.divtags);
  //}
  //
  //function form_add_open_dialog_for_new_tag(link_add_tag, inputTag, form_name, divtags)
  //{       
  //  
  //  
  //  // Effet fade-in du fond opaque
  //  $('body').append($('<div>').attr('id', 'fade')); 
  //  //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
  //  $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
  //  
  //  // En premier lieux on fait apparaître la fenêtre de confirmation
  //  var popup = $('<div>')
  //  .attr('id', 'add_tag')
  //  .addClass('popin_block')
  //  .css('width', '400px')
  //    //.append($('<h2>').append(string_tag_add_title))
  //  .append($('<form>')
  //    .attr('action', url_add_tag)
  //    .attr('method', 'post')
  //    .attr('name', 'add_tag')
  //    .ajaxForm(function(response) {
  //      /*
  //      *
  //      */
  //
  //      if (response.status == 'mustbeconnected')
  //      {
  //        $(location).attr('href', url_index);
  //      }
  //
  //      if (response.status == 'success')
  //      {
  //        var tag_id   = response.tag_id;
  //        var tag_name = response.tag_name;
  //
  //        $('input#tags_selected_tag_'+form_name).val(tag_id);
  //        inputTag.val(tag_name);
  //        // Et on execute l'évènement selectTag de l'input
  //        inputTag.trigger("selectTag");
  //        // On cache la liste puisque le choix vient d'être fait
  //        divtags.hide();
  //
  //        link_add_tag.parents('div.search_tag_list').find('img.tag_loader').hide();
  //
  //        $('#fade').fadeOut(400, function(){$('#fade').remove();});
  //        $('#add_tag').remove();
  //      }
  //
  //      if (response.status == 'error')
  //      {
  //        $('form[name="add_tag"]').find('ul.error_list').remove();
  //        var ul_errors = $('<ul>').addClass('error_list');
  //
  //        for (i in response.errors)
  //        {
  //          ul_errors.append($('<li>').append(response.errors[i]));
  //        }
  //
  //        $('form[name="add_tag"]').prepend(ul_errors);
  //      }
  //
  //      return false;
  //    })
  //
  //    .append($('<div>').addClass('tag')
  //      .append($('<ul>')
  //        .append($('<li>').addClass('button')
  //          .append(link_add_tag.text()))))
  //    .append($('<p>').append(string_tag_add_text))
  //    .append($('<p>').append(string_tag_add_argument))
  //    .append($('<textarea>').attr('name', 'argument'))
  //    .append($('<div>').addClass('inputs')
  //      .append($('<input>')
  //        .attr('type', 'button')
  //        .attr('value', string_tag_add_inputs_cancel)
  //        .addClass('button')
  //        .click(function(){
  //          $('#fade').fadeOut(1000, function(){$('#fade').remove();});
  //          $('#add_tag').remove();
  //
  //          return false;
  //        })
  //      )
  //      .append($('<input>')
  //        .attr('type', 'submit')
  //        .attr('value', string_tag_add_inputs_submit)
  //        .addClass('button')
  //        .click(function(){
  //
  //          link_add_tag.parents('div.search_tag_list').find('img.tag_loader').show();
  //
  //        })
  //      )
  //      .append($('<input>').attr('type', 'hidden').attr('name', 'tag_name').val(link_add_tag.text()))
  //    ))
  //  ;
  //
  //  // Il faut ajouter le popup au dom avant de le positionner en css
  //  // Sinon la valeur height n'est pas encore calculable
  //  $('body').prepend(popup);
  //
  //  //Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
  //  var popMargTop = (popup.height() + 50) / 2;
  //  var popMargLeft = (popup.width() + 50) / 2;
  //
  //  //On affecte le margin
  //  $(popup).css({
  //    'margin-top' : -popMargTop,
  //    'margin-left' : -popMargLeft
  //  });
  //
  //  return false;
  //}
  //
  //var last_keypress = 0;
  //
  //function check_timelaps_and_search(input, form_name, time_id, timed, info)
  //{
  //  if (!timed)
  //  {
  //    // C'est une nouvelle touche (pas redirigé) on lui donne un id
  //    // et on met a jour l'id de la dernière pressé
  //    last_keypress = new Date().getTime(); 
  //    var this_time_id = last_keypress;
  //  }
  //  else
  //  {
  //    // Si elle a été redirigé, on met son id dans cette variable
  //    var this_time_id = time_id;
  //  }
  //  
  //  // C'est une touche redirigé dans le temps qui a été suivit d'une autre touche
  //  if (time_id != last_keypress && timed)
  //  {
  //    // elle disparait
  //  }
  //  else
  //  {
  //    //
  //    if ((new Date().getTime() - last_keypress) < 600 || timed == false)
  //    {
  //      // Si elle vient d'être tapé (timed == false) elle doit attendre (au cas ou une autre touche soit tapé)
  //      // Si c'est une redirigé qui n'a pas été remplacé par une nouvelle lettre
  //      // elle doit attendre au cas ou soit pressé.
  //      setTimeout(function(){check_timelaps_and_search(input, form_name, this_time_id, true, info)}, 700);
  //    }
  //    else
  //    {
  //      // il n'y a plus a attendre, on envoie la demande de tag.
  //      autocomplete_tag(input, form_name);
  //    }
  //  }
  //}
  //
  //// Autocompletion de tags
  //$("div.tags_prompt ul.tagbox li.input input").live('keypress', function(e){
  //  
  //  var form_name = $(this).parents('form').attr('name');
  //  var code = (e.keyCode ? e.keyCode : e.which);
  //
  //  if ((e.which !== 0 && e.charCode !== 0) || (code == 8 || code == 46))
  //  {
  //    check_timelaps_and_search($(this), form_name, new Date().getTime(), false, $(this).val());
  //  }
  //   
  //});
  //
  //// Un click sur ce lien affiche tout les tags cachés de la liste
  //$('div.search_tag_list a.more').live('click', function(){
  //  jQuery.each( $(this).parent('div').find('ul.search_tag_list li') , function(){
  //    $(this).show();
  //  });
  //  $(this).hide();
  //  return false;
  //});
  //
  //$('ul.tagbox li.input input[type="text"]').formDefaults();
  //
  ////////////////// FIN TAG PROMPT ///////////////
 
  // Suppression d'un element
  $('a.group_remove_link').jConfirmAction({
    question : "Supprimer ce groupe ?", 
    yesAnswer : "Oui", 
    cancelAnswer : "Non",
    onYes: function(link){
      window.location = link.attr('href');
      return false;
    },
    onOpen: function(){},
    onClose: function(){}
  });
  
  // Selection Réseau global / Mon réseau
  $('a.all_network, a.my_network').live('click', function(){
    
    if ($('form[name="search"]').length)
    {
      $(this).parent('li').parent('ul').find('li').removeClass('selected')
      
      if ($(this).hasClass('all_network'))
      {
        $(this).parent('li').addClass('selected');
        $('#element_search_form_network').val('network_public');
      }
      else
      {
        $(this).parent('li').addClass('selected');
        $('#element_search_form_network').val('network_personal');
      }
      
      $('form[name="search"] input[type="submit"]').trigger('click');
      
      return false;
    }
    return true;
  });
  
  function element_add_proceed_json_response(response)
  {
    if (response.status == 'success')
    {
      $('form[name="add"]').find('ul.error_list').remove();
      $('ul.elements').prepend(response.html);
      $('form[name="add"] input[type="text"]').val('');
      
      if ($('form[name="search"]').length)
      {
        if ($('a#tabs_tag_search_with_tags').parent('li').hasClass('selected'))
        {
          $('div.top_tools').slideDown();
        }
      }
      remove_tags('add');
      recolorize_element_list();
      
      $('div#element_add_box').slideUp();
      
      if (response.groups.length)
      {
        // Des groupes sont proposés pour diffuser cet élément
        $('div#added_element_to_group').slideDown();
        for (i in response.groups)
        {
          var group = response.groups[i];
          $('ul#groups_to_add_element').html('');
          $('ul#groups_to_add_element')
            .append($('<li>')
              .append($('<a>')
                .addClass('added_element_add_to_group')
                .attr('href', group.url)
                .append(group.name)
              )
            )
          ;
        }
      }
      else
      {
        $('a#element_add_link').show();
        $('a#element_add_close_link').hide();
      }
      
      form_add_hide_errors();
      
      return true;
    }
    else if (response.status == 'error')
    {
      form_add_display_errors(response.errors);
      $('#form_add_loader').hide();
      return false;
    }
    
    return false;
  }
  
  function form_add_hide_errors()
  {
    $('form[name="add"]').find('ul.error_list').remove();
  }
  
  // Affichage des erreurs lors de laprocédure d'ajout d'un élément
  function form_add_display_errors(errors)
  {
    $('form[name="add"]').find('ul.error_list').remove();
    var ul_errors = $('<ul>').addClass('error_list');

    for (i in errors)
    {
      ul_errors.append($('<li>').append(errors[i]));
    }
    
    $('form[name="add"]').prepend(ul_errors);
  }

  // Ajout d'un element #ajouter (première partie)
  
//  // Click sur "ajouter" (l'url)
//  $('a#form_add_check_url').click(function(){
//    
//    // On fait tourner notre gif loader
//    $('img#form_add_loader').show();
//    
//    $.ajax({
//      type: 'POST',
//      url: url_datas_api,
//      data: {'url':$('input#element_add_url').val()},
//      success: function(response){
//        
//        if (response.status == 'mustbeconnected')
//        {
//          $(location).attr('href', url_index);
//        }
//
//        if (response.status == 'success')
//        {
//          // On cache notre gif loader.
//          $('img#form_add_loader').hide();
//          
//          // On commence par renseigner les champs si on a du concret
//          // name
//          if (response.name)
//          {
//            $('input#element_add_name').val(response.name);
//          }
//          
//          // thumb
//          $('div#form_add_thumb img').attr('src', '/bundles/muzichcore/img/nothumb.png');
//          if (response.thumb)
//          {
//            $('div#form_add_thumb img').attr('src', response.thumb);
//          }
//          
//          // Proposition de tags
//          if (response.tags)
//          {
//            $('ul#form_add_prop_tags li').remove();
//            $('ul#form_add_prop_tags').show();
//            $('ul#form_add_prop_tags_text').show();
//            
//            for (tags_index = 0; tags_index < response.tags.length; tags_index++)
//            {
//              var tag = response.tags[tags_index];
//              var tag_id = '';
//              var tag_name = tag.original_name;
//              // Si il y a des équivalent en base.
//              if (tag.like_found)
//              {
//                tag_id = tag.like.id;
//                tag_name = tag.like.name;
//              }
//                
//              // On aura plus qu'a vérifie le href pour savoir si c'est une demande d'ajout de tags =)
//              $('ul#form_add_prop_tags').append(
//                '<li>'+
//                  '<a href="#'+tag_id+'" class="form_add_prop_tag">'+
//                    tag_name+
//                  '</a>'+
//                '</li>'
//              );
//            }
//          }
//          
//          // On a plus qu'a afficher les champs
//          $('div#form_add_second_part').slideDown();
//          $('div#form_add_first_part').slideUp();
//          form_add_hide_errors();
//        }
//        else if (response.status == 'error')
//        {
//          form_add_display_errors(response.errors);
//          $('#form_add_loader').hide();
//          return false;
//        }
//      },
//      dataType: 'json'
//    });
//    
//  });
  
  function element_add_proceed_data_apis(response)
  {
    if (response.status == 'mustbeconnected')
    {
      $(location).attr('href', url_index);
    }

    if (response.status == 'success')
    {
      // On cache notre gif loader.
      $('img#form_add_loader').hide();

      // On commence par renseigner les champs si on a du concret
      // name
      if (response.name)
      {
        $('input#element_add_name').val(response.name);
      }

      // thumb
      $('div#form_add_thumb img').attr('src', '/bundles/muzichcore/img/nothumb.png');
      if (response.thumb)
      {
        $('div#form_add_thumb img').attr('src', response.thumb);
      }

      // Proposition de tags
      if (response.tags)
      {
        $('ul#form_add_prop_tags li').remove();
        $('ul#form_add_prop_tags_api').show();
        $('p#form_add_prop_tags_text').hide();
        
        if (response.tags.length)
        {
          $('p#form_add_prop_tags_text').show();
        }
        
        $('ul#form_add_prop_tags_api li').remove();
        for (tags_index = 0; tags_index < response.tags.length; tags_index++)
        {
          var tag = response.tags[tags_index];
          var tag_id = '';
          var tag_name = tag.original_name;
          // Si il y a des équivalent en base.
          if (tag.like_found)
          {
            tag_id = tag.like.id;
            tag_name = tag.like.name;
          }

          // On aura plus qu'a vérifie le href pour savoir si c'est une demande d'ajout de tags =)
          $('ul#form_add_prop_tags_api').append(
            '<li>'+
              '<a href="#'+tag_id+'" class="form_add_prop_tag">'+
                tag_name+
              '</a>'+
            '</li>'
          );
        }
      }

      return true;
    }
    else if (response.status == 'error')
    {
      return false;
    }
    
    return true;
  }
  
  /*
   * Formulaire d'ajout: click sur proposition de tags du a une api
   */
  
  $('a.form_add_prop_tag').live('click', function(){
    
    var form_name = "add";
    var tag_id = str_replace('#', '', $(this).attr('href'));
    
    // Si on connait le tag id (pas un nouveau tag donc)
    if (tag_id)
    {
      var tag = new Tag(tag_id, $(this).text());
      window.add_tag_prompt_connector.addTagToTagPrompt(tag);
    }
    else
    {
      window.add_tag_prompt_connector.openTagSubmission($(this).text());
    }
    
    // On nettoie le champs de saisie des tags
    $('input.form-default-value-processed').val('');
    
  });
  
  // #ajouter ajouter un élément (envoi du formulaire)
  $('form[name="add"] input[type="submit"]').live('click', function(){
    $('form[name="add"]').find('img.tag_loader').show();
  });
  $('form[name="add"]').ajaxForm(function(response) {
    if (response.status == 'mustbeconnected')
    {
      $(location).attr('href', url_index);
    }
    
    $('form[name="add"] img.tag_loader').hide();
    
    // Si on en est a la promière étape la réponse sera des données récupérés auprès
    // des apis
    if ($('input#form_add_step').val() == '1')
    {
      if (element_add_proceed_data_apis(response))
      {
        // On a plus qu'a afficher les champs
        $('div#form_add_second_part').slideDown();
        $('div#form_add_first_part').slideUp();
        form_add_hide_errors();
        $('#form_add_loader').hide();
        $('input#form_add_step').val('2');
        
        // On doit avoir le slug du groupe si on ajoute a un groupe
        if (!$('input#add_element_group_page').length)
        {
          $('form[name="add"]').attr('action', url_element_add);
        }
        else
        {
          $('form[name="add"]').attr('action', url_element_add+'/'+$('input#add_element_group_page').val());
        }
        $('span#add_url_title_url').html($('input#element_add_url').val());
        // Mise a zero des tags
        window.add_tag_prompt_connector.initializeTags([]);
        $('input#element_add_need_tags').attr('checked', false);
      }
      else
      {
        form_add_display_errors(response.errors);
        $('#form_add_loader').hide();
      }
    }
    else if ($('input#form_add_step').val() == '2')
    {
      if (element_add_proceed_json_response(response))
      {
        form_add_reinit();
      }
    }

    
    return false;
  });
  
  
  function form_add_reinit()
  {
    $('div#element_add_box').slideUp();
    $('div#form_add_first_part').show();
    $('div#form_add_second_part').hide();
    $('ul#form_add_prop_tags_api').hide();
    $('ul#form_add_prop_tags_text').hide();
    $('input#element_add_url').val('');
    $('input#element_add_name').val('');
    $('input#form_add_step').val(1);
    $('form[name="add"]').attr('action', url_datas_api);
  }

 /////////////////////
 var tags_ids_for_filter = new Array();
 // Filtre par tags (show, favorite)
 function refresh_elements_with_tags_selected(link)
  {
    
    
    // Puis on fait notre rekékéte ajax.
    $('ul.elements').html('');
    $('div.no_elements').hide();
    $('img.elements_more_loader').show();
    $.getJSON($('input#get_elements_url').val()+'/'+array2json(tags_ids_for_filter), function(response){
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      $('ul.elements').html(response.html);
      
      if (response.count)
       {
         $('img.elements_more_loader').hide();
         $('span.elements_more').show();
         $('a.elements_more').show();
       }
    });
    
    return false;
  }
  
  function list_tag_clicked(link, erease)
  {
    if (erease)
    {
      $('ul#favorite_tags a.tag').removeClass('active');
    }
    
    // Ensuite on l'active ou le désactive
    if (link.hasClass('active'))
    {
      link.removeClass('active');
    }
    else
    {
      link.addClass('active');
    }
    
    // On construit notre liste de tags
    tags_ids_for_filter = new Array();
    $('ul#favorite_tags a.tag.active').each(function(index){
      var id = str_replace('filtering_tag_', '', $(this).attr('id'));
      tags_ids_for_filter[id] = id;
    });
    
    // On adapte le lien afficher plus de résultats
    var a_more = $('a.elements_more');
    a_more.attr('href', $('input#more_elements_url').val()+'/'+array2json(tags_ids_for_filter));
    
    // On adapte aussi le lien de l'autoplay
    //$('a.autoplay_link').attr('href', $('input#autoplay_url').val()+'/'+array2json(tags_ids_for_filter));
    //$('a.autoplay_link').each(function(){
    //  console.debug($(this));
    //  console.log(
    //    str_replace('__ELEMENT_ID__', $(this).data('element_id'), $('input#autoplay_url').val()+'/'+array2json(tags_ids_for_filter))
    //  );
    //  $(this).attr('href', str_replace('__ELEMENT_ID__', $(this).data('element_id'), $('input#autoplay_url').val()+'/'+array2json(tags_ids_for_filter)));
    //});
    
    return check_timelaps_and_find_with_tags(link, new Date().getTime(), false);
  }
   
  $('ul#favorite_tags a.tag').click(function(){
    list_tag_clicked($(this));
    return false;
  });
  
  last_keypress = 0;
  function check_timelaps_and_find_with_tags(link, time_id, timed)
  {
    if (!timed)
    {
      // C'est une nouvelle touche (pas redirigé) on lui donne un id
      // et on met a jour l'id de la dernière pressé
      last_keypress = new Date().getTime(); 
      var this_time_id = last_keypress;
    }
    else
    {
      // Si elle a été redirigé, on met son id dans cette variable
      var this_time_id = time_id;
    }
    
    // C'est une touche redirigé dans le temps qui a été suivit d'une autre touche
    if (time_id != last_keypress && timed)
    {
      // elle disparait
    }
    else
    {
      //
      if ((new Date().getTime() - last_keypress) < 800 || timed == false)
      {
        // Si elle vient d'être tapé (timed == false) elle doit attendre (au cas ou une autre touche soit tapé)
        // Si c'est une redirigé qui n'a pas été remplacé par une nouvelle lettre
        // elle doit attendre au cas ou soit pressé.
        setTimeout(function(){check_timelaps_and_find_with_tags(link, this_time_id, true)}, 900);
      }
      else
      {
        // il n'y a plus a attendre, on envoie la demande de tag.
        return refresh_elements_with_tags_selected(link);
      }
    }
    
    return null;
  }
  
  ////////////////////////////////////////
  /// Gestion de nouveaux éléments
  
  var do_check_new_elements = false;
  
  function check_new_elements()
  {
    if ($('ul.elements li').length)
    {
      // Si l'utilisateur a quitté la page on reporte le check
      if ($('body.blurred').length)
      {
        // on passe la variable a vrai de façon a ce que lorsque la page
        // et ré affiché on lance le check
        do_check_new_elements = true;
      }
      else
      {
        var url = url_element_new_count
          +'/'
          +str_replace('element_', '', $('ul.elements li:first').attr('id'))
        ;
        
        $.ajax({
          type: 'POST',
          url: url,
          data: $('form[name="search"]').serialize(),
          success: function(response){
          
            if (response.status == 'mustbeconnected')
            {
              $(location).attr('href', url_index);
            }

            if (response.status == 'success' && response.count)
            {
              $('div.display_more_elements').show();
              $('div.display_more_elements span').html(response.message);
            }

            setTimeout(check_new_elements, 150000);
          },
          dataType: "json"
        });
        
        
//        $.getJSON(url, function(response){
//          
//          if (response.status == 'mustbeconnected')
//          {
//            $(location).attr('href', url_index);
//          }
//
//          if (response.status == 'success' && response.count)
//          {
//            $('div.display_more_elements').show();
//            $('div.display_more_elements span').html(response.message);
//          }
//
//          setTimeout(check_new_elements, 150000);
//        });
        
        do_check_new_elements = false;
      }
      
    }
  }
  
  if ($('div.display_more_elements').length)
  {
    setTimeout(check_new_elements, 150000);
  }
  
  $('a.show_new_elements').live('click', function(){
    var url = url_element_new_get
      +'/'
      +str_replace('element_', '', $('ul.elements li:first').attr('id'))
    ;
    $('img.elements_new_loader').show();
    
    
    $.ajax({
      type: 'POST',
      url: url,
      data: $('form[name="search"]').serialize(),
      success: function(response){
      
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }

        if (response.status == 'success')
        {
          if (response.count)
          {
            $('div.display_more_elements').show();
            $('div.display_more_elements span').html(response.message);
          }
          else
          {
            $('div.display_more_elements').hide();
          }

          $('ul.elements').prepend(response.html);
          recolorize_element_list();
        }

        $('img.elements_new_loader').hide();
      },
      dataType: "json"
    });
    
//    $.getJSON(url, function(response){
//      
//      if (response.status == 'mustbeconnected')
//      {
//        $(location).attr('href', url_index);
//      }
//      
//      if (response.status == 'success')
//      {
//        if (response.count)
//        {
//          $('div.display_more_elements').show();
//          $('div.display_more_elements span').html(response.message);
//        }
//        else
//        {
//          $('div.display_more_elements').hide();
//        }
//        
//        $('ul.elements').prepend(response.html);
//        recolorize_element_list();
//      }
//      
//      $('img.elements_new_loader').hide();
//    });
  });

  function recolorize_element_list()
  {
    $('ul.elements li.element').each(function(index){
      if ((index & 1) == 1)
      {
        $(this).removeClass('even');
        $(this).removeClass('odd');
        $(this).addClass('odd');
      }
      else
      {
        $(this).removeClass('odd');
        $(this).removeClass('even');
        $(this).addClass('even');
      }
    });
  }
  
  /*
   * Action a effectuer lorsque l'utilisateur met le focus sur la page
   */
  function do_action_body_focused()
  {
    if (do_check_new_elements)
    {
      check_new_elements();
    }
  }
  
  /*
   * Commentaires d'élément
   */
  
  // Afficher les commentaires
    $('td.element_content a.display_comments').live('click', function(){
      display_comments($(this).parents('li.element'));
    });
    
    $('td.element_content a.hide_comments').live('click', function(){
      hide_comments($(this).parents('li.element'));
    });
  
    function display_comments(li_element)
    {
      li_element.find('div.comments').slideDown();
      li_element.find('a.display_comments').hide();
      li_element.find('a.hide_comments').show();
    }
  
    function hide_comments(li_element)
    {
      li_element.find('div.comments').slideUp();
      li_element.find('a.display_comments').show();
      li_element.find('a.hide_comments').hide();
    }
    
  // Ajouter un commentaire
    $('li.element a.add_comment').live('click', function(){
      display_add_comment($(this).parents('li.element'));
    });
    
    $('form.add_comment input[type="submit"]').live('click', function(){
      $(this).parents('div.comments').find('img.comments_loader').show();
    });
        
    function display_add_comment(li_element)
    {
      display_comments(li_element);
      li_element.find('a.add_comment').hide();
      li_element.find('form.add_comment').show();
      
      li_element.find('form.add_comment').ajaxForm(function(response) {
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }

        li_element.find('img.comments_loader').hide();
        
        if (response.status == 'success')
        {
          li_element.find('form.add_comment').find('ul.error_list').remove();
          li_element.find('div.comments ul.comments').append(response.html);
          hide_add_comment(li_element);
        }
        else if (response.status == 'error')
        {
          li_element.find('form.add_comment').find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');

          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }

          li_element.find('form.add_comment').prepend(ul_errors);
        }

        return false;
      });
      
    }
    
    $('form.add_comment input.cancel').live('click', function(){
      var li_element = $(this).parents('li.element');
      hide_add_comment(li_element);
    });
    
    function hide_add_comment(li_element)
    {
      li_element.find('a.add_comment').show();
      li_element.find('form.add_comment').hide();
      li_element.find('form.add_comment textarea').val('');
    }
   
   // Modifier et supprimer
   // Affichage du bouton Modifier et Supprimer
    $('ul.comments li.comment').live({
      mouseenter:
        function()
        {
          $(this).find('a.comment_edit_link').show();
          $(this).find('a.comment_remove_link').show();
        },
      mouseleave:
        function()
        {
          if (!$(this).find('a.comment_edit_link').hasClass('mustBeDisplayed'))
          {
            $(this).find('a.comment_edit_link').hide();
          }
          if (!$(this).find('a.comment_remove_link').hasClass('mustBeDisplayed'))
          {
            $(this).find('a.comment_remove_link').hide();
          }
        }
      }
    );
      
    // Supprimer
    $('a.comment_remove_link').jConfirmAction({
    question : string_comment_delete_confirm_sentence, 
    yesAnswer : string_comment_delete_confirm_yes, 
    cancelAnswer : string_comment_delete_confirm_no,
    onYes: function(link){
      
      var li = link.parents('li.comment');
      li.find('img.comment_loader').show();
      
      $.getJSON(link.attr('href'), function(response){
        
        li.find('img.comment_loader').hide();
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        if (response.status == 'success')
        {
          li.remove();
        }
      });

      return false;
    },
    onOpen: function(link){
      var li = link.parents('li.comment');
      li.find('a.comment_edit_link').addClass('mustBeDisplayed');
      li.find('a.comment_remove_link').addClass('mustBeDisplayed');
    },
    onClose: function(link){
      var li = link.parents('li.comment');
      li.find('a.comment_edit_link').removeClass('mustBeDisplayed');
      li.find('a.comment_remove_link').removeClass('mustBeDisplayed');
      li.find('a.comment_edit_link').hide();
      li.find('a.comment_remove_link').hide();
    }
  });
  
  var comments_edited = new Array();
  
  // Modification
  // Ouverture du formulaire de modification
  $('a.comment_edit_link').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.comment');
    // On garde en mémoire l'élément édité en cas d'annulation
    comments_edited[li.attr('id')] = li.html();
    var loader = li.find('img.comment_loader');
    li.html(loader);
    li.find('img.comment_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.html(response.html);
      // On rend ce formulaire ajaxFormable
      $('li#'+li.attr('id')+' form.edit_comment input[type="submit"]').live('click', function(){
        var li_current = $(this).parents('li.comment');
        li_current.prepend(loader);
        li_current.find('img.comment_loader').show();
      });
      
      li.find('form.edit_comment').ajaxForm(function(response){
        
        li = $('li#'+response.dom_id);
        li.find('img.comment_loader').hide();
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        if (response.status == 'success')
        {
          li.html(response.html);
          delete(comments_edited[li.attr('id')]);
        }
        else if (response.status == 'error')
        {
          li.find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
          
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
          
          li.prepend(ul_errors);
        }
      });
      
    });
    return false;
  });
  
  // Annulation d'un formulaire de modification d'un comment
  $('form.edit_comment input.cancel').live('click', function(){
    var li = $(this).parents('li.comment');
    li.html(comments_edited[li.attr('id')]);
    delete(comments_edited[li.attr('id')]);
  });
  
  /*
   * Ajout d'un tag en favoris a partir d'un élément
   */
  
  $('li.element_tag').live({
      mouseenter:
        function()
        {
          $(this).find('a.tag_to_favorites').show();
          $(this).find('a.element_tag').addClass('element_tag_large_for_fav');
        },
      mouseleave:
        function()
        {
          if (!$(this).find('a.tag_to_favorites').hasClass('mustBeDisplayed'))
          {
            $(this).find('a.tag_to_favorites').hide();
            $(this).find('a.element_tag').removeClass('element_tag_large_for_fav');
          }
        }
      }
    );
      
  $('a.tag_to_favorites').jConfirmAction({
    question : string_tag_addtofav_confirm_sentence, 
    yesAnswer : string_tag_addtofav_confirm_yes, 
    cancelAnswer : string_tag_addtofav_confirm_no,
    onYes: function(link){
      
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
      });
      
      $('div.question').fadeOut();
      return false;
    },
    onOpen: function(link){
      var li = link.parents('li.element_tag');
      li.find('a.tag_to_favorites').addClass('mustBeDisplayed');
    },
    onClose: function(link){
      var li = link.parents('li.element_tag');
      li.find('a.tag_to_favorites').removeClass('mustBeDisplayed');
      li.find('a.element_tag').removeClass('element_tag_large_for_fav');
      li.find('a.tag_to_favorites').hide();
    }
  });
  
  /*
   * Ajout dans un groupe de l'élément envoyé 
   */
  
  $('a.added_element_add_to_group').live('click', function(){
    
    var loader = $('div#added_element_to_group').find('img.loader');
    loader.show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      loader.hide();
    
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        $('li#'+response.dom_id).html(response.html);
      }
      
      $('div#added_element_to_group').slideUp();
      $('a#element_add_link').show();
      $('a#element_add_close_link').hide();
      
    });
    return false;
  });
  
  $('div#added_element_to_group a.cancel').live('click', function(){
    $('div#added_element_to_group').slideUp();
    $('a#element_add_link').show();
    $('a#element_add_close_link').show();
    return false;
  });
   
   /*
    * Report / signalement d'un élément
    */
   
   $('a.element_report').jConfirmAction({
    question : string_elementreport_confirm_sentence, 
    yesAnswer : string_elementreport_confirm_yes, 
    cancelAnswer : string_elementreport_confirm_no,
    onYes: function(link){
      
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
      });
      
      $('div.question').fadeOut();
      return false;
    },
    onOpen: function(link){
      
    },
    onClose: function(link){
      
    }
  });

  /*
   * Vote sur element
   */
  
  $('li.element a.vote').live('click', function(){
    
    var img = $(this).find('img');
    var link = $(this);
    img.attr('src', url_img_ajax_loader);
    
    $.getJSON(link.attr('href'), function(response){
        
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        link.attr('href', response.data.a.href);
        img.attr('src', response.data.img.src);
        link.parents('ul.element_thumb_actions').find('li.score').text(response.data.element.points);
      }
      
    });
    
    return false;
  });
  
  
  // Enlever les ids du ElementSearch
  $('div.more_filters a.new_comments, div.more_filters a.new_favorites, div.more_filters a.new_tags').live('click', function(){
    
    $('img.elements_more_loader').show();
    $('ul.elements').html('');
    var link = $(this);
    
    $.getJSON(link.attr('href'), function(response){
        
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        $('form[name="search"]').submit();
        $('div.more_filters a.new_comments').hide();
        $('div.more_filters a.new_favorites').hide();
        $('div.more_filters a.new_tags').hide();
      }
      
    });
    
    return false;
  });
  
  /*
   * 
   * Proposition de tags sur un élément
   * 
   */
  
 // Ouverture du formulaire de modification
  $('a.element_propose_tags').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        
        // On prépare le tagBox
        var table = li.find('table:first');
        li.find('div.tag_proposition').remove();
        table.after(response.html);

        // Pour le click sur l'input de saisie de tag
        //li.find('ul.tagbox li.input input[type="text"]').formDefaults();

        var options = new Array();
        options.form_name  = response.form_name;
        options.tag_init   = response.tags;

        ajax_query_timestamp = null;

        //$("#tags_prompt_list_"+response.form_name).tagBox(options);
      
      // On rend ce formulaire ajaxFormable
      $('form[name="'+response.form_name+'"] input[type="submit"]').live('click', function(){
        li = $(this).parents('li.element');
        li.find('img.element_loader').show();
      });
      $('form[name="'+response.form_name+'"]').ajaxForm(function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
                
        if (response.status == 'success')
        {
          li = $('li#'+response.dom_id);
          li.find('img.element_loader').hide();
          li.find('form')
          li.find('div.tag_proposition').remove();
        }
        else if (response.status == 'error')
        {
          li.find('img.element_loader').hide();
          li.find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
          
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
          
          li.find('div.tag_proposition div.tags_prompt').prepend(ul_errors);
        }
        
      });
      
      }
    });
    return false;
  });
  
  // Annulation d'un formulaire de modification d'élément
  $('div.tag_proposition input.cancel').live('click', function(){
    $(this).parents('div.tag_proposition').slideUp();
  });
  
  // Ouvrir les propositions de tags de l'élément
  $('a.element_view_propositions_link').live('click', function(){
    
    var link = $(this);
    li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        var table = li.find('table:first');
        li.find('div.tags_proposition_view').remove();
        table.after(response.html);
      }
    });
    
    return false;
  });
  
  $('a.accept_tag_propotision').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        li.html(response.html);
      }
    });
    
    return false;
  });
  
  //
  $('a.refuse_tag_propositions').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        li.find('div.tags_proposition_view').remove();
      }
    });
    
    return false;
  });
  
  /*
   * Proposition de tag sur un élément FIN
   */
  
  /*
    * Report / signalement d'un commentaire
    */
   
   $('a.comment_report').jConfirmAction({
    question : string_commentreport_confirm_sentence, 
    yesAnswer : string_commentreport_confirm_yes, 
    cancelAnswer : string_commentreport_confirm_no,
    onYes: function(link){
      
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
      });
      
      $('div.question').fadeOut();
      return false;
    },
    onOpen: function(link){
      
    },
    onClose: function(link){
      
    }
  });
  
  
  /*
   * reshare repartage
   */
  
  $('a.element_reshare').jConfirmAction({
    question : string_elementreshare_confirm_sentence, 
    yesAnswer : string_elementreshare_confirm_yes, 
    cancelAnswer : string_elementreshare_confirm_no,
    onYes: function(link){
      
      $('div.question').fadeOut();
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        // On affiche l'élément que si on voit que le formulaire est sur la page
        // Sinon c'est qu'on est sur une page ou on a pas normalement la possibilité
        // d'ajouetr un élément.
        if ($('form[name="add"]').length)
        {
          element_add_proceed_json_response(response);
        }
        return false;
      });
      
      
      
      return false;
    },
    onOpen: function(link){
      
    },
    onClose: function(link){
      
    }
  });

  /*
   * Cloud tags
   */
  
  $('a#display_all_cloud_tag').click(function(){
    $('ul#favorite_tags li').show();
    $(this).hide();
  });
  
  $('input#cloud_tags_filter').keyup(function(){
    var search_string = $(this).val();
    
    $('ul#favorite_tags li a').removeClass('highlight');
    
    if (search_string.length > 1)
    {
      $('ul#favorite_tags li a').each(function(){

        if ($(this).text().toUpperCase().search(search_string.toUpperCase()) != -1)
        {
          $(this).addClass('highlight')
        }

      });
    }
    
  });
  
  /* Click sur le bouton de recherche des champs de recherches */
  $('div.seachboxcontainer a.global_search_link').click(function(){
    $(this).parents('div.seachboxcontainer').find('form').submit();
  });
  
  /* Ouverture des menus deroulants */
  $('ul.secondarymenu a.top_menu_link').click(function(){
    
    if ($(this).parents('li.top_menu_element').hasClass('close'))
    {
      $(this).parents('li.top_menu_element').find('ul.submenu').hide();
      $(this).parents('li.top_menu_element').removeClass('close');
      $(this).parents('li.top_menu_element').addClass('open');
      $(this).parents('li.top_menu_element').find('ul.submenu').slideDown();
    }
    else
    {
      $(this).parents('li.top_menu_element').removeClass('open');
      $(this).parents('li.top_menu_element').addClass('close');
    }
    
    return false;
  });

  $('div#secondarymenu ul.submenu').each(function(){
    if ($(this).find('li').length > 7)
    {
      $(this).css('overflow', 'auto');
      $(this).css('height', '283px');
    }
  });
  
  /* Sou-menus page mon compte (myaccount) */
  $('div#myaccount h2').click(function(){
    $('div#myaccount div.myaccount_part:visible').slideUp();
    $('div#'+$(this).data('open')).slideDown();
  });
  
  /* Languages placement */
  var selected_language = $('div#languages a.selected');
  $('div#languages').prepend(selected_language);
  
  /* Compatibilité placeholder (IE again ...) */
  if ($.browser.msie)
  {
    if ($.browser.version < 10)
    {
      $('[placeholder]').each(function(){
        $(this).addClass('placeholder');
        $(this).val($(this).attr('placeholder'));
      });
      
      $('[placeholder]').focus(function() {
          var input = $(this);
          if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
          }
       }).blur(function() {
          var input = $(this);
          if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
          }
       }).blur().parents('form').submit(function() {
          $(this).find('[placeholder]').each(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
            }
          })
      });
    }
  }
  
});



/*
 * Ouverture d'une boite avec effet fade et centré
 *   code origine: form_add_open_dialog_for_new_tag
 */

  function open_popin_dialog(object_id)
  {
    
    // Effet fade-in du fond opaque
    $('body').append($('<div>').attr('id', 'fade')); 
    //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
    $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
    
    $('#'+object_id).css({
      position: 'absolute',
      left: ($(window).width() 
        - $('#'+object_id).width())/2,
//      top: ($(window).height() 
//        - $('#'+object_id).height())/2
        top: '10%'
      });
    $('#'+object_id).show();
  }
