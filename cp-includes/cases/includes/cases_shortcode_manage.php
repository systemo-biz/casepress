<?php

/*
*   Шорткод контроля дела
*/
class CaseShortcodeControlSingltone {
private static $_instance = null;
private function __construct() {

    add_shortcode('case_meta', array($this, 'shortcode_meta'));
    add_action('wp', array($this, 'save_case_data_control'));
    add_action('wp_enqueue_scripts', array($this, 'add_ss'));
    add_action( 'wp_ajax_query_posts_cases', array($this, 'query_posts_cases_callback') );
}




/*
Добавляем шорткод для контроля дел [case_meta]
*/
function shortcode_meta(){
    if(!is_singular('cases')){
        return;
    }

    global $post;


    ob_start();
    ?>
    <form method="post">
        <input type="hidden" name="update_case_control" value="1">
        
        <!-- Категория дела -->

        <div id="case_category_wrapper" class="form-group">
            <div class="label_wrapper">
                <label for="case_category">Категория дела</label>
            </div>
            <div>
                <?php
                $category_ids = wp_get_post_terms($post->ID, 'functions', array("fields" => "ids"));
                $selected_category = empty($category_ids) ? 0 : $category_ids[0];
                wp_dropdown_categories( array(
                    'name'              => 'case_category',
                    'taxonomy'          => 'functions', 
                    'id'                => 'case_category', 
                    'class'             => "form-control-cp",
                    'selected'          => $selected_category,
                    'hide_if_empty'     => false,
                    'hierarchical'      => 1,
                    'show_option_none'  => 'Без категории',
                    ));
                ?>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                     $('#case_category').select2({
                        width: '100%',
                        allowClear: true,
                     });
                });
            </script>            
        </div>

        <!-- Ответственый -->

        <div id="cp_case_responsible_wrapper" class="form-group">
            <div>
                <label class="cp_label" id="cp_case_responsible_label" for="cp_case_responsible_input" onclick="">Ответственный:</label>
                <span id="cp_case_responsible_view" class="cp_forms">
                    <?php $responsible_id = get_post_meta( $post->ID, 'responsible-cp-posts-sql', true ); ?>
                    <?php if($responsible_id != ''): ?>
                        <a href="<?php echo get_permalink($responsible_id); ?>"><?php echo get_the_title($responsible_id); ?></a>
                    <?php else: ?>
                        <span>Нет</span>
                    <?php endif; ?>
                </span>
                <div id="cp_case_responsible_edit" style="display: none">
                    <div id="cp_case_responsible_edit_input">
                        <input type="hidden" id="cp_case_responsible_input" name="cp_responsible" class="cp_select2_single" />
                    </div>  
                    <p>
                        <button type="button" id="cp_action_cancel_responsible" class="btn btn-link">Отмена</button>
                    </p>
                </div>
            </div>
            <script type="text/javascript">
                
                //Скрываем и расскрываем поле для редактирования
                (function($) {
                    $("#cp_case_responsible_label").click(function(){
                        $("#cp_case_responsible_edit").show();
                        $("#cp_case_responsible_view").hide();
                    });
                    
                    $("#cp_action_cancel_responsible").click(function(){
                        $("#cp_case_responsible_edit").hide();
                        $("#cp_case_responsible_view").show();

                    });
                })(jQuery);


                jQuery(document).ready(function($) {

                    $("#cp_case_responsible_input").select2({
                        placeholder: "Выберите ответственного",
                        width: '100%',
                        allowClear: true,
                        minimumInputLength: 1,
                        ajax: {
                                url: "<?php echo admin_url('admin-ajax.php') ?>",
                                dataType: 'json',
                                quietMillis: 100,
                                data: function (term, page) { // page is the one-based page number tracked by Select2
                                        return {
                                                action: 'query_persons',
                                                page_limit: 10, // page size
                                                page: page, // page number
                                                //params: {contentType: "application/json;charset=utf-8"},
                                                q: term //search term
                                        };
                                },
                                results: function (data, page) {
                                        //alert(data.total);
                                        var more = (page * 10) < data.total; // whether or not there are more results available

                                        // notice we return the value of more so Select2 knows if more results can be loaded
                                        return {
                                                results: data.elements,
                                                more: more
                                                };
                                }
                        },
                        
                        formatResult: elementFormatResult, // omitted for brevity, see the source of this page
                        formatSelection: elementFormatSelection, // omitted for brevity, see the source of this page
                        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                        escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                    });

                    //Если есть данные о значении, то делаем выбор
                    <?php if($responsible_id != ''): ?>   
                        $("#cp_case_responsible_input").select2(
                            "data", 
                            <?php echo json_encode(array('id' => $responsible_id, 'title' => get_the_title($responsible_id))); ?>
                        ); 
                    <?php endif; ?>


                });
            </script>    
        </div>

        <!-- Результат -->

        <div id="case_result_wrapper" class="form-group">
            <div class="label_wrapper">
                <label for="case_result">Результат</label>
            </div>
            <div>
                <?php
                $result_ids = wp_get_post_terms($post->ID, 'results', array("fields" => "ids"));
                $selected_result = empty($result_ids) ? 0 : $result_ids[0];
                wp_dropdown_categories( array(
                    'name'              => 'case_result',
                    'taxonomy'          => 'results', 
                    'id'                => 'case_result_dropdown', 
                    'class'             => "form-control-cp",
                    'selected'          => $selected_result,
                    'hide_if_empty'     => false,
                    'hierarchical'      => 1,
                    'show_option_none'  => 'Без выбора',
                    ));
                ?>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                     $('#case_result_dropdown').select2({
                        allowClear: true,
                        width: '100%',
                     });
                });
            </script>            
        </div>
        
        <!-- Срок -->

        <div id="deadline_cp_wrapper" class="form-group">
            <label id="deadline_cp_label" for="deadline_cp">Срок: </label>
            <span id="deadline_cp_view"><?php echo get_post_meta( $post->ID, 'deadline_cp', true ); ?></span>
            <div id="deadline_cp_edit" style="display: none">
                <div>
                    <input id="deadline_cp" name="deadline_cp" class="form-control" autocomplete="off" value="<?php  echo get_post_meta( $post->ID, 'deadline_cp', true ); ?>">
                </div>  
                <div>
                    <button type="button" id="deadline_cp_action_cancel" class="btn btn-link">Отмена</button>
                </div>
            </div>

            <script type="text/javascript">

                //Скрываем и расскрываем поле для редактирования
                (function($) {
                    $("#deadline_cp_label").click(function(){
                        $("#deadline_cp_edit").show();
                        $("#deadline_cp_view").hide();
                    });
                    
                    $("#deadline_cp_action_cancel").click(function(){
                        $("#deadline_cp_edit").hide();
                        $("#deadline_cp_view").show();

                    });
                })(jQuery);


                jQuery(document).ready(function($) {
                     rome(deadline_cp, { weekStart: 1 });
                });
            </script>
        </div>

        <!-- Дата закрытия-->

        <div id="cp_date_end_wrapper" class="form-group">
            <label id="cp_date_end_label" for="cp_date_end">Дата закрытия</label>
            <span id="cp_date_end_view"><?php echo get_post_meta( $post->ID, 'cp_date_end', true ); ?></span>
            
            <div id="cp_date_end_edit" style="display: none">
                <div>
                    <input id="cp_date_end" name="date_end" class="form-control" autocomplete="off" value="<?php  echo get_post_meta( $post->ID, 'cp_date_end', true ); ?>">
                </div>  
                <div>
                    <button type="button" id="cp_date_end_action_cancel" class="btn btn-link">Отмена</button>
                </div>
            </div>

            <script type="text/javascript">

                //Скрываем и расскрываем поле для редактирования
                (function($) {
                    $("#cp_date_end_label").click(function(){
                        $("#cp_date_end_edit").show();
                        $("#cp_date_end_view").hide();
                    });
                    
                    $("#cp_date_end_action_cancel").click(function(){
                        $("#cp_date_end_edit").hide();
                        $("#cp_date_end_view").show();

                    });
                })(jQuery);

                jQuery(document).ready(function($) {
                     rome(cp_date_end, { weekStart: 1 });
                });
            </script>
        </div>

        <!-- Основание дела -->
        
        <div id="cp_case_post_parent" class="form-group">
            <div class="label_wrapper">
                <label class="cp_label" id="cp_case_post_parent_input_label" for="cp_case_post_parent_input">Основание</label>
                <span id="case_post_parent_view">
                    <?php $post_parent_id = $post->post_parent ?>
                    <?php if($post_parent_id != ''): ?>
                        <a href="<?php echo get_permalink($post_parent_id); ?>"><?php echo get_the_title($post_parent_id); ?></a>
                    <?php else: ?>
                        <span>Нет</span>
                    <?php endif; ?>
                </span>
            </div>
            <div id="cp_case_post_parent_edit" style="display: none">
                <input type="hidden" id="case_post_parent_input" name="case_post_parent_cp" />
                <p>
                    <button type="button" id="cp_case_post_parent_action_cancel" class="btn btn-link">Отмена</button>
                </p>
            </div>
            <script type="text/javascript">

                (function($) {
                    $("#cp_case_post_parent_input_label").click(function(){
                        $("#cp_case_post_parent_edit").show();
                        $("#case_post_parent_view").hide();
                    });
                    
                    $("#cp_case_post_parent_action_cancel").click(function(){
                        $("#cp_case_post_parent_edit").hide();
                        $("#case_post_parent_view").show();

                    });
                })(jQuery);

                jQuery(document).ready(function($) {

                    $("#case_post_parent_input").select2({
                        placeholder: "Родительская задача",
                        width: '100%',
                        allowClear: true,
                        minimumInputLength: 1,
                        ajax: {
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            dataType: 'json',
                            quietMillis: 100,
                            data: function (term, page) { // page is the one-based page number tracked by Select2
                                return {
                                    action: 'query_posts_cases',
                                    posts_per_page: 10, // page size
                                    paged: page, // page number
                                    s: term //search term
                                };
                            },
                            results: function (data, page) {
                                    //alert(data.total);
                                    var more = (page * 10) < data.total; // whether or not there are more results available

                                    // notice we return the value of more so Select2 knows if more results can be loaded
                                    return {
                                        results: data.items,
                                        more: more
                                    };
                            }
                        },
                        formatResult: elementFormatResult, // omitted for brevity, see the source of this page
                        formatSelection: elementFormatSelection, // omitted for brevity, see the source of this page
                        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                        escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                    });
                    
                    //Если есть данные о значении, то делаем выбор
                    <?php if($post_parent_id != ''): ?>   
                        $("#case_post_parent_input").select2(
                            "data", 
                            <?php echo json_encode(array('id' => $post_parent_id, 'title' => get_the_title($post_parent_id))); ?>
                        ); 
                    <?php endif; ?>

                });
            </script>
        </div>

        <!-- Сохранить -->

        <button type="submit" class="btn btn-default">Сохранить</button>
    </form>
    <?php

    $html = ob_get_contents();

    ob_get_clean();

    return $html;
}


