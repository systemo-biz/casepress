<?php



//Добавляем секцию с действиями над комментарием
function add_section_actions_to_comment_text($comment_text, $comment) {
    ob_start();
    ?>
    <div class="comment_text_wrapper_before_action">
        <?php echo $comment_text ?>
    </div>
    <div class="comment_actions_cp">
        <ul class="nav nav-pills">
            <?php do_action('comment_actions_cp', $comment_text, $comment); ?>
        </ul>
    </div><!-- .comment_actions_cp --><?php
    $html = ob_get_contents();
    ob_get_clean();
    return $html;
}
add_filter('comment_text', 'add_section_actions_to_comment_text', 10, 2);





//Добавляем действия в секцию
function add_actions_to_comment_cp($comment_text, $comment) {
?>

    <li><?php edit_comment_link( '<span class="edit-link glyphicon glyphicon-pencil"></span>' ); ?></li>
    <li><a href="<?php echo get_admin_url( null, 'post-new.php?post_type=cases&content=' . str_replace('#', '%23', get_comment_link( $comment->comment_ID ) )); ?>">Отправить в деле</a></li>

<?php
} add_action('comment_actions_cp', 'add_actions_to_comment_cp', 10, 2);