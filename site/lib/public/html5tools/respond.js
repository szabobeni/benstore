(function(e){"use strict";function x(){b(true)}var t={};e.respond=t;t.update=function(){};var n=[],r=function(){var t=false;try{t=new e.XMLHttpRequest}catch(n){t=new e.ActiveXObject("Microsoft.XMLHTTP")}return function(){return t}}(),i=function(e,t){var n=r();if(!n){return}n.open("GET",e,true);n.onreadystatechange=function(){if(n.readyState!==4||n.status!==200&&n.status!==304){return}t(n.responseText)};if(n.readyState===4){return}n.send(null)};t.ajax=i;t.queue=n;t.regex={media:/@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi,keyframes:/@(?:\-(?:o|moz|webkit)\-)?keyframes[^\{]+\{(?:[^\{\}]*\{[^\}\{]*\})+[^\}]*\}/gi,urls:/(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,findStyles:/@media *([^\{]+)\{([\S\s]+?)$/,only:/(only\s+)?([a-zA-Z]+)\s?/,minw:/\([\s]*min\-width\s*:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/,maxw:/\([\s]*max\-width\s*:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/};t.mediaQueriesSupported=e.matchMedia&&e.matchMedia("only all")!==null&&e.matchMedia("only all").matches;if(t.mediaQueriesSupported){return}var s=e.document,o=s.documentElement,u=[],a=[],f=[],l={},c=30,h=s.getElementsByTagName("head")[0]||o,p=s.getElementsByTagName("base")[0],d=h.getElementsByTagName("link"),v,m,g,y=function(){var e,t=s.createElement("div"),n=s.body,r=o.style.fontSize,i=n&&n.style.fontSize,u=false;t.style.cssText="position:absolute;font-size:1em;width:1em";if(!n){n=u=s.createElement("body");n.style.background="none"}o.style.fontSize="100%";n.style.fontSize="100%";n.appendChild(t);if(u){o.insertBefore(n,o.firstChild)}e=t.offsetWidth;if(u){o.removeChild(n)}else{n.removeChild(t)}o.style.fontSize=r;if(i){n.style.fontSize=i}e=g=parseFloat(e);return e},b=function(t){var n="clientWidth",r=o[n],i=s.compatMode==="CSS1Compat"&&r||s.body[n]||r,l={},p=d[d.length-1],w=(new Date).getTime();if(t&&v&&w-v<c){e.clearTimeout(m);m=e.setTimeout(b,c);return}else{v=w}for(var E in u){if(u.hasOwnProperty(E)){var S=u[E],x=S.minw,T=S.maxw,N=x===null,C=T===null,k="em";if(!!x){x=parseFloat(x)*(x.indexOf(k)>-1?g||y():1)}if(!!T){T=parseFloat(T)*(T.indexOf(k)>-1?g||y():1)}if(!S.hasquery||(!N||!C)&&(N||i>=x)&&(C||i<=T)){if(!l[S.media]){l[S.media]=[]}l[S.media].push(a[S.rules])}}}for(var L in f){if(f.hasOwnProperty(L)){if(f[L]&&f[L].parentNode===h){h.removeChild(f[L])}}}f.length=0;for(var A in l){if(l.hasOwnProperty(A)){var O=s.createElement("style"),M=l[A].join("\n");O.type="text/css";O.media=A;h.insertBefore(O,p.nextSibling);if(O.styleSheet){O.styleSheet.cssText=M}else{O.appendChild(s.createTextNode(M))}f.push(O)}}},w=function(e,n,r){var i=e.replace(t.regex.keyframes,"").match(t.regex.media),s=i&&i.length||0;n=n.substring(0,n.lastIndexOf("/"));var o=function(e){return e.replace(t.regex.urls,"$1"+n+"$2$3")},f=!s&&r;if(n.length){n+="/"}if(f){s=1}for(var l=0;l<s;l++){var c,h,p,d;if(f){c=r;a.push(o(e))}else{c=i[l].match(t.regex.findStyles)&&RegExp.$1;a.push(RegExp.$2&&o(RegExp.$2))}p=c.split(",");d=p.length;for(var v=0;v<d;v++){h=p[v];u.push({media:h.split("(")[0].match(t.regex.only)&&RegExp.$2||"all",rules:a.length-1,hasquery:h.indexOf("(")>-1,minw:h.match(t.regex.minw)&&parseFloat(RegExp.$1)+(RegExp.$2||""),maxw:h.match(t.regex.maxw)&&parseFloat(RegExp.$1)+(RegExp.$2||"")})}}b()},E=function(){if(n.length){var t=n.shift();i(t.href,function(n){w(n,t.href,t.media);l[t.href]=true;e.setTimeout(function(){E()},0)})}},S=function(){for(var t=0;t<d.length;t++){var r=d[t],i=r.href,s=r.media,o=r.rel&&r.rel.toLowerCase()==="stylesheet";if(!!i&&o&&!l[i]){if(r.styleSheet&&r.styleSheet.rawCssText){w(r.styleSheet.rawCssText,i,s);l[i]=true}else{if(!/^([a-zA-Z:]*\/\/)/.test(i)&&!p||i.replace(RegExp.$1,"").split("/")[0]===e.location.host){if(i.substring(0,2)==="//"){i=e.location.protocol+i}n.push({href:i,media:s})}}}}E()};S();t.update=S;t.getEmValue=y;if(e.addEventListener){e.addEventListener("resize",x,false)}else if(e.attachEvent){e.attachEvent("onresize",x)}})(this)