<?php

class PersonPage {

    function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'load_ss'));
        add_action('admin_enqueue_scripts', array($this, 'load_ss'));
    	add_action('cp_entry_footer_after', array($this, 'add_datatable_to_page_person'), 30 );
        add_action('wp_ajax_get_dossier_datatable', array($this, 'ckpp_ajax_get_dossier_datatable') );
    }
    
    function ckpp_ajax_get_dossier_datatable() {

		// Params is sent
		if ( ( isset( $_POST['meta'] ) && $_POST['meta'] ) && ( isset( $_POST['tax'] ) && $_POST['tax'] ) ) {
			// Define params
			$params = array(
				'fields' => 'ID:link, post_title:link, prioritet, state:tax, post_date, functions:tax',
				'tax' => 'results',
				'meta' => $_POST['meta']
                
			);
			if ( isset( $_POST['group'] ) && $_POST['group'] == 'true' )
				$params['group'] = 'prioritet';
			// Correct tax param
			switch ( $_POST['tax'] ) {
				case 'open':
					$params['tax'] = 'results:NONE';
					break;
				case 'closed':
					$params['fields'] = $params['fields'] . ', results';
					$params['tax'] = 'results:ALL';
					break;
				case 'all':
					$params['fields'] = $params['fields'] . ', results';
					$params['tax'] = '';
					break;
			}
		}
		// Generate datatable
		if ( function_exists( 'datatable_generator' ) && is_array( $params ) )
			datatable_generator( $params );
		// Prevent unwanted output
		die();
	}

	

    function load_ss() {
        global $post;
        if (!(is_singular('persons'))) return;
        
        wp_register_style( 'person-page-frontend', plugins_url( 'assets/css/frontend.css',__FILE__ ), false, "1", 'all' );
        wp_register_script( 'person-page-frontend', plugins_url( 'assets/js/frontend.js' ,__FILE__), array( 'jquery' ), '1', false );

        if ( !is_admin() ) {
            wp_enqueue_style( 'person-page-frontend' );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'person-page-frontend' );
            wp_localize_script('person-page-frontend', 'cp_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        }
    }
    
    
    function add_datatable_to_page_person() {
        global $post;
        if (!(is_singular('persons'))) return;
        
        
		
		$cp_components_url = plugin_dir_url(__FILE__).'../../cp-components/';
		wp_enqueue_script('datatable', $cp_components_url.'cp-datatable/assets/dt.js', array('jquery'));
		wp_enqueue_script('datatable.tt', $cp_components_url.'cp-datatable/assets/dt.tableTools.js', array('datatable'));
		wp_enqueue_script('datatable.rg', $cp_components_url.'cp-datatable/assets/dt.rowGrouping.js', array('datatable'));
		wp_enqueue_script('datatable.tg', $cp_components_url.'cp-datatable/assets/dt.treeGrid.js', array('datatable'));
		wp_enqueue_script('datatable.init', $cp_components_url.'cp-datatable/assets/init.js', array('datatable'));
		wp_enqueue_style('datatable', $cp_components_url.'cp-datatable/assets/theme.css');
        
        ?>
	<!-- Action priority: 30, <?php echo __FILE__; ?> -->
		<div id="ckpp-box-dossier" class="cases-box cases-box-open" data-person="<?php the_ID(); ?>" data-loading-text="Загрузка...">
			<div class="cases-box-header">
                <div>
                    <h1>Досье</h1>
                </div>
				<div class="person-box-actions btn-toolbar">
					<div id="ckpp-box-dossier-roles" class="btn-group">
						<button class="btn btn-mini btn-primary" data-role="members-cp-posts-sql">Участник</button>
						<button class="btn btn-mini" data-role="responsible-cp-posts-sql">Ответственный</button>
						<button class="btn btn-mini" data-role="member_from-cp-posts-sql">Инициатор</button>
					</div>
					<div id="ckpp-box-dossier-states" class="btn-group">
						<button class="btn btn-mini btn-primary" data-state="open">Открыто</button>
						<button class="btn btn-mini" data-state="closed">Закрыто</button>
						<button class="btn btn-mini" data-state="all">Все</button>
					</div>
					<div class="btn-group">
						<button class="btn btn-mini" id="ckpp-groupby" data-groupby="false">Приоритет</button>
					</div>
				</div>
			</div>
			<div class="cases-box-content"></div>
		</div>
		<!-- Action priority: 30, <?php echo __FILE__; ?> -->
        <?php
    }

}

$ThePersonPage = new PersonPage;


?>