<?php
	// Check that class doesn't exists
	if ( !class_exists( 'CasePress_Chooser' ) ) {

		/**
		 * CasePress Chooser Class
		 *
		 * @author Vladimir Anokhin <http://gndev.info/>
		 * @link http://casepress.org/
		 * @version 1.0.0
		 */
		class CasePress_Chooser {

			/** @var mixed Class version */
			protected $version = '1.0.0';

			/** @var mixed Default settings */
			protected $defaults = array(
				'auto_load' => true,
				'post_type' => 'persons',
				'tax' => null,
				'term' => null,
				'trigger' => array(
					'persons' => 'persons',
					'groups' => 'groups'
				),
				'results' => null,
				'limit' => 10,
				'limits' => array( 10, 25, 50, 100 ),
				'textdomain' => 'casepress-chooser',
				'childs' => false,
				'actions' => ''
			);

			/** @var mixed User settings */
			protected $config;

			/**
			 * Constructor
			 */
			public function CasePress_Chooser( $args = array( ) ) {
				// Prepare config
				$this->config = ( array ) wp_parse_args( $args, $this->defaults );
				// Correct tax and term selection
				if ( isset( $this->config['tax'] ) && isset( $this->config['term'] ) )
					$this->config['tax'] = null;
				// Add Table generator action
				add_action( 'wp_ajax_CasePress_Chooser_get_table', array( &$this, 'get_table' ) );
			}

			/**
			 * Print the Chooser form
			 *
			 * @uses CasePress_Chooser::sidebar()
			 * @uses CasePress_Chooser::search()
			 * @uses CasePress_Chooser::table()
			 * @uses CasePress_Chooser::results()
			 */
			public function render() {
				?>
				<div class="cpchooser" data-auto-load="<?php
				echo ( $this->config['auto_load'] ) ? 'true' : 'false';
				?>" data-post-type="<?php echo $this->config['post_type']; ?>">
					<div class="cpchooser-sidebar">
						<?php $this->sidebar(); ?>
					</div>
					<div class="cpchooser-content">
						<?php
						$this->search();
						$this->table();
						$this->results();
						if ( $this->config['actions'] )
							echo '<div class="cpchooser-actions">' . $this->config['actions'] . '</div>';
						?>
					</div>
				</div>
				<?php
			}

			/**
			 * Sidebar with taxes and terms
			 *
			 * @uses CasePress_Chooser::tax_list()
			 */
			protected function sidebar() {
				// Prepare vars
				$sidebar = array();
				// Add groups search
				$sidebar[] = '<div class="cpchooser-group-search"><input type="text" name="group" placeholder="' . __( 'Filter groups', $this->config['textdomain'] ) . '&hellip;" /><span class="cpchooser-hidden" title="' . __( 'Clear field', $this->config['textdomain'] ) . '"></span></div>';
				// Start sidebar tree
				$sidebar[] = '<div class="cpchooser-sidebar-tree cpchooser-hidden"><ul>';
				// Prepare post type taxonomies
				$post_type_taxes = get_object_taxonomies( $this->config['post_type'], 'objects' );
				// Fill sidebar
				foreach ( $post_type_taxes as $tname => $tdata ) {
					// Start top level
					$sidebar[] = '<li class="cpchooser-top-level"><a href="#">' . $tdata->label . '</a>';
					// Add sub levels
					$sidebar[] = $this->tax_list( $tname );
					// End top level
					$sidebar[] = '</li>';
				}
				// End sidebar tree
				$sidebar[] = '</ul></div>';
				// Add hidden inputs
				$sidebar[] = '<input type="hidden" name="tax" value="' . $this->config['tax'] . '" />';
				$sidebar[] = '<input type="hidden" name="term" value="' . $this->config['term'] . '" />';
				// Print result
				echo implode( "\n", $sidebar );
			}

			/**
			 * Search box
			 *
			 * @uses CasePress_Chooser::trigger()
			 */
			public function search() {
				$limits = array( );
				// Prepare limits
				foreach ( $this->config['limits'] as $limit ) {
					$selected = ( $this->config['limit'] == $limit ) ? ' selected="selected"' : '';
					$limits[] = '<option value="' . $limit . '"' . $selected . '>' . $limit . '</option>';
				}
				?>
				<div class="cpchooser-search">
					<form action="" method="post">
						<input type="text" name="s" value="" placeholder="<?php _e( 'Search', $this->config['textdomain'] ); ?>&hellip;" />
						<input type="submit" value="<?php _e( 'Find', $this->config['textdomain'] ); ?>" class="btn btn-primary button button-primary" />
						<select name="limit" title="<?php _e( 'Results count', $this->config['textdomain'] ); ?>"><?php echo implode( "\n", $limits ); ?></select>
						<?php $this->trigger(); ?>
					</form>
				</div>
				<?php
			}

			/**
			 * Triggers under search box
			 */
			public function trigger() {
				// Prepare vars
				$triggers = array( );
				$checked = ' checked="checked"';
				// Check that triggers array is filled
				if ( !is_array( $this->config['trigger'] ) || count( $this->config['trigger'] ) < 1 ) {
					echo '<div class="cpchooser-clear"></div>';
					return;
				}
				// Print triggers
				foreach ( $this->config['trigger'] as $trigger => $name ) {
					$triggers[] = '<label><input type="radio" name="trigger" value="' . $trigger . '"' . $checked . ' /> ' . $name . '</label>';
					$checked = '';
				}
				echo '<div class="cpchooser-trigger">' . implode( "\n", $triggers ) . '</div>';
			}

			/**
			 * Results pills
			 */
			protected function results() {
				// Prepare vars
				$results = array( );
				// Results array is filled
				if ( is_array( $this->config['results'] ) && count( $this->config['results'] ) > 0 )
					foreach ( $this->config['results'] as $result )
						$results[] = '<span data-type="' . $result['type'] . '" data-id="' . $result['value'] . '" data-tax="' . $result['tax'] . '"><i></i> ' . $result['name'] . '</span>';
				// Print results
				echo '<div class="cpchooser-results">' . implode( "\n", $results ) . '</div>';
			}

			/**
			 * Tax list builder
			 *
			 * @param string $taxonomy Requested taxonomy slug
			 * @return string Unordered list with terms of selected taxonomy
			 */
			protected function tax_list( $taxonomy ) {
				return '<ul data-tax="' . $taxonomy . '">' . wp_list_categories( array(
						'title_li' => '',
						'echo' => 0,
						'taxonomy' => $taxonomy,
						'depth' => 2,
						'hide_empty' => 0,
						'textdomain' => $this->config['textdomain'],
						'walker' => new CasePress_Chooser_Walker_Terms
					) ) . '</ul>';
			}

			/**
			 * Table markup
			 */
			protected function table() {
				echo '<div class="cpchooser-table"><div class="cpchooser-table-content"></div><div class="cpchooser-loading-indicator">' . __( 'Loading data', $this->config['textdomain'] ) . '&hellip;</div></div>';
			}

			/**
			 * Table generator
			 */
			public function get_table() {
				// Prepare params
				$param = $_POST['params'];
				// Parse params
				$param['limit'] = ( $param['limit'] > 100 || $param['limit'] < 1 ) ? $this->config['limit']
						: $param['limit'];
				// Base args
				$args = array(
					'posts_per_page' => $param['limit'],
					'post_type' => trim( $param['post_type'] ),
					's' => trim( $param['search'] )
				);
				// Tax query
				if ( $param['tax'] )
					$args[$param['tax']] = $param['term'];
				// Query posts
				$posts = new WP_Query( $args );
				// Print results
				if ( count( $posts->posts ) ) {
					if ( count( $posts->posts ) > 1 )
						echo '<div class="cpchooser-select-all">' . __( 'Select all', $this->config['textdomain'] ) . '</div>';
					foreach ( $posts->posts as $item )
						if ( $item->post_title )
							echo '<div class="cpchooser-result" data-id="' . $item->ID . '">' . $item->post_title . '</div>';
				}
				// Print Nothing found
				else
					echo '<div class="cpchooser-not-found">' . __( 'Nothing found', $this->config['textdomain'] ) . '</div>';
				// Prevent unwanted output
				die();
			}

		}

	}

	if ( !class_exists( 'CasePress_Chooser_Walker_Terms' ) ) {

		/**
		 * Walker Class to show up terms with custom formatting
		 */
		class CasePress_Chooser_Walker_Terms extends Walker_Category {

			function start_el( &$output, $category, $depth, $args ) {
				global $wpdb;
				extract( $args );
				$cat_name = esc_attr( $category->name );
				$cat_name = '<a href="#">' . apply_filters( 'list_cats', $cat_name, $category ) . '</a>';
				if ( 'list' == $args['style'] ) {
					$output .= "\t" . '<li data-term="' . $category->slug . '" data-tax="' . $category->taxonomy . '"';
					$children = $wpdb->get_results( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent=" . $category->term_id );
					$output .= '>' . $cat_name . '<i title="' . __( 'Add this group', $textdomain ) . '"></i>';
				} else {
					$output .= '' . $cat_name . '' . '<br />';
				}
			}

		}

	}
?>