// Amazon
// Amazon services
(function(hello) {

	hello.init({

		simplesaml: {
			name: 'simpleSAMLphp',

			oauth: {
				version: 2,
				auth: 'http://localhost/simplesaml/module.php/oauth2/authorize.php',
				grant: 'http://localhost/simplesaml/module.php/oauth2/access_token.php',
				response_type: 'token'
			},

			// Refresh the access_token once expired
			refresh: true,

			scope: {
				basic: 'basic'
			},

			scope_delim: ' ',

			login: function(p) {
				p.options.popup.width = 710;
			},

			base: 'http://localhost/simplesaml/module.php/oauth2/',

			// There aren't many routes that map to the hello.api so I included me/bikes
			// ... because, bikes
			get: {
				userinfo: 'userinfo.php'
			},
			wrap: {
				userinfo: function(o, headers) {}
			}
		}
	});

})(hello);

