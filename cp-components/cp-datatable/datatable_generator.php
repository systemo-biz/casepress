<?php



function datatable_generator($params, $sql=null){

	wp_enqueue_script('datatable', plugin_dir_url(__FILE__).'assets/dt.js', array('jquery'));
		// wp_enqueue_script('datatable.cf', plugin_dir_url(__FILE__).'assets/dt.columnFilter.js', array('datatable'));
		wp_enqueue_script('datatable.tt', plugin_dir_url(__FILE__).'assets/dt.tableTools.js', array('datatable'));
		wp_enqueue_script('datatable.rg', plugin_dir_url(__FILE__).'assets/dt.rowGrouping.js', array('datatable'));
		wp_enqueue_script('datatable.tg', plugin_dir_url(__FILE__).'assets/dt.treeGrid.js', array('datatable'));
		// wp_enqueue_script('datatable.cr', plugin_dir_url(__FILE__).'assets/dt.colRR.js', array('datatable'));
		wp_enqueue_script('datatable.init', plugin_dir_url(__FILE__).'assets/init.js', array('datatable'));
		wp_enqueue_style('datatable', plugin_dir_url(__FILE__).'assets/theme.css');
		
	//add_action('wp_enqueue_scripts', 'add_datatable_scripts');


  if ( ! cases_datatable_is_ajax_request() ) {
    $raw_params = $params;
    $params = cases_datatable_prepare_params( $params );
  }

  // prepare fields
  $fields = cases_datatable_prepare_fields( $params );

  // prepare table params
  $datatable = array();
  if(isset($params['view'])) foreach(array_filter(explode(",", $params['view'])) as $v){
    $v = explode(":", trim($v));
    if(strlen($v[0])) $datatable[$v[0]] = $v[1];
  }
  if(!isset($datatable['id']) || !strlen($datatable['id'])) $datatable['id'] = "dt_".substr(md5(json_encode($params)), 0, 8);
  if(isset($params['title'])) $datatable['title'] = $params['title'];
  $datatable['group'] = array(); if(isset($params['group'])) foreach(array_filter(explode(",", $params['group'])) as $group){
    $group = trim($group);
    if(isset($fields[$group])) $datatable['group'][] = $group;
  }
  $datatable['tree'] = array(); if(isset($params['tree'])) foreach(array_filter(explode(",", $params['tree'])) as $tree){
    $tree = explode(":", trim($tree));
    if(isset($fields[$tree[0]]) && isset($fields[$tree[1]])) $datatable['tree'][$tree[0]] = $tree[1];
  }
  $datatable['filters'] = array(); if(isset($params['filters'])) foreach(array_filter(explode(";", $params['filters'])) as $f){
    $filter = array();
    foreach(array_filter(explode(",", $f)) as $fi){
      $fi = explode(":", trim($fi));
      if(isset($fi[1]) && isset($fields[$fi[0]])) $filter[array_search($fi[0], array_keys($fields))] = $fi[1];
    }
    if(count($f)) $datatable['filters'][] = array($f, $filter);
  }

  $datatable = apply_filters('cases_datatable_view', $datatable);
  $posts = array();

  // get data. Min real SQL query length is 15: "select * from t"
  if ( strlen( $sql ) > 10 )
    $posts = get_data_from_sql( $sql, $datatable['sql'] == 'validate', $params['base'] );
  elseif ( cases_datatable_is_ajax_request() || ! $params['server-side'] )
    $posts = get_data_from_wpquery( $params );

  $posts = apply_filters('cases_datatable_data', $posts);
  $posts = apply_filters('cases_datatable_posts', $posts, $sql);

  if ( ! cases_datatable_is_ajax_request() ) {
    // render template
    include('datatable_template.php');
  }

  return $posts;
}

