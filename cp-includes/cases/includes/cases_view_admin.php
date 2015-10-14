<?php 

/*
Общие механизмы вывода элементов дела в админке
*/


class CaseViewsAdminSingltone {
private static $_instance = null;

private function __construct() {

    add_action( 'edit_form_after_title', array($this, 'form_case_members_render') );



    add_action( 'post_submitbox_misc_actions', array($this, 'form_case_parameters_render') );

    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_case_category'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_case_branche'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_responsible'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_deadline'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_date_end'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_result'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_parent'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_parser_result_function_cp'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_from'));
    add_action('add_field_for_case_aside_parameters', array($this, 'add_field_to'));

    add_action( 'save_post', array($this, 'save_data_post'));

}

    // Add field "To"
function add_field_to($post) {
 ?>
    <!-- Кому  -->

    <div id="cp_case_to_wrapper">
        <div>
            <div>
                <label class="cp_label" id="cp_case_to_label" for="cp_case_to_input" onclick="">Адресат:</label>
            </div>
            <div id="cp_case_to_edit">
                <div id="cp_case_to_edit_input">
                    <input type="hidden" id="cp_case_to_input" name="cp_to" class="cp_select2_single" />
                </div>  
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $("#cp_case_to_input").select2({
                    placeholder: "Выберите субъекта",
                    width: '100%',
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            dataType: 'json',
                            quietMillis: 100,
                            data: function (term, page) { // page is the one-based page number tracked by Select2
                                    return {
                                            action: 'query_to',
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
                    
                    formatResult: function(element){ return "<div>" + element.title + "</div>" }, // omitted for brevity, see the source of this page
                    formatSelection: function(element){  return element.title; }, // omitted for brevity, see the source of this page
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                });

                //Если есть данные о значении, то делаем выбор
                <?php 
                    $item_id = get_post_meta( $post->ID, 'cp_to', true );

                    if($item_id != ''): ?>   
                    $("#cp_case_to_input").select2(
                        "data", 
                        <?php echo json_encode(array('id' => $item_id, 'title' => get_the_title($item_id))); ?>
                    ); 
                <?php endif; ?>


            });
        </script>   
    </div>
    <?php
}
    
// Add field "From"
function add_field_from($post) {
 ?>
    <!-- От  -->

    <div id="cp_case_from_wrapper">
        <div>
            <div>
                <label class="cp_label" id="cp_case_from_label" for="cp_case_from_input" onclick="">От:</label>
            </div>
            <div id="cp_case_from_edit">
                <div id="cp_case_from_edit_input">
                    <input type="hidden" id="cp_case_from_input" name="cp_from" class="cp_select2_single" />
                </div>  
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $("#cp_case_from_input").select2({
                    placeholder: "Выберите субъекта",
                    width: '100%',
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            dataType: 'json',
                            quietMillis: 100,
                            data: function (term, page) { // page is the one-based page number tracked by Select2
                                    return {
                                            action: 'query_from',
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
                    
                    formatResult: function(element){ return "<div>" + element.title + "</div>" }, // omitted for brevity, see the source of this page
                    formatSelection: function(element){  return element.title; }, // omitted for brevity, see the source of this page
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                });

                //Если есть данные о значении, то делаем выбор
                <?php 
                    $item_id = get_post_meta( $post->ID, 'cp_from', true );

                    if($item_id != ''): ?>   
                    $("#cp_case_from_input").select2(
                        "data", 
                        <?php echo json_encode(array('id' => $item_id, 'title' => get_the_title($item_id))); ?>
                    ); 
                <?php endif; ?>


            });
        </script>   
    </div>
    <?php
}
    
//Функция для парсинга результатов AJAX запроса
function add_parser_result_function_cp(){
?>
        <script type="text/javascript">


            //format data from server and render list nodes for select2
            function elementFormatResult(element) {
                    //alert(element.title);
                    var markup = "<div id=\"select-list\">";
                    //if (movie.posters !== undefined && movie.posters.thumbnail !== undefined) {
                    //	markup += "<td class='movie-image'><img src='" + movie.posters.thumbnail + "'/></td>";
                    //}
                    markup += "<div class='node-title'>" + element.title + "</div>";
                    if (element.email !== undefined) {
                            markup += "<div class='node-email'>" + element.email + "</div>";
                    }

                    markup += "</div>";
                    //alert(markup);
                    return markup;
            }

            //get field for put to input 
            function elementFormatSelection(element) {
                    return element.title;
            }
    </script>

<?php

}
    
//Добавляем поле Участники под заголовок
function form_case_members_render($post){
    
    //check right post type
    if (($post->post_type != 'cases')) return;
    $members = get_post_meta($post->ID, 'members-cp-posts-sql');
    ?>  
    <div id="case_members_wrapper" class="panel panel-default">
        <div id="members_heading">
            <label>Участники</label>

        </div>
        <div id="case_members_edit_wrapper">
            <input type="hidden" id="case_members" name="case_members"/>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                //Создаем поле Select2 + AJAX для выбора участников в деле
                $("#case_members").select2({
                    placeholder: "Добавить участника...",
                    formatInputTooShort: function (input, min) { return "Пожалуйста, введите " + (min - input.length) + " или более символов"; },
                    minimumInputLength: 1,
                    formatSearching: function () { return "Поиск..."; },
                    formatNoMatches: function () { return "Ничего не найдено"; },
                    width: '100%',
                    multiple: true,
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

                    formatResult: function(element){ return "<div>" + element.title + "</div>" }, // omitted for brevity, see the source of this page
                    formatSelection: function(element){  return element.title; }, // omitted for brevity, see the source of this page
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                });

                //Если есть данные о значении, то делаем выбор
                <?php
                if(! empty($members)):
                    $members_data = array();
                    foreach ($members as $member):
                        if(!empty($member)) $members_data[] = array('id' => $member, 'title' => get_the_title($member));
                    endforeach;
                    ?>
                    $("#case_members").select2(
                        "data", 
                        <?php echo json_encode($members_data); ?>
                    );
                <?php endif; ?>
            });
        </script>           
    </div>

    <?php
}


