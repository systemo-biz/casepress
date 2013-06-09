/**
 * CasePress Chooser Class
 */
function CasePress_Chooser(id) {

	// Prepare global data
	var self = this,
		J = jQuery;

	// Save presented ID and parse wrapper element
	self.wrap = typeof id != 'undefined' ? '#' + id : 'body';

	// Prepare default params
	self.defaults = {
		multiple: true,
		auto_load: false,
		post_type: 'persons',
		tax: 0,
		term: 0,
		search: '',
		trigger: ''
	};

	// CSS selectors
	self.css = {
		container: '.cpchooser',
		sidebar: {
			container: '.cpchooser-sidebar',
			tree: '.cpchooser-sidebar-tree',
			tax: '.cpchooser-sidebar h4',
			term: '.cpchooser-sidebar li a',
			add_term: '.cpchooser-sidebar li i',
			search: {
				input: '.cpchooser-group-search input:text',
				clear: '.cpchooser-group-search span'
			},
			hidden: {
				tax: '.cpchooser-sidebar input[name="tax"]',
				term: '.cpchooser-sidebar input[name="term"]'
			}
		},
		search: {
			form: '.cpchooser-search form',
			input: '.cpchooser-search input[type="text"]',
			submit: '.cpchooser-search input:submit',
			trigger: '.cpchooser-trigger input',
			limit: '.cpchooser-search select'
		},
		table: {
			container: '.cpchooser-table',
			content: '.cpchooser-table-content',
			results: '.cpchooser-result',
			select_all: '.cpchooser-select-all',
			loading: '.cpchooser-loading-indicator'
		},
		results: {
			container: '.cpchooser-results',
			single: '.cpchooser-results span',
			remove: '.cpchooser-results i',
			tpl: '<span class="%class%" data-type="%type%" data-id="%id%" data-tax="%tax%"><i></i> %name%</span>'
		}
	};

	/**
	 * Initialization
	 */
	self.init = function () {
		// Initial state parsing
		self.parseState();
		// Add basical event listeners
		self.events.init();
		// Add sortable
		self.events.sortable();
		// Init tree
		if (self.state.auto_load) self.tree.init();
		// Initial table loading if needed
		if (self.state.auto_load) self.table.load();
	}

	/**
	 * Parse actual form state
	 */
	self.parseState = function () {
		// Prepare wrapper
		var $wrap = J(self.wrap);
		// Parse and return params
		self.state = J.extend(self.defaults, {
			multiple: $wrap.find(self.css.container).data('multiple'),
			auto_load: $wrap.find(self.css.container).data('auto-load'),
			post_type: $wrap.find(self.css.container).data('post-type'),
			tax: $wrap.find(self.css.sidebar.hidden.tax).val(),
			term: $wrap.find(self.css.sidebar.hidden.term).val(),
			search: $wrap.find(self.css.search.input).val(),
			trigger: $wrap.find(self.css.search.trigger).filter(':checked').val(),
			limit: $wrap.find(self.css.search.limit).val()
		});
	};

	/**
	 * Update form state manually
	 *
	 * @param params Object with params to update. Availble values:
	 * {
	 *		auto_load: null,
	 *		post_type: null,
	 *		tax: null,
	 *		term: null,
	 *		search: null,
	 *		trigger: null,
	 *		limit: null
	 * }
	 * @param load Reload table when state is changed. If false, you need to
	 * reload table manually. New state is parsed anyway. (false*|true)
	 */
	self.updateState = function (params, load) {
		// Prepare wrapper
		var $wrap = J(self.wrap);
		// Prepare args
		load = (typeof load == 'undefined') ? true : load;
		// Multiple
		if (typeof params.multiple != 'undefined') $wrap.find(self.css.container).data('multiple', params.multiple);
		// Auto load
		if (params.auto_load) $wrap.find(self.css.container).data('auto-load', params.auto_load);
		// Post type
		if (params.post_type) $wrap.find(self.css.container).data('post-type', params.post_type);
		// Taxonomy
		if (params.tax) $wrap.find(self.css.sidebar.hidden.tax).val(params.tax);
		// Term
		if (params.term) {
			// Prepare menu item
			var $term = $wrap.find(self.css.sidebar.term).filter('[data-term="' + params.term + '"]');
			// Save data
			var tax = (params.tax) ? params.tax : $term.parent('ul').data('tax');
			$wrap.find(self.css.sidebar.hidden.tax).val(tax);
			$wrap.find(self.css.sidebar.hidden.term).val(params.term);
			// Remove selected classes
			$wrap.find(self.css.sidebar.container).find('.selected').removeClass('selected');
			// Add selected class to current item
			$term.addClass('selected');
		}
		// Search
		if (params.search) $wrap.find(self.css.search.input).val(params.search);
		// Trigger
		if (params.trigger) {
			$wrap.find(self.css.search.trigger).attr('checked', false);
			$wrap.find(self.css.search.trigger).filter('[value="' + params.trigger + '"]').attr('checked', false);
		}
		// Limit
		if (params.limit) {
			$wrap.find(self.css.search.limit).find('option').attr('selected', false);
			$wrap.find(self.css.search.limit).find('option[value="' + params.limit + '"]').attr('selected', true);
		}
		// Reload table
		if (load) self.table.load();
		// Parse new statement
		else self.parseState();
	};

	/**
	 * jsTree controller
	 */
	self.tree = {
		/**
		 * Init jsTree
		 */
		init: function () {
			var // Prepare data
			$tree = J(self.css.sidebar.tree);
			// Apply jsTree
			$tree.jstree({
				plugins: ['html_data', 'themes', 'ui', 'search'],
				core: {
					animation: 0
				},
				search: {
					case_insensitive: true,
					show_only_matches: true
				}
			}).bind("loaded.jstree", function (event, data) {
				// Show rendered list
				$tree.removeClass('cpchooser-hidden');
			});
		}
	};

	/**
	 * Table generator
	 */
	self.table = {
		/**
		 * Load table
		 */
		load: function () {
			// Parse state before request
			self.parseState();
			// Insert person data into form
			self.table.request = J.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'CasePress_Chooser_get_table',
					params: self.state
				},
				beforeSend: function () {
					// Abort previous requests
					if (typeof self.table.request === 'object') self.table.request.abort();
					// Clear the container
					J(self.css.table.content).html('');
					// Show loading animation
					J(self.css.table.container).addClass('loading');
				},
				success: function (data) {
					// Put received data to the container
					J(self.css.table.content).html(data);
				},
				complete: function () {
					// Hide loading animation
					J(self.css.table.container).removeClass('loading');
				},
				dataType: 'html'
			});
		}
	};

	/**
	 * Results controls object
	 */
	self.results = {
		/**
		 * Add one result
		 */
		add: function (id, type, name, tax) {
			// Parse tax
			tax = typeof tax == 'undefined' ? '' : tax;
			// Prepare class
			cssclass = (type != 'person') ? 'cpchooser-group' : '';
			// Prepare result markup
			var result = self.css.results.tpl.replace('%type%', type).replace('%id%', id).replace('%name%', name).replace('%tax%', tax).replace('%class%', cssclass);
			// Prevent multiple selection
			self.results.multiple();
			// Remove previous same selections
			J(self.wrap).find(self.css.results.single).filter('[data-type="' + type + '"][data-id="' + id + '"]').remove();
			// Put markup into container
			J(self.wrap).find(self.css.results.container).append(result);
		},
		/**
		 * Remove one result
		 */
		remove: function (id, type) {
			J(self.wrap).find(self.css.results.container).find('span[data-type="' + type + '"][data-id="' + id + '"]').slideUp(200, function () {
				J(this).remove();
			});
		},
		/**
		 * Get all results
		 */
		get: function () {
			// Prepare vars
			var results = new Array();
			// Get results
			J(self.wrap).find(self.css.results.single).each(function (i) {
				results[i] = {
					id: J(this).data('id'),
					name: J(this).text(),
					type: J(this).data('type'),
					tax: J(this).data('tax')
				};
			});
			// Return results
			return results;
		},
		/**
		 * Set all results
		 */
		set: function (results) {
			// Clear the container
			J(self.wrap).find(self.css.results.container).html('');
			// Paste in results
			J(results).each(function (i) {
				self.results.add(results[i].id, results[i].type, results[i].name, results[i].tax);
			});
			// Apply sortable
			self.events.sortable();
		},
		/**
		 * Helper to prevent multiple selection in single-fields
		 */
		multiple: function () {
			if (!self.state.multiple) J(self.wrap).find(self.css.results.container).html('');
		}
	};

	/**
	 * Event listeners
	 */
	self.events = {
		/**
		 * Base initialization
		 */
		init: function () {
			// Groups search
			J(self.css.sidebar.search.input).live({
				keyup: function (e) {
					var // Prepare data
					$tree = J(self.css.sidebar.tree),
						$clear = J(self.css.sidebar.search.clear),
						val = J(this).val();
					// Show/hide clear button
					if (val.length > 0) $clear.removeClass('cpchooser-hidden');
					else {
						$clear.addClass('cpchooser-hidden');
						$tree.jstree('close_all');
					}
					// Start search
					$tree.jstree('search', val);
				}
			});
			// Clear button for groups search
			J(self.css.sidebar.search.clear).live('click', function () {
				var $input = J(self.css.sidebar.search.input);
				// Clear input
				$input.val('').trigger('keyup');
			});
			// Term menu
			J(self.css.sidebar.term).live('click', function (e) {
				var $wrap = J(self.wrap),
					is_top = J(this).parent('li').hasClass('cpchooser-top-level'),
					tax = J(this).parent('li').data('tax'),
					term = J(this).parent('li').data('term');
				// Save data
				$wrap.find(self.css.sidebar.hidden.tax).val(tax);
				$wrap.find(self.css.sidebar.hidden.term).val(term);
				// Open/close childs
				J(this).parent('li').children('ins').trigger('click');
				// Reload table if term not active and not top-level
				if (!is_top) self.table.load();
				e.stopPropagation();
				e.preventDefault();
			});
			// Add term from menu
			J(self.css.sidebar.add_term).live('click', function (e) {
				// Add result
				self.results.add(J(this).parent('li').data('term'), 'term', J(this).parent('li').children('a').text(), J(this).parent('li').data('tax'));
				e.stopPropagation();
				e.preventDefault();
			});
			// Search form
			J(self.css.search.submit).live('click', function (e) {
				// Reload table
				self.table.load();
				e.preventDefault();
			});
			J(self.css.search.input).live('keyup', function (e) {
				// Reload table on hitting enter
				if ( e.keyCode === 13 ) self.table.load();
				e.preventDefault();
			});
			J(self.css.search.form).live('submit', function (e) {
				// Reload table
				self.table.load();
				e.preventDefault();
			});
			// Trigger
			J(self.css.search.trigger).live('change', function (e) {
				// Reload table
				self.table.load();
				e.preventDefault();
			});
			// Limit
			J(self.css.search.limit).live('change', function (e) {
				// Reload table
				self.table.load();
				e.preventDefault();
			});
			// Search results
			J(self.css.table.results).live('click', function (e) {
				// Add result
				self.results.add(J(this).data('id'), 'person', J(this).text());
				e.preventDefault();
			});
			// Select all link
			J(self.css.table.select_all).live('click', function (e) {
				J(self.wrap).find(self.css.table.results).each(function () {
					J(this).trigger('click');
				});
				e.preventDefault();
			});
			// Selected results (remove)
			J(self.css.results.remove).live('click', function (e) {
				// Remove result
				J(this).parent('span').slideUp(200, function () {
					J(this).remove();
				});
				e.preventDefault();
			});
		},
		/**
		 * Enable sortable handler for selected results
		 */
		sortable: function () {
			// Check that sortable is a function and the form is multiple
			if (typeof J.fn.sortable != 'function' || !self.state.multiple) return;
			// Appply sortable
			J(self.wrap).find(self.css.results.container).sortable({
				placeholder: 'ui-state-highlight',
				forcePlaceholderSize: true,
				start: function (e, ui) {
					ui.placeholder.width(ui.item.width());
				}
			}).disableSelection();
		}
	};

	// Auto-init
	self.init();
}