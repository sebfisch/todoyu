var TimePicker = Class.create({
	
	/**
	 * Selected hour and minute
	 */
	hour:	0,
	minute:	0,

	/**
	 * Input element
	 */
	element: null,

	/**
	 * Picker divs. Container, hour, minute
	 */
	picker:		null,
	divHour:	null,
	divMinute:	null,



	/**
	 * Configuration
	 */
	config: {
		height: 22,
		rangeHour: [0,99],
		stepHour: 1,
		rangeMinute: [0,55],
		stepMinute: 5
	},



	/**
	 * Constructor
	 * @param	String		idElement
	 */
	initialize: function(idElement, config) {		
		this.element= $(idElement);
		this.config	= $H(this.config).merge(config || {}).toObject();

		var dur = this._readDuration();

		if( dur.min % 5 !== 0 ) {
			this.config.stepMinute = 1;
			this.config.rangeMinute = [0,59];
		} else {
			this.config.stepMinute = 5;
			this.config.rangeMinute = [0,55];
		}

		this._build();
		this._observePicker();
				
		this.setHour(dur.hour);
		this.setMinute(dur.min);

		this.updateElement();

		this.show();
	},



	/**
	 * Show picker near the element
	 */
	show: function() {
		this._setPosition();
		
		this.picker.show();
	},



	/**
	 * Hide the picker
	 */
	hide: function() {
		this.picker.hide();
	},



	/**
	 * Set hour and update scroll
	 * @param	Integer		hour
	 */
	setHour: function(hour) {
		this.hour = this._keepInRange(hour, this.config.rangeHour);
		
		this.updateScroll();
	},



	/**
	 * Set minute and update scroll
	 * @param	Integer		minute
	 */
	setMinute: function(minute) {		
		this.minute = this._keepInRange(minute, this.config.rangeMinute);
				
		this.updateScroll();
	},



	/**
	 * Update scroll of minute and hour
	 */
	updateScroll: function() {
		var newHourPos = this.hour * this.config.height * -1 + 2 * this.config.height - (2 * this.config.height);
		new Effect.Move(this.divHour, {
			'y': newHourPos,
			'mode': 'absolute',
			'duration': 0.3
		});

		var newMinPos = (this.minute/this.config.stepMinute) * this.config.height * -1 + 2 * this.config.height - (2 * this.config.height);
				
		new Effect.Move(this.divMinute, {
			'x': 25,
			'y': newMinPos,
			'mode': 'absolute',
			'duration': 0.3
		});
		
		this.updateElement.bind(this).delay(0.2);
	},



	/**
	 * Update current selection in element
	 */
	updateElement: function() {
		this.element.value = this.hour + ':' + Todoyu.Helper.twoDigit(this.minute);
	},



	/**
	 * Set picker position near the element
	 */
	_setPosition: function() {
		var elOffset= this.element.cumulativeOffset();
		var elDim	= this.element.getDimensions();
		var dpHeight= this.picker.getHeight();
		var left	= elOffset.left + elDim.width + 1;
		var top		= elOffset.top + (elDim.height/2) - (dpHeight/2);
		
		this.picker.setStyle({
			'display': 'block',
			'left': left + 'px',
			'top': top + 'px'
		});
	},



	/**
	 * Make element id, prefixed with element id
	 * @param	String		name
	 */
	_makeID: function(name) {
		return this.element.id + '-' + name;
	},
	
	/**
	 * Build picker html
	 */
	_build: function() {
		this._remove();

		this.picker = new Element('div', {
			'id': this._makeID('durationpicker'),
			'class': 'dpPicker',
		});

		this.divHour = new Element('div', {
			'id': this._makeID('durationpicker-hour'),
			'class': 'dpHour dpCol'
		});

		this.divMinute = new Element('div', {
			'id': this._makeID('durationpicker-minute'),
			'class': 'dpMinute dpCol'
		});

		this.picker.insert(new Element('div', {
			'id': this._makeID('durationpicker-mask'),
			'class': 'dpMask'
		}));

		this._insertHours();
		this._insertMinute();

		this.picker.insert(this.divHour);
		this.picker.insert(this.divMinute);

		this.hide();

		$(document.body).insert(this.picker);
	},



	/**
	 * Insert hour elements
	 */
	_insertHours: function() {
		for(var i = this.config.rangeHour[0]; i <= this.config.rangeHour[1]; i += this.config.stepHour) {
			this.divHour.insert(new Element('div').update(i));
		}
	},



	/**
	 * Insert minute elements
	 */
	_insertMinute: function() {
		for(var i = this.config.rangeMinute[0]; i <= this.config.rangeMinute[1]; i += this.config.stepMinute) {
			this.divMinute.insert(new Element('div').update(Todoyu.Helper.twoDigit(i)));
		}
	},



	/**
	 * Remove picker from document
	 */
	_remove: function() {
		var idPicker = this._makeID('durationpicker');
		
		if( Todoyu.exists(idPicker) ) {
			$(idPicker).remove();
		};
	},



	/**
	 * Observe picker for click and wheel turning
	 * Observe element for clicks
	 */
	_observePicker: function() {
		var wheelEventName	= Prototype.Browser.Gecko ? 'DOMMouseScroll' : 'mousewheel';
		
		this.picker.observe('click', this._onSelection.bindAsEventListener(this));
		this.divHour.observe(wheelEventName, this._onHourScroll.bindAsEventListener(this));
		this.divMinute.observe(wheelEventName, this._onMinuteScroll.bindAsEventListener(this));
		
		this.element.observe('click', this._onElementClick.bindAsEventListener(this));
	},



	/**
	 * Event handler for picker click
	 * @param	Event		event
	 */
	_onSelection: function(event) {
		var column	= event.findElement('div.dpCol');
		var delay	= 0;
		
		if( column !== event.element() ) {
			var type = column.id.split('-').last();
			var value= Todoyu.Helper.intval(event.element().innerHTML);
			
			if( type == 'hour' ) {
				this.setHour(value);
			} else {
				this.setMinute(value);
			}
			
			delay = 0.5;
		}
		
		this.updateElement.bind(this).delay(delay);
		this.hide.bind(this).delay(delay);
	},



	/**
	 * Event handler for element click
	 * @param	Event		event
	 */
	_onElementClick: function(event) {
		this.hide();
	},



	/**
	 * Event handler for hour scroll
	 * @param	Event		event
	 */
	_onHourScroll: function(e) {
		Event.stop(e);
		
		var hour = this.hour - Event.wheel(e) * this.config.stepHour;
		
		this.setHour(hour);
	},



	/**
	 * Event handler for minute scroll
	 * @param	Event		event
	 */
	_onMinuteScroll: function(e) {
		Event.stop(e);
		
		var minute = this.minute - Event.wheel(e) * this.config.stepMinute;
		
		this.setMinute(minute);
	},



	/**
	 * Make sure the value stays in the range.
	 * @param	Integer		value
	 * @param	Array		range		Bottom and top range
	 */
	_keepInRange: function(value, range) {
		if( value < range[0] ) {
			value = range[0];
		}
		
		if( value > range[1] ) {
			value = range[1];
		}
		
		return value;
	},



	/**
	 * Read duration from element
	 */
	_readDuration: function() {
		var value = $F(this.element);
		var dur	= {
			'hour': 0,
			'min': 0
		};
		
		if( value !== '' ) {
			if( value.indexOf(':') === -1 ) {
				dur.hour = Todoyu.Helper.intval(value);
			} else {
				var parts	= value.split(':');
		
				if( parts.size() === 2 ) {
					dur.hour = Todoyu.Helper.intval(parts[0]);
					dur.min = Todoyu.Helper.intval(parts[1]);
				}
			}
		}
		
		return dur;	
	}

});