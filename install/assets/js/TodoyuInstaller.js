TodoyuInstaller = {
	
	/**
	 * User has agreed to license agreement: hide agreement and show installation dialog
	 */
	agreedToLicense: function() {
		$('licenseagreement').hide();
		$('installation-steps').show();	
		$('submit').show();	
	},



	/**
	 * Disable given text box if selected value == 0
	 * 
	 * @param	Element	selector 
	 */
	disableTextBox: function(selector)	{
		textbox = document.getElementById('database_new');
	
		if(selector.options[selector.selectedIndex].value == '0')	{
			textbox.disabled = false;
		} else {
			textbox.disabled = true;
		}
	},
	
	
	
	checkDbSelect: function() {
		if ( $F('database_new') !== '' ) {
			$('database').selectedIndex	= 0;
			$('database').disabled	= true;
		} else {
			$('database').disabled	= false;
		}
	},



	/**
	 * Toggle display of SQL review
	 * 
	 * @param	String	togglerID
	 * @param	String	sqlElementID	 
	 */
	toggle: function(togglerID, sqlElementID) {
		var toggler	= $(togglerID);

		if ( ! toggler.hasClassName('expanded') ) {
				// Collapse toggler
			toggler.innerHTML	= 'Hide SQL';
			toggler.addClassName('expanded');
			$(sqlElementID).show();						 			
		} else {
				// Expand toggler
			toggler.innerHTML	= 'View SQL';
			toggler.removeClassName('expanded');
			$(sqlElementID).hide();
		}
	},



	/**
	 * Ensure password and it's repetition are identical
	 */
	validatePasswordRepetition: function() {
		var areIdentic	= ( $F('password') == $F('password_confirm') );
		var longEnough	= $F('password').length >= 5;

		if ( areIdentic && longEnough ) {
			$('passwordLabel').removeClassName('redLabel');
			$('passwordConfirmLabel').removeClassName('redLabel');
			$('submit').show();
		} else {
			$('passwordLabel').addClassName('redLabel');
			$('passwordConfirmLabel').addClassName('redLabel');
			$('submit').hide();
		}
	},



	/**
	 *	Skip data import action (by altering action to next one) 
	 */
	skipDataImport: function() {
		document.getElementById('action').value = 'config';
	}

};