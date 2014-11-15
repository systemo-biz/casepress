<!DOCTYPE html>

<html>
<head>
<title>
	<?php
		global $wpdb, $page;
		wp_title( '|', true, 'right' );
		bloginfo( 'name' );
		$site_description = get_bloginfo( 'description', 'display' );
	?>
</title>
<?php
	$url=get_permalink();
	wp_head();
?>
    <link rel="stylesheet" id="print-css"  href='<?php echo plugins_url( '/print.css', __FILE__ ); ?>' type="text/css" media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
</head>

<body>

<div id="content" class="clearfix">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); 
    global $post;
    ?>
		<div class="qrcode_wrapper">
            <div id="qrcodeCanvas"></div>
            <script type="text/javascript" src="<?php echo plugins_url( '/src/jquery.qrcode.js', __FILE__ ); ?>"></script>
            <script type="text/javascript" src="<?php echo plugins_url( '/src/qrcode.js', __FILE__ ); ?>"></script>
            <script>
                jQuery('#qrcodeCanvas').qrcode({
                render	: "canvas",
                width: 100,
                height: 100,
                text	: "<? echo $url; ?>"
                });	
            </script>
        </div>
        <div id="article">
			<h1 class="article-title">#<?php the_ID(); ?>-<?php the_title(); ?></h1>
			<div>(<?php echo get_the_term_list( $post->ID, 'functions', ' ', ',', '' ); ?>)</div>
			<p id="print"><a href="javascript:window.print()">print</a></p>
			<div class="article-content"><?php echo $post->post_content; ?></div>
		</div>
	<?php endwhile; ?>
</div>

<div id="footer" class="clearfix">Copyright &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></div>

</body>
</html>