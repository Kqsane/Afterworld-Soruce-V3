;// bundle: headerinit___ac2199185f91bca310e661a553124494_m
;// files: GPTAdScript.js, leancore/libs/json3.min.js, roblox.js, leancore/RobloxHeaderInit.js, jquery.cookie.js

;// GPTAdScript.js
var googletag=googletag||{};googletag.cmd=googletag.cmd||[],function(){var n=document.createElement("script"),i,t;n.async=!0,n.type="text/javascript",i="https:"==document.location.protocol,n.src=(i?"https:":"http:")+"//www.googletagservices.com/tag/js/gpt.js",t=document.getElementsByTagName("script")[0],t.parentNode.insertBefore(n,t)}();

;// leancore/libs/json3.min.js
/*! JSON v3.3.2 | http://bestiejs.github.io/json3 | Copyright 2012-2014, Kit Cambridge | http://kit.mit-license.org */
(function () {
    function N(p, r) {
        function q(a) {
            if (q[a] !== w) return q[a]; var c; if ("bug-string-char-index" == a) c = "a" != "a"[0]; else if ("json" == a) c = q("json-stringify") && q("json-parse"); else {
                var e; if ("json-stringify" == a) {
                    c = r.stringify; var b = "function" == typeof c && s; if (b) {
                        (e = function () { return 1 }).toJSON = e; try {
                            b = "0" === c(0) && "0" === c(new t) && '""' == c(new A) && c(u) === w && c(w) === w && c() === w && "1" === c(e) && "[1]" == c([e]) && "[null]" == c([w]) && "null" == c(null) && "[null,null,null]" == c([w, u, null]) && '{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}' ==
                            c({ a: [e, !0, !1, null, "\x00\b\n\f\r\t"] }) && "1" === c(null, e) && "[\n 1,\n 2\n]" == c([1, 2], null, 1) && '"-271821-04-20T00:00:00.000Z"' == c(new C(-864E13)) && '"+275760-09-13T00:00:00.000Z"' == c(new C(864E13)) && '"-000001-01-01T00:00:00.000Z"' == c(new C(-621987552E5)) && '"1969-12-31T23:59:59.999Z"' == c(new C(-1))
                        } catch (f) { b = !1 }
                    } c = b
                } if ("json-parse" == a) {
                    c = r.parse; if ("function" == typeof c) try {
                        if (0 === c("0") && !c(!1)) {
                            e = c('{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}'); var n = 5 == e.a.length && 1 === e.a[0]; if (n) {
                                try { n = !c('"\t"') } catch (d) { } if (n) try {
                                    n =
                                    1 !== c("01")
                                } catch (g) { } if (n) try { n = 1 !== c("1.") } catch (m) { }
                            }
                        }
                    } catch (X) { n = !1 } c = n
                }
            } return q[a] = !!c
        } p || (p = k.Object()); r || (r = k.Object()); var t = p.Number || k.Number, A = p.String || k.String, H = p.Object || k.Object, C = p.Date || k.Date, G = p.SyntaxError || k.SyntaxError, K = p.TypeError || k.TypeError, L = p.Math || k.Math, I = p.JSON || k.JSON; "object" == typeof I && I && (r.stringify = I.stringify, r.parse = I.parse); var H = H.prototype, u = H.toString, v, B, w, s = new C(-0xc782b5b800cec); try {
            s = -109252 == s.getUTCFullYear() && 0 === s.getUTCMonth() && 1 === s.getUTCDate() &&
            10 == s.getUTCHours() && 37 == s.getUTCMinutes() && 6 == s.getUTCSeconds() && 708 == s.getUTCMilliseconds()
        } catch (Q) { } if (!q("json")) {
            var D = q("bug-string-char-index"); if (!s) var x = L.floor, M = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334], E = function (a, c) { return M[c] + 365 * (a - 1970) + x((a - 1969 + (c = +(1 < c))) / 4) - x((a - 1901 + c) / 100) + x((a - 1601 + c) / 400) }; (v = H.hasOwnProperty) || (v = function (a) {
                var c = {}, e; (c.__proto__ = null, c.__proto__ = { toString: 1 }, c).toString != u ? v = function (a) {
                    var c = this.__proto__; a = a in (this.__proto__ = null, this); this.__proto__ =
                    c; return a
                } : (e = c.constructor, v = function (a) { var c = (this.constructor || e).prototype; return a in this && !(a in c && this[a] === c[a]) }); c = null; return v.call(this, a)
            }); B = function (a, c) {
                var e = 0, b, f, n; (b = function () { this.valueOf = 0 }).prototype.valueOf = 0; f = new b; for (n in f) v.call(f, n) && e++; b = f = null; e ? B = 2 == e ? function (a, c) { var e = {}, b = "[object Function]" == u.call(a), f; for (f in a) b && "prototype" == f || v.call(e, f) || !(e[f] = 1) || !v.call(a, f) || c(f) } : function (a, c) {
                    var e = "[object Function]" == u.call(a), b, f; for (b in a) e && "prototype" ==
                    b || !v.call(a, b) || (f = "constructor" === b) || c(b); (f || v.call(a, b = "constructor")) && c(b)
                } : (f = "valueOf toString toLocaleString propertyIsEnumerable isPrototypeOf hasOwnProperty constructor".split(" "), B = function (a, c) { var e = "[object Function]" == u.call(a), b, h = !e && "function" != typeof a.constructor && F[typeof a.hasOwnProperty] && a.hasOwnProperty || v; for (b in a) e && "prototype" == b || !h.call(a, b) || c(b); for (e = f.length; b = f[--e]; h.call(a, b) && c(b)); }); return B(a, c)
            }; if (!q("json-stringify")) {
                var U = {
                    92: "\\\\", 34: '\\"', 8: "\\b",
                    12: "\\f", 10: "\\n", 13: "\\r", 9: "\\t"
                }, y = function (a, c) { return ("000000" + (c || 0)).slice(-a) }, R = function (a) { for (var c = '"', b = 0, h = a.length, f = !D || 10 < h, n = f && (D ? a.split("") : a) ; b < h; b++) { var d = a.charCodeAt(b); switch (d) { case 8: case 9: case 10: case 12: case 13: case 34: case 92: c += U[d]; break; default: if (32 > d) { c += "\\u00" + y(2, d.toString(16)); break } c += f ? n[b] : a.charAt(b) } } return c + '"' }, O = function (a, c, b, h, f, n, d) {
                    var g, m, k, l, p, r, s, t, q; try { g = c[a] } catch (z) { } if ("object" == typeof g && g) if (m = u.call(g), "[object Date]" != m || v.call(g,
                    "toJSON")) "function" == typeof g.toJSON && ("[object Number]" != m && "[object String]" != m && "[object Array]" != m || v.call(g, "toJSON")) && (g = g.toJSON(a)); else if (g > -1 / 0 && g < 1 / 0) {
                        if (E) { l = x(g / 864E5); for (m = x(l / 365.2425) + 1970 - 1; E(m + 1, 0) <= l; m++); for (k = x((l - E(m, 0)) / 30.42) ; E(m, k + 1) <= l; k++); l = 1 + l - E(m, k); p = (g % 864E5 + 864E5) % 864E5; r = x(p / 36E5) % 24; s = x(p / 6E4) % 60; t = x(p / 1E3) % 60; p %= 1E3 } else m = g.getUTCFullYear(), k = g.getUTCMonth(), l = g.getUTCDate(), r = g.getUTCHours(), s = g.getUTCMinutes(), t = g.getUTCSeconds(), p = g.getUTCMilliseconds();
                        g = (0 >= m || 1E4 <= m ? (0 > m ? "-" : "+") + y(6, 0 > m ? -m : m) : y(4, m)) + "-" + y(2, k + 1) + "-" + y(2, l) + "T" + y(2, r) + ":" + y(2, s) + ":" + y(2, t) + "." + y(3, p) + "Z"
                    } else g = null; b && (g = b.call(c, a, g)); if (null === g) return "null"; m = u.call(g); if ("[object Boolean]" == m) return "" + g; if ("[object Number]" == m) return g > -1 / 0 && g < 1 / 0 ? "" + g : "null"; if ("[object String]" == m) return R("" + g); if ("object" == typeof g) {
                        for (a = d.length; a--;) if (d[a] === g) throw K(); d.push(g); q = []; c = n; n += f; if ("[object Array]" == m) {
                            k = 0; for (a = g.length; k < a; k++) m = O(k, g, b, h, f, n, d), q.push(m === w ? "null" :
                            m); a = q.length ? f ? "[\n" + n + q.join(",\n" + n) + "\n" + c + "]" : "[" + q.join(",") + "]" : "[]"
                        } else B(h || g, function (a) { var c = O(a, g, b, h, f, n, d); c !== w && q.push(R(a) + ":" + (f ? " " : "") + c) }), a = q.length ? f ? "{\n" + n + q.join(",\n" + n) + "\n" + c + "}" : "{" + q.join(",") + "}" : "{}"; d.pop(); return a
                    }
                }; r.stringify = function (a, c, b) {
                    var h, f, n, d; if (F[typeof c] && c) if ("[object Function]" == (d = u.call(c))) f = c; else if ("[object Array]" == d) { n = {}; for (var g = 0, k = c.length, l; g < k; l = c[g++], (d = u.call(l), "[object String]" == d || "[object Number]" == d) && (n[l] = 1)); } if (b) if ("[object Number]" ==
                    (d = u.call(b))) { if (0 < (b -= b % 1)) for (h = "", 10 < b && (b = 10) ; h.length < b; h += " "); } else "[object String]" == d && (h = 10 >= b.length ? b : b.slice(0, 10)); return O("", (l = {}, l[""] = a, l), f, n, h, "", [])
                }
            } if (!q("json-parse")) {
                var V = A.fromCharCode, W = { 92: "\\", 34: '"', 47: "/", 98: "\b", 116: "\t", 110: "\n", 102: "\f", 114: "\r" }, b, J, l = function () { b = J = null; throw G(); }, z = function () {
                    for (var a = J, c = a.length, e, h, f, k, d; b < c;) switch (d = a.charCodeAt(b), d) {
                        case 9: case 10: case 13: case 32: b++; break; case 123: case 125: case 91: case 93: case 58: case 44: return e =
                        D ? a.charAt(b) : a[b], b++, e; case 34: e = "@"; for (b++; b < c;) if (d = a.charCodeAt(b), 32 > d) l(); else if (92 == d) switch (d = a.charCodeAt(++b), d) { case 92: case 34: case 47: case 98: case 116: case 110: case 102: case 114: e += W[d]; b++; break; case 117: h = ++b; for (f = b + 4; b < f; b++) d = a.charCodeAt(b), 48 <= d && 57 >= d || 97 <= d && 102 >= d || 65 <= d && 70 >= d || l(); e += V("0x" + a.slice(h, b)); break; default: l() } else { if (34 == d) break; d = a.charCodeAt(b); for (h = b; 32 <= d && 92 != d && 34 != d;) d = a.charCodeAt(++b); e += a.slice(h, b) } if (34 == a.charCodeAt(b)) return b++, e; l(); default: h =
                        b; 45 == d && (k = !0, d = a.charCodeAt(++b)); if (48 <= d && 57 >= d) { for (48 == d && (d = a.charCodeAt(b + 1), 48 <= d && 57 >= d) && l() ; b < c && (d = a.charCodeAt(b), 48 <= d && 57 >= d) ; b++); if (46 == a.charCodeAt(b)) { for (f = ++b; f < c && (d = a.charCodeAt(f), 48 <= d && 57 >= d) ; f++); f == b && l(); b = f } d = a.charCodeAt(b); if (101 == d || 69 == d) { d = a.charCodeAt(++b); 43 != d && 45 != d || b++; for (f = b; f < c && (d = a.charCodeAt(f), 48 <= d && 57 >= d) ; f++); f == b && l(); b = f } return +a.slice(h, b) } k && l(); if ("true" == a.slice(b, b + 4)) return b += 4, !0; if ("false" == a.slice(b, b + 5)) return b += 5, !1; if ("null" == a.slice(b,
                        b + 4)) return b += 4, null; l()
                    } return "$"
                }, P = function (a) { var c, b; "$" == a && l(); if ("string" == typeof a) { if ("@" == (D ? a.charAt(0) : a[0])) return a.slice(1); if ("[" == a) { for (c = []; ; b || (b = !0)) { a = z(); if ("]" == a) break; b && ("," == a ? (a = z(), "]" == a && l()) : l()); "," == a && l(); c.push(P(a)) } return c } if ("{" == a) { for (c = {}; ; b || (b = !0)) { a = z(); if ("}" == a) break; b && ("," == a ? (a = z(), "}" == a && l()) : l()); "," != a && "string" == typeof a && "@" == (D ? a.charAt(0) : a[0]) && ":" == z() || l(); c[a.slice(1)] = P(z()) } return c } l() } return a }, T = function (a, b, e) {
                    e = S(a, b, e); e ===
                    w ? delete a[b] : a[b] = e
                }, S = function (a, b, e) { var h = a[b], f; if ("object" == typeof h && h) if ("[object Array]" == u.call(h)) for (f = h.length; f--;) T(h, f, e); else B(h, function (a) { T(h, a, e) }); return e.call(a, b, h) }; r.parse = function (a, c) { var e, h; b = 0; J = "" + a; e = P(z()); "$" != z() && l(); b = J = null; return c && "[object Function]" == u.call(c) ? S((h = {}, h[""] = e, h), "", c) : e }
            }
        } r.runInContext = N; return r
    } var K = typeof define === "function" && define.amd, F = { "function": !0, object: !0 }, G = F[typeof exports] && exports && !exports.nodeType && exports, k = F[typeof window] &&
    window || this, t = G && F[typeof module] && module && !module.nodeType && "object" == typeof global && global; !t || t.global !== t && t.window !== t && t.self !== t || (k = t); if (G && !K) N(k, G); else { var L = k.JSON, Q = k.JSON3, M = !1, A = N(k, k.JSON3 = { noConflict: function () { M || (M = !0, k.JSON = L, k.JSON3 = Q, L = Q = null); return A } }); k.JSON = { parse: A.parse, stringify: A.stringify } } K && define(function () { return A })
}).call(this);

