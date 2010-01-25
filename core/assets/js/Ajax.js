/**
 * General ajax helper functions
 *
 */
Todoyu.Ajax = {

	/**
	 * Check if a no access header has been sent.
	 * Cancel execution and show error message if so
	 *
	 * @param	Ajax.Response	response
	 */
	checkNoAccessHeader: function(response) {
		if( response.hasNoAccess() ) {
				// Delete onComplete handler to prevent processing an empty respone
			delete response.request.options.onComplete;
			var missingRight = response.getTodoyuHeader('noAccess-right');
			Todoyu.notifyError('[LLL:core.noAccess.errorMessage] (' + missingRight + ')');
		}
	},



	/**
	 * Check if a php error header has been sent
	 * Cancel execution and show error message if so
	 *
	 * @param	Ajax.Response		response
	 */
	checkPhpErrorHeader: function(response) {
		if( response.hasPhpError() ) {
			delete response.request.options.onComplete;
			Todoyu.notifyError(response.getPhpError(), 0);
			Todoyu.log(response.getPhpError());
		}
	}

};