<?php
	acf_form_head();
	get_header();
?>
<?php roots_content_before(); ?>
<div id="content" class="<?php echo CONTAINER_CLASSES; ?>">
	<?php roots_main_before(); ?>
	<div id="main" class="span12" role="main">
		<?php roots_loop_before(); ?>
		<?php get_template_part( 'loop', 'single' ); ?>
		<?php get_template_part( 'template', 'acf-form' ); ?>
		<?php roots_loop_after(); ?>
	</div><!-- /#main -->
	<?php roots_main_after(); ?>
</div><!-- /#content -->
<?php roots_content_after(); ?>
<?php get_footer(); ?>
<!-- <?php echo basename( __FILE__ ); ?> -->