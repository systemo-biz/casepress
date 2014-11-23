<?php
/*
Plugin Name: Context Search
Description: Добавляет виджет поиска, который зависим от контекста текущего запроса
*/


//Добавляем шорткод поиска в контексте

function search_context_form($attr) {
	global $wp;
	ob_start();
	?>
    <div id="navigation">
		<ul>
            <?php do_action('add_navigation_item'); ?>
        </ul>
	</div>

    <?php

	//Если это одиночная запись, то поиск перейдет на главную страницу, иначе будет выполняться поиск в текущем списке
	if(is_single()) {
		$current_url = home_url( '/' );
	} else {
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	}

	$s = esc_attr( get_search_query() );

	?>
	<form role="search" method="get" class="search-context-form" action="<?php echo esc_url( $current_url ); ?>">
		<div class="form-group">
			<label for="search-field-cp">Найти:</label>
			<input id="search-field-cp" type="search" class="search-field-cp form-control" placeholder="Введите искомый текст" value="<?php echo $s; ?>" name="s" />
		</div>
		<?php do_action('search_form_add_item'); ?>
		<div>
			<input type="submit" class="search-submit btn btn-default" value="Найти...">
		</div>
	</form>
	<?php

	$html = ob_get_contents();
	ob_get_clean();

	return $html;

} add_shortcode('search_context_form', 'search_context_form');



//Добавляем поиск по типу поста на страницы поиска

function add_dropdown__post_type_to_context_search() {

	if(! is_search()) return;

    if(empty($_REQUEST['post_type'])) {
        $post_type_ruquest ='';
    } else {
        $post_type_ruquest = $_REQUEST['post_type'];
    }

    $post_types = get_post_types(array(
        'public'   				=> true,
        '_builtin'	=> false,
        'has_archive'			=> true,
        'exclude_from_search'	=> false,
        ), 'objects');
//var_dump($post_types);
?>

	<div id="post_type_field_wrapper_cp" class="form-group">
		<label for="post_type_field_cp"><span>Тип</span></label>
		<select id="post_type_field_cp" class="form-control" placeholder="Выберите тип" name="post_type">
		<?php
		echo '<option value="" ' . selected( $post_type_ruquest, '', false ) . '>Все типы</option>'; 
		echo '<option value="post" ' . selected( $post_type_ruquest, 'post', false ) . '>Посты (Блог)</option>'; 
		echo '<option value="page" ' . selected( $post_type_ruquest, 'page', false ) . '>Страницы</option>'; 
		foreach($post_types as $post_type) {
		    echo '<option value="' . $post_type->name . '"' . selected( $post_type_ruquest, $post_type->name, false ) . '>' . $post_type->labels->name . '</option>';
		}
		?>
		</select>
	</div>

<?php
} add_action('search_form_add_item', 'add_dropdown__post_type_to_context_search');