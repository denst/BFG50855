Loader = function()								{ return Loader.implement.init(); }	

Loader.showCategory	= function(cat_id,page)		{ Loader.implement.showCategory(cat_id,page) }
Loader.showInfo		= function(item_id)			{ Loader.implement.showInfo(item_id) }

Loader.implement = {
	
	libraries_path: '/themes/js/',
	// Объект, в котором перечислены все библиотеки системы со всеми зависимостями
	libraries: {
		'core': {
			path: 'core',
			requires: ['jquery','jquery.mousewheel','jquery.scrollto']
		},
		'jquery': {
			path: 'jquery-1.6.2',
			requires: []
		},
		'card': {
			path: 'class/card',
			requires: ['','jquery.cookie','jquery.json']
		}
	},
	
	init: function () {
		//var self = this;return self;
	}
}
