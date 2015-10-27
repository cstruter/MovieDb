var App = angular.module('App', ['jqueryMobile', 'facebook'])
	.service('DBService', DBService)
	.service('OMDBService', OMDBService)
	.controller('MovieController', MovieController)
	.filter('byPropertyInArray', function() {
		return function(input, property, array) {
			if ((array == null) || (array.length == 0)) {
				return output;
			}
			var output = [];
			for (var i = 0; i < input.length; i++)
			{
				var item = input[i];
				for(var j = 0; j < array.length; j++)
				{
					if (item[property].indexOf(array[j]) > -1) {
						output.push(item);
						break;
					}
				}
			}
			return output;
		}
	});
	