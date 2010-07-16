Todoyu.PanelWidgetStatusSelector = Class.create({

	list: null,

	/**
	 * Initialize panel widget
	 *
	 * @param	{String}	list		element ID
	 */
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



	/**
	 * Handle list selection change
	 *
	 * @param	{Object}	event
	 */
	_onChange: function(event) {
		var runDefault = true;

		if( this.onChange ) {
			runDefault = this.onChange(event);
		}

		if( runDefault ) {
			this._defaultOnChange(event);
		}
	},



	/**
	 * Default selection change handler: select all if no option selected
	 *
	 * @param	{Object}	event
	 */
	_defaultOnChange: function(event) {
		if( ! this.isAnyStatusSelected() ) {
			this.selectAll();
		}
	},


	/**
	 * OnChange handler
	 *
	 * @param	{Object}	event
	 * @return	{Boolean}
	 */
	onChange: function(event) {
		return true;	
	},
	

	/**
	 * Get form value of the panel widget (selected statuses)
	 *
	 * @return	{Array}
	 */
	getValue: function() {
		return this.getSelectedStatuses();
	},



	/**
	 * Get selected statuses of panel widget
	 *
	 * @return	{Array}
	 */
	getSelectedStatuses: function() {
		return $F(this.list);
	},



	/**
	 * Get amount of selected statuses
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



	/**
	 * Evoke update of given panel widget
	 *
	 * @param	{String}	key
	 */
	fireUpdate: function(key) {
		Todoyu.PanelWidget.fire(key, this.getValue());
	}

});