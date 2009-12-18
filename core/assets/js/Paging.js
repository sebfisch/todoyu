Todoyu.Paging = {
	
	config: {},
	
	init: function(name, offset, url) {
		url	= url.split('/');
		
		this.config[name] = {
			'name':		name,
			'offset':	offset,
			'url': {
				'ext':			url[0],
				'controller': 	url[1],
				'action':		url[2]
			}
		};
	},
	
	update: function(name, offset) {
		var url		= Todoyu.getUrl(this.config[name].url.ext, this.config[name].url.controller);
		var options	= {
			'parameters': {
				'action': 	this.config[name].url.action,
				'name':		name,
				'offset':	offset
			},
			'onComplete': this.onUpdated.bind(this, name, offset)
		};
		
		Todoyu.Ui.updateContent(url, options);
	},
	
	onUpdated: function(name, offset, response) {
		
	}
	
};