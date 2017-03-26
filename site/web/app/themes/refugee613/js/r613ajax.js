function r613_get_services( _type ) {
  var _languages = [ 'english', 'arabic' ];
  var _clients = [ 'canadian-citizens' ]; //'canadian-citizens', 'permanent-residents', 'refugee-claimants'
	var data = {
		action: 'json_get_services',
    security : MyAjax.security,
		service_type: _type,
    languages: _languages,
    clients: _clients
	};
	jQuery.post( ajaxurl, data, function( response_ ) {
		console.log( 'AJAX', response_ );
	} );
}
