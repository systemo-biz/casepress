<?php
	/**
	 * Шаблон таксономии Структура организации (organization_structure)
	 */
	get_header();
?>

<?php do_action('cp_content_before'); ?>
<div id="content" class="clearfix row-fluid">
	<?php do_action('cp_sidebar_before'); ?>
	<aside id="sidebar" class="fluid-sidebar sidebar span3" role="complementary">
		<?php do_action('cp_sidebar_inside_before'); ?>
		<div class="well">
			<?php dynamic_sidebar( 'persons' ); ?>
		</div>
		<?php do_action('cp_sidebar_inside_after'); ?>
	</aside><!-- /#sidebar -->
	<?php do_action('cp_sidebar_after'); ?>

	<?php do_action('cp_main_before'); ?>
	<div id="main" class="span9 clearfix" role="main">
		<?php do_action('cp_loop_before'); ?>
		<?php /* Start loop */ ?>
		<?php
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			echo "<h1>". $term->name ."</h1>";
			echo '<hr/>';
			// Term description
			$category_description = category_description();
			if ( !empty( $category_description ) )
				echo apply_filters( 'category_archive_meta', '<div class="page-header">' . $category_description . '</div>' );
		?>
		<?php
			// DataTable

			if ( function_exists( 'datatable_generator' ) )
				datatable_generator( array(
					'tax' => 'organization_structure:' . $term->term_id,
					'type' => 'persons',
					'fields' => 'ID, post_title:link, email',
					'titles' => 'post_title:Организация, email:Email',
                    'view' => 'rows:25'
				) );
		?>
		<?php do_action('cp_loop_after'); ?>
	</div><!-- /#main -->
	<?php do_action('cp_main_after'); ?>
</div><!-- /#content -->
<?php do_action('cp_content_after'); ?>
<?php get_footer(); ?>