//Возвращает JSON данные о кейсах для Select2
function query_posts_cases_callback(){
    $args = array(
        'fields' => 'ids',
        's' => $_GET['s'],
        'paged' => $_GET['paged'],
        'posts_per_page' => $_GET['posts_per_page'],
        'post_type' => 'cases'
        );

    $query = new WP_Query( $args );

    $items = array();
    foreach ($query->posts as $post_id){
        
        $items[] = array(
            'id' => $post_id,
            'title' => get_the_title($post_id)
            );
    }
    
    $data[] = array(
        "total" => (int)$query->found_posts, 
        'items' => $items);
    //$data[] = $query;
    wp_send_json($data[0]);
}





/*
Механизм сохранения данных контроля кейсов
*/
function save_case_data_control() {
    if(! isset($_REQUEST['update_case_control'])) return;

    /**
     * Сохраняем только если находимся на странице дела
     */

    if(!is_singular('cases')) return;

    global $post;

    /**
     * Если пришло ID категории дела - сохраняем его
     */

    if(isset($_REQUEST['case_category']) && is_numeric($_REQUEST['case_category']) && $_REQUEST['case_category'] > -1) {

        wp_set_post_terms( $post->ID, $_REQUEST['case_category'], 'functions', false );
    }



    /**
     * Если пришло ID основания дела - сохраняем его
     */

    if(isset($_REQUEST['case_post_parent_cp'])) {
        
        if(is_numeric($_REQUEST['case_post_parent_cp'])) $case_post_parent_cp = $_REQUEST['case_post_parent_cp'];

        wp_update_post(array(
            'ID' => $post->ID, 
            'post_parent' => $case_post_parent_cp
        ));
    }
    

    //Сохраняем мету Дата закрытия
    if(isset($_REQUEST['date_end'])) {
        //'cp_date_end' => isset($_REQUEST['date_end']) ? $_REQUEST['date_end'] : ''
        $date_end_new = $_REQUEST['date_end'];
        //$date_end_current = get_post_meta($post->ID, 'cp_date_end', true);
        update_post_meta( $post->ID, 'cp_date_end', $date_end_new );


    }

    /**
     * Если пришло ID результата дела - сохраняем его
     */

    if(isset($_REQUEST['case_result']) && is_numeric($_REQUEST['case_result'])) {
        $result = $_REQUEST['case_result'];
        if($result < 0) {
            wp_delete_object_term_relationships ($post->ID, 'results');
        } else {
            wp_set_post_terms( $post->ID, $_REQUEST['case_result'], 'results', false );
        }
    }

    /**
     * Сохраняем все меты из виджета
     */

    $meta = array(
        'deadline_cp' => isset($_REQUEST['deadline_cp']) ? $_REQUEST['deadline_cp'] : '',
        'responsible-cp-posts-sql' => isset($_REQUEST['cp_responsible']) ? $_REQUEST['cp_responsible'] : '',
    );

    foreach($meta as $meta_name => $meta_value){

        update_post_meta( $post->ID, $meta_name, $meta_value );
    }

    //print_r($_REQUEST);

    //print_r(get_post_meta($post->ID));
    //die();


}

//Добавляем стили и скрипты
function add_ss(){
        //wp_register_script( 'case_shortcode_manage', plugins_url( '/select2/select2.js',__FILE__ ), array(), '3.4.0', 'all');
        //wp_enqueue_script('select2');


        wp_register_style( 'case_shortcode_manage', plugins_url( '/style.css',__FILE__ ), array(), '20140926', 'all' );
        wp_enqueue_style('case_shortcode_manage');

}



/*
************************************************************************************************************************************************************************************************
************************************************************************************************************************************************************************************************
************************************************************************************************************************************************************************************************
************************************************************************************************************************************************************************************************
************************************************************************************************************************************************************************************************
*/



    protected function __clone() {
        // ограничивает клонирование объекта
    }

    static public function getInstance() {
        if(is_null(self::$_instance))
        {
        self::$_instance = new self();
        }
        return self::$_instance;
    }

} $CaseShortcodeControl = CaseShortcodeControlSingltone::getInstance();

