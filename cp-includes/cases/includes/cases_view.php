<?php


/*
 * Class for render fields
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
 ******************************************************************************************************************************************
 ******************************************************************************************************************************************
 */
class CP_Render_Fields {
    function __construct(){
        //
    }


    /**
    *  get field for action "Close"
    *  
    *  @return html
    */  
    function field_action_close(){

          ?>
            <div>
                <a href="#close">Закрыть</a>
            </div>
        <?php          
    }
    
    /*
     * get field for action "Accept"
     * 
     * @return html
     */
    function field_action_accept() {
          ?>
            <div>
                <a href="#accepts">Принять</a>
            </div>
        <?php   
    }

    /**
    *  get field for action "Delegate"
    *  
    *  @return html
    */  
    function field_action_delegate(){

        ?>
            <div>
                <a href="#delegate">Делегировать</a>
            </div>
        <?php
    }
        
    /**
    *  get field for add other field
    *  
    *  @return html
    */ 
	function field_action_add (){
		?>
		<div class="cp_add_case_data">

			<select id="cp_add_case_data_select" onchange="cp_add_field(this);">
				<option selected="selected">Добавить поле</option>
				<option value="cp_date_end_div">Дата завершения</option>
				<option value="cp_prioritet_div">Приоритет</option>
			</select>
			<script>
				function cp_add_field(v){
					var id = v.options[v.selectedIndex].value; //get id DOM for display
					jQuery("#" + id).show();
					jQuery('select#cp_add_case_data_select').prop('selectedIndex',0);
				};
			</script>
		</div>
		<?php
	}
	
