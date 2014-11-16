<?php
/*
Plugin Name: CasePress. Обложка
Description: Обложка дела
License: Free
*/

/*
*   Шорткод контроля дела
*/

//загружаем шаблон из папки, если обнаружен параметр view=cover
function view_visits_cp() {

    if(empty($_REQUEST['view'])) return;

    if ( $_REQUEST['view'] == 'visits' ) {
            include( plugin_dir_path(__FILE__) . 'view.php' );
            exit();
    }
} add_action( 'template_redirect', 'view_visits_cp', 0, 5);
    
 
//добавляем ссылку в меню Действий через хук
function add_view_visits_to_action_box(){

    if (is_singular('cases')){ 
        $url = add_query_arg( array('view' => 'visits'));
        ?>
        <li>
            <a href="<?php echo $url ?>&KeepThis=true&TB_iframe=true&height=300&width=500" class="thickbox">Посмотреть визиты</a>
        </li>
        <?php
    }
} add_action('add_action_cp', 'add_view_visits_to_action_box', 11);

//Выводим список комментов типа Визиты в нужном виде для шаблона
function visits_walker_cp($comment, $args, $depth){
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         
         <?php printf(__('<cite class="fn">%s</cite> <span class="says">wrote:</span>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?></div>

      <?php comment_text() ?>
      
     </div>
<?php
}
