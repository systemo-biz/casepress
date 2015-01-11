<?php
/*
Plugin Name: commentAction
Version: 0.7
Author: dmnbars
*/

add_action('comment_form','add_comment_fields_place');
function add_comment_fields_place() {
    ?>
    <div class="comment-fields">
        <ul class="list-unstyled">
            <?php do_action('add_comment_action_cp') ?>
        </ul>
    </div>
    <?php
};

add_action('add_comment_action_cp','add_custom_fields');
function add_custom_fields() {
    if(has_term('', 'post_tag')) {
        ?>
        <li>
            <input type="checkbox" id="openCase" name="openCase" value="openCase">
            <label for="openCase">Возобновить дело</label>
        </li>
        <?php
    } else {
        ?>
        <li>
            <input type="checkbox" id="closeCase" name="closeCase" value="closeCase" data-toggle="collapse" data-target="#select-result" aria-expanded="false" aria-controls="select-result">
            <label for="closeCase">Закрыть дело</label>
        </li>
        <li>
            <div><span>Укажите результат:</span>
                <?php wp_dropdown_categories('taxonomy=post_tag&hide_empty=0&name=resultSelect&id=resultSelect&hide_if_empty=true'); ?>
            </div>
        </li>
        <?php
    };
};

add_action('comment_post', 'toggle_case');
function toggle_case() {
    global $post;
    if (isset($_POST['closeCase'])) {
        $tag = get_term_by('id', $_POST['resultSelect'], 'post_tag');
        wp_set_post_terms($post->ID, $tag->slug, 'post_tag');
    } elseif (isset($_POST['openCase'])) {
        wp_delete_object_term_relationships( $post->ID, 'post_tag');
    };
    return;
};

?>