;// roblox.js
(function(n,t){function b(n,i){var r=i.split(".");for(i=r.shift();r.length>0;n=n[i],i=r.shift())if(n[i]===t)return t;return n[i]}function y(n,i,r){var u=i.split(".");for(i=u.shift();u.length>0;n=n[i],i=u.shift())n[i]===t&&(n[i]={});n[i]=r}function w(n,t){var i=f.createElement("link");i.href=n,i.rel="stylesheet",i.type="text/css",u.parentNode.insertBefore(i,u),t()}function p(n,t){var i=f.createElement("script");i.type="text/javascript",i.src=n,i.onload=i.onreadystatechange=function(){i.readyState&&i.readyState!="loaded"&&i.readyState!="complete"||(t(),i.onload=i.onreadystatechange=null)},u.parentNode.insertBefore(i,u)}function g(n){return n.split(".").pop().split("?").shift()}function l(n){if(n.indexOf(".js")<0)return n;if(n.indexOf(r.modulePath)>=0)return n.split(r.modulePath+"/").pop().split(".").shift().replace("/",".");for(var t in r.paths)if(r.paths[t]==n)return t;return n}function v(n){return n.indexOf(".js")>=0||n.indexOf(".css")>=0?n:r.paths[n]||r.baseUrl+r.modulePath+"/"+n.replace(".","/")+".js"}function c(n){for(var r,u=[],i=0;i<n.length;i++)r=b(Roblox,l(n[i])),r!==t&&u.push(r);return u}function e(n){var t=i[n];if(t.loaded&&t.depsLoaded)while(t.listeners.length>0)t.listeners.shift()()}function a(n,u){var f,o,s;if(!d(n)||r.externalResources.toString().indexOf(n)>=0)return u();f=l(n),i[f]===t?(i[f]={loaded:!1,depsLoaded:!0,listeners:[]},i[f].listeners.push(u),o=v(f),s=g(o)=="css"?w:p,s(o,function(){i[f].loaded=!0,e(f)})):(i[f].listeners.push(u),e(f))}function o(n,t){var i=n.shift(),r=n.length==0?t:function(){o(n,t)};a(i,r)}function h(n,t){s(n)||(n=[n]);var i=function(){t.apply(null,c(n))};o(n.slice(0),i)}function nt(n,t,r){k(t)?(r=t,t=[]):s(t)||(t=[t]),i[n]=i[n]||{loaded:!0,listeners:[]},i[n].depsLoaded=!1,i[n].listeners.unshift(function(){y(Roblox,n,r.apply(null,c(t)))}),h(t,function(){i[n].depsLoaded=!0,e(n)})}var f=n.document,u=f.getElementsByTagName("script")[0],d=function(n){return typeof n=="string"},s=function(n){return Object.prototype.toString.call(n)=="[object Array]"},k=function(n){return Object.prototype.toString.call(n)=="[object Function]"},i={},r={baseUrl:"",modulePath:"/js/modules",paths:{},externalResources:[]};typeof Roblox=="undefined"&&(Roblox={}),Roblox.config=r,Roblox.require=h,Roblox.define=nt})(window);

