<?php
/**
 * @package Hello_Dolly
 * @version 1.6
 */
/*
Plugin Name: Список комментариев-уведомлений
Description: Этот плагин добавляет шорткод с последними комментариями, которые являются уведомлением для текущего пользователя
*/

include_once 'add_option_select_page_notice.php';


//Добавляем ссылку на уведомления, если выбрана страница
function add_page_notice_to_navigation(){
	$page_notice_cp = esc_attr( get_option( 'page_notice_cp' ) );
    if(empty($page_notice_cp)) return;
    
	?>
	<li>
        <a href="<?php echo get_permalink( $page_notice_cp ); ?>">Уведомления</a>
	</li>

	<?php
} add_action('add_navigation_item', 'add_page_notice_to_navigation');

//добавляем шорткод на страницу уведомлений, если такая выбрана в опциях
function add_sc_to_page_notice($content){
    
    global $post;
    
    $page_notice_cp = esc_attr( get_option( 'page_notice_cp' ) );
    
    if($page_notice_cp == $post->ID) 
        $content .= do_shortcode('[notice_list]');
    return $content;
    
} add_filter('the_content', 'add_sc_to_page_notice', 10, 2);


// Если есть шорткод, то включаем пагинацию комментов и выключаем комментарии у самой страницы
function enable_comment_pagination_cp(){
    global $post;
    $page_notice_cp = esc_attr( get_option( 'page_notice_cp' ) );
    
    if(has_shortcode($post->post_content, 'list_comments') or $page_notice_cp == $post->ID) {
        add_filter( 'pre_option_page_comments', '__return_true' );
        //add_filter( 'comments_open', '__return_false' );
    }
    //exit(has_shortcode($post->post_content, 'list_comments'));
}
add_action('wp', 'enable_comment_pagination_cp');


//делаем шорткод для вывода комментов
function get_all_comments_list(){
    
    global $post;
    
    ob_start();
    //exit(has_shortcode($post->post_content, 'list_comments'));
    # The comment functions use the query var 'cpage', so we'll ensure that's set
    $page = intval( get_query_var( 'cpage' ) );
    if ( 0 == $page ) {
        $page = 1;
        set_query_var( 'cpage', $page );
    }

    # We'll do 10 comments per page...
    # Note that the 'page_comments' option in /wp-admin/options-discussion.php must be checked
    $comments_per_page = 10;
    $comments = get_comments( array( 
        'status' => 'approve',
        'meta_key'=>'notify_user',
        'meta_value'=> get_current_user_id()
    ));
    ?>
    <ol start="<?php echo $comments_per_page * $page - $comments_per_page + 1 ?>">
        <?php wp_list_comments( array (
            'style' => 'ol',
            'per_page' => $comments_per_page,
            'page' => $page,
            'reverse_top_level' => false
        ), $comments ); ?>
    </ol>

    <?php 
    // Now you can either use paginate_comments_links ... 
    paginate_comments_links();
    $html = ob_get_contents();

    ob_end_clean();
    
    return $html;
}
add_shortcode('notice_list', 'get_all_comments_list');

//Добавляем ссылку на пост к которму добавлен коммент
function add_post_link_for_notice_cp($comment_text, $comment) {
	global $post;
    
    $page_notice_cp = esc_attr( get_option( 'page_notice_cp' ) );
//exit(var_dump($comment->comment_post_ID));
    if($post->ID == $page_notice_cp) {
        ob_start();
        ?>
        <div class="post_link_for_notice_cp">
            <p>Добавилен в пост: <a href="<?php echo get_permalink($comment->comment_post_ID) ?>"><?php echo get_the_title ($comment->comment_post_ID) ?></a></p>
        </div>
        <?php
        $html = ob_get_contents();
        ob_get_clean();
        $comment_text = $html . $comment_text;
    }
    return $comment_text;
}
add_filter('comment_text', 'add_post_link_for_notice_cp',10,2);