/**
 * jQuery Flexo
 * jQuery plugin that makes gmail-like popup windows
 *
 * Copyright 2012-2013 Vladimir Anokhin (gndev.info)
 *
 * Released under the GPL License.
 *
 * ------------------------------------------------
 *  author:  Vladimir Anokhin
 *  version: 1.0.0
 */
;
(function ($, window, document, undefined) {

	// Plugin constructor
	var Flexo = function (elem, options) {

			// Prepare inner plugin data
			this.elem = elem;
			this.$elem = $(elem);
			this.options = options;

			// This next line takes advantage of HTML5 data attributes
			// to support customization of the plugin on a per-element
			// basis. For example,
			// <div class="item" data-flexo="{'title':'Flexo window!'}"></div>
			this.metadata = $.parseJSON((this.$elem.attr('data-flexo') || '').replace(/'/g, '"'));
			// Trigger event
			this.event('before_init');
			// Init
			this.init();
			// Trigger event
			this.event('ready');
		};

	// The plugin prototype
	Flexo.prototype = {

		// Default settings
		defaults: {
			id: false,
			type: 'html',
			width: 200,
			height: 300,
			show: true,
			minimized: false,
			fullscreen: false,
			overlay: false,
			overlayConfig: {
				opacity: 0.8,
				color: 'white',
				click2close: true,
				esc2close: true
			},
			zIndex: 100000,
			controls: {
				drag: true,
				resize: true,
				minify: true,
				fullscreen: true,
				close: true
			},
			title: '',
			content: '',
			footer: '',
			footerConfig: {
				height: 40
			},
			size: 'normal',
			destroyClosed: true,
			stackMinimized: false,
			position: 'center',
			theme: {
				title: '#464646',
				titleText: '#fff',
				controls: 'white',
				border: 'none',
				background: '#fff',
				footer: '#f5f5f5',
				shadow: '0 5px 25px #333'
			},
			ajax: {
				type: 'post',
				url: false,
				data: {},
				dataType: 'html'
			},
			draggableConfig: {
				handle: '.flexo-header',
				cancel: '.flexo-controls',
				iframeFix: '.flexo-iframe',
				containment: 'window',
				stack: 'body',
				scroll: false
			},
			resizableConfig: {
				containment: 'document'
			},
			mobile: {
				fullscreen: true,
				scroll: true
			}
		},

		/**
		 * Base init method
		 */
		init: function () {
			// Prepare data
			var utils = this.utils;
			// Parse $elem's content and title
			if (utils.isset(this.$elem.attr('id'))) this.defaults.id = this.$elem.attr('id');
			if (utils.isset(this.$elem.attr('title'))) this.defaults.title = this.$elem.attr('title');
			if (utils.isset(this.$elem.html())) this.defaults.content = this.$elem.html();
			// Introduce defaults that can be extended either
			// globally or using an object literal.
			this.config = $.extend(true, {}, this.defaults, this.options, this.metadata);
			// Prepare the ID
			if (!this.config.id) this.config.id = $('.flexo-container').length;
			if (!this.config.ajax.url) this.config.ajax.url = this.config.content;
			// Hide original element
			this.$elem.hide();
			// Create new window and return it
			this.create();
			// Save plugin instance
			this.$elem.data('_flexo', this);
		},

		/**
		 * Create new window
		 */
		create: function () {
			// Prepare data
			var $node = $('<div />'),
				inner, config = this.config,
				utils = this.utils,
				id = config.id,
				controls = '';
			// Destroy same windows if exists
			if (this.getWindow(id).length > 0) {
				this.destroy(id);
				console.log('Flexo: detected identical windows with the same ID "%s", rendered only last called window!', id);
			}
			// Prepare controls
			$.each(['minify', 'fullscreen', 'close'], function (i, control) {
				if (config.controls[control]) controls += '<span class="flexo-control-' + control + '"></span>';
			});
			// Customize node
			$node.attr('id', 'flexo-window-' + config.id);
			$node.attr('class', 'flexo-container flexo-size-' + config.size + ' flexo-type-' + config.type);
			if (config.overlay) $node.addClass('flexo-has-overlay');
			if (config.footer) $node.addClass('flexo-has-footer');
			// Set node CSS
			$node.css({
				width: utils.size(config.width, 'w') + 'px',
				height: utils.size(config.height, 'h') + 'px',
				zIndex: parseInt(config.zIndex) + 1,
				boxShadow: config.theme.shadow,
				position: 'fixed'
			});
			// Set window colors
			if (utils.isset(config.theme.background)) $node.css('background', config.theme.background);
			if (utils.isset(config.theme.border)) $node.css('border', config.theme.border);
			// Prepare window markup
			inner = '<div class="flexo-header" style="background: ' + config.theme.title + '; color: ' + config.theme.titleText + '"><div class="flexo-title" title="' + config.title + '">' + config.title + '</div></div>';
			inner += '<div class="flexo-controls flexo-controls-' + config.theme.controls + '">' + controls + '</div>';
			inner += '<div class="flexo-content"></div>';
			if (utils.isset(config.footer)) inner += '<div class="flexo-footer" style="background:' + config.theme.footer + '; height: ' + new String(config.footerConfig.height).replace('px', '') + 'px;"><div class="flexo-footer-inner">' + config.footer + '</div></div>';
			// Insert node html and put it to document
			$node.html(inner).appendTo('body');
			// Create $window object
			this.$window = this.getWindow(id);
			// Add window event handlers
			this.handlers();
			// Set window position
			this.position();
			// Create and show overlay if needed
			if (config.overlay) {
				this.overlay('create');
				this.overlay('show');
			}
			// Show window if needed
			if (config.show) this.show();
			// Make window draggable if needed
			if (config.controls.drag) this.draggable();
			// Make window resizable if needed
			if (config.controls.resize) this.resizable();
			// Minimize window if needed
			if (config.minimized) this.minimize();
			// Fullscreen window if needed
			if (config.fullscreen || (typeof $.browser.mobile === 'boolean' && $.browser.mobile && config.mobile.fullscreen)) this.fullscreen();
			// Scroll to top left for mobile browsers
			if (typeof $.browser.mobile === 'boolean' && $.browser.mobile && config.mobile.scroll) $('html, body').animate({
				scrollTop: 0
			}, 100, function () {
				$('html, body').animate({
					scrollLeft: 0
				}, 100);
			});
			// Load window content
			this.update();
			// Save plugin instance
			this.getWindow(id).data('_flexo', this);
			// Trigger event
			this.event('created');
		},

		/**
		 * Get window by ID
		 */
		getWindow: function (id) {
			var $window = this.$window;
			if (id) $window = $('#flexo-window-' + id);
			else if (typeof $window === 'object') $window = $window;
			else $window = $('#flexo-window-' + this.config.id);
			return $window;
		},

		update: function (config) {
			// Prepare data
			var self = this,
				$window = self.getWindow(),
				utils = self.utils,
				params = (config) ? $.extend(true, {}, self.config, config) : self.config,
				type = params.type,
				content = '',
				iframe_id = 'flexo-iframe-' + params.id;
			// Create content by type
			switch (type) {
			case 'html':
				// Get HTML content if specified only ID
				if (utils.strpos(params.content, '#') === 0) params.content = $(params.content).html();
				content = '<div class="flexo-html-content">' + params.content + '</div>';
				break;
			case 'image':
				content = '<img src="' + params.content + '" alt="' + params.title + '" class="flexo-image" />';
				break;
			case 'iframe':
				content = '<iframe id="' + iframe_id + '" src="' + params.content + '" class="flexo-iframe"></iframe>';
				break;
			case 'ajax':
				content = '<div class="flexo-loading"></div>';
				// Set handler to AJAX success event
				params.ajax.success = function (data) {
					$window.find('.flexo-content').html(data);
					self.event('loaded');
				}
				// Run AJAX
				$.ajax(params.ajax);
				break;
			}
			// Update window title
			$window.find('.flexo-title').html(params.title);
			// Update window content
			$window.find('.flexo-content').html(content);
			// Update window footer
			$window.find('.flexo-footer').html(params.footer);
			// Update window size
			this.resizeContent();
			// Trigger event
			this.event('updated');
		},

		/**
		 * Attach $window event handlers
		 */
		handlers: function () {
			var self = this,
				$window = this.getWindow(),
				resizeTimer = null;
			// Minify button
			$window.on('click', '.flexo-control-minify', function (e) {
				if ($window.hasClass('flexo-minimized')) self.expand();
				else self.minimize();
				e.preventDefault();
				e.stopPropagation();
			});
			// Fullscreen button
			$window.on('click', '.flexo-control-fullscreen', function (e) {
				if ($window.hasClass('flexo-fullscreen')) self.normalscreen();
				else self.fullscreen();
				e.preventDefault();
				e.stopPropagation();
			});
			// Close button
			$window.on('click', '.flexo-control-close', function (e) {
				self.close();
				e.preventDefault();
				e.stopPropagation();
			});
			// Center windows on browser resize
			$(window).on('resize', function (e) {
				if (e.target != window) return;
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function () {
					self.position(false, true);
					self.resizeContent();
				}, 200);
			});
		},

		/**
		 * Calculate correct position
		 */
		position: function (id, position, animate) {
			// Parse position arg
			if (position) this.config.position = position;
			// Parse animate arg
			animate = (animate) ? animate : false;
			// Prepare window dimensions
			var self = this,
				$W = {
					width: $(window).width(),
					height: $(window).height()
				};
			// Check window dimension and convert percentage config
			this.config.width = this.utils.size(this.config.width, 'w');
			this.config.height = this.utils.size(this.config.height, 'h');
			if (this.config.width > $W.width || this.config.height > $W.height) {
				setTimeout(function () {
					self.fullscreen();
				}, 500);
			}
			// Prepare data
			var config = this.config,
				$window = this.getWindow(id),
				utils = this.utils,
				wpx = utils.size(config.width, 'w'),
				hpx = utils.size(config.height, 'h');
			// Window is centered
			if (config.position === 'center') {
				if (animate) $window.animate({
					left: ($W.width - wpx) / 2,
					top: ($W.height - hpx) / 2
				}, 200);
				else $window.css({
					left: ($W.width - wpx) / 2,
					top: ($W.height - hpx) / 2
				})
			}
			// Window position is specified
			else if (typeof config.position === 'object') $.each(['top', 'right', 'bottom', 'left'], function (i, pos) {
				var prop = config.position[pos];
				if (utils.isset(prop)) $window.css(pos, (utils.is_numeric(prop) ? prop + 'px' : prop));
			});
		},

		/**
		 * Resize content to fit the window size
		 */
		resizeContent: function () {
			var $window = this.$window,
				config = this.config,
				header_height = 30,
				footer_height = (config.footer) ? new String(config.footerConfig.height).replace('px', '') : 0,
				content_width = $window.width(),
				content_height = $window.height() - header_height - footer_height;

			// Resize window content
			$window.find('.flexo-content').css({
				width: content_width + 'px',
				height: content_height + 'px'
			});
		},

		/**
		 * Show window by ID
		 */
		show: function (id) {
			// Get window
			var $window = this.getWindow(id);
			// Show overlay if needed
			if (this.config.overlay) this.overlay('show');
			// Show window
			$window.addClass('flexo-visible');
			// Trigger event
			this.event('show');
		},

		/**
		 * Hide windows by ID
		 */
		hide: function (id) {
			// Get window
			var $window = this.getWindow(id);
			// Hide overlay if needed
			if ($window.hasClass('flexo-has-overlay')) this.overlay('hide');
			// Hide window
			$window.removeClass('flexo-visible');
			// Trigger event
			this.event('hide');
		},

		/**
		 * Close window
		 */
		close: function (id) {
			if (this.config.destroyClosed) this.destroy(id);
			else this.hide(id);
			// Trigger event
			this.event('close');
		},

		/**
		 * Minimize selected window
		 */
		minimize: function (id) {
			// Normalscreen before
			this.normalscreen(id);
			// Minimize
			this.getWindow(id).addClass('flexo-minimized');
			// Disable resizable
			this.resizable(false);
			// Trigger event
			this.event('minimize');
		},

		/**
		 * Expand selected window
		 */
		expand: function (id) {
			// Expand
			this.getWindow(id).removeClass('flexo-minimized');
			// Make window resizable
			if (this.config.controls.resize) this.resizable();
			// Trigger event
			this.event('expand');
		},

		/**
		 * Toggle fullscreen ON
		 */
		fullscreen: function (id) {
			// Expand window before
			this.expand(id);
			// Prepare data
			var $window = this.getWindow(id),
				config = this.config,
				header_height = 30,
				footer_height = (config.footer) ? new String(config.footerConfig.height).replace('px', '') : 0,
				original_width = $window.width(),
				original_height = $window.height() - header_height - footer_height,
				new_width = $(window).width(),
				new_height = $(window).height() - header_height - footer_height;
			// Save original width & height
			$window.attr('data-original-content-width', original_width);
			$window.attr('data-original-content-height', original_height);
			// Fullscreen
			$window.addClass('flexo-fullscreen').css({
				zIndex: this.config.zIndex + 100
			}).find('.flexo-content').css({
				width: new_width + 'px',
				height: new_height + 'px'
			});
			// Disable resizing
			this.resizable(false);
			// Disable dragging
			this.draggable(false);
			// Trigger event
			this.event('fullscreen');
		},

		/**
		 * Toggle fullscreen OFF
		 */
		normalscreen: function (id) {
			// Prepare data
			var $window = this.getWindow(id),
				config = this.config;
			original_height = $window.attr('data-original-content-height');
			// Normal screen
			$window.removeClass('flexo-fullscreen').css({
				zIndex: config.zIndex
			});
			// Window repositioning
			this.position(false, true);
			this.resizeContent();
			// Make window resizable
			if (config.controls.resize) this.resizable();
			// Make window draggable
			if (config.controls.drag) this.draggable();
			// Trigger event
			this.event('normalscreen');
		},

		/**
		 * Overlay processor
		 *
		 * @param action Action to exec (create|destroy|show|hide)
		 * @param id Window ID
		 */
		overlay: function (action, id) {
			// Define overlay element
			var self = this,
				config = self.config,
				oid = 'flexo-overlay-' + (id || config.id),
				$overlay = $('#' + oid);
			// Create overlay
			if (action === 'create' && $overlay.length < 1) {
				$('<div></div>').attr('id', oid).addClass('flexo-overlay').css({
					background: config.overlayConfig.color,
					opacity: config.overlayConfig.opacity,
					zIndex: config.zIndex
				}).appendTo('body');
				$overlay = $('#' + oid);
				// Overlay: click2close
				if (config.overlayConfig.click2close) $overlay.on('click', function (e) {
					self.close();
					e.preventDefault();
					e.stopPropagation();
				});
				// Overlay: esc2close
				if (config.overlayConfig.esc2close) $(window).on('keyup', function (e) {
					if (e.keyCode == 27) self.close();
					e.preventDefault();
					e.stopPropagation();
				});
			}
			// Destroy overlay
			else if (action === 'destroy') $overlay.off().remove();
			// Show overlay
			else if (action === 'show') $overlay.addClass('flexo-overlay-visible');
			// Hide overlay
			else if (action === 'hide') $overlay.removeClass('flexo-overlay-visible');
		},

		/**
		 * Enable or disable draggable mode
		 */
		draggable: function (draggable) {
			// Check jQuery UI
			if (typeof $.fn.draggable !== 'function') return;
			// Prepare data
			var config = this.config,
				$window = this.$window,
				utils = this.utils;
			// Check new state
			draggable = (utils.isset(draggable)) ? draggable : true;
			// Add custom draggable config
			if (!utils.isset(config.draggableConfig.start)) config.draggableConfig.start = function (event, ui) {
				$window.addClass('flexo-dragged');
				$window.css({
					zIndex: config.zIndex + 1
				});
			}
			// Make $window draggable
			if (draggable) $window.draggable(config.draggableConfig);
			// Make $window static
			else $window.draggable('destroy');
		},

		/**
		 * Enable or disable resizable mode
		 */
		resizable: function (resizable) {
			// Check jQuery UI
			if (typeof $.fn.resizable !== 'function') return;
			// Prepare data
			var self = this,
				config = this.config,
				$window = this.$window,
				utils = this.utils;
			// Check new state
			resizable = (utils.isset(resizable)) ? resizable : true;
			// Add custom draggable config
			if (!utils.isset(config.resizableConfig.resize)) config.resizableConfig.resize = function (event, ui) {
				self.resizeContent();
			}
			if (!utils.isset(config.resizableConfig.stop)) config.resizableConfig.stop = function (event, ui) {
				// Set CSS
				$window.css({
					position: 'fixed',
					top: $window.position().top - $(window).scrollTop() + 'px'
				});
			}
			// Make $window draggable
			if (resizable) $window.resizable(config.resizableConfig);
			// Make $window static
			else $window.resizable('destroy');
		},

		/**
		 * Destroy window by specified ID
		 */
		destroy: function (id) {
			// Get window
			var $window = this.getWindow(id);
			// Destroy overlay if exists
			if ($window.hasClass('flexo-has-overlay')) this.overlay('destroy');
			// Deattach handlers
			$window.off();
			// Remove window
			$window.remove();
			// Trigger event
			this.event('destroy');
		},

		/**
		 * Set window content
		 */
		setContent: function (content) {
			this.update({
				content: content
			});
		},

		/**
		 * Trigger custom event on $elem
		 */
		event: function (name) {
			this.$elem.trigger(name + '.flexo');
		},

		/**
		 * Utilities
		 */
		utils: {

			/**
			 * Checks that value is numeric
			 */
			is_numeric: function (value) {
				return !isNaN(value);
			},

			/**
			 * Checks that value is set
			 */
			isset: function (value) {
				return value !== undefined && value !== null && value !== '';
			},

			/**
			 * Find position of first occurrence of a string
			 */
			strpos: function (haystack, needle, offset) {
				var i = haystack.indexOf(needle, offset);
				return i >= 0 ? i : false;
			},

			/**
			 * Convert value to pixels
			 *
			 * (10%, 10px, 10) => 10 or 10% of screen in pixels
			 *
			 * @param value Value to convert
			 * @param wh Width or height (for percentage only)
			 */
			size: function (value, wh) {
				var val = new String(value),
					W = (wh == 'w') ? $(window).width() : $(window).height(),
					result = 0;
				// Percentage
				if (this.strpos(val, '%')) result = W / 100 * val.replace('%', '');
				else result = val.replace('px', '');
				return parseInt(result);
			}
		}
	}

	// Publish defaults
	Flexo.defaults = Flexo.prototype.defaults;

	/**
	 * Plugin function declaration
	 */
	$.flexo = function (options) {
		// Prepare vars
		var ret = null,
			elem = document.createElement('div');
		// If options specified, return new object
		if (typeof options === 'object') ret = new Flexo(elem, options);
		// Trigger error
		else console.error('Flexo: please specify at least the type and content');
		// Return result
		return ret;
	};

	// Plugin registration
	$.fn.flexo = function (param) {
		// Prepare vars
		var ret = null,
			args = Array.prototype.slice.call(arguments, 1);
		// Elements not found
		if (this.length == 0) ret = null;
		// Call specified method
		else if (typeof Flexo.prototype[param] === 'function') ret = this.each(function () {
			Flexo.prototype[param].apply($(this).data('_flexo'), args);
		});
		// Call init method
		else if (typeof param === 'object' || !param) ret = this.each(function () {
			new Flexo(this, param);
		});
		// Trigger error
		else console.error('Flexo: "%s" is not correct method name!', param);
		// Return result
		return ret;
	};

	window.Flexo = Flexo;

})(jQuery, window, document);

jQuery(document).ready(function ($) {
	$('.flexo-link').live('click', function (e) {
		$($(this).attr('href')).flexo();
		e.preventDefault();
	});
});