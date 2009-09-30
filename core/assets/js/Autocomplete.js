/**
 * @author ferni
 */
Todoyu.Autocomplete = {
	
	config: {
		paramName: 'sword',
		minChars: 2
	},
	
	acRefs: {},
	
	
	/**
	 *	Initialize autocompleter ('inputAC')
	 *
	 */
	install: function(idElement, config)	{
		var inputField		= idElement + '-fulltext';
		var suggestDiv		= idElement + '-suggest';

			// setup request
		var url		= Todoyu.getUrl(config.acListener.ext, config.acListener.controller);
		var options = {
			paramName:	this.config.paramName,
			minChars:	config.acListener.minChars || this.config.minChars,
			callback:	this.beforeRequestCallback.bind(this),
			parameters:	'&cmd=' + config.acListener.cmd	+ '&acelementid=' + idElement,
			afterUpdateElement:	this.onElementSelected.bind(this)
		};
		
			// Create autocompleter
		this.acRefs[idElement] = new Ajax.Autocompleter(inputField, suggestDiv, url, options);

			// Observe input
		$(inputField).observe('change', this.onInputChange.bindAsEventListener(this));
	},
		
	beforeRequestCallback: function(idElement, acParam) {
		var form	= $(idElement).up('form');
		var name	= form.readAttribute('name');
		var data	= form.serialize();

		return acParam + '&formName=' + name + '&' + data;
	},
		
	onInputChange: function(event) {
		var idElement = event.element().id.split('-').without('fulltext').join('-');
		this.clear(idElement);
	},
	
		/**
	 *	Update li
	 *
	 *	@param	unknown_type	input
	 *	@param	unknown_type	li
	 */
	onElementSelected: function(inputField, selectedListElement) {
		var baseID			= inputField.id.split('-').without('fulltext').join('-');
		var selectedValue	= selectedListElement.id;
		
		$(baseID).setValue(selectedValue);
	},
	
	clear: function(element) {
		var idElement = $(element).id;
		$(idElement).setValue('0');
		$(idElement + '-fulltext').setValue('');
	}
	
};