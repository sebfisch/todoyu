Todoyu.PanelWidgetStatusSelector = Class.create({

	list: null,

	initialize: function(list) {
		this.list = $(list);

		this._observeList();
	},

	/**
	 * Install (selection change) event observer for the PanelWidget
	 */
	_observeList: function() {
		this.list.observe('change', this._onChange.bindAsEventListener(this));
	},

	_onChange: function(event) {
		var runDefault = true;

		if( this.onChange ) {
			runDefault = this.onChange(event);
		}

		if( runDefault ) {
			this._defaultOnChange(event);
		}
	},

	_defaultOnChange: function(event) {
		if( ! this.isAnyStatusSelected() ) {
			this.selectAll();
		}
	},


	onChange: function(event) {
		return true;	
	},
	

	/**
	 * Get form value of the PanelWidget (selected statuses)
	 *
	 * @return	Array
	 */
	getValue: function() {
		return this.getSelectedStatuses();
	},



	/**
	 * Get selected statuses
	 *
	 * @return	Array
	 */
	getSelectedStatuses: function() {
		return $F(this.list);
	},



	/**
	 * Get the number of selected statuses
	 *
	 * @return	{Number}
	 */
	getNumSelected: function() {
		return this.getValue().length;
	},



	/**
	 * Check if any status' checkbox is checked
	 *
	 * @return	{Boolean}
	 */
	isAnyStatusSelected: function() {
		return this.getNumSelected() > 0;
	},



	/**
	 * Select all statuses
	 */
	selectAll: function() {
		$(this.list).childElements().each(function(option){
			option.selected = true;
		});
	},

	fireUpdate: function(key) {
		Todoyu.PanelWidget.fire(key, this.getValue());
	}

});