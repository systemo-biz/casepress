<?php
/*
Plugin Name: commentAction
Version: 0.1
Author: dmnbars
*/

function add_comment_fields_place($defaults) {
    ob_start();
    ?>
    <div class="comment-fields">
        <ul class="list-unstyled">
            <?php do_action('add_comment_action_cp') ?>
        </ul>
    </div>
    <?php
    $defaults['comment_notes_after'] .= ob_get_contents();
    ob_end_clean();
    return $defaults;
};
add_filter('comment_form_defaults','add_comment_fields_place');

function toggle_case() {
    global $post;
    if (isset($_POST['closeCase'])) {
        wp_set_post_tags( $post->ID, $_POST['resultSelect'], true );
    } elseif (isset($_POST['openCase'])) {
        wp_remove_object_terms( $post->ID, 'done', 'post_tag');
    };
    return;
};
add_filter('comment_post', 'toggle_case');

function add_custom_fields() {
    if(has_tag('done')) {
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
            <div class="collapse" id="select-result"><span>Укажите результат:</span>
                <select name="resultSelect">
                    <option value="done">Готово</option>
                </select>
            </div>
        </li>
        <?php
    };
};
add_action('add_comment_action_cp','add_custom_fields');

function add_another_field() {
    ?>
    <li>another_field</li>
    <?php
};
add_action('add_comment_action_cp','add_another_field');

?>
