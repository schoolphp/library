var myTimeout = 15000;

Storage.prototype.setObject = function(key, value) {
	this.setItem(key, JSON.stringify(value));
};

Storage.prototype.getObject = function(key) {
	var value = this.getItem(key);
	return value && JSON.parse(value);
};

// Обеспечиваем поддержу XMLHttpRequest`а в IE
var xmlVersions = new Array(
    "Msxml2.XMLHTTP.6.0",
    "MSXML2.XMLHTTP.3.0",
    "MSXML2.XMLHTTP",
    "Microsoft.XMLHTTP"
    );
if( typeof XMLHttpRequest == "undefined" ) XMLHttpRequest = function() {
	for(var i in xmlVersions)
	{
		try { return new ActiveXObject(xmlVersions[i]); }
		catch(e) {}
	}
	throw new Error( "This browser does not support XMLHttpRequest." );
};


// Собственно, сам наш обработчик. 
function myErrHandler(message, url, line) {
	var tmp = window.location.toString().split("/");
	var server_url = tmp[0] + '//' + tmp[2];
	var params = "logJSErr=logJSErr&message="+message+'&url='+url+'&line='+line;
	var req =  new XMLHttpRequest();
	req.open('POST', server_url+'/jslogerror?ajax=1', true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	req.setRequestHeader("Content-length", params.length);
	req.setRequestHeader("Connection", "close");
	req.send(params); 
	// Чтобы подавить стандартный диалог ошибки JavaScript, 
	// функция должна возвратить true
	return true;
}
// window.onerror = myErrHandler;
//назначаем обработчик для события onerror
// ПОТОМ ВКЛЮЧИТЬ window.onerror = myErrHandler;

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function nl2br(str) {
	return str.replace(/([^>])\n/g, '$1<br>');
}

function htmlspecialchars(text) {
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};

	return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
