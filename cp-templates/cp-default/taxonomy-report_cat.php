<?php
	/**
	 * Шаблон таксономии Категории объектов (objects_category)
	 */
	get_header();
?>

<?php roots_content_before(); ?>
<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
	<?php roots_sidebar_before(); ?>
	<aside id="sidebar" class="<?php echo SIDEBAR_CLASSES; ?>" role="complementary">
		<?php roots_sidebar_inside_before(); ?>
		<div class="well">
			<?php dynamic_sidebar( 'report' ); ?>
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
//echo $term->term_id;
	?>	<h2>
<?php echo $term->name;	?>	
</h2> <?
 query_posts(array( 'post_type'=>'report', 'tax_query' => array(
array(
'taxonomy' => 'report_cat',
'field' => 'id',
'terms' => $term->term_id
)
))); 
 ?>
	<ul class="thumbnails">
	<?php while (have_posts()) : the_post(); ?>
	  <li class="span4">
			<div class="caption">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				<?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
		 </div>
	  </li>

	  <?php endwhile; ?>
	</ul>
		
		<?php roots_loop_after(); ?>
	</div><!-- /#main -->
	<?php roots_main_after(); ?>
</div><!-- /#content -->
<?php
	roots_content_after();
	get_footer();
?>
<!-- <?php echo basename( __FILE__ ); ?> -->