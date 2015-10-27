angular.module('facebook', [])
	.service('FacebookService', function ($q, $window) {
		var _loaded = $q.defer();
		var _serviceMethods = {
			signedRequest : null,
			GetAlbums : function() {
				return $FB('/me/albums');
			},
			GetPhotos: function(albumId) {
				return $FB('/' + albumId + '/photos');
			},
			GetFeed: function() {		
				return $FB('/me/home', 'get', 
					{ fields: 'full_picture,name,message,story,description,type,status_type,link'});			
			},
			GetProfile: function() {
				return $FB('/me', 'get');		
			}
		};
		
		$window.fbAsyncInit = function() {
			FB.init({
				appId      : Settings.FbApi,
				xfbml      : true,
				cookie	   : true,
				version    : 'v2.3'
			});

			FB.getLoginStatus(function(response) {
				if (response.status == 'connected') {
					_serviceMethods.signedRequest = response.authResponse.signedRequest;
					_loaded.resolve();
				} else if (response.status == 'not_authorized') {
					_loaded.reject();
				} else {
					FB.login(function (response) {
						if (response.status == "connected") {
							_serviceMethods.signedRequest = response.authResponse.signedRequest;
							_loaded.resolve();
						} else {
							_loaded.reject();
						}
					}, { scope: 'public_profile,email,user_photos,read_stream' });
				}
			});	
		};	

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		
		function $FB()
		{
			var _deferred = $q.defer();
			var args = Array.prototype.slice.call(arguments);
			args.push(function(response) {
				if (!response || response.error) {
					 deferred.reject(response);
				} else {
					_deferred.resolve(response);
				}
			});
			_loaded.promise.then(function() {
				FB.api.apply(this, args);
			}, function() {
				_deferred.reject();
			});
			return _deferred.promise;		
		}
		
		return _serviceMethods;
	});