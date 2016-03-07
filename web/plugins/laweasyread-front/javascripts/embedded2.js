window.onload = function() {
	var orig = window.onload ? window.onload : function(){};
	return function() {
		var result = orig.apply(this, arguments);
		LER.parse(document.body);
		return result;
	};
}();
