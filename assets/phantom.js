console.log('Starting server on port 4445');

var server, webtest, page, current_url, page_evaluate;

page = require('webpage').create();

page.viewportSize = { width: 1024, height: 800 };

page.onError = function (msg, trace) {
    console.log(msg);
    trace.forEach(function(item) {
        console.log('  Page Error: ', item.file, ':', item.line);
    })
}

page.onConsoleMessage = function (msg) { console.log('  Page Message: ',msg); };

server = require('webserver').create();

webtest = server.listen(4445, function (request, response) 
{
	var value;

	response.result = function (value) {
		response.write(JSON.stringify(value));
		response.close();
	};

	response.result_evaluate = function (callback) {
		var args = [].slice.call(arguments, 1);
		var callback_string = "function () { return (" + callback.toString() + ").apply(this, " + JSON.stringify(args) + ");}";
		var r = page.evaluate(callback_string);
		this.result(r);
	};

	request.connect = function (method, url, callback) {
		if (this.method == method && this.url.match(url)) {
			console.log('  Executing command ', this.method, this.url, this.post ? this.post.value : null);
			callback.apply(page, this.url.match(url).slice(1));
		}
		return this;
	};

	console.log('Recieved command ', request.method, request.url, this.post ? this.post.value : null);

	response.statusCode = 200;

	request

		.connect('GET', /^\/source$/, function () {
			response.result_evaluate(function(){
				return document.documentElement.outerHTML;
			});
		})

		.connect('DELETE', /^\/session$/, function () {
			response.result();
			phantom.exit();
		})

		.connect('GET', /^\/url$/, function () {
			response.result(current_url);
		})

		.connect('POST', /^\/url$/, function () {
			current_url = request.post.value;

			page.onLoadFinished = function (status) {
				response.result();

				// Handle subsequent requests
				page.onLoadFinished = null;

				page.onLoadFinished = function (statys) {
					current_url = page.evaluate(function(){ return window.location });
				}
			}
			page.open(current_url);
		})

		.connect('DELETE', /^\/cookie$/, function () {
			page.evaluate(function () {
				var cookies = document.cookie.split(";");

				for (var i = 0; i < cookies.length; i++) {
						var cookie = cookies[i];
						var eqPos = cookie.indexOf("=");
						var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
						document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
				}
			});
			response.result();
		})

		.connect('POST', /^\/elements$/, function () {
			response.result_evaluate(function (xpath) {
				if ( ! window._phantomjs_elements) window._phantomjs_elements = [];

				var xpath = document.evaluate(xpath, document.body, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null),
						result_ids = [],
						item;

				while (item = xpath.iterateNext()) {
					var i = 0;
					for (var i = 0; i < window._phantomjs_elements.length; i++) {
						if (window._phantomjs_elements[i] === item) {
							result_ids.push(i);
							break;
						}
					};
					if (i === window._phantomjs_elements.length ) {
						window._phantomjs_elements.push(item);
						result_ids.push(i);
					}
				};
				
				return result_ids;
			}, request.post.value);
		})

		.connect('POST', /^\/element\/(\d+)\/elements$/, function (id) {
			response.result_evaluate(function (xpath, parent) {
				if ( ! window._phantomjs_elements) window._phantomjs_elements = [];
				
				
				parent = window._phantomjs_elements[parent];
				
				var xpath = document.evaluate(xpath, parent || document.body, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null),
						result_ids = [],
						item;

				while (item = xpath.iterateNext()) {
					var i = 0;
					for (var i = 0; i < window._phantomjs_elements.length; i++) {
						if (window._phantomjs_elements[i] === item) {
							result_ids.push(i);
							break;
						}
					};
					if (i === window._phantomjs_elements.length ) {
						window._phantomjs_elements.push(item);
						result_ids.push(i);
					}
				};
				
				return result_ids;
			}, request.post.value, id);
		})

		.connect('GET', /^\/element\/(\d+)\/name$/, function (id) {
			response.result_evaluate(function (id) {
				return window._phantomjs_elements[id].tagName.toLowerCase();
			}, id);
		})

		.connect('GET', /^\/element\/(\d+)\/attribute\/([a-zA-Z0-9]+)$/, function (id, name) {
			response.result_evaluate(function (id, name) {
				return jQuery(window._phantomjs_elements[id]).attr(name);
			}, id, name);
		})

		.connect('GET', /^\/element\/(\d+)\/html$/, function (id) {
			response.result_evaluate(function (id) {
				return window._phantomjs_elements[id].outerHTML;
			}, id);
		})

		.connect('GET', /^\/element\/(\d+)\/text$/, function (id) {
			response.result_evaluate(function (id) {
				return window._phantomjs_elements[id].textContent;
			}, id);
		})

		.connect('GET', /^\/element\/(\d+)\/value$/, function (id) {
			response.result_evaluate(function (id) {
				return jQuery(window._phantomjs_elements[id]).val();
			}, id);
		})

		.connect('POST', /^\/element\/(\d+)\/value/, function (id) {
			response.result_evaluate(function (id, value) {
				return jQuery(window._phantomjs_elements[id]).val(value);
			}, id, request.post.value);
		})

		.connect('GET', /^\/element\/(\d+)\/visible$/, function (id) {
			response.result_evaluate(function (id) {
				return jQuery(window._phantomjs_elements[id]).is(":visible");
			}, id);
		})

		.connect('POST', /\/element\/(\d+)\/click$/, function (id) {
			response.result_evaluate(function (id) {
				return jQuery(window._phantomjs_elements[id]).click();
			}, id);
		})

		.connect('POST', /^\/element\/(\d+)\/selected$/, function (id) {
			response.result_evaluate(function (id, value) {
				return window._phantomjs_elements[id].selected = value;
			}, id, request.post.value);
		})

		.connect('GET', /^\/element\/(\d+)\/selected$/, function (id) {
			response.result_evaluate(function (id) {
				return window._phantomjs_elements[id].selected;
			}, id);
		})

});