function cases_datatable_prepare_params( $params ) {
  // get additional sources
  // foreach((array)$_POST['dt'] as $key=>$param) $params[$key] = $param.", ".$params[$key];
  if(isset($params['src'])) foreach(array_filter(explode(",", $params['src'])) as $src){
    switch(trim($src)){
      case 'request':
        $params = array_merge($params, (array)$_REQUEST);
        break;
      case 'global':
        global $post;
        $tax = get_query_var('taxonomy'); $term = intval(get_query_var('term_id'));
        if(strlen($tax)>0 && $term>0) $params['tax'] = "$tax:$term, ".$params['tax'];
        else $params['parent']=$post->ID;
        break;
    }
  }

  // add default filter
  if(isset($_GET['dt_state'])) switch($_GET['dt_state']){
    case 'open':
      $params['tax'] = "results:NONE, ".$params['tax'];
      break;
    case 'close':
      $params['tax'] = "results:ALL, ".$params['tax'];
      break;
    case 'all':
      // echo $params['tax'];
      if(!isset($params['tax'])) $params['tax'] = '';
      break;
  }

  if(!isset($params['fields']) || !strlen($params['fields'])) $params['fields'] = "ID:link, post_title:link, initiator:post, responsible:post, prioritet:int, date_deadline:date, date_end:date, state:tax, results:tax, functions:tax, post_date:date, post_parent:post";
  if(!isset($params['titles'])) $params['titles'] = ''; $params['titles'] = "object:ID объекта, relise:ID релиза, post_title:Заголовок, initiator:Инициатор, responsible:Ответственный, prioritet:Приоритет, date_deadline:Дедлайн, date_end:Дата закрытия, state:Статус, results:Результат, functions:Функции, post_date:Дата публикации, post_parent:Родитель,".$params['titles'];
  if(!isset($params['sort']) || !strlen($params['sort'])) $params['sort'] = 'ID:desc';

  $params['server-side'] = ( ! isset( $params['server-side'] ) || 'false' !== $params['server-side'] );

  return $params;
}

function cases_datatable_prepare_fields( $params ) {
  $fields = array();
  foreach(array_filter(explode(",", $params['fields'])) as $field){
    $field = explode(":", trim($field));
    if(strlen($field[0])){
      if(!isset($field[1])) $field[1]='';
      $fields[$field[0]] = array('title'=>$field[0], 'type'=>$field[1]);
      switch($field[1]){
        case 'select':
        case 'cbox':
          $fields[$field[0]]['values'] = get_terms($field[0], array('fields'=>'names'));
          break;
      }
    } 
  }
  foreach(array_filter(explode(",", $params['titles'])) as $title){
    $title = explode(":", trim($title));
    if(isset($fields[$title[0]])) $fields[$title[0]]['title'] = $title[1];
  }
  if(isset($params['filter'])) foreach(array_filter(explode(",", $params['filter'])) as $filter){
    $filter = explode(":", trim($filter));
    if(isset($fields[$filter[0]])) $fields[$filter[0]]['filter'] = $filter[1];
  }
  foreach(array_filter(explode(",", $params['sort'])) as $sort){
    $sort = explode(":", trim($sort));
    if(isset($fields[$sort[0]])) $fields[$sort[0]]['sort'] = $sort[1];
  }

  return apply_filters('cases_datatable_fields', $fields);
}

function get_data_from_sql($sql, $validate=false, $dbname=null){
  if(!preg_match("/\b(select|show)\b/i", $sql)){
    echo "USE SELECT QUERY PLEASE";
    return array();
  }
  if(preg_match("/\b(insert|update|delete|drop|truncate|repair|optimize)\b/i", $sql)){
    echo "ONLY SELECT QUERY ALLOWED";
    return array();
  }

  global $config_databases;
  $conf = array('user'=>DB_USER, 'password'=>DB_PASSWORD, 'base'=>DB_NAME, 'host'=>DB_HOST);
  if(isset($dbname)){
    foreach($config_databases[$dbname] as $k=>$v) $conf[$k]=$v;
    $conf['base'] = $dbname;
  }
  $dtdb = new wpdb($conf['user'], $conf['password'], $conf['base'], $conf['host']);

  foreach($_GET as $k=>$v) if(is_string($v) && strlen($v)>0) $sql=str_replace("%$k%", mysql_real_escape_string($v), $sql);
  $sql = apply_filters('cases_datatable_sql', $sql);

  $posts = array();
  if($validate) $sql = $dtdb->prepare($sql);
  $res = $dtdb->get_results($sql);
  foreach($res as $p){
    $p->metas = array();
    $p->terms = array();
    $posts[] = $p;
  }
  return $posts;
}