;// leancore/RobloxHeaderInit.js
typeof Roblox=="undefined"&&(Roblox={},Roblox.Resources={});

;// jquery.cookie.js
jQuery.cookie=function(n,t,i){var f,r,e,o,u,s;if(typeof t!="undefined"){i=i||{},t===null&&(t="",i.expires=-1),f="",i.expires&&(typeof i.expires=="number"||i.expires.toUTCString)&&(typeof i.expires=="number"?(r=new Date,r.setTime(r.getTime()+i.expires*864e5)):r=i.expires,f="; expires="+r.toUTCString());var h=i.path?"; path="+i.path:"",c=i.domain?"; domain="+i.domain:"",l=i.secure?"; secure":"";document.cookie=[n,"=",encodeURIComponent(t),f,h,c,l].join("")}else{if(e=null,document.cookie&&document.cookie!="")for(o=document.cookie.split(";"),u=0;u<o.length;u++)if(s=jQuery.trim(o[u]),s.substring(0,n.length+1)==n+"="){e=decodeURIComponent(s.substring(n.length+1));break}return e}};

;// dababy.js
const dababyCode = ['d', 'a', 'b', 'a', 'b', 'y'];
let dababyIndex = 0;
let dababyActive = false;

function closeDababyVideo() {
  document.getElementById('dababyOverlay')?.remove();
  document.getElementById('dababyVideoContainer')?.remove();
  document.getElementById('dababyCloseBtn')?.remove();
  dababyIndex = 0;
  dababyActive = false;
}

