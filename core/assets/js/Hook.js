/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Hook implementation in JS
 * Add named hooks to a list. Call all registered
 * hook functions with custom parameters
 * 
 * How to use:
 * define: 		function myCallback(arg1, arg2) {...}
 * register:	Todoyu.Hook.add('demoHookName', myCallback);
 * call:		Todoyu.Hook.exec('demoHookName', arg1, arg2); 
 */
Todoyu.Hook = {

	/**
	 * Hooks container
	 */
	hooks: {},



	/**
	 * Add a new hook function
	 * 
	 * @param	{String}		name
	 * @param	{Function}	callback
	 */
	add: function(name, callback) {
		if( typeof this.hooks[name] !== 'object' ) {
			this.hooks[name] = [];
		}

		this.hooks[name].push(callback);
	},



	/**
	 * Get registered functions
	 * 
	 * @param	{String}		name
	 * @return	Array
	 */
	get: function(name) {
		if( typeof this.hooks[name] !== 'object' ) {
			this.hooks[name] = [];
		}

		return this.hooks[name];
	},



	/**
	 * Clear named hook (remove functions)
	 *
	 * @param	{String}		name
	 */
	clear: function(name) {
		this.hooks[name] = [];
	},



	/**
	 * Clear all hooks
	 */
	clearAll: function() {
		this.hooks = {};
	},



	/**
	 * Call all registered functions for a named hook
	 * All arguments passed after the hookname will be
	 * passed in this order to the callback
	 * Ex: Todoyu.Hook.exec('taskopen', idTask, myFlag1, option2); => myTaskCallback(idTask, myFlag1, option2);
	 * 
	 * @param	{String}		name
	 */
	exec: function(name) {
		var params = $A(arguments).slice(1);

		if( typeof this.hooks[name] === 'object' ) {
			this.hooks[name].each(function(item){
				item.apply(item, params);
			});
		}
	}

};