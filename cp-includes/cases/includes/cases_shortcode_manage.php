<?php




/*
*
*
*   Шорткод контроля дела
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
****************************************************************************************************************************************************************
*/
class CaseShortcodeControlSingltone {
private static $_instance = null;
private function __construct() {

    add_shortcode('case_meta', array($this, 'shortcode_meta'));
    add_action('wp', array($this, 'save_case_data_control'));
    add_action('wp_enqueue_scripts', array($this, 'add_ss'));
    add_action('admin_enqueue_scripts', array($this, 'add_ss'));

}


/*
Добавляем шорткод для контроля дел [case_meta]
*/
function shortcode_meta(){
    if(!is_singular('cases')){
        return;
    }

    global $post;

    $responsible_id = get_post_meta( $post->ID, 'cp_responsible', true );
    $terms = wp_get_post_terms($post->ID, 'functions', array("fields" => "ids"));
    $selected = empty($terms) ? 0 : $terms[0];

    ob_start();
    ?>
    <form method="post" action="<?php echo add_query_arg( 'update_case_control', '1', get_permalink() ); ?>">
        <div id="case_category_wrapper" class="form-group">
            <div class="label_wrapper">
                <label for="case_category">Категория дела</label>
            </div>
            <div>
                <?php
                wp_dropdown_categories( array(
                    'name'              => 'case_category',
                    'taxonomy'          => 'functions', 
                    'id'                => 'case_category', 
                    'class'             => "form-control-cp",
                    'selected'          => $selected,
                    'hide_if_empty'     => false,
                    'hierarchical'      => 1,
                    'show_option_none'  => 'Без выбора',
                    ));
                ?>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                     $('#case_category').select2({
                     	width: '100%'
                     });
                });
            </script>            
        </div>
        <div id="deadline_cp_wrapper">
            <label for="deadline_cp">Срок</label>
            <input id="deadline_cp" name="deadline_cp" class="form-control" value="<?php  echo get_post_meta( $post->ID, 'deadline_cp', true ); ?>">
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                     rome(deadline_cp);
                });
            </script>
        </div>
        <div class="cp_field form-group">
            <div>
                <label class="cp_label" id="cp_case_responsible_label" for="cp_case_responsible_input" onclick="">Ответственный</label>
                <span id="cp_case_responsible_view" class="cp_forms">
                <?php if($responsible_id != ''): ?>
                    <a href="<?php echo get_permalink($responsible_id); ?>"><?php echo get_the_title($responsible_id); ?></a>
                <?php endif; ?>
                </span>
                <div id="cp_case_responsible_edit" style="display: none">
                    <div id="cp_case_responsible_edit_input">
                        <input type="hidden" id="cp_case_responsible_input" name="cp_responsible" class="cp_select2_single" />
                    </div>  
                    <p>
                        <a href="#cancel" id="cp_action_cancel_responsible">Отмена</a>
                    </p>
                </div>
            </div>
            <script type="text/javascript">
                (function($) {
                        $("#cp_case_responsible_label").click(function(){
                            $("#cp_case_responsible_edit").show();
                            $("#cp_case_responsible_view").hide();
                        });
                        
                        $("#cp_action_cancel_responsible").click(function(){
                            $("#cp_case_responsible_edit").hide();
                            $("#cp_case_responsible_view").show();

                        });
                        
                        /*$("#cp_action_save_responsible").click(function(){
                            cp_responsible = $("#cp_case_responsible_input").val();
                            $.ajax({
                                data: ({
                                    cp_responsible: cp_responsible,
                                    case_id: <?php echo $post->ID ?>,
                                    action: 'save_data_post'
                                }),
                                url: "<?php echo admin_url('admin-ajax.php') ?>",
                                success: function(data) {
                                    data = $.parseJSON(data);
                                    $("#cp_case_responsible_input").select2('data', data[0]);
                                    $.ajax({
                                        data: ({
                                            action: 'persons_links',
                                            data: data
                                        }),
                                        url: "<?php echo admin_url('admin-ajax.php') ?>",
                                        success: function(links){
                                            $("#cp_case_responsible_view").html(links);
                                            $("#cp_case_responsible_edit").hide();
                                            $("#cp_case_responsible_view").show();
                                        }
                                    });
                                }
                            });
                        });*/
                       

                })(jQuery);


                    jQuery(document).ready(function($) {

                        $("#cp_case_responsible_input").select2({
                            placeholder: "",
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
                        /*$.ajax({
                            data: ({
                                action: 'get_responsible',
                                dataType: 'json',
                                case_id: <?php echo $post->ID ?>
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data) {
                                data = $.parseJSON(data);
                                console.log(data);
                                $('#cp_case_responsible_input').select2('data', data);
                                data = [data];
                                $.ajax({
                                    data: ({
                                        action: 'persons_links',
                                        data: data
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(links){
                                        $("#cp_case_responsible_view").html(links);
                                    }
                                });
                            }
                        });*/
                        <?php if($responsible_id != ''): ?>
                        $('#cp_case_responsible_input').select2('data', <?php echo json_encode(array('id' => $responsible_id, 'title' => get_the_title($responsible_id))); ?>);
                        <?php endif; ?>
                    });
                </script>    
        </div>
            
        <button type="submit" class="btn btn-default">Сохранить</button>
    </form>
    <?php

    $html = ob_get_contents();

    ob_get_clean();

    return $html;
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
     * Сохраняем все меты из виджета
     */

    $meta = array(
        'deadline_cp',
        'cp_responsible'
    );

    foreach($meta as $meta_name){
        if(isset($_REQUEST[$meta_name])){

            update_post_meta( $post->ID, $meta_name, $_REQUEST[$meta_name] );
            //print_r(array($post->ID, $meta_name, $_REQUEST[$meta_name]));
        }
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

