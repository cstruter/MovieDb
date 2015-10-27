angular.module('jqueryMobile', [])
	.directive('jqmOnShow', function($q) {
		return {
			link : function($scope, $element, attr) {
				$element.on('click', function() {
					Helpers.Page.Delegate.Show(attr.href, $q).then(function() {
						$scope.$eval(attr.jqmOnShow);
					});
				});
			}
		}
	})
	.directive('jqmListview', function($timeout) {
		return {
			link: function($scope, $element, attr){
				$($element).hide();
				$element.attr({
					'data-role': 'listview', 
					'data-inset': 'true'
				});
				$scope.$watch(function() {
					return $element.children().length;
				}, function () {
					if ($element.hasClass('ui-listview')) {
						$timeout(function() {
							$($element).listview('refresh');
							$($element).show();
						});
					} else {
						$($element).show();
					}
				});
			}
		}
	})
	.directive('jqmSelect', function($timeout) {
		return {
			controller: function($scope, $element, $attrs) {
				var select = $element.find('select');
				var model = select.controller('ngModel');
				$scope.$watch(function() { 
						return select.children().length; 
					}, function() {
					if (select.parent().hasClass('ui-select')) {
						$timeout(function() {
							$(select).selectmenu('refresh');
						});
					}
				});
				$(select).on('change', function() {
					$scope.$apply(function() {
						model.$setViewValue(select.val());
					});
				});
			},
			compile: function(element, attrs) {
				element.append(
					angular.element('<select></select>').attr({
						'data-theme': attrs.jqmTheme,
						'data-native-menu': attrs.jqmNativeMenu,
						'ng-model': attrs.ngModel,
						'ng-options': attrs.jqmOptions,
						'multiple': ((attrs.jqmMultiple) ? attrs.jqmMultiple : undefined)
					})
				);
			}
		};
	})
	.config(function($httpProvider) {
		$httpProvider.interceptors.push(function($q) {
			return {
				request : function (req) {
					Helpers.Loader.Show();
					return req;
				},
				response: function(res) {
					Helpers.Loader.Hide();
					return res;
				},
				responseError : function(rejection) {
					Helpers.Loader.Hide();
					return $q.reject(rejection);
				}
			};
		});
	});