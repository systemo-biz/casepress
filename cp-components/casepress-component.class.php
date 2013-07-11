<?php

	// Check that class doesn't exists
	if ( !class_exists( 'CasePress_Component' ) ) {

		/**
		 * CasePress Component Class
		 *
		 * @author Vladimir Anokhin <ano.vladimir@gmail.com>
		 * @link http://gndev.info/
		 * @version 1.0.4
		 */
		class CasePress_Component {

			/** @var string Class version */
			public $class_version = '1.0.4';

			/** @var string component version */
			public $version;

			/** @var string Main component file */
			public $file;

			/** @var string Component textdomain */
			public $textdomain;

			/** @var string Component slug */
			public $slug;

			/** @var string Component URL - http://example.com/wp-content/plugins/CasePress/cp-components/component-name */
			public $url;

			/** @var string Component control panel URL */
			public $admin_url;

			/** @var string Component option name. This option contains all component settings */
			public $option;

			/** @var string Component menu location. Default is submenu of 'options-general.php' */
			public $settings_parent;

			/** @var string Component settings title */
			public $settings_page_title;

			/** @var string Component settings menu label */
			public $settings_menu_title;

			/** @var string Required capability to access component page */
			public $settings_capability;

			/** @var string Relative path to includes directory */
			public $includes = 'inc/component';

			/** @var string Relative path to assets directory */
			public $assets_dir = 'assets';

			/**
			 * Constructor
			 *
			 * @param string $file Put here actual __FILE__ const
			 * @param string $base Relative path from main plugin directory. Default is 'CasePress/cp-components'
			 */
			//function __construct( $file, $base = 'cp-components' ) {
			function __construct( $file, $base = 'cp-components' ) {
				// Save recieved file path and base
				$this->file = trim( $file, '\\/' );
				$this->base = trim( $base, '\\/' );
				// Get component slug
				$this->slug = basename( $this->file, '.php' );
				// Initialize component data
				$this->textdomain = $this->slug;
				//$this->url = plugins_url( '', $this->file );
				//$this->url = plugins_url() . '/' . $this->base . '/' . $this->slug;
				$this->url = plugin_dir_url(__FILE__);// . '/' . $this->base . '/' . $this->slug;
				$this->option = str_replace( '-', '_', $this->slug ) . '_options';
				$this->includes = implode( '/', array( WP_PLUGIN_DIR, $this->base, $this->slug, trim( $this->includes, '/' ) ) );
				$this->assets_dir = trim( $this->assets_dir, '/' );
				// Setup version
				$this->version = $this->class_version;
				// Make component translatable
				load_plugin_textdomain( $this->textdomain, false, $this->slug . '/languages/' );
				// Enqueue assets
				add_action( 'wp_head', array( &$this, 'enqueue_assets' ) );
				add_action( 'admin_head', array( &$this, 'enqueue_assets' ) );
				// Insert default settings if doesn't exists
				add_action( 'admin_init', array( &$this, 'default_settings' ) );
				// Manage options
				add_action( 'admin_init', array( &$this, 'manage_options' ) );
			}

			/**
			 * Conditional tag to check there is settings page
			 */
			function is_settings() {
				global $pagenow;
				return is_admin() && $pagenow == $this->settings_parent && $_GET['page'] == $this->slug;
			}

			/**
			 * Enqueue assets
			 */
			function enqueue_assets() {
				// Enqueue admin page assets
				if ( $this->is_settings() && $this->settings_parent ) {
					wp_enqueue_style( 'thickbox' );
					wp_enqueue_style( 'farbtastic' );
					wp_enqueue_style( 'casepress-component', $this->assets( 'css', 'component.css' ), false, $this->class_version, 'all' );
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'media-upload' );
					wp_enqueue_script( 'thickbox' );
					wp_enqueue_script( 'farbtastic' );
					wp_enqueue_script( 'casepress-component-form', $this->assets( 'js', 'form.js' ), array( 'jquery' ), $this->class_version, false );
					wp_enqueue_script( 'casepress-component', $this->assets( 'js', 'component.js' ), array( 'casepress-component-form' ), $this->class_version, false );
				}
				// Assets file path
				$assets_file = $this->includes . '/assets.php';
				// Check that file exists and include it
				if ( file_exists( $assets_file ) )
					require_once $assets_file;
			}

			/**
			 * Helper function to get assets url by type
			 */
			function assets( $type = 'css', $file = 'component.css' ) {
				return implode( '/', array(
						trim( $this->url, '/' ),
						trim( $this->assets_dir, '/' ),
						trim( $type, '/' ),
						trim( $file, '/' )
					) );
			}

			/**
			 * Set component settings to default
			 */
			function default_settings( $manual = false ) {
				// Settings page is created
				if ( $this->settings_parent ) {
					if ( $manual || !get_option( $this->option ) ) {
						// Create array with default options
						$defaults = array( );
						// Loop through available options
						foreach ( $this->get_options() as $value ) {
							$defaults[$value['id']] = $value['std'];
						}
						// Insert default options
						update_option( $this->option, $defaults );
					}
				}
			}

			/**
			 * Get plugin options
			 *
			 * @return mixed $options Returns options from options.php or false if file doesn't exists
			 */
			function get_options() {
				// Prepare vars
				$options = array( );
				// Check that file exists and include it
				$options_file = $this->includes . '/options.php';
				if ( file_exists( $options_file ) )
					require $options_file;
				// Return options if it's set
				return ( isset( $options ) ) ? $options : false;
			}

			/**
			 * Get single option value
			 *
			 * @return mixed $option Returns option by specified key
			 */
			function get_option( $option = false ) {
				// Get options from database
				$options = get_option( $this->option );
				// Check option is specified
				$value = ( $option ) ? $options[$option] : $options;
				// Return result
				return ( is_array( $value ) ) ? array_filter( $value, 'esc_attr' ) : esc_attr( stripslashes( $value ) );
			}

			/**
			 * Update single option value
			 *
			 * @return mixed $option Returns option by specified key
			 */
			function update_option( $key = false, $value = false ) {
				// Prepare variables
				$settings = get_option( $this->option );
				$new_settings = array( );
				// Prepare data
				foreach ( $settings as $id => $val )
					$new_settings[$id] = ( $id == $key ) ? $value : $val;
				// Update option and return operation result
				return update_option( $this->option, $new_settings );
			}

			/**
			 * Save/reset options
			 */
			function manage_options() {
				// Check this is settings page
				if ( !$this->is_settings() )
					return;
				// ACTION: SAVE
				if ( $_POST['action'] == 'save' ) {
					// Prepare vars
					$options = $this->get_options();
					$new_options = array( );
					// Prepare data
					foreach ( $options as $value )
						$new_options[$value['id']] = ( is_array( $_POST[$value['id']] ) ) ? $_POST[$value['id']]
								: htmlspecialchars( $_POST[$value['id']] );
					// Save new options
					if ( update_option( $this->option, $new_options ) ) {
						// Redirect
						wp_redirect( $this->admin_url . '&message=3' );
						exit;
					}
					// Options not saved
					else {
						// Redirect
						wp_redirect( $this->admin_url . '&message=4' );
						exit;
					}
				}
				// ACTION: RESET
				elseif ( $_GET['action'] == 'reset' ) {
					// Prepare variables
					$options = $this->get_options();
					$new_options = array( );
					// Prepare data
					foreach ( $options as $value ) {
						$new_options[$value['id']] = $value['std'];
					}
					// Save new options
					if ( update_option( $this->option, $new_options ) ) {

						// Redirect
						wp_redirect( $this->admin_url . '&message=1' );
						exit;
					}
					// Option doesn't updated
					else {
						// Redirect
						wp_redirect( $this->admin_url . '&message=2' );
						exit;
					}
				}
			}

			/**
			 * Register settings page
			 *
			 * @param mixed $options Settings page params
			 */
			function add_settings_page( $options ) {
				// Settings page parent
				$this->settings_parent = ( isset( $options['parent'] ) ) ? $options['parent']
						: 'options-general.php';
				// Settings page title
				$this->settings_page_title = ( isset( $options['page_title'] ) ) ? $options['page_title']
						: $this->name;
				// Settings page menu name
				$this->settings_menu_title = ( isset( $options['menu_title'] ) ) ? $options['menu_title']
						: $this->name;
				// Settings page capability
				$this->settings_capability = ( isset( $options['capability'] ) ) ? $options['capability']
						: 'manage_options';
				// Settings link in plugins dashboard
				$this->settings_link = ( $options['settings_link'] ) ? true : false;
				// Redefine admin url
				$this->admin_url = admin_url( $this->settings_parent . '?page=' . $this->slug );
				// Add settings page
				add_action( 'admin_menu', array( &$this, 'settings_page' ) );
			}

			/**
			 * Register settings page
			 */
			function settings_page() {
				add_submenu_page( $this->settings_parent, __( $this->settings_page_title, $this->textdomain ), __( $this->settings_menu_title, $this->textdomain ), $this->settings_capability, $this->slug, array( &$this, 'render_settings_page' ) );
			}

			/**
			 * Display settings page
			 */
			function render_settings_page() {
				$backend_file = $this->includes . '/views/settings.php';
				if ( file_exists( $backend_file ) )
					require_once $backend_file;
			}

			/**
			 * Add settings link to plugins dashboard
			 */
			function add_settings_link( $links ) {
				$links[] = '<a href="' . $this->admin_url . '">' . __( 'Settings', $this->textdomain ) . '</a>';
				return $links;
			}

			/**
			 * Display settings panes
			 */
			function render_panes() {
				// Get plugin options
				$options = $this->get_options();
				// Get current settings
				$settings = get_option( $this->option );
				// Options loop
				foreach ( $options as $option ) {
					// Get option file path
					$option_file = $this->includes . '/views/' . $option['type'] . '.php';
					// Check that file exists and include it
					if ( file_exists( $option_file ) )
						include( $option_file );
					else
						trigger_error( 'Option file <strong>' . $option_file . '</strong> not found!', E_USER_NOTICE );
				}
			}

			/**
			 * Display settings tabs
			 */
			function render_tabs() {
				// Tabs
				foreach ( $this->get_options() as $option ) {
					if ( $option['type'] == 'opentab' ) {
						$active = ( isset( $active ) ) ? ' cp-component-tab-inactive' : ' nav-tab-active cp-component-tab-active';
						echo '<span class="nav-tab' . $active . '">' . $option['name'] . '</span>';
					}
				}
			}

			/**
			 * Show notifications
			 */
			function notifications( $notifications ) {
				$n_file = $this->includes . '/views/notifications.php';
				if ( file_exists( $n_file ) )
					include $n_file;
			}

		}

	}
?>