function createDababyVideo() {
  dababyActive = true;
  const overlay = document.createElement('div');
  overlay.id = 'dababyOverlay';
  Object.assign(overlay.style, {
    position: 'fixed',
    top: 0,
    left: 0,
    width: '100vw',
    height: '100vh',
    backgroundColor: 'rgba(0,0,0,0.85)',
    zIndex: 1000
  });
  document.body.appendChild(overlay);

  const videoContainer = document.createElement('div');
  videoContainer.id = 'dababyVideoContainer';
  Object.assign(videoContainer.style, {
    position: 'fixed',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: '80vw',
    maxWidth: '960px',
    aspectRatio: '16 / 9',
    zIndex: 1001
  });

  const iframe = document.createElement('iframe');
  iframe.src = 'https://www.youtube.com/embed/4teKSe9Dx3Q?autoplay=1&controls=1&modestbranding=1';
  iframe.allow = 'autoplay; encrypted-media';
  iframe.allowFullscreen = true;
  Object.assign(iframe.style, {
    width: '100%',
    height: '100%',
    border: 'none',
    borderRadius: '10px',
    boxShadow: '0 0 25px #000'
  });
  
  videoContainer.appendChild(iframe);
  document.body.appendChild(videoContainer);
  const closeBtn = document.createElement('button');
  closeBtn.id = 'dababyCloseBtn';
  closeBtn.textContent = 'Close';
  Object.assign(closeBtn.style, {
    position: 'fixed',
    top: '20px',
    right: '20px',
    zIndex: 1002,
    background: '#ff3c3c',
    color: '#fff',
    padding: '10px 18px',
    fontSize: '16px',
    border: 'none',
    borderRadius: '8px',
    cursor: 'pointer'
  });
  closeBtn.onmouseenter = () => closeBtn.style.background = '#cc2f2f';
  closeBtn.onmouseleave = () => closeBtn.style.background = '#ff3c3c';
  closeBtn.onclick = closeDababyVideo;
  document.body.appendChild(closeBtn);
}

