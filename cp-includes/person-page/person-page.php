<?php

class PersonPage {

    function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'load_ss'));
        add_action('admin_enqueue_scripts', array($this, 'load_ss'));

        add_action( 'cp_entry_footer_after', array($this, 'ckpp_datatable_before_person'), 30 );
    	add_action( 'cp_entry_footer_after', array($this, 'add_datatable_to_page_person'), 31 );
        
        add_action( 'wp_ajax_get_dossier_datatable', array($this, 'ckpp_ajax_get_dossier_datatable') );
        
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
        ?>
	<!-- Action priority: 30, <?php echo __FILE__; ?> -->
		<div id="ckpp-box-dossier" class="cases-box cases-box-open" data-person="<?php the_ID(); ?>" data-loading-text="Загрузка...">
			<div class="cases-box-header">
                <div>
                    <h3>
                        <a href="#" class="cases-box-toggle">Досье</a>
                        <a href="#dossier" name="dossier" class="cases-box-anchor">#</a>
                    </h3>
                </div>
				<div class="cases-box-actions btn-toolbar">
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
    
    
    function ckpp_datatable_before_person() {
		global $ckpp, $post;
		if ( !is_single() || get_post_type() != 'persons' )
			return;
		$uvol = get_post_meta( $post->ID, 'date_uvol', true );
		if ( ($uvol) && ($uvol != '') )
			wp_delete_object_term_relationships( $post->ID, 'organization_structure' );
		$myterms = get_the_terms( $post->ID, 'organization_structure' );
		$otdel_id = get_post_meta( $post->ID, 'unit_id', true );
		$podrazd_id = get_post_meta( $otdel_id, 'separate_org_unit', true );
		$otdel_term = get_the_terms( $otdel_id, 'organization_structure' );
		$podrazd_term = get_the_terms( $podrazd_id, 'organization_structure' );
		foreach ( $myterms as $myterm ) {
			if ( $myterm->parent != 0 ) {
				$parent = $myterm->parent;
				$my_slug = $myterm->slug;
				$my_name = $myterm->name;
			}
		}
		
		
		$cp_components_url = plugin_dir_url(__FILE__).'../../cp-components/';
		wp_enqueue_script('datatable', $cp_components_url.'cp-datatable/assets/dt.js', array('jquery'));
		wp_enqueue_script('datatable.tt', $cp_components_url.'cp-datatable/assets/dt.tableTools.js', array('datatable'));
		wp_enqueue_script('datatable.rg', $cp_components_url.'cp-datatable/assets/dt.rowGrouping.js', array('datatable'));
		wp_enqueue_script('datatable.tg', $cp_components_url.'cp-datatable/assets/dt.treeGrid.js', array('datatable'));
		wp_enqueue_script('datatable.init', $cp_components_url.'cp-datatable/assets/init.js', array('datatable'));
		wp_enqueue_style('datatable', $cp_components_url.'cp-datatable/assets/theme.css');
		//dfdf
		
		
		//$parent = current($myterm)->parent;
		//$org_str = get_term_by( 'id', $parent, 'organization_structure' );
		//$org = get_field( 'org' );
		//$org = ( is_numeric( $org[0]->ID ) ) ? '<a href="' . get_permalink( $org[0]->ID ) . '">' . $org[0]->post_title . '</a>': __( '- no data -', $ckpp->textdomain );
        $org = "";
		?>
		<div id="ckpp-box-data" class="cases-box cases-box-open">
			<div class="cases-box-header">
				<h3>
					<a href="#" class="cases-box-toggle">Данные</a>
					<a href="#data" name="data" class="cases-box-anchor">#</a>
				</h3>
			</div>
			<div class="cases-box-content">
				<?php
//				if ( has_post_thumbnail() ) {
//					the_post_thumbnail( 'ckpp-person-image', array( 'class' => 'ckpp-person-image thumbnail', 'width' => '', 'height' => '' ) );
//					echo '<div class="ckpp-data-float clearfix">';
//				}
				?>
				<div class="ckpp-data-col ckpp-data-col-wide" style="clear:none;float:left">
					<h4>О персоне</h4>
					<?php
					//remove_filter( 'the_content', 'ckpp_hide_person_content' );
					//the_content();
					//add_filter( 'the_content', 'ckpp_hide_person_content' );
					?>
				</div>
				<div class="ckpp-data-col ckpp-fancy-data">
					<p><strong>Имя:</strong><?php echo the_title(); ?></p>
					<p><strong>Эл.почта:</strong><?php echo ( get_field( 'email' ) )
							? '<a href="mailto:' . get_field( 'email' ) . '">' . get_field( 'email' ) . '</a>'
							: '- нет данных -' ?></p>
				</div>
				<div class="ckpp-data-col ckpp-fancy-data">
					<p><strong>Дата регистрации в системе: </strong><?php echo cases_pretty_date( $post->post_date ); ?></p>
				</div>
				<div class="ckpp-data-clear"></div>

				<div class="ckpp-data-clear"></div>
			</div>
		</div>
	
		<?php
	}

}

$ThePersonPage = new PersonPage;
?>