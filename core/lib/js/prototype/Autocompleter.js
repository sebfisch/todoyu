Todoyu.Autocompleter = Class.create(Ajax.Autocompleter, {
	onComplete: function(request) {
			// If a custom onComplete defined
		if( this.options.onCompleteCustom ) {
			var funResult = Todoyu.callUserFunction(this.options.onCompleteCustom, window, request);
				// If the custom function return something, override request
			if( typeof(funResult) === 'object' ) {
				request = funResult;
			}
		}		
		this.updateChoices(request.responseText);
	}
});