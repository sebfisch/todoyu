// JavaScript Document

function disableTextBox(selector)	{
	textbox = document.getElementById('database_new');

	if(selector.options[selector.selectedIndex].value == '0')	{
		textbox.disabled = false;
	} else {
		textbox.disabled = true;
	}
}

function skipDataImport() {
	document.getElementById('action').value = 'config';
}