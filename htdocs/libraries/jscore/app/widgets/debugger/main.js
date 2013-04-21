define(function(require) {
  var $ = require('jquery')
  , cookie = require('util/require-utils/cookie')
  , app = {
    initialize: function() {
      $(document).ready(function() {
        var stored = cookie.getCookie('icms_debug');
        if(stored !== 'undefined' && stored) {
          $(stored).addClass('active').siblings().removeClass('active');
          $('#xo-logger-output .nav a[href='+stored+']').parent().addClass('active').siblings().removeClass('active');
        }

        $('#xo-logger-output .nav a').on({
          click: function() {
            cookie.createCookie('icms_debug', $(this).attr('href'));
          }
        });
      });
    }
  };
  return app;
});