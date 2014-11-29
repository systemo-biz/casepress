<?php


/*
Общие механизмы вывода элементов дела
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
******************************************************************************************************************************************
*/


class CaseViewsSingltone {
	private static $_instance = null;

	private function __construct() {

		add_filter('the_content', array($this, 'add_wrapper_content_case_cp'), 9, 2);
        add_action('content_before_wrapper_cp', array($this, 'add_meta_content_top'), 14, 2);
        //add_filter('the_excerpt', array($this, 'add_meta_content_top'), 14, 2);
        add_action('content_before_wrapper_cp', array($this, 'add_field_members_cp'), 22);

	    add_action('wp_footer', array($this, 'add_js_functions'));

        add_action('case_meta_top_add_li', array($this, 'add_id_and_category_to_case_meta'));
        add_action('case_meta_top_add_li', array($this, 'add_result_meta'));
        add_action('case_meta_top_add_li', array($this, 'add_deadline'));
        add_action('case_meta_top_add_li', array($this, 'add_responsible'));
        

        add_action('wp', array($this, 'save_members'));
        add_action( 'wp_ajax_query_persons', array($this, 'query_persons_callback') );

	}

//Добавляем Срок в поле под заголовок поста
function add_deadline($violation_of_date = false){
    global $post;
    $deadline_cp = get_post_meta($post->ID, 'deadline_cp', true);

    //Если срока нет, то ничего не выводим
    if(empty($deadline_cp)) return;


    $cp_date_end = get_post_meta($post->ID, 'cp_date_end', true);
    $now = date('Y-m-d H:i');

    //Если срок больше даты завершения или текущего времени то отмечаем факт нарушения срока
    if ( ($deadline_cp <= $cp_date_end) and $cp_date_end > 0) {
         $violation_of_date = true;
    } elseif(empty($cp_date_end) and $deadline_cp < $now) { 
         $violation_of_date = true;
    }
    ?>
    <li>
    <?php if($violation_of_date) : ?>
        <span class="label label-danger">
    <?php else: ?>
        <span class="label label-default">
    <?php endif; ?>
        <span class="glyphicon glyphicon-calendar"></span> <?php echo $deadline_cp ?>
    </li>
    <?php
}

//Добавляем поле Ответственный под заголовок поста
function add_responsible(){
    if(is_single()) return; // если это одиночный кейс, то там уже есть вывод этих данных и потом добавлять не надо
    global $post;
    ?>
    <span id="cp_case_responsible_view" class="cp_forms">
        <?php $responsible_id = get_post_meta( $post->ID, 'responsible-cp-posts-sql', true ); ?>
        <?php if($responsible_id != ''): ?>
            <span class='glyphicon glyphicon-pushpin'></span>
            <a href="<?php echo get_permalink($responsible_id); ?>"><?php echo get_the_title($responsible_id); ?></a>
        <?php endif; ?>
    </span>
    <?php
}

//Функция ответа JSON для AJAX SELECT2
function query_persons_callback(){
    $args = array(
        'fields' => 'ids',
        's' => $_GET['q'],
        'paged' => $_GET['page'],
        'posts_per_page' => $_GET['page_limit'],
        'post_type' => 'persons'
        );

    $query = new WP_Query( $args );

    $elements = array();
    foreach ($query->posts as $post_id){
        //try get organization
        $organization = "без организации";
        if ($organization_id = get_post_meta($post_id, 'organization-cp-posts-array', true)) {
            $organization = get_the_title($organization_id[0]);
        }
        
        $elements[] = array(
            'id' => $post_id,
            'title' => get_the_title($post_id),
            'organization' => $organization
            );
    }
    
    $data[] = array(
        "total" => (int)$query->found_posts, 
        'elements' => $elements);
    //$data[] = $query;
    wp_send_json($data[0]);
}

//Сохраняем участников
function save_members(){
    global $post;

    if(! isset($_REQUEST['save_case_members'])) return;

    if(isset($_REQUEST['case_members'])) $case_members = $_REQUEST['case_members'];

    $case_members = explode(',', $case_members);

    $current_members = get_post_meta($post->ID, 'members-cp-posts-sql');

    $members_remove = array_diff($current_members, $case_members);
    $members_add = array_diff($case_members, $current_members);

    //error_log('dddd '.print_r($members_add));

    //удаляем лишних учатсников
    foreach($members_remove as $member){
        delete_post_meta($post->ID, 'members-cp-posts-sql', trim($member));
    }

    //удаляем лишних учатсников
    foreach($members_add as $member){
        add_post_meta($post->ID, 'members-cp-posts-sql', trim($member));
    }   
}
 
/*
Добавляем поле Участники над описанием
*/
function add_field_members_cp($content){
    if(!is_singular('cases')) return $content;
    
    global $post;

    ?>

    <div id="case_members_wrapper" class="panel panel-default">
        <div id="members_heading" class="panel-heading">Участники <button type="button" id="edit_members_btn" class="btn btn-link">Изменить</button></div>
        <div class="panel-body">
            <div id="case_members_view" class="show">
                <?php
                $members = get_post_meta($post->ID, 'members-cp-posts-sql');

                if(empty($members)) :
                    echo '<span>Без участников</span>';
                else:
                    $members = get_posts( array(
                        'post_type' => 'persons',
                        'include'   => get_post_meta($post->ID, 'members-cp-posts-sql')
                    )); 
                    ?>
                    <ul class="list-inline">
                    <?php foreach ($members as $member): ?>
                        <li><a href="<?php echo get_permalink($member->ID) ?>"><?php echo $member->post_title; ?></a>, </li>
                    <?php endforeach; ?>
                    </ul>

                <?php endif; ?>

            </div>
            <div id="case_members_edit_wrapper" class="hidden">
                <form method="post">
                    <input type="hidden" name="save_case_members" value=true />
                    <div class="form-group">
                        <input type="hidden" id="case_members" name="case_members"/>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
        <script type="text/javascript">
        
           //Скрываем и расскрываем поле для редактирования
            (function($) {
                $("#edit_members_btn").click(function(){
                    if($('#case_members_edit_wrapper').hasClass("hidden")){
                        //alert(1);
                        $('#case_members_edit_wrapper').removeClass('hidden').addClass('show');
                        $('#case_members_view').removeClass('show').addClass('hidden');
                    } else {
                        $('#case_members_edit_wrapper').removeClass('show').addClass('hidden');
                        $('#case_members_view').removeClass('hidden').addClass('show');                        
                    }
                });

            })(jQuery);

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

                    formatResult: elementFormatResult, // omitted for brevity, see the source of this page
                    formatSelection: elementFormatSelection, // omitted for brevity, see the source of this page
                    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                    escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                });

                //Если есть данные о значении, то делаем выбор
                $("#case_members").select2(
                    "data", 
                    <?php 
                        $members_data = array();
                        foreach ($members as $member):
                            $members_data[] = array('id' => $member->ID, 'title' => $member->post_title);
                        endforeach;
                        echo json_encode($members_data); //(array('id' => $post_parent_id, 'title' => get_the_title($post_parent_id)));
                    ?>
                ); 
            });

         


     
        </script>           
    </div>


             

    <?php

}




    //Доавляем секцию с мета данными
    function add_meta_content_top(){
        global $post;
        
        if (!(is_singular('cases') or (is_search() and get_post_type($post->ID) == 'cases') or (get_post_type($post->ID) == 'cases' and is_archive()))) return;

        

        ?>
        <section id='meta-case'>
            <ul class="list-inline">
                <?php do_action('case_meta_top_add_li'); ?>
            </ul> 
        </section>
        <?php

    }

    function add_id_and_category_to_case_meta(){
        global $post;
        ?>
        <li>
            <span>
                <span class="glyphicon glyphicon-link"></span><span id="case_id"><a href="<?php the_permalink(); ?>">#<?php the_ID() ?></a></span>
            </span>
        </li>
        <li>
            <span id="case_category_meta_wrapper">
                <?php 
                $category_case = wp_get_post_terms($post->ID, 'functions'); 
                //var_dump($category_case);
                ?>
                <?php if(empty($category_case)): ?>
                    <span class="label label-default"><span class="glyphicon glyphicon-folder-open"></span> Без категории</span>
                <?php else: ?>
                    <a href="<?php echo get_term_link( $category_case[0]->term_id, 'functions'); ?>">
                        <span class="label label-default"><span class="glyphicon glyphicon-folder-open"></span> <?php echo $category_case[0]->name; ?></span>
                    </a>
                <?php endif; ?>

            </span>
        </li>
        <?php
    }
    
   //Добавляем результат в мету кейса, если есть
    function add_result_meta(){
        global $post;
        $result = wp_get_post_terms($post->ID, 'results'); 
        if(! empty($result)):
        ?>
        <li>
            <span class="label label-success"><span class="glyphicon glyphicon-ok"></span> <?php echo $result[0]->name ?></span>
        </li>
        <?php
        endif;
    }


//Добавляем JS функции для работы Select 2 AJAX
	function add_js_functions() {

		?>
		<script type="text/javascript" id='add_js_functions_for_select2_ajax_render'>
			// служебные функции для парсинга данных к полю
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
			        if (element.organization !== undefined) {
			                markup += "<div class='node-organization'>" + element.organization + "</div>";
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






/*
Добавляем обертку для контента. С пониженным приоритетом. Чтобы затем можно было точно отделить контент от остальных секций добавляемых через хук the_content
*/
function add_wrapper_content_case_cp($content){
	if(!is_singular('cases')) return $content;
	return '<div class="jumbotron">' . $content . '</div>';
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

} $CasesView = CaseViewsSingltone::getInstance();













