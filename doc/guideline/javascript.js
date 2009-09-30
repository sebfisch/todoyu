function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function replacePRECode(str) {
	str = str_replace("<", "&lt;",str);
	str = str_replace(">", "&gt;",str);
	str = str_replace("[[green]]", "<span style='color:green;'>",str);
	str = str_replace("[[/green]]", "</span>",str);
	return str;
}

document.observe('dom:loaded', function() {
	$$('pre.htmlcode').each(function(pre) {
		$t = $(pre).innerHTML;
		$t = replacePRECode($t);
		$t = str_replace("class", "<span style='color:#800080;'>class</span>",$t);
		$(pre).update($t);
	});

	$$('pre.xmlcode').each(function(pre) {
		$t = $(pre).innerHTML;
		$t = replacePRECode($t);
		$(pre).update($t);
	});

});