function get_data_from_wpquery(&$params){
  global $wpdb;

  $args = array(
    'posts_per_page'=>-1,
    'post_status'=>'publish',
    'tax_query'=>array('relation'=>'AND'),
    'meta_query'=>array('relation'=>'AND'),
  );
  if(isset($params['limit'])) $args['posts_per_page'] = intval($params['limit']);
  if(isset($params['offset'])) $args['offset'] = intval($params['offset']);
  if(isset($params['search'])) $args['s'] = $params['search'];
  if(isset($params['order'])) $args['order'] = $params['order'];
  if(isset($params['orderby'])) $args['orderby'] = $params['orderby'];
  if(isset($params['meta_key'])) $args['meta_key'] = $params['meta_key'];

  $types = array();
  if(!isset($params['type'])) $params['type']='cases';
  if('any' == $params['type']) {
    $args['post_type'] = $params['type'];
  } else {
    foreach(array_filter(explode(",", $params['type'])) as $type) $types[]=trim($type);
    if(count($types)) $args['post_type'] = $types;
  }

  $statuses = array();
  if(isset($params['status'])) foreach(array_filter(explode(",", $params['status'])) as $status) $statuses[]=trim($status);
  if(count($statuses)) $args['post_status'] = $statuses;

  if(isset($params['parent']) && strlen($params['parent'])){
    $pids = array(); foreach($types as $type) foreach(get_pages(array('child_of'=>$params['parent'], 'post_type'=>$type)) as $p) $pids[]=$p->ID;
    if(count($pids)) $args['post__in'] = $pids;
    else $args['post_parent'] = $params['parent'];
  }

  $taxes = array(); if(!isset($params['tax'])) $params['tax']='results:NONE';
  foreach(array_filter(explode(",", $params['tax'])) as $tax){
    $tax = explode(":", trim($tax));
    if(strlen($tax[0])==0) continue;
    if(isset($taxes[$tax[0]])) $currtax = $taxes[$tax[0]];
    else $currtax = array('taxonomy'=>$tax[0], 'field'=>'id', 'terms'=>array());
    switch($tax[1]){
      case '':
      case '-':
      case 'NONE':
        $currtax['operator'] = 'NOT IN';
        foreach(get_terms($tax[0]) as $t) $currtax['terms'][] = $t->term_id;
        break;
      case 'ALL':
      case 'ANY':
        $currtax['operator'] = 'IN';
        foreach(get_terms($tax[0]) as $t) $currtax['terms'][] = $t->term_id;
        break;
      default:
        $currtax['terms'][] = $tax[1];
        // foreach(get_terms($tax[0], "child_of=".$tax[1]) as $t) $currtax['terms'][] = $t->term_id;
    }
    $taxes[$tax[0]] = $currtax;
  }
  foreach($taxes as $tax){
    $tax['terms'] = array_unique($tax['terms']);
    $args['tax_query'][] = $tax;
  }

  $metas = array(); if(isset($params['meta'])) foreach(array_filter(explode(",", $params['meta'])) as $meta){
    $meta = explode(":", trim($meta));
    if(isset($meta[1])) $metas[$meta[0]] = $meta[1];
  }
  foreach($metas as $k=>$v) $args['meta_query'][] = array('key'=>$k, 'compare'=>'LIKE', 'value'=>$v);

  $args = apply_filters('cases_datatable_args', $args);

  $posts = array();
  if($args['posts_per_page']!=0){
   
 /* $args['cache_results'] = true;
  $args['update_post_meta_cache'] = true;
  $args['update_post_term_cache'] = true;*/

	//add_filter( 'posts_where' , 'allow_ids' );
	
    $query = new WP_Query($args);
	//remove_action( 'pre_get_posts', 'acl_restrict_queries' );
//	remove_filter( 'posts_where' , 'allow_ids' );
	
		//anton fix
	/*	$c_i = count($query->posts);
		$old_count = $args['posts_per_page'];
		if (count($query->posts)<$args['posts_per_page'])
		while ($c_i<$old_count)
		{
			$args['posts_per_page'] = $args['posts_per_page']+10;
			$query = new WP_Query($args);
			$c_i = count($query->posts);
		}*/
	//end
	
	
	
	
    foreach($query->posts as $p){
      $p->metas = array();
      $p->terms = array();
      $posts[$p->ID] = $p;
    }
    $GLOBALS['cases_datatable_found_posts'] = $query->found_posts;
  }
  if(count($posts)){
    $posts_ids = implode(',', array_keys($posts));
    $metas = $wpdb->get_results("SELECT pm.* FROM $wpdb->postmeta pm
      WHERE pm.post_id IN ( $posts_ids )
    ");
    $terms = $wpdb->get_results("SELECT t.*, tt.*, tr.object_id FROM $wpdb->terms t
      LEFT JOIN $wpdb->term_taxonomy tt ON ( tt.term_id = t.term_id )
      LEFT JOIN $wpdb->term_relationships tr ON ( tr.term_taxonomy_id = tt.term_taxonomy_id )
      WHERE tr.object_id IN ( $posts_ids )
    ");
    foreach($metas as $meta) $posts[$meta->post_id]->metas[$meta->meta_key]=$meta;
    foreach($terms as $term) $posts[$term->object_id]->terms[$term->taxonomy]=$term;
  }
  return $posts;
}





function echo_datatable_head($fields){
  $fields = apply_filters('cases_datatable_head', $fields);
  echo "<tr>";
  foreach($fields as $k=>$v) echo "<th>".$v['title']."</th>";
  echo "</tr>";
}
function echo_datatable_foot($fields){
  $fields = apply_filters('cases_datatable_foot', $fields);
  echo "<tr>";
  foreach($fields as $k=>$v) echo "<th>".$v['title']."</th>";
  echo "</tr>";
}
function echo_datatable_row($fields, $p){
  $fields = apply_filters('cases_datatable_row', $fields);
  $class = ( isset( $p->terms['results'] ) ) ? 'closed' : '';
  echo "<tr class='$class'>";
  foreach($fields as $k=>$v){
    if(property_exists($p, $k)) $value = $p->$k;
    else if(isset($p->terms[$k])) $value = $p->terms[$k]->name;
    else if(isset($p->metas[$k])) $value = $p->metas[$k]->meta_value;
    else $value = '';

    $value = apply_filters('cases_datatable_value', $value, $k, $v, $p);
    if(!strlen($value)) $value = '---';

    $class = esc_attr( 'type-' . $k );
    echo "<td class='$class'>$value</td>";
  }
  echo "</tr>";
}

function cases_datatable_value($value, $k, $v, $p){
  switch($v['type']){
    case 'cbox':
      $value = "<input type='checkbox' value='{$p->ID}' name='post[]' id='cb-select-{$p->ID}'>";
      break;
    case 'date':
      // date convertation needed
      break;
    case 'link':
      $link = get_permalink($p->ID);
      $value = "<a href='$link'>$value</a>";
      break;
	case 'serialize_org':
     // $link = get_permalink($p->ID);
	if ($value == '66') $ser_mas[0] = 66;
	if ($value != '66') $ser_mas = unserialize($value);	 
	if ($ser_mas > 0)
	{
		$value='';	
		$link = get_permalink($ser_mas[0]);
		$post_idd = get_post($ser_mas[0], ARRAY_A);  
		$title = $post_idd['post_title'];
		if(is_string($link)) $value = "<a href='$link'>$title</a><br/>";
		//$value.=$ser_mas[0].'<br/>';	
		 // $value =$value;
	}
     break;
    case 'post':
      $pid = intval($value);
      if($pid>0){
        $post = get_post($pid);
        $link = get_permalink($pid);
        if(is_string($link)) $value = "<a href='$link'>$post->post_title</a>";
      }
      break;
    case 'type':
      $post_type_object = get_post_type_object($p->post_type);
      if ($post_type_object)
		$value = $post_type_object->labels->singular_name;
      break;
    case 'tax':
      $terms = array();
      $taxterms = get_the_terms($p->ID, $k); if(is_array($taxterms)) foreach($taxterms as $term) $terms[]= "<a href='".get_term_link($term->slug, $k)."'>$term->name</a>";
      $value = implode(', ', $terms);
      break;
	case 'spers':
		$pid = unserialize($value);
		if ($pid>0){
			 $post = get_post($pid[0]);
			$link = get_permalink($pid[0]);
			if(is_string($link)) $value = "<a href='$link'>$post->post_title</a>";
		}
	break;
	case 'filial':
		$org_unit = get_post_meta(intval($value),'unit_id',true);
		$separate = get_post_meta(intval($org_unit),'separate_org_unit',true);
		$value = get_the_title($separate);
	break;
	case 'respo':
		$pid = intval(get_post_meta($value,'responsible',true));
		$post = get_post($pid);
        	$link = get_permalink($pid);
		if(is_string($link)) $value = "<a href='$link'>$post->post_title</a>";
	break;
	
	case 'employe':
		if ($value != 'Требуется риелтор') $value = get_post_meta($p->ID,'find_employee_position',true);
	break;
  }
  return $value;
} add_filter('cases_datatable_value', 'cases_datatable_value', 10, 4);

function cases_datatable_args($args){
  foreach($args['meta_query'] as &$meta) if(is_array($meta)) switch($meta['key']){
    case "initiator":
    case "responsible":
      $meta['compare'] = '=';
      break;
  }
  return $args;
} add_filter('cases_datatable_args', 'cases_datatable_args', 10);

function cases_datatable_is_ajax_request() {
	return ( isset( $_REQUEST['action'] ) && 'cases_datatable_server_processing' === $_REQUEST['action'] );
}

function cases_datatable_get_post_value( $post, $key, $field_data ) {
	if ( property_exists( $post, $key ) )
		$dt_value = $post->$key;
	elseif ( isset( $post->terms[ $key ] ) )
		$dt_value = $post->terms[ $key ]->name;
	elseif ( isset( $post->metas[ $key ] ) )
		$dt_value = $post->metas[ $key ]->meta_value;
	else
		$dt_value = '';

	return apply_filters( 'cases_datatable_value', $dt_value, $key, $field_data, $post );
}

function cases_datatable_get_ajax_row( $fields, $post ) {
	$row = array();

	$fields = apply_filters( 'cases_datatable_row', $fields );
	$row['DT_RowClass'] = ( isset( $post->terms['results'] ) ) ? 'closed' : '';

	foreach ( $fields as $key => $field_data ) {
		$dt_value = cases_datatable_get_post_value( $post, $key, $field_data );
		if ( ! strlen( $dt_value ) )
			$dt_value = '---';
		$row[] = $dt_value;
	}

	return $row;
}

function cases_datatable_get_sort_columns( $fields ) {
	$request = stripslashes_deep( $_REQUEST );

	$sort_columns = array();
	$sort_column_numbers = array();

	for ( $i = 0; $i < intval( $request['iSortingCols'] ); $i++ ) {
		if ( isset( $request[ "iSortCol_$i" ] ) && isset( $request[ "sSortDir_$i" ] ) )
			$sort_column = array( 'column_number' => intval( $request[ "iSortCol_$i" ] ), 'order' => $request[ "sSortDir_$i" ] );
			if ( ! in_array( $sort_column['column_number'], $sort_column_numbers ) ) {
				$sort_column_numbers[] = $sort_column['column_number'];
				$sort_columns[] = $sort_column;
			}
	}

	foreach ( $fields as $key => $field_data ) {
		if ( isset( $field_data['sort'] ) ) {
			$sort_column = array( 'column_number' => array_search( $key, array_keys( $fields ) ), 'order' => $field_data['sort'] );
			if ( ! in_array( $sort_column['column_number'], $sort_column_numbers ) ) {
				$sort_column_numbers[] = $sort_column['column_number'];
				$sort_columns[] = $sort_column;
			}
		}
	}

	return $sort_columns;
}

function cases_datatable_sort_results( $posts, $fields, $sort_columns ) {
	if ( empty( $sort_columns ) )
		return $posts;
	add_filter( 'cases_datatable_enable_links_in_values', '__return_false' );
	$compare_function = sprintf( '
		$fields = %1$s;
		$field_keys = array_keys( $fields );
		$field_values = array_values( $fields );

		$sort_columns = %2$s;

		$result = 0;

		foreach ( $sort_columns as $sort_column ) {
			$column_number = $sort_column["column_number"];

			$a_value = strip_tags( cases_datatable_get_post_value( $a, $field_keys[ $column_number ], $field_values[ $column_number ] ) );
			$b_value = strip_tags( cases_datatable_get_post_value( $b, $field_keys[ $column_number ], $field_values[ $column_number ] ) );

			if ( $a_value != $b_value ) {
				if ( "desc" === strtolower( $sort_column["order"] ) ) {
					$result = ( $a_value > $b_value ) ? -1 : 1;
					break;
				} else {
					$result = ( $a_value < $b_value ) ? -1 : 1;
					break;
				}
			}
		}

		return $result;
		', var_export( $fields, true ), var_export( $sort_columns, true ) );

	uasort( $posts, create_function( '$a, $b', $compare_function ) );

	return $posts;
}

function cases_datatable_filter_results( $posts, $fields, $search_string ) {
	preg_match_all( '/".*?("|$)|((?<=[\r\n\t ",+])|^)[^\r\n\t ",+]+/', $search_string, $matches );
	$search_terms = array_map( '_search_terms_tidy', $matches[0] );

	$fields_to_search = array( 'post_title', 'initiator', 'responsible', 'functions', 'post_date' );
	$fields_to_search = apply_filters( 'cases_datatable_fields_to_search', $fields_to_search );

	add_filter( 'cases_datatable_enable_links_in_values', '__return_false' );

	foreach ( $search_terms as $term ) {
		foreach ( $posts as $post_id => $post ) {
			$found_term = false;

			foreach ( $fields as $key => $field_data ) {
				if ( ! in_array( $key, $fields_to_search ) )
					continue;

				$dt_value = cases_datatable_get_post_value( $post, $key, $field_data );

				if ( false !== mb_stripos( $dt_value, $term ) ) {
					$found_term = true;
					break;
				}
			}

			if ( ! $found_term )
				unset( $posts[ $post_id ] );
		}
	}

	remove_filter( 'cases_datatable_enable_links_in_values', '__return_false' );

	return $posts;
}

function cases_datatable_server_processing() {
	$request = stripslashes_deep( $_REQUEST );

	$params = ( isset( $request['dt_params'] ) ) ? (array) json_decode( $request['dt_params'] ) : array();
	$params = cases_datatable_prepare_params( $params );

	$fields = cases_datatable_prepare_fields( $params );
	$field_keys = array_keys( $fields );

	$sort_in_wp_query = false;
	if ( ! empty( $request['iSortingCols'] ) && empty( $request['sSearch'] ) ) {
		$sort_columns = cases_datatable_get_sort_columns( $fields );
		$primary_sort_column = $sort_columns[0];

		$fields_to_sort_in_wp_query = array( 'ID', 'post_title', 'post_date' );
		$fields_to_sort_in_wp_query = apply_filters( 'cases_datatable_fields_to_sort_in_wp_query', $fields_to_sort_in_wp_query );

		$orderby = $field_keys[ $primary_sort_column['column_number'] ];
		if ( in_array( $orderby, $fields_to_sort_in_wp_query )  ) {
			$sort_in_wp_query = true;
			$sort_columns = array_slice( $sort_columns, 1 );

			$params['order'] = $primary_sort_column['order'];
			$params['orderby'] = str_replace( 'post_', '', $orderby );

			if ( ! empty( $request['iDisplayStart'] ) )
				$params['offset'] = intval( $request['iDisplayStart'] );
			if ( ! empty( $request['iDisplayLength'] ) && -1 != $request['iDisplayLength'] )
				$params['limit'] = intval( $request['iDisplayLength'] );
		}
	}

	$posts = datatable_generator( $params, null );

	$total_records = ( $sort_in_wp_query ) ? $GLOBALS['cases_datatable_found_posts'] : count( $posts );
	$total_display_records = $total_records;

	if ( ! empty( $request['sSearch'] ) ) {
		$posts = cases_datatable_filter_results( $posts, $fields, $request['sSearch'] );
		$total_display_records = count( $posts );
	}

	if ( ! empty( $request['iSortingCols'] ) )
		$posts = cases_datatable_sort_results( $posts, $fields, $sort_columns );

	if ( ! $sort_in_wp_query ) {
		if ( ! empty( $request['iDisplayStart'] ) )
			$posts = array_slice( $posts, intval( $request['iDisplayStart'] ), null, true );

		if ( ! empty( $request['iDisplayLength'] ) && -1 != $request['iDisplayLength'] )
			$posts = array_slice( $posts, 0, intval( $request['iDisplayLength'] ), true );
	}

	$output = array(
		'sEcho' => intval( $request['sEcho'] ),
		'iTotalRecords' => $total_records,
		'iTotalDisplayRecords' => $total_display_records,
		'aaData' => array(),
	);

	foreach ( $posts as $post )
		$output['aaData'][] = cases_datatable_get_ajax_row( $fields, $post );

	die( json_encode( $output ) );
}
add_action( 'wp_ajax_cases_datatable_server_processing', 'cases_datatable_server_processing' );
