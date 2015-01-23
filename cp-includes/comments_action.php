<?php
/*
Plugin Name: commentAction
Version: 0.8.1
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
            <input type="checkbox" id="closeCase" name="closeCase" value="closeCase">
            <label for="closeCase">Закрыть дело</label>
        </li>
        <li style="display: none">
            <div id="result_select_container"><span>Укажите результат:</span>
                <?php wp_dropdown_categories('taxonomy=results&hide_empty=0&name=result_select&id=result_select&hide_if_empty=true'); ?>
            </div>
        </li>
        <script type="text/javascript">
            var checkbox = document.getElementById('closeCase');
            var conteiner = document.getElementById('result_select_container').parentNode;
            if("onpropertychange" in checkbox) {
                // для старого IE
                checkbox.onpropertychange = function() {
                    // проверим имя изменённого свойства
                    conteiner.style.display = (checkbox.checked) ? 'block' : 'none';
                };
            } else {
                // остальные браузеры
                checkbox.onchange = function() {
                    conteiner.style.display = (checkbox.checked) ? 'block' : 'none';
                };
            }
        </script>
        <?php
    };
};

add_action('comment_post', 'toggle_case');
function toggle_case() {
    global $post;
    if (isset($_POST['closeCase'])) {
        $tag = get_term_by('id', $_POST['result_select'], 'results');
        wp_set_post_terms($post->ID, $tag->slug, 'results');
    } elseif (isset($_POST['openCase'])) {
        wp_delete_object_term_relationships( $post->ID, 'results');
    };
};

?>