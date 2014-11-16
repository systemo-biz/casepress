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

            $args = array(
                'type' => 'visited',
                'number' => 33,
                'post_id' => $post->ID,
            );
            ?>
 

            <ul class="list-group">
                <?php 

                if( $comments = get_comments( $args ) ){

                    foreach($comments as $comment){

                    $j++;

                    $user = get_user_by( 'id', $comment->user_id );

                        ?>
                        <li class="list-group-item">
                            <div class="user-meta">
                                <span class="badge"><?php echo $j;?></span>
                                <span><?php echo $user->display_name . ' (' . $comment->user_id . ')' ?></span>
                            </div>
                            <ul class='dates'>
                                <li>Дата первого посещения: <?php echo $comment->comment_date ?></li>
                                <li>Дата последнего: <?php echo $comment->comment_content ?></li>
                            </ul>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>

        <?php endwhile; ?>
    </div>
</body>
</html>