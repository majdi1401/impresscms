define(function (require) {
  var $ = require('jquery')
  , _flex = require('themeBase/app/modules/flexslider/flexcore')
  , module = {
    initialize: function(ele) {
      $(document).ready(function() {
        ele
        .fitVids()
        .flexslider({
          animation: "slide",
          useCSS: false,
          animationLoop: false,
          smoothHeight: true
        });

      });
    }
  };
  return module;
});