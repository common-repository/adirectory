/*!
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */ !function(n,o){let u,t=n.jQuery||n.Cowboy||(n.Cowboy={});t.throttle=u=function(n,u,i,e){let r,c=0;function f(){const t=this,f=+new Date-c,d=arguments;function a(){c=+new Date,i.apply(t,d)}e&&!r&&a(),r&&clearTimeout(r),e===o&&f>n?a():!0!==u&&(r=setTimeout(e?function n(){r=o}:a,e===o?n-f:n))}return"boolean"!==typeof u&&(e=i,i=u,u=o),t.guid&&(f.guid=i.guid=i.guid||t.guid++),f},t.debounce=function(n,t,i){return i===o?u(n,t,!1):u(n,i,!1!==t)}}(this);