function add_field_responsible($post) {
 ?>
    <!-- Ответственный -->

    <div id="cp_case_responsible_wrapper">
        <div>
            <div>
                <label class="cp_label" id="cp_case_responsible_label" for="cp_case_responsible_input" onclick="">Ответственный:</label>
            </div>
            <div id="cp_case_responsible_edit">
                <div id="cp_case_responsible_edit_input">
                    <input type="hidden" id="cp_case_responsible_input" name="cp_responsible" class="cp_select2_single" />
                </div>  
            </div>
        </div>
        <script type="text/javascript">
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
                    
                    formatResult: function(element){ return "<div>" + element.title + "</div>" }, // omitted for brevity, see the source of this page
                    formatSelection: function(element){  return element.title; }, // omitted for brevity, see the source of this page
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                });

                //Если есть данные о значении, то делаем выбор
                <?php 
                    $responsible_id = get_post_meta( $post->ID, 'responsible-cp-posts-sql', true );

                    if($responsible_id != ''): ?>   
                    $("#cp_case_responsible_input").select2(
                        "data", 
                        <?php echo json_encode(array('id' => $responsible_id, 'title' => get_the_title($responsible_id))); ?>
                    ); 
                <?php endif; ?>


            });
        </script>   
    </div>
    <?php
}


function add_field_parent($post) {
    ?>
    <!-- Основание дела -->

    <div id="cp_case_post_parent_div">
        <?php
            $case_parent_id = '0';
                
            if ($post->post_parent){
                $case_parent_id = $post->post_parent;
            } elseif (isset($_REQUEST['case_parent_id']) && is_numeric($_REQUEST['case_parent_id'])) {
               $case_parent_id = $_REQUEST['case_parent_id'];
            } else $case_parent_id = '0';
        ?>
        <div>
            <label class="cp_label" id="cp_case_post_parent_input_label" for="cp_case_post_parent_input">Основание</label>
        </div>
        <div id="cp_case_post_parent_edit">
            <input type="hidden" id="case_post_parent_input" name="cp_case_post_parent" class="cp_select2_single" />
        </div>
        <script type="text/javascript">
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
                    formatResult: function(element){ return "<div>" + element.title + "</div>" }, // omitted for brevity, see the source of this page
                    formatSelection: function(element){  return element.title; }, // omitted for brevity, see the source of this page
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                });
                
                //Если есть данные о значении, то делаем выбор
                <?php if(!empty($case_parent_id)): ?>   
                    $("#case_post_parent_input").select2(
                        "data", 
                        <?php echo json_encode(array('id' => $case_parent_id, 'title' => get_the_title($case_parent_id))); ?>
                    ); 
                <?php endif; ?>

            });
        </script>
    </div>

    <?php
}

