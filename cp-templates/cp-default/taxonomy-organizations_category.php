<?php
	/**
	 * Шаблон таксономии Категории организаций (organizations_category)
	 */
	get_header();
?>

<?php roots_content_before(); ?>
<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
	<?php roots_sidebar_before(); ?>
	<aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
		<?php roots_sidebar_inside_before(); ?>
		<div class="well">
			<?php dynamic_sidebar( 'organizations' ); ?>
		</div>
		<?php roots_sidebar_inside_after(); ?>
	</aside><!-- /#sidebar -->
	<?php roots_sidebar_after(); ?>
	<?php roots_main_before(); ?>
	<div id="main" class="<?php echo MAIN_CLASSES; ?>" role="main">
		<?php
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			echo "<h1>". $term->name ."</h1>";
			echo '<hr/>';
			// Term description
			$category_description = category_description();
			if ( !empty( $category_description ) )
				echo apply_filters( 'category_archive_meta', '<div class="page-header">' . $category_description . '</div>' );
		?>
		<?php roots_loop_before(); ?>
		<?php /* Start loop */ ?>
		<?php
			// DataTable

			if ( function_exists( 'datatable_generator' ) )
				datatable_generator( array(
					'tax' => 'organizations_category:' . $term->term_id,
					'type' => 'organizations',
					'fields' => 'ID:int, post_title:link',
					'titles' => 'post_title:Организация'
				) );
		?>
		<?php roots_loop_after(); ?>
	</div><!-- /#main -->
	<?php roots_main_after(); ?>
</div><!-- /#content -->
<?php
	roots_content_after();
	get_footer();
?>
<!-- <?php echo basename( __FILE__ ); ?> -->