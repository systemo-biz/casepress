!function ($) {

	"use strict"; // jshint ;_;


	/* DROPDOWN CLASS DEFINITION
  * ========================= */

	var toggle = '[data-toggle="dropdown"]'
	, Dropdown = function (element) {
		var $el = $(element).on('click.dropdown.data-api', this.toggle)
		$('html').on('click.dropdown.data-api', function () {
			$el.parent().removeClass('open')
		})
	}

	Dropdown.prototype = {

		constructor: Dropdown

		,
		toggle: function (e) {
			var $this = $(this)
			, $parent
			, selector
			, isActive

			if ($this.is('.disabled, :disabled')) return

			selector = $this.attr('data-target')

			if (!selector) {
				selector = $this.attr('href')
				selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
			}

			$parent = $(selector)
			$parent.length || ($parent = $this.parent())

			isActive = $parent.hasClass('open')

			clearMenus()

			if (!isActive) $parent.toggleClass('open')

			return false
		}

	}

	function clearMenus() {
		$(toggle).parent().removeClass('open')
	}


	/* DROPDOWN PLUGIN DEFINITION
   * ========================== */

	$.fn.dropdown = function (option) {
		return this.each(function () {
			var $this = $(this)
			, data = $this.data('dropdown')
			if (!data) $this.data('dropdown', (data = new Dropdown(this)))
			if (typeof option == 'string') data[option].call($this)
		})
	}

	$.fn.dropdown.Constructor = Dropdown


	/* APPLY TO STANDARD DROPDOWN ELEMENTS
   * =================================== */

	$(function () {
		$('html').on('click.dropdown.data-api', clearMenus)
		$('body')
		.on('click.dropdown', '.dropdown form', function (e) {
			e.stopPropagation()
		})
		.on('click.dropdown.data-api', toggle, Dropdown.prototype.toggle)
	})

}(window.jQuery);