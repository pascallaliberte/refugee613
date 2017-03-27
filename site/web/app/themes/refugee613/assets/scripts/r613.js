var app = new Vue({
  el: '#app',
  data: function() {
     return {
         services: []
     }
  },
  mounted: function(){
    // TODO: clean up
      var $ = window.jQuery;
      var _type = "health";
      var _languages = [ 'english', 'arabic' ];
      var _clients = [ 'canadian-citizens' ];

      $.post('/wp/wp-admin/admin-ajax.php', { action: 'json_get_services', security : window.MyAjax.security, service_type: _type, languages: _languages, clients: _clients }, function(response) {
        app.services = response;
      });
  }
});
