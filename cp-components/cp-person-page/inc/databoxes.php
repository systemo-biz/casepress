<?php

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
		
		
		$cp_components_url = plugin_dir_url(__FILE__).'../../../../casepress/cp-components/';
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
					<a href="#" class="cases-box-toggle"><?php _e( 'Data', $ckpp->textdomain ); ?></a>
					<a href="#data" name="data" class="cases-box-anchor">#</a>
				</h3>
			</div>
			<div class="cases-box-content">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'ckpp-person-image', array( 'class' => 'ckpp-person-image thumbnail', 'width' => '', 'height' => '' ) );
					echo '<div class="ckpp-data-float clearfix">';
				}
				?>
				<div class="ckpp-data-col ckpp-data-col-wide" style="clear:none;float:left">
					<h4><?php _e( 'About', $ckpp->textdomain ); ?></h4>
					<?php
					remove_filter( 'the_content', 'ckpp_hide_person_content' );
					the_content();
					add_filter( 'the_content', 'ckpp_hide_person_content' );
					?>
				</div>
				<div class="ckpp-data-col ckpp-fancy-data">
					<h4><?php _e( 'Contact data', $ckpp->textdomain ); ?></h4>
					<p><strong><?php _e( 'Full name', $ckpp->textdomain ); ?></strong><?php echo the_title(); ?></p>
					<p><strong><?php _e( 'E-mail', $ckpp->textdomain ); ?></strong><?php echo ( get_field( 'email' ) )
							? '<a href="mailto:' . get_field( 'email' ) . '">' . get_field( 'email' ) . '</a>'
							: __( '- no data -', $ckpp->textdomain ); ?></p>
					<p><strong><?php _e( 'General phone number', $ckpp->textdomain ); ?></strong><?php ( get_field( 'tel_general' ) )
								? the_field( 'tel_general' ) : _e( '- no data -', $ckpp->textdomain ); ?></p>
					<p><strong><?php _e( 'Additional phone number', $ckpp->textdomain ); ?></strong><?php ( get_field( 'tel_add' ) )
								? the_field( 'tel_add' ) : _e( '- no data -', $ckpp->textdomain ); ?></p>
					<p><strong>Отдел</strong><?php echo '<a href="' . get_site_url() . '/organization_structure/' . current( $podrazd_term )->slug . '">' . get_the_title( $podrazd_id ) . '</a> / <a href="' . get_site_url() . '/organization_structure/' . current( $otdel_term )->slug . '">' . get_the_title( $otdel_id ) . '</a>'; ?></p>
				</div>
				<div class="ckpp-data-col ckpp-fancy-data">
					<h4><?php _e( 'Employee data', $ckpp->textdomain ); ?></h4>
					<p><strong><?php _e( 'Registered', $ckpp->textdomain ); ?></strong><?php echo cases_pretty_date( $post->post_date ); ?></p>
					<p><strong><?php _e( 'Person category', $ckpp->textdomain ); ?></strong><?php ( get_field( 'category_person' ) )
						? the_field( 'category_person' ) : _e( '- no data -', $ckpp->textdomain ); ?></p>
					<p><strong><?php _e( 'Position', $ckpp->textdomain ); ?></strong><?php ( get_field( 'position' ) )
					? the_field( 'position' ) : _e( '- no data -', $ckpp->textdomain ); ?></p>
					<p><strong><?php _e( 'Organization', $ckpp->textdomain ); ?></strong><?php echo $org; ?></p>
				<?php if ( ($uvol) && ($uvol != '') ) echo "<p><strong>Уволен: </strong>" . $uvol; ?>
				</div>
				<div class="ckpp-data-clear"></div>
		<?php
		if ( has_post_thumbnail() ) {
			echo '</div>';
		}
		?>
				<div class="ckpp-data-clear"></div>
			</div>
		</div>
		<!-- Action priority: 30, <?php echo __FILE__; ?> -->
		<div id="ckpp-box-dossier" class="cases-box cases-box-open" data-person="<?php the_ID(); ?>" data-loading-text="<?php _e( 'Loading data', $ckpp->textdomain ); ?>&hellip;">
			<div class="cases-box-header">
				<h3>
					<a href="#" class="cases-box-toggle"><?php _e( 'Dossier', $ckpp->textdomain ); ?></a>
					<a href="#dossier" name="dossier" class="cases-box-anchor">#</a>
				</h3>
				<div class="cases-box-actions btn-toolbar">
					<div id="ckpp-box-dossier-roles" class="btn-group">
						<button class="btn btn-mini btn-primary" data-role="responsible"><?php _e( 'Responsible', $ckpp->textdomain ); ?></button>
						<button class="btn btn-mini" data-role="initiator"><?php _e( 'Initiator', $ckpp->textdomain ); ?></button>
						<button class="btn btn-mini" data-role="participant"><?php _e( 'Participant', $ckpp->textdomain ); ?></button>
					</div>
					<div id="ckpp-box-dossier-states" class="btn-group">
						<button class="btn btn-mini btn-primary" data-state="open"><?php _e( 'Open', $ckpp->textdomain ); ?></button>
						<button class="btn btn-mini" data-state="closed"><?php _e( 'Closed', $ckpp->textdomain ); ?></button>
						<button class="btn btn-mini" data-state="all"><?php _e( 'All', $ckpp->textdomain ); ?></button>
					</div>
					<div class="btn-group">
						<button class="btn btn-mini" id="ckpp-groupby" data-groupby="false"><?php _e( 'Group by priority', $ckpp->textdomain ); ?></button>
					</div>
				</div>
			</div>
			<div class="cases-box-content"></div>
		</div>
		<!-- Action priority: 30, <?php echo __FILE__; ?> -->
		<?php
	}

	add_action( 'roots_entry_content_before', 'ckpp_datatable_before_person', 30 );
?>
