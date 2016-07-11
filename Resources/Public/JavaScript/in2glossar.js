(function($) {
	"use strict";

	/**
	 * Definitions Object
	 *
	 * @constructor
	 * @param {string} data
	 */
	var Definitions = function(data) {
		this.definitions = $.parseJSON(data);
	};
	Definitions.prototype = {

		/**
		 * @param {string} term
		 * @returns {Object}
		 */
		getDefinitionForTerm: function(term) {
			for (var i in this.definitions) {
				var definition = this.definitions[i];
				if (definition.term == term || definition.term.toLowerCase() == term.toLowerCase()) {
					return definition;
				}
			}
		},

		/**
		 * @returns {Array}
		 */
		getTermsInArray: function() {
			var terms = [];
			for (var i in this.definitions) {
				var definition = this.definitions[i];
				terms.push(definition.term);
			}
			return terms;
		},

		/**
		 * @returns {string}
		 */
		getTermSelector: function(callback) {
			var terms = this.getTermsInArray();
			for (var i in terms) {
				terms[i] = callback(terms[i]);
			}
			return terms.join(', ');
		}

	};

	/**
	 * @param options
	 */
	$.in2glossar = function(options) {

		var settings = $.extend({
			selectors: 'body',
			allowedSubtypes: '*',
			attributeSelector: 'data-glossar',
			excludeSelector: 'div.in2glossar-container',
			iconTag: ''
		}, $.in2code.in2glossar, options);

		console.log(settings);

		var $data = $('[data-in2glossar]');
		var definitions = new Definitions($data.html());

		var $body = $('body');
		$body.on('mouseenter', '[' + settings.attributeSelector + ']', function(e) {
			var $item = $(this);
			var top = ($item.offset().top + $item.height());
			var left = ($item.offset().left);
			var value = $item.attr(settings.attributeSelector);
			var definition = definitions.getDefinitionForTerm(value);

			$.in2glossarTooltip().clearDelay().show(definition.description, function() {
				$(this).css({
					'top': top + 'px',
					'left': left + 'px'
				});
			});

			$item.on('mouseleave', function() {
				$.in2glossarTooltip().delayedClose();
			});
		});

		var term = definitions.getTermsInArray().join('|');
		var re = new RegExp('(?:^|\\b)(' + term + ')(?!\\w)', 'ig');

		/**
		 * @param {jQuery} $elem
		 * @param {Function} callback
		 */
		var traverse = function($elem, callback) {
			$elem.contents().each(function() {
				if ($(this).is(settings.allowedSubtypes) && !$(this).is(settings.excludeSelector)) {
					traverse($(this), callback);
				} else if (this.nodeType == 3) {
					callback(this);
				}
			});
		};

		$(settings.selectors).each(function() {
			traverse($(this), function(textnode) {
				var processedHtml = textnode.textContent.replace(re, function(match, item , offset, string) {
					return '<abbr class="in2glossar-abbr" ' + settings.attributeSelector + '="' + match + '">' + match + settings.iconTag + '</abbr>';
				});
				$(textnode).replaceWith(processedHtml);
			});
		});

	};

	/**
	 *
	 */
	$.in2glossarTooltip = function() {

		if ($('#in2glossar-overlay').data('in2glossarTooltip')) {
			return $('#in2glossar-overlay').data('in2glossarTooltip');
		}

		if ($('#in2glossar-overlay').length == 0) {
			$('<div id="in2glossar-overlay" />').hide().appendTo('body');
		}

		var $overlay = $('#in2glossar-overlay');
		var overlayObject = {
			timeout: null,
			init: function() {
				$overlay.on('mouseenter', (function() {
					this.clearDelay();
				}).bind(this)).on('mouseleave', (function() {
					this.delayedClose();
				}).bind(this));
				return this;
			},
			show: function(content, callback) {
				$overlay.html(content).show(0, callback);
				return this;
			},
			close: function(callback) {
				$overlay.html('').hide(0, callback);
				return this;
			},
			clearDelay: function() {
				clearTimeout(this.timeout);
				this.timeout = null;
				return this;
			},
			delayedClose: function(callback) {
				if (this.timeout) {
					this.clearDelay();
				}
				this.timeout = setTimeout((function() {
					this.close(callback);
					this.clearDelay();
				}.bind(this)), 250);
				return this;
			}
		}.init();
		$('#in2glossar-overlay').data('in2glossarTooltip', overlayObject);
		return overlayObject;
	};

})(jQuery);
