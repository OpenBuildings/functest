var PhantomjsConnection = (function (document, undefined) {
	"use strict";

	var ids = [];
	var current = 0;

	return {
		/**
		 * Return the ids of elements for a given xpath and parent element context, store them for later referance
		 * @param  {string} xpath  xpath string
		 * @param  {integer} parent parent element id
		 * @return {array}        array of ids
		 */
		ids: function (xpath, parent) {
			var context = ids[parent] || document.body,
					result_ids = [],
					item,
					xpath_result,
					i;

			xpath_result = document.evaluate(xpath, context, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);

			while (item = xpath_result.iterateNext()) {
				for (i = 0; i < ids.length; i++) {
					if (ids[i] === item) {
						result_ids.push(i);
						break;
					}
				}
				if (i === ids.length ) {
					ids.push(item);
					result_ids.push(i);
				}
			}

			return result_ids;
		},

		/**
		 * Return the HTMLElement for a given id, already found
		 * @param  {integer} id element id
		 * @return {HTMLElement}    the actual html element
		 */
		item: function(id) {
			return ids[id];
		},

		/**
		 * Generate a unique selector for any element
		 * @param  {integer} id element id
		 * @return {string}    selector
		 */
		uniqueSelector: function(id) {
			var className = '_PhantomjsConnection_' + (current++);
			ids[id].className = ids[id].className + ' ' + className;
			return '.' + className;
		},

		/**
		 * Set a value on form element, focus and blur the input
		 * @param  {integer} id    element id
		 * @param  {mixed} value the new value
		 */
		setValue: function(id, value) {
			var elem = ids[id];

			elem.focus();
			elem.value = value;
			elem.blur();
		},

		/**
		 * Set an option in a select as "selected" or "not selected", focus, blur and fire a changed event
		 * @param  {integer} id    element id
		 * @param  {boolean} value new selected status
		 */
		setSelected: function(id, value) {
			var elem = ids[id],
					select = elem.parentNode.tagName === 'OPTGROUP' ? elem.parentNode.parentNode : elem.parentNode;

			select.focus();
			elem.selected = value;
			select.blur();

			this.fireEvent(select, 'change');
		},

		/**
		 * Fire a native html event
		 * @param  {HTMLElement} elem      the dispatcher
		 * @param  {string} eventName event name
		 */
		fireEvent: function(elem, eventName){
			var event = document.createEvent('HTMLEvents');
			event.initEvent(eventName, true, true);
			elem.dispatchEvent(event);
		},

		/**
		 * Check if an element is visible
		 * @param  {integer} id element id
		 * @return {boolean}    is visible?
		 */
		isVisible: function(id) {
			var elem = ids[id];

			return elem.offsetWidth > 0 && elem.offsetHeight > 0;
		}
	};

}(document));