document.addEventListener('keydown', (e) => {
  if (dababyActive && e.key === 'Escape') {
    closeDababyVideo();
    return;
  }
  if (!dababyActive && e.key.toLowerCase() === dababyCode[dababyIndex]) {
    dababyIndex++;
    if (dababyIndex === dababyCode.length) {
      createDababyVideo();
    }
  } else if (!dababyActive) {
    dababyIndex = 0;
  }
});

;// usada.js
(() => {
  let imageCreated = false;
  let buffer = "";

  function createBouncingImage() {
    if (imageCreated) return;
    imageCreated = true;

    const img = document.createElement("img");
    img.src = "https://media.discordapp.net/attachments/1388547484426440815/1394191605782548500/Usada_Pekora.png?ex=6875e99d&is=6874981d&hm=94e39675e068d1bafa71d1f8b0ec68d1cc2ab367f4fc5e1b096d969aec568198&=&format=webp&quality=lossless";
    Object.assign(img.style, {
      position: "fixed",
      width: "100px",
      height: "100px",
      left: "50px",
      top: "50px",
      zIndex: 9999,
      pointerEvents: "none"
    });

    document.body.appendChild(img);

    let dx = 2, dy = 2;

    function animate() {
      const rect = img.getBoundingClientRect();
      const winW = window.innerWidth;
      const winH = window.innerHeight;

      let newX = rect.left + dx;
      let newY = rect.top + dy;

      if (newX < 0 || newX + rect.width > winW) dx *= -1;
      if (newY < 0 || newY + rect.height > winH) dy *= -1;

      img.style.left = `${rect.left + dx}px`;
      img.style.top = `${rect.top + dy}px`;

      requestAnimationFrame(animate);
    }

    requestAnimationFrame(animate);
  }

  document.addEventListener("keydown", (e) => {
    buffer += e.key.toLowerCase();
    if (buffer.length > 10) buffer = buffer.slice(-10);
    if (buffer.includes("usada")) createBouncingImage();
  });
})();
