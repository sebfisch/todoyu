/**
 * Created by IntelliJ IDEA.
 * User: ferni
 * Date: 08.09.2010
 * Time: 16:40:24
 * To change this template use File | Settings | File Templates.
 */

Todoyu.OverflowWindows = {};

Todoyu.OverflowWindow = Class.create({

	/**
	 * Default window configuration
	 *
	 * @param	{Array}		config
	 */
	config: {
		id: 'default',
		onUpdate: Prototype.emptyFunction,
		onDisplay: Prototype.emptyFunction,
		onHide: Prototype.emptyFunction,
		width: 400,
		url: '',
		options: {},
		loadOnCreate: true
	},

	/**
	 * Div elements which builds the window
	 *
	 * @param	{Element}	divElement
	 */
	divElement: null,



	/**
	 * Constructor
	 *
	 * @param	{Object}	config
	 */
	initialize: function(config) {
		Todoyu.OverflowWindows[config.id] = this;

		this.config = $H(this.config).update(config).toObject();

		this._buildWindow(this.config.id);

		if( this.config.loadOnCreate ) {
			this.update(this.config.url, this.config.options);
		}
	},


	/**
	 * Build the window in the DOM
	 *
	 * @param	{String}	idWindow
	 */
	_buildWindow: function(idWindow) {
		this.divElement = new Element('div', {
			id: 'overflow-window-' + idWindow
		}).setStyle({
			display: 'none',
			'z-index': '1000',
			width: this.config.width + 'px'
		}).addClassName('overflowWindow');

		document.body.appendChild(this.div());

		Todoyu.Ui.centerElement(this.div());
	},



	/**
	 * Animate fade in and out
	 *
	 * @param	{Boolean}	show
	 */
	_animate: function(show) {
		show 			= show ? true : false;
		var screenDim	= document.viewport.getDimensions();
		var windowDim	= this.div().getDimensions();

		var left	= parseInt((screenDim.width-windowDim.width)/2);
		var topHide	= -windowDim.height - 30;
		var top;

		if( show ) {
			var styles	= {
				'left': left + 'px',
				'top': topHide + 'px',
				'display': 'block'
			};

			if( this.config.width > 0 ) {
				styles.width = this.config.width + 'px';
			}
			if( this.config.height > 0 ) {
				styles.height = this.config.height + 'px';
			}

			this.div().setStyle(styles);

			top	= parseInt((screenDim.height-windowDim.height)/2);
			top	= top < 0 ? 0 : top;
		} else {
			top	= topHide;
		}

			// Move in/out
		new Effect.Move(this.div(), {
			y: top,
			x: left,
			'mode': 'absolute',
			'duration': 0.5,
			'afterFinish': show ? this._onAnimateIn.bind(this) : this._onAnimateOut.bind(this)
		});
	},



	/**
	 * Callback when show animation has finished
	 */
	_onAnimateIn: function() {
		this.config.onDisplay();
	},



	/**
	 * Callback when hide animation has finished
	 */
	_onAnimateOut: function() {
		this.div().hide();
		this.config.onHide();
	},



	/**
	 * Callback when windows has been updated
	 *
	 * @param	{Ajax.Response}		response
	 */
	_onUpdated: function(response) {
		this.show();
		this._addCloseButton();
		this._adjustHeight.bind(this).defer();
		this.config.onUpdate(response);
	},



	/**
	 * Adjust height of window if bigger than the screen height
	 */
	_adjustHeight: function() {
		if( (this.div().getHeight() + 20) > document.viewport.getHeight() ) {
			this.div().setStyle({
				height: (document.viewport.getHeight() - 40) + 'px'	,
				top: '10px'
			});
		}
	},



	/**
	 * Add the close button when windows was updated
	 */
	_addCloseButton: function() {
		var closeButton	= new Element('div', {
			id: 'overflow-window-' + this.config.id	+ '-close',
			'class': 'close'
		});

		closeButton.observe('mouseup', this._onCloseClick.bindAsEventListener(this));

		this.div().appendChild(closeButton);
	},



	/**
	 * Handler when clicked on the close button
	 *
	 * @param	{Event}		event
	 */
	_onCloseClick: function(event) {
		this.hide();
	},



	/**
	 * Get window div element
	 */
	div: function() {
		return this.divElement;
	},



	/**
	 * Update windows content
	 *
	 * @param	{String}	url
	 * @param	{Object}	options
	 * @param	{Boolean}	replaceOptions
	 */
	update: function(url, options, replaceOptions) {
		url		= url || this.config.url;

		if( options ) {
			if( replaceOptions !== true ) {
				options = $H(options).merge(this.config.options).toObject();
			}
		} else {
			options = this.config.options;
		}

		if( options.onComplete ) {
			options.onComplete = options.onComplete.wrap(
				function(callOriginal, response) {
					this._onUpdated(response);
					callOriginal(response);
				}.bind(this)
			);
		} else {
			options.onComplete = this._onUpdated.bind(this);
		}

		Todoyu.Ui.update(this.div(), url, options);
	},



	/**
	 * Show window
	 */
	show: function() {
		if( ! this.visible() ) {
			this._animate(true);
		} else {
			this.div().show();
			this.config.onDisplay();
		}
	},



	/**
	 * Hide windows
	 */
	hide: function() {
		if( this.visible() ) {
			this._animate(false);
		} else {
			this.div().hide();
		}
	},



	/**
	 * Check whether the windows is visible
	 */
	visible: function() {
		return this.div().visible();
	},


	setContent: function(content) {
		this.div().update(content);
		this.config.onUpdate();
	}
});