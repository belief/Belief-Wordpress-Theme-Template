define(["_nav","fastclick","lazyload"],function(e,i){var t,n,o;return{init:function(s,a){t=s,n=a,o=$("body"),n.ready(function(){e.init(t,n),i.attach(o[0]),o.on("touchmove",function(e){o.hasClass("is-active")&&e.preventDefault()}),t.resize(function(){this.resizeTO&&clearTimeout(this.resizeTO),this.resizeTO=setTimeout(function(){$(this).trigger("resizeEnd")},200)}),javascript_args.wp_debug&&console.log("common loaded")})}}});