    function field_case_category_render(){
        global $post;
        $post_id = $post->ID;
        $taxonomy = 'functions';
        $terms = get_the_terms( $post_id, $taxonomy );

        //get first term from array
        if (is_array($terms)) $term = array_shift($terms);
        
        ?>
        <div id="cp_case_category_div">
            
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
            <div id="cp_field_case_category_edit" style="display: none">
                <a href="#ok" class="cp_button" id="cp_field_case_category_button_save">OK</a>
            </div>
            <script type="text/javascript">
                (function($) {
					$("#cp_case_category_select").change(function(){
						$("#cp_field_case_category_edit").show();
                    });
                    $("#cp_field_case_category_button_save").click(function(){
                        //alert("!!!");
                        
                        $.ajax({
                            data: ({
                                case_category: $("#cp_case_category_select").val(),
                                case_id: <?php echo $post->ID?>,
                                action: 'save_data_post'
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data) {
                                $("#cp_field_case_category_edit").hide();
                                <?php if (!(is_admin())) echo "location.reload();"; ?>
                            }                                
                         });
                    });

                })(jQuery);   
            </script>
        </div>
        <?php
    }
    
	function field_post_parent_render(){
		global $post;
		$case_parent_id = '0';
            
        if ($post->post_parent){
            $case_parent_id = $post->post_parent;
        } elseif (isset($_REQUEST['case_parent_id']) && is_numeric($_REQUEST['case_parent_id'])) {
           $case_parent_id = $_REQUEST['case_parent_id'];
        } else $case_parent_id = '0';
		
		wp_update_post(
				array(
					'ID' => $post->ID, 
					'post_parent' => $case_parent_id
				)
			);
		
		?>
		
		<div id="cp_case_post_parent_div">
			<label class="cp_label" id="cp_case_post_parent_input_label" for="cp_case_post_parent_input">Основание</label>
			<span id="cp_case_post_parent_view" class="cp_forms">
			<?php // echo $out; ?>
			</span>
			<div id="cp_case_post_parent_edit" style="display: none">
				<input type="hidden" id="cp_case_post_parent_input" name="cp_case_post_parent" class="cp_select2_single" />
				<a href="#ok" class="cp_button" id="cp_field_case_post_parent_button_save">OK</a>
				<a href="#cancel" class="cp_button" id="cp_field_case_post_parent_button_cancel">Отмена</a>
			</div>
		</div>
		<script type="text/javascript">

			(function($) {
			
							url = "<?php echo get_site_url() ?>";
							$("#cp_case_post_parent_input_label").click(function(){
                                $("#cp_case_post_parent_edit").show();
                                $("#cp_case_post_parent_view").hide();
                            });
                            
                            $("#cp_field_case_post_parent_button_cancel").click(function(){
                                $("#cp_case_post_parent_edit").hide();
                                $("#cp_case_post_parent_view").show();

                            });
							
                           $("#cp_field_case_post_parent_button_save").click(function(){
								cp_post_parent = $("#cp_case_post_parent_input").val();
								//console.log(cp_post_parent);
                                $.ajax({
                                    data: ({
                                        cp_post_parent: cp_post_parent,
                                        case_id: <?php echo $post->ID ?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data) {
										data = $.parseJSON(data);
										//console.log(data);
                                        $("#cp_case_post_parent_input").select2('data', data[0]);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: data
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_case_post_parent_view").html(links);
												$("#cp_case_post_parent_edit").hide();
												$("#cp_case_post_parent_view").show();
											}
										});
									}
								});
							});
                           

                })(jQuery);
								jQuery(document).ready(function($) {

									$("#cp_case_post_parent_input").select2({
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
                                                                action: 'query_posts_cases',
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
									$.ajax({
										data: ({
											action: 'get_post_parent',
											dataType: 'json',
											case_id: <?php echo $post->ID ?>
										}),
										url: "<?php echo admin_url('admin-ajax.php') ?>",
										success: function(data) {
											data = $.parseJSON(data);
											//console.log(data);
											$('#cp_case_post_parent_input').select2('data', data[0]);
											//data = [data];
											$.ajax({
												data: ({
													action: 'persons_links',
													data: data
												}),
												url: "<?php echo admin_url('admin-ajax.php') ?>",
												success: function(links){
													$("#cp_case_post_parent_view").html(links);
												}
											});
										}
									});
								});
                        </script>
        <?php
    }
	
    function field_date_end_render(){
        global $post;
		
        //convert date
        $date_end = "";
        $value = "";
        $timestamp = strtotime(get_post_meta($post->ID, "cp_date_end", true));
        if ($timestamp > 0) {
            $value = date('Y-m-d\TH:i', $timestamp); // format: 2013-12-31T23:55
            $date_end = date('d.m.Y H:i', $timestamp);
        }

        ?>
        <div id="cp_date_end_div" <?php //echo $hide; ?>>
            <label class="cp_label" for="cp_date_end_input" id="cp_field_date_end_label">Дата завершения:</label>
            <span id="cp_field_date_end_view" class="cp_forms"><?php echo $date_end ?></span>
            <div id="cp_field_date_end_edit" style="display: none">
                <input type="datetime-local" id="cp_date_end_input" name="cp_date_end" class="cp_full_width cp_input_datepicker" value="<?php echo $value ?>"/>
                <p>
                    <a href="#ok" id="cp_action_save_date_end" class="button">OK</a>
                    <a href="#cancel" id="cp_action_cancel_date_end">Отмена</a>
                </p>
            </div>
            <script type="text/javascript">
                (function($) {
                    $("#cp_field_date_end_label").click(function(){
                        $("#cp_field_date_end_edit").show();
                        $("#cp_field_date_end_view").hide();
                    });

                    $("#cp_action_cancel_date_end").click(function(){
                        $("#cp_field_date_end_edit").hide();
                        $("#cp_field_date_end_view").show();

                    });

                    $("#cp_action_save_date_end").click(function(){
                        date_end = $("#cp_date_end_input").val();
                        $.ajax({
                            data: ({
                                date_end: date_end,
                                case_id: <?php echo $post->ID?>,
                                action: 'save_data_post'
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data, str) {
                                $("#cp_field_date_end_view").text(data);
                                $("#cp_field_date_end_edit").hide();
                                $("#cp_field_date_end_view").show();                                     }
                         });

                    });



                })(jQuery);
            </script>
        </div>
        <?php
    }
    
    function field_prioritet_render(){
        global $post;
        $post_id = $post->ID;

        $key='cp_prioritet';

        //chek field for show
        $value = get_post_meta($post_id, $key, true);
        //$hide = (($value == "") ? "style='display: none;'" : ""); //temporarily off this

        ?>
        <div id="cp_prioritet_div"  <?php //echo $hide; ?>>
            <label class="cp_label" for="cp_prioritet_select">Приоритет</label><br/>
            <select id="cp_prioritet_select" name="cp_prioritet">
                <option <?php if($value=="") echo "selected='selected'" ?> >Без приоритета</option>
                <option <?php if($value=="1") echo "selected='selected'" ?> value="1">Критичный</option>
                <option <?php if($value=="2") echo "selected='selected'" ?> value="2">Высокий</option>
                <option <?php if($value=="3") echo "selected='selected'" ?> value="3">Нормальный</option>
                <option <?php if($value=="4") echo "selected='selected'" ?> value="4">Низкий</option>
                <option <?php if($value=="5") echo "selected='selected'" ?> value="5">Планируемый</option>
            </select>
        </div>
        <?php
    }
    
    function field_result_render() {
        global $post;
        $post_id = $post->ID;
        $taxonomy = 'results';
        $terms = get_the_terms( $post_id, $taxonomy );
        
        //get first term from array
        if (is_array($terms)) $term = array_shift($terms);
        if (isset($term->term_id)){
            $case_result_id = $term->term_id;
        } else $case_result_id = '0';
        ?>    
        <div id="cp_field_result_div">
            <label for="cp_field_result_select" class="cp_label">Результат</label>
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
                )) ;
                ?>
            <div id="cp_field_result_edit" style="display: none">
                <a href="#ok" class="cp_button" id="cp_field_result_button_save">OK</a>
            </div>
            <script type="text/javascript">
                (function($) {
                    $("#cp_field_result_select").change(function(){
                        $("#cp_field_result_edit").show();
                    });

                    $("#cp_field_result_button_save").click(function(){
                        //alert("!!!");
                        result = $("#cp_field_result_select").val();
                        $.ajax({
                            data: ({
                                result: result,
                                case_id: <?php echo $post->ID?>,
                                action: 'save_data_post'
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data) {
                                $("#cp_field_result_edit").hide();
                            }                                
                         });
                    });

                })(jQuery);   
            </script>
        </div>

        <?php
    }
	



    

    
    function field_date_deadline(){
        global $post;

        //convert date
        $timestamp = strtotime(get_post_meta($post->ID, "cp_date_deadline", true));
        $value = "";
        $date_deadline = "";
        if ($timestamp > 0) {
            $value = date('Y-m-d\TH:i', $timestamp);
            $date_deadline = date('d.m.Y H:i', $timestamp);
        }
        

        ?>
        <div id="cp_field_date_deadline_div" >
                <label for="cp_field_date_deadline_input" class="cp_forms cp_label" id="cp_field_date_deadline_label">Срок:</label>
                <span id="cp_field_date_deadline_view" class="cp_forms"><?php echo $date_deadline?></span>
                <div id="cp_field_date_deadline_edit" style="display: none">
                    <div id="cp_field_date_deadline_edit_input">
                        <input type="datetime-local" id="cp_field_date_deadline_input" name="cp_date_deadline" class="cp_full_width cp_input_datepicker" value="<?php echo $value?>"/>
                    </div>  
                    <p>
                        <a href="#ok" id="cp_action_save_deadline" class="button">OK</a>
                        <a href="#cancel" id="cp_action_cancel_deadline">Отмена</a>
                    </p>
                    <script type="text/javascript">
                        (function($) {
                            $("#cp_field_date_deadline_label").click(function(){
                                $("#cp_field_date_deadline_edit").show();
                                $("#cp_field_date_deadline_view").hide();
                            });
                            
                            $("#cp_action_cancel_deadline").click(function(){
                                $("#cp_field_date_deadline_edit").hide();
                                $("#cp_field_date_deadline_view").show();

                            });
                            
                            $("#cp_action_save_deadline").click(function(){
                                deadline = $("#cp_field_date_deadline_input").val();
                                $.ajax({
                                    data: ({
                                        deadline: deadline,
                                        case_id: <?php echo $post->ID?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data, str) {
                                        $("#cp_field_date_deadline_view").text(data);
                                        $("#cp_field_date_deadline_edit").hide();
                                        $("#cp_field_date_deadline_view").show();
									}
                                });
                            });

                        })(jQuery);
                    </script>
               </div>
        </div>
        <?php
    }
}



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


class CasesViewSingltone {
	private static $_instance = null;

	private function __construct() {

		add_filter('the_content', array(&$this, 'add_wrapper_content_case_cp'));
		add_filter('the_content', array($this, 'add_label_to_content'));
		add_filter('the_content', array($this, 'add_field_members_cp'));
		add_shortcode('case_meta', array($this, 'shortcode_meta'));
	    add_action( 'edit_form_after_title', array($this, 'form_case_members_in_admin') );
	    add_action('wp_footer', array($this, 'add_js_functions'));

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




	//Выводим поля указания участников

	private function form_case_members_in_admin(){
        global $post; 
        
        //check right post type
        if (is_admin() && !($post->post_type == 'cases')) return;

        //$fields_render = new CP_Render_Fields;
        ?>	
                <div id="cp_case_managment_div" class="postbox">
                        <div id="cp_case_members_div">
                                <?php $this->field_members_render_in_admin();

                                //$fields_render->field_member_responsible_render()
                                 //$fields_render->field_member_from_render() ?>
                        </div>
                </div>

        <?php
    }


    //Генерация поля Участники для ввода

	function field_members_render_in_admin(){
        global $post;


        ?>
            <div class="cp_field">
                <p>
                    <label for="cp_case_members_input" id="cp_case_members_label" class="cp_label">Участники</label>
					<div id="cp_case_members_edit">
						<div id="cp_case_members_edit_input">
							<input type="hidden" id="cp_case_members_input" name="cp_case_members" class="cp_select2" />
						</div>  
						<p>
							<button id="cp_action_save_members">OK</button>
							<button id="cp_action_cancel_members">Отмена</button>
						</p>
					</div>
                </p>
            </div>
	
            <script type="text/javascript">
                (function($) {
							$("#cp_case_managment_div").mouseenter(function(){
								$("#cp_action_add_person").css('visibility','visible');
							})
							$("#cp_case_managment_div").mouseleave(function(){
								$("#cp_action_add_person").css('visibility','hidden');
							});

							url = "<?php echo get_site_url() ?>";
							$("#cp_case_members_label").click(function(){
                                $("#cp_case_members_edit").show();
                                $("#cp_case_members_view").hide();
                            });
                            
                            $("#cp_action_cancel_members").click(function(){
                                $("#cp_case_members_edit").hide();
                                $("#cp_case_members_view").show();

                            });
							
                            $("#cp_action_save_members").click(function(){
								cp_case_members = $("#cp_case_members_input").val();
                                $.ajax({
                                    data: ({
                                        cp_case_members: cp_case_members,
                                        case_id: <?php echo $post->ID ?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data) {
										data = $.parseJSON(data);
										$("#cp_case_members_input").select2('data', data);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: data
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_case_members_view").html(links);
												$("#cp_case_members_edit").hide();
												$("#cp_case_members_view").show();
											}
										});                                    }
                                 });
								});
                        

                })(jQuery);
                jQuery(document).ready(function($) {
                    $("#cp_case_members_input").select2({
                        placeholder: "",
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
                    $.ajax({
                        data: ({
                            action: 'get_members',
                            dataType: 'json',
                            case_id: <?php echo $post->ID ?>,
                        }),
                        url: "<?php echo admin_url('admin-ajax.php') ?>",
                        success: function(data) {
                            members = $.parseJSON(data);
                            $('#cp_case_members_input').select2('data',  members);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: members
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_case_members_view").html(links);
											}
										});
                        }
                    });
                });
            </script>
        <?php
    }

/*
Добавляем поле Участники над описанием
*/
function add_field_members_cp($content){
	if(!is_singular('cases')) return $content;
	
	global $post;

	ob_start();
	?>
	<div id="case_members">
		<div class="label_wrapper_cp">
			<label>Участники</label>
		</div>
		<div id="members_list_wrapper">
			<?php $this->the_members_list_cp() ?>
		</div>
		<div class="hid1e">
			<div id="members_edit_cp">
                <form method="post" action="<?php echo add_query_arg( 'update_members', '1', get_permalink() ); ?>">
                    <div class="dialogModal_header form-group">Изменение состава участников</div>
                    <div class="dialogModal_content form-group">
                        <input type="hidden" id="case_members_edit" name="case_members" />
                    </div>
                    <div class="dialogModal_footer form-group">
                        <button type="submit" data-dialogmodalbut="ok">ОК</button>
                        <!--<button type="button" data-dialogmodalbut="cancel">Отмена</button>-->
                    </div>
                </form>
			</div>
		</div>
		<script type="text/javascript">

	        jQuery(document).ready(function($) {

	        	//Создаем поле Select2 + AJAX для выбора участников в деле
				$("#case_members_edit").select2({
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
                <?php  $members_json_list = $this->the_members_json_list_cp();
                 if(!empty($members_json_list)) : ?>
                $('#case_members_edit').select2('data',  <?php echo '['.$members_json_list.']'; ?>);
                <?php endif;  ?>
/*
	            $.ajax({
	                data: ({
	                    action: 'get_members',
	                    dataType: 'json',
	                    case_id: <?php echo $post->ID ?>,
	                }),
	                url: "<?php echo admin_url('admin-ajax.php') ?>",
	                success: function(data) {
	                    members = $.parseJSON(data);
	                    $('#cp_case_members_input').select2('data',  members);
						$.ajax({
							data: ({
								action: 'persons_links',
								data: members
							}),
							url: "<?php echo admin_url('admin-ajax.php') ?>",
							success: function(links){
								$("#cp_case_members_view").html(links);
							}
						});
	            	}
	        	});
*/

	        });


			
            (function($) {

            	//вызов popModal при клике по лейблу Участники
            	/*
            	$('#case_members labe1l').click(function(){
	            	$('#members_edit_cp').dialogModal({
						showCloseBut : true, 
						onDocumentClickClose : true, 
						onDocumentClickClosePrevent : '', 
						onOkBut : function(){ }, 
						onCancelBut : function(){ }, 
						onLoad : function(){ }, 
						onClose : function(){ }
	            	});
            	})
*/
            	/*
            	$('#case_members label').click(function(){
	            	$('#case_members label').popModal({
	            		html : $('#members_edit_cp').html(),
						placement : 'bottomLeft', 
						showCloseBut : true, 
						onDocumentClickClose : true, 
						onDocumentClickClosePrevent : '', 
						inline : true, 
						overflowContent : false, 
						onOkBut : function(){ }, 
						onCancelBut : function(){ }, 
						onLoad : function(){ }, 
						onClose : function(){ }
	            	});
            	})
				*/

            })(jQuery);   
		</script>		
	</div>
	<?php
	$html = ob_get_contents();
	ob_get_clean();
	return $html . $content;
}






/*
Добавляем обертку для контента. С пониженным приоритетом. Чтобы затем можно было точно отделить контент от остальных секций добавляемых через хук the_content
*/
function add_wrapper_content_case_cp($content){
	if(!is_singular('cases')) return $content;
	return $content;
}






/*
Добавляем метку "Описание" к основной части, если описание есть.
*/
function add_label_to_content($content){
	global $post;

	if(isset($post->post_content) and is_singular('cases')) {
		$content = '<div id="case_description" class="label_content_wrapper_cp"><label>Описание</label></div>' . $content;
	}

	return $content;
}




/*
Вспомогательная функция. Генерирует список участников в виде гиперссылок.
*/

private function the_members_list_cp() {
	global $post;

    $meta = get_post_meta($post->ID, 'members-cp-posts-sql');

    if(empty($meta)) return;

	$members = get_posts( array(
		'post_type' => 'persons',
		'include'	=> $meta
	));

	echo '<ul class="list-inline">';

    $members_html = array();

	foreach ($members as $member) {
        $members_html[] = '<li><a href="'. get_permalink($member->ID) .'">' . $member->post_title .'</a></li>';
	}

    echo implode(',', $members_html);

	echo '</ul>';
}


/*
Вспомогательная функция. Генерирует список участников в json объекта.
*/

private function the_members_json_list_cp() {
	global $post;

    $meta = get_post_meta($post->ID, 'members-cp-posts-sql');

    if(empty($meta)) return;

	$members = get_posts( array(
		'post_type' => 'persons',
		'include'	=> $meta
	));

    $members_html = array();

	foreach ($members as $member) {
        $members_html[] = json_encode(array('id' => $member->ID, 'title' => $member->post_title));
	}

    return implode(',', $members_html);
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

} $CasesView = CasesViewSingltone::getInstance();













