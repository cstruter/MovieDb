var Helpers = {
	App: {
		Delegate: {
			_event : function($q, name) {
				var deferred = $q.defer();
				var callback = function() {
					$(document).off(name, callback);
					deferred.resolve();
				};
				$(document).on(name, callback);
				return deferred.promise;
			},
			Init: function($q) { return this._event($q, 'pageinit'); },
			Show: function($q) { return this._event($q, 'pageshow'); }			
		}
	},
	Page: {
		Delegate: {
			Navigate :  function(id, $q) {
				$.mobile.changePage(id);
				this.Show(id, $q);
			},
			Show: function(id, $q) {
				if ($q.defer !== undefined) {
					var deferred = $q.defer();
					var callback = function() {
						$(document).off('pageshow', id, callback);
						deferred.resolve();
					};
					$(document).on('pageshow', id, callback);
					return deferred.promise;
				} else {
					var callback = function() {
						$(document).off('pageshow', id, callback);
						$q();
					};
					$(document).on('pageshow', id, callback);
				}
			}
		}
	},
	Loader: {
		Show : function() {
			$.mobile.loading('show');
			$('body').addClass('ui-disabled');
		},
		Hide : function() {
			$.mobile.loading('hide');
			$('body').removeClass('ui-disabled');
		}
	},
	Dialog: {
		_dialog : function(id, options, buttons) {
			return $('<div></div>', {
						'id' : id,
						'data-theme' : 'b',
						'overlay-data-theme' : 'b',
						'data-role' : 'dialog'
					}).append(
					$('<div></div>', { 'data-role': 'content' })
						.append($('<h3></h3>', { text : options.title }))
						.append($('<p></p>', { text : options.message }))
						.append(buttons)
				);
		},
		Prompt : function(options, yes, no) {
			$('#prompt').remove();
			this._dialog('prompt', options, 
				$('<div></div>')
					.append($('<a></a>', { 
						'href' : '#', 
						'data-role' : 'button', 
						text : 'Yes' })
					.on('click', yes))
					.append($('<a></a>', { 
						'href' : '#', 
						'data-role' : 'button', 
						'data-rel' : 'back', 
						text : 'No' })
					.on('click', no))
			).appendTo('body')
			 .dialog();
			$.mobile.changePage('#prompt');
		},
		Alert : function(options, ok) {
			$('#alert').remove();
			this._dialog('alert', options, 
				$('<div></div>')
					.append($('<a></a>', { 
						'href' : '#', 
						'data-role' : 'button', 
						'data-rel' : 'back', 
						text : 'Ok' })
					.on('click', ok))
			).appendTo('body')
			 .dialog();
			$.mobile.changePage('#alert');
		}		
	}
};