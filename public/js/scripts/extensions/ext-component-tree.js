$((function(){"use strict";var t=$("#jstree-basic"),e=$("#jstree-custom-icons"),s=$("#jstree-context-menu"),a=$("#jstree-drag-drop"),n=$("#jstree-checkbox"),p=$("#jstree-ajax"),c="../../../app-assets/";"laravel"===$("body").attr("data-framework")&&(c=$("body").attr("data-asset-path")),t.length&&t.jstree(),e.length&&e.jstree({core:{data:[{text:"css",children:[{text:"app.css",type:"css"},{text:"style.css",type:"css"}]},{text:"img",state:{opened:!0},children:[{text:"bg.jpg",type:"img"},{text:"logo.png",type:"img"},{text:"avatar.png",type:"img"}]},{text:"js",state:{opened:!0},children:[{text:"jquery.js",type:"js"},{text:"app.js",type:"js"}]},{text:"index.html",type:"html"},{text:"page-one.html",type:"html"},{text:"page-two.html",type:"html"}]},plugins:["types"],types:{default:{icon:"far fa-folder"},html:{icon:"fab fa-html5 text-danger"},css:{icon:"fab fa-css3-alt text-info"},img:{icon:"far fa-file-image text-success"},js:{icon:"fab fa-node-js text-warning"}}}),s.length&&s.jstree({core:{check_callback:!0,data:[{text:"css",children:[{text:"app.css",type:"css"},{text:"style.css",type:"css"}]},{text:"img",state:{opened:!0},children:[{text:"bg.jpg",type:"img"},{text:"logo.png",type:"img"},{text:"avatar.png",type:"img"}]},{text:"js",state:{opened:!0},children:[{text:"jquery.js",type:"js"},{text:"app.js",type:"js"}]},{text:"index.html",type:"html"},{text:"page-one.html",type:"html"},{text:"page-two.html",type:"html"}]},plugins:["types","contextmenu"],types:{default:{icon:"far fa-folder"},html:{icon:"fab fa-html5 text-danger"},css:{icon:"fab fa-css3-alt text-info"},img:{icon:"far fa-file-image text-success"},js:{icon:"fab fa-node-js text-warning"}}}),a.length&&a.jstree({core:{check_callback:!0,data:[{text:"css",children:[{text:"app.css",type:"css"},{text:"style.css",type:"css"}]},{text:"img",state:{opened:!0},children:[{text:"bg.jpg",type:"img"},{text:"logo.png",type:"img"},{text:"avatar.png",type:"img"}]},{text:"js",state:{opened:!0},children:[{text:"jquery.js",type:"js"},{text:"app.js",type:"js"}]},{text:"index.html",type:"html"},{text:"page-one.html",type:"html"},{text:"page-two.html",type:"html"}]},plugins:["types","dnd"],types:{default:{icon:"far fa-folder"},html:{icon:"fab fa-html5 text-danger"},css:{icon:"fab fa-css3-alt text-info"},img:{icon:"far fa-file-image text-success"},js:{icon:"fab fa-node-js text-warning"}}}),n.length&&n.jstree({core:{data:[{text:"css",children:[{text:"app.css",type:"css"},{text:"style.css",type:"css"}]},{text:"img",state:{opened:!0},children:[{text:"bg.jpg",type:"img"},{text:"logo.png",type:"img"},{text:"avatar.png",type:"img"}]},{text:"js",state:{opened:!0},children:[{text:"jquery.js",type:"js"},{text:"app.js",type:"js"}]},{text:"index.html",type:"html"},{text:"page-one.html",type:"html"},{text:"page-two.html",type:"html"}]},plugins:["types","checkbox","wholerow"],types:{default:{icon:"far fa-folder"},html:{icon:"fab fa-html5 text-danger"},css:{icon:"fab fa-css3-alt text-info"},img:{icon:"far fa-file-image text-success"},js:{icon:"fab fa-node-js text-warning"}}}),p.length&&p.jstree({core:{data:{url:c+"data/jstree-data.json",dataType:"json",data:function(t){return{id:t.id}}}},plugins:["types","state"],types:{default:{icon:"far fa-folder"},html:{icon:"fab fa-html5 text-danger"},css:{icon:"fab fa-css3-alt text-info"},img:{icon:"far fa-file-image text-success"},js:{icon:"fab fa-node-js text-warning"}}})}));