function add_field_result($post){
    ?>
    <!-- Результат -->

    <div id="cp_field_result_div">
        <?php
        $terms = get_the_terms( $post_id, 'results' );
        
        //get first term from array
        if (is_array($terms)) $term = array_shift($terms);
        if (isset($term->term_id)){
            $case_result_id = $term->term_id;
        } else $case_result_id = '0';
        ?>

        <div>
            <label for="cp_field_result_select" class="cp_label">Результат</label>
        </div>
        <div>
            <?php
            wp_dropdown_categories( array(
                'name' => 'cp_case_result',
                'class' => 'cp_full_width',
                'id' => 'cp_field_result_select',
                'echo' => 1,
                'hide_empty' => 0, 
                'show_option_none' => 'Без результата',
                'option_none_value' => '0',
                'selected' => $case_result_id,
                'hierarchical' => 1,
                'taxonomy' => 'results'
            ));
            ?>
        </div>
    </div>

    <?php
}

function add_field_date_end($post){
?>

    <!-- Дата закрытия -->

    <div id="cp_date_end_wrapper" >
        <?php
        $date_deadline = get_post_meta($post->ID, "cp_date_end", true);
        $value = $date_deadline;
        ?>
        <div>
            <label for="cp_date_end" class="cp_forms cp_label" id="cp_field_date_deadline_label">Дата закрытия:</label>
        </div>
        <div>
            <input id="cp_date_end" name="date_end" class="form-control" autocomplete="off" value="<?php  echo get_post_meta( $post->ID, 'cp_date_end', true ); ?>">
        </div> 
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                 rome(cp_date_end, { weekStart: 1 });
            });
        </script>
    </div>
<?php
}


function add_field_case_category($post){
?>
    <div id="cp_case_category_div">
       <?php

        $post_id = $post->ID;
        $taxonomy = 'functions';
        $terms = get_the_terms( $post_id, $taxonomy );

        //get first term from array
        if (is_array($terms)) $term = array_shift($terms);
        
        ?>

        <label class="cp_label" for="cp_case_category_select">Категория дела</label>
        <?php
        $case_category_id = '0';
        
        if (isset($term->term_id)){
            $case_category_id = $term->term_id;
        } elseif (isset($_REQUEST['case_category_id'])) {
            $case_category_id = $_REQUEST['case_category_id'];
        } else $case_category_id = '0';

        wp_dropdown_categories( array(
            'name' => 'cp_case_category',
            'class' => 'cp_full_width',
            'id' => 'cp_case_category_select',
            'echo' => 1,
            'hide_empty' => 0, 
            'show_option_none' => 'Выберите категорию дела',
            'option_none_value' => '0',
            'selected' => $case_category_id,
            'hierarchical' => 1,
            'taxonomy' => 'functions'
        )) ; ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                 $('#cp_case_category_select').select2({
                    width: '100%',
                    allowClear: true,
                 });
            });
        </script>  

    </div>
<?php
}


function add_field_case_branche($post){

?>

    <!--------Подразделения-------->
    <div id="cp_case_branche_div">
       <?php

        $post_id = $post->ID;
        $taxonomy = 'branche';
        $terms = get_the_terms( $post_id, $taxonomy );

        //get first term from array
        if (is_array($terms)) $term = array_shift($terms);
        
        ?>

        <label class="cp_label" for="cp_case_branche_select">Подразделение</label>
        <?php
        $case_branche_id = '0';
        
        if (isset($term->term_id)){
            $case_branche_id = $term->term_id;
        } elseif (isset($_REQUEST['case_branche_id'])) {
            $case_branche_id = $_REQUEST['case_branche_id'];
        } else $case_branche_id = '0';

        wp_dropdown_categories( array(
            'name' => 'cp_case_branche',
            'class' => 'cp_full_width',
            'id' => 'cp_case_branche_select',
            'echo' => 1,
            'hide_empty' => 0, 
            'show_option_none' => 'Выберите подразделение',
            'option_none_value' => '0',
            'selected' => $case_branche_id,
            'hierarchical' => 1,
            'taxonomy' => 'branche'
        )) ; ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                 $('#cp_case_branche_select').select2({
                    width: '100%',
                    allowClear: true,
                 });
            });
        </script>  

    </div>
<?php
}

function add_field_deadline($post){
    ?>

    <!-- Срок -->

    <div id="cp_field_date_deadline_div" >
        <?php
        $date_deadline = get_post_meta($post->ID, "deadline_cp", true);
        $value = $date_deadline;
        ?>
        <div>
            <label for="cp_field_date_deadline_input" class="cp_forms cp_label" id="cp_field_date_deadline_label">Срок:</label>
        </div>
        <div>
            <input type="text" id="deadline_cp" name="cp_date_deadline" class="cp_full_width cp_input_datepicker" value="<?php echo $value?>"/>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                 rome(deadline_cp, { weekStart: 1 });
            });
        </script>
    </div>

    <?php
}

/**
*  get form "Case Paramenters"
*  
*  @return html
*/     
function form_case_parameters_render(){
    global $post; 

    //check post type
    if ((is_admin()) && !($post->post_type == 'cases')) return;

    ?>
    <div class="misc-pub-section">
    <?php do_action('add_field_for_case_aside_parameters', $post); ?>
    </div>
<?php
}



//Сохраняем данные поста
function save_data_post(){
    $post = get_post();
    //check right post type
    if (!(is_object($post))) return;
    if ($post->post_type != 'cases') return;
    
    $post_id = $post->ID;
 
    /** Save date end
     * field name: cp_to
     */
    if (isset($_REQUEST['cp_to'])) {
        $key = 'cp_to';
        $value = $_REQUEST['cp_to'];
        update_post_meta( $post_id, $key, $value);
    }
    
     /** Save date end
     * field name: cp_from
     */
    if (isset($_REQUEST['cp_from'])) {
        $key = 'cp_from';
        $value = $_REQUEST['cp_from'];
        update_post_meta( $post_id, $key, $value);
    }
    
    /*
     * Save case category
     * field name: cp_case_category
     */
    if (isset($_REQUEST['cp_case_category']) && $_REQUEST['cp_case_category'] != ''){
        $terms = $_REQUEST['cp_case_category'];
        $taxonomy = "functions";
        $append = false;
        wp_set_post_terms( $post_id, $terms, $taxonomy, $append );
    }

    if (isset($_REQUEST['cp_case_branche']) && $_REQUEST['cp_case_branche'] != ''){
        $terms = $_REQUEST['cp_case_branche'];
        $taxonomy = "branche";
        $append = false;
        wp_set_post_terms( $post_id, $terms, $taxonomy, $append );
    }
    
     /** Save date end
     * field name: cp_date_end
     */
    if (isset($_REQUEST['date_end'])) {
        $key = 'cp_date_end';
        $value = $_REQUEST['date_end'];
        update_post_meta( $post_id, $key, $value);
    }
    
    /*
     * save result
     */
    if (isset($_REQUEST['cp_case_result'])) {
        $term = $_REQUEST['cp_case_result'];
        $taxonomy = "results";
        $append = false;
        wp_set_post_terms( $post_id, $term, $taxonomy, $append );
    }
    
    /*
     * save deadline
     */
    if (isset($_REQUEST['cp_date_deadline'])) {
        $key = 'deadline_cp';
        $timestamp = $_REQUEST['cp_date_deadline'];
                
        update_post_meta( $post_id, $key, $timestamp);
    }
            
    /*
     * Field "Members"
     */
    if (isset($_REQUEST['case_members'])) {
        
        $key = 'members-cp-posts-sql';
        $case_members = trim( $_REQUEST['case_members'] );
        
        $case_members = explode(',', $case_members);

        $current_members = get_post_meta($post->ID, 'members-cp-posts-sql');
        

        //получаем массив участников, которых убрали из поля
        $members_remove = array_diff($current_members, $case_members);
        
        //получаем массив участников, которых добавили
        $members_add = array_diff($case_members, $current_members);

        //Проверяем есть ли ответственный и если есть, то включаем его в список участников на проверку
        $responsible_id = get_post_meta($post->ID, 'responsible-cp-posts-sql', true);

        //удаляем лишних учатсников
        foreach($members_remove as $member){
            
            //если участника на удаление есть в поле Ответственный, то пропускаем удаление
            if($responsible_id == $member) continue;
            
            //удаляем участника из списка
            delete_post_meta($post->ID, 'members-cp-posts-sql', trim($member));
        }

        //удаляем лишних учатсников
        foreach($members_add as $member){
            add_post_meta($post->ID, 'members-cp-posts-sql', trim($member));
        }
    }   


   
    /*
     * Field "Responsible"
     */
    if (isset($_REQUEST['cp_responsible'])) {
    
        $data = trim( $_REQUEST['cp_responsible'] );
        $key = 'responsible-cp-posts-sql';
        
        if(empty($data)) delete_post_meta($post_id, $key);

        update_post_meta($post_id, $key, $data);
    }

    /*
     * Field "Post Parent"
     */
    // infinity loop  fixed
    if (isset($_REQUEST['cp_case_post_parent'])) {

        $post_parent = trim( $_REQUEST['cp_case_post_parent'] );
        if ($post_parent > 0 && $post->post_parent != $post_parent){
        //unhook
            remove_action( 'save_post', array($this, 'save_data_post'));
            wp_update_post(array(
                'ID' => $post_id, 
                'post_parent' => $post_parent
            )); 
        //rehook
            add_action( 'save_post', array($this, 'save_data_post'));
        }
    } 

}   




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

} $CasesView = CaseViewsAdminSingltone::getInstance();
