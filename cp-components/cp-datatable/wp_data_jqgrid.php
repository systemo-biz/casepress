<?php
function wp_data_jqgrid($params){
  global $wpdb, $post;
  $postID = $_POST['postID'];
  $page = $_POST['page'];
  $limit = $_POST['rows'];
  $sidx = isset($_POST['sidx']) ? $_POST['sidx'] : 1;
  $sord = $_POST['sord'];
  $search = $_POST['_search'];
  $posttype = $_GET['posttype'];
  $tax_slug = $_GET['tax_slug'];
  $tax_ids = $_GET['tax_id'];
  $status = $_GET['status'];
  $fields = explode(',', $_GET['fields']);
  $id_object = $_GET['id_object'];
  $parent = $_GET['parent'];

  // Get child terms
  $tid_arr = array();
  foreach(explode(",", $tax_ids) as $tid){
    $tid_arr[]=$tid;
    foreach(get_terms($tax_slug, "child_of=$tid") as $t) $tid_arr[]=$t->term_id;
  }
  $t_id = implode(",", array_filter(array_unique($tid_arr)));

  // Count posts and get navigation
  if($id_object) $count = gf_count_cases($id_object,$posttype);
  else $count = wp_count_posts($posttype)->publish;
  if($count>0 && $limit>0) $total_pages = ceil($count/$limit);
  else $total_pages = 0;

  if($page > $total_pages) $page=$total_pages;
  $start = $limit*$page - $limit; if($start<0) $start = 0;

  // Start of data
  $s = "<?xml version='1.0' encoding='utf-8'?>
  <rows>
    <page>$page</page>
    <total>$total_pages</total>
    <records>$count</records>
  ";

  // Get data
  switch ($sidx){
    case 'responsible':
					$query = "SELECT *,
							(SELECT TRIM($wpdb->posts.post_title) FROM $wpdb->posts WHERE $wpdb->posts.ID IN($wpdb->postmeta.meta_value)) AS 'repe'
							FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->postmeta.meta_key = 'responsible')
							LEFT JOIN $wpdb->term_relationships
								ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy
								ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE $wpdb->posts.post_type = '$posttype'
							AND $wpdb->posts.post_status = 'publish'
							AND term_id IN ($t_id)
							AND taxonomy = '$tax_slug'
							AND $wpdb->posts.ID = $wpdb->postmeta.post_id
							GROUP BY $wpdb->posts.ID
							ORDER BY repe $sord
							LIMIT $start, $limit";
					break;
    case 'initiator':
					$query = "SELECT *,
							(SELECT TRIM($wpdb->posts.post_title) FROM $wpdb->posts WHERE $wpdb->posts.ID IN($wpdb->postmeta.meta_value)) AS 'repe'
							FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->postmeta.meta_key = 'initiator')
							LEFT JOIN $wpdb->term_relationships
								ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy
								ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE $wpdb->posts.post_type = '$posttype'
							AND $wpdb->posts.post_status = 'publish'
							AND term_id IN ($t_id)
							AND taxonomy = '$tax_slug'
							AND $wpdb->posts.ID = $wpdb->postmeta.post_id
							GROUP BY $wpdb->posts.ID
							ORDER BY repe $sord
							LIMIT $start, $limit";
					break;
    case 'participant':
					$query = "SELECT *,
							(SELECT TRIM($wpdb->posts.post_title) FROM $wpdb->posts WHERE $wpdb->posts.ID IN($wpdb->postmeta.meta_value)) AS 'repe'
							FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->postmeta.meta_key = 'participant')
							LEFT JOIN $wpdb->term_relationships
								ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy
								ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE $wpdb->posts.post_type = '$posttype'
							AND $wpdb->posts.post_status = 'publish'
							AND term_id IN ($t_id)
							AND taxonomy = '$tax_slug'
							AND $wpdb->posts.ID = $wpdb->postmeta.post_id
							GROUP BY $wpdb->posts.ID
							ORDER BY repe $sord
							LIMIT $start, $limit";
					break;
    case 'prioritet':
					$query = "SELECT *
							FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->postmeta.meta_key = 'prioritet')
							LEFT JOIN $wpdb->term_relationships
								ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy
								ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE $wpdb->posts.post_type = '$posttype'
							AND $wpdb->posts.post_status = 'publish'
							AND term_id IN ($t_id)
							AND taxonomy = '$tax_slug'
							AND $wpdb->posts.ID = $wpdb->postmeta.post_id
							GROUP BY $wpdb->posts.ID
							ORDER BY $wpdb->postmeta.meta_value $sord
							LIMIT $start, $limit";
					break;
    case 'date_end':
					$query = "SELECT *
							FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->postmeta.meta_key = 'date_end')
							LEFT JOIN $wpdb->term_relationships
								ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy
								ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE $wpdb->posts.post_type = '$posttype'
							AND $wpdb->posts.post_status = 'publish'
							AND term_id IN ($t_id)
							AND taxonomy = '$tax_slug'
							AND $wpdb->posts.ID = $wpdb->postmeta.post_id
							GROUP BY $wpdb->posts.ID
							ORDER BY $wpdb->postmeta.meta_value $sord
							LIMIT $start, $limit";
					break;
    case 'date_deadline':
					$query = "SELECT *
							FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->postmeta.meta_key = 'date_deadline')
							LEFT JOIN $wpdb->term_relationships
								ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy
								ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE $wpdb->posts.post_type = '$posttype'
							AND $wpdb->posts.post_status = 'publish'
							AND term_id IN ($t_id)
							AND taxonomy = '$tax_slug'
							AND $wpdb->posts.ID = $wpdb->postmeta.post_id
							GROUP BY $wpdb->posts.ID
							ORDER BY $wpdb->postmeta.meta_value $sord
							LIMIT $start, $limit";
					break;
    case 'state':
					$query = "SELECT * 
							FROM $wpdb->posts
							LEFT JOIN $wpdb->term_relationships ON ( $wpdb->posts.ID = $wpdb->term_relationships.object_id ) 
							LEFT JOIN $wpdb->term_taxonomy ON ( $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id ) 
							LEFT JOIN $wpdb->terms ON ( $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ) 
							WHERE post_status = 'publish'
							AND post_type = '$posttype'
							AND taxonomy = 'state'
							GROUP BY $wpdb->posts.ID
							ORDER BY name $sord 
							LIMIT $start, $limit";
					break;
    case 'functions':
					$query = "SELECT * 
							FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID=$wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key='date_end')
							LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID=$wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id=$wpdb->term_taxonomy.term_taxonomy_id)
							LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id=$wpdb->terms.term_id)
							WHERE post_status='publish' AND post_type = '$posttype'";
					if($_GET['tax_id']!=0) $query .= " AND term_id IN($t_id) AND taxonomy='$tax_slug'";
          // if($status=='open') $query .= " AND (meta_value='' OR meta_value IS NULL)"; // WHAT IS IT????
					$query .= " GROUP BY $wpdb->posts.ID ORDER BY name $sord LIMIT $start, $limit";
					break;
    default:
					$query = "SELECT * FROM $wpdb->posts
							LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID=$wpdb->postmeta.post_id)
							LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID=$wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id=$wpdb->term_taxonomy.term_taxonomy_id)
							WHERE post_status='publish' AND post_type='$posttype'";
					if($id_object) $query .= " AND meta_key='object' AND meta_value='$id_object'";
          if($parent) $query .= " AND parent_id='$parent'";
					if($_GET['tax_id']!=0) $query .= " AND term_id IN($t_id) AND taxonomy='$tax_slug'";
          // if($status=='open') $query .= " AND (meta_value='' OR meta_value IS NULL)"; // WHAT IS IT????
					$query .= " GROUP BY $wpdb->posts.ID ORDER BY $sidx $sord LIMIT $start, $limit";
	}


  // if($id_object){
  //   echo($wpdb->prepare($query));
  //   print_r($wpdb->get_results($wpdb->prepare($query)));
  // }
  // Set and get data fields
	foreach($wpdb->get_results($wpdb->prepare($query)) as $item){
		$a = 0;
		$search_op = 'cn';
		if(isset($_POST['_search']) && $_POST['_search'] == 'true'){
			$search_op = $_POST['searchOper'];
			$getop_what = $_POST['searchString'];
			switch ($_POST['searchField']){
				case 'id':
							$getop_where = $item->ID;
							break;
				case 'post_title':
							$getop_where = $item->post_title;
							break;
				case 'responsible':
							$re_ps = get_post_meta($item->ID,'responsible', true);
							$rp = explode(',', $re_ps);
							foreach ($rp as $k => $person)
								$rp[$k] = get_the_title($person);
							$responsible_persons = implode(', ', $rp);
							$getop_where = $responsible_persons;
							break;
				case 'initiator':
							$init = get_post_meta($item->ID,'initiator', true);
							$in = explode(',', $init);
							foreach ($in as $k => $initiat)
								$in[$k] = get_the_title($initiat);
							$initiator = implode(', ', $in);
							$getop_where = $initiator;
							break;
				case 'participant':
							$init = get_post_meta($item->ID,'participant', true);
							$in = explode(',', $init);
							foreach ($in as $k => $particip)
								$in[$k] = get_the_title($particip);
							$participant = implode(', ', $in);
							$getop_where = $participant;
							break;
				case 'date_deadline':
							$init = get_post_meta($item->ID,'date_deadline', true);
							$getop_where = $init;
							break;
				case 'date_end':
							$init = get_post_meta($item->ID,'date_end', true);
							$getop_where = $init;
							break;
				case 'prioritet':
							$prt = get_post_meta($item->ID,'prioritet', true);
							$pr = explode(',', $prt);
							foreach ($pr as $k => $rior)
								$pr[$k] = get_the_title($prior);
							$prioritet = implode(', ', $pr);
							$getop_where = $prioritet;
							break;
				case 'functions':
							$category_terms = get_the_terms($item->ID,'functions');
							if(is_array($category_terms)){
								unset($cats);
								foreach ($category_terms as $k => $category)
									$cats[$k] = $category->name;
								$categories = implode(', ', $cats);
							}else unset($categories);
							$getop_where = $categories;
							break;
				case 'state':
							$category_terms = get_the_terms($item->ID,'state');
							if(is_array($category_terms)){
								unset($cats);
								foreach ($category_terms as $k => $category)
									$cats[$k] = $category->name;
								$categories = implode(', ', $cats);
							}else unset($categories);
							$getop_where = $categories;
							break;
				case 'post_date':
							$getop_where = $item->post_date;
							break;
			}
		}

		if (isset($_POST['id'])){
			$getop_what = $_POST['id'];
			$getop_where = $item->ID;
		}

		if (isset($_POST['post_title'])){
			$getop_what = $_POST['post_title'];
			$getop_where = $item->post_title;
		}

		if (isset($_POST['responsible'])){
			$re_ps = get_post_meta($item->ID,'responsible', true);
			$rp = explode(',', $re_ps);
			foreach ($rp as $k => $person)
				$rp[$k] = get_the_title($person);
			$responsible_persons = implode(', ', $rp);
			$getop_what = $_POST['responsible'];
			$getop_where = $responsible_persons;
		}

		if (isset($_POST['initiator'])){
			$init = get_post_meta($item->ID,'initiator', true);
			$in = explode(',', $init);
			foreach ($in as $k => $initiat)
				$in[$k] = get_the_title($initiat);
			$initiator = implode(', ', $in);
			$getop_what = $_POST['initiator'];
			$getop_where = $initiator;
		}

		if (isset($_POST['participant'])){
			$init = get_post_meta($item->ID,'participant', true);
			$in = explode(',', $init);
			foreach ($in as $k => $particip)
				$in[$k] = get_the_title($particip);
			$participant = implode(', ', $in);
			$getop_what = $_POST['participant'];
			$getop_where = $participant;
		}

		if (isset($_POST['date_deadline'])){
			$init = get_post_meta($item->ID,'date_deadline', true);
			$getop_what = $_POST['date_deadline'];
			$getop_where = $init;
		}

		if (isset($_POST['date_end'])){
			$init = get_post_meta($item->ID,'date_end', true);
			$getop_what = $_POST['date_end'];
			$getop_where = $init;
		}

		if (isset($_POST['prioritet'])){
			$prt = get_post_meta($item->ID,'prioritet', true);
			$pr = explode(',', $prt);
			foreach ($pr as $k => $rior)
				$pr[$k] = get_the_title($prior);
			$prioritet = implode(', ', $pr);
			$getop_what = $_POST['prioritet'];
			$getop_where = $prioritet;
		}

		if (isset($_POST['functions'])){
			$category_terms = get_the_terms($item->ID,'functions');
			if(is_array($category_terms)){
				unset($cats);
				foreach ($category_terms as $k => $category)
					$cats[$k] = $category->name;
				$categories = implode(', ', $cats);
			}
			else unset($categories);
			$getop_what = $_POST['functions'];
			$getop_where = $categories;
		}

		if (isset($_POST['state'])){
			$category_terms = get_the_terms($item->ID,'state');
			if(is_array($category_terms)){
				unset($cats);
				foreach ($category_terms as $k => $category)
					$cats[$k] = $category->name;
				$categories = implode(', ', $cats);
			}
			else unset($categories);
			$getop_what = $_POST['state'];
			$getop_where = $categories;
		}

		if (isset($_POST['post_date'])){
			$getop_what = $_POST['post_date'];
			$getop_where = $item->post_date;
		}

		if($getop_where || $getop_what){
			$search_op = (!$search_op) ? 'cn' : $search_op;
			$g = get_op($search_op, $getop_what, $getop_where);
			if(!$g) $a = 1;
		}

		if($a==1) continue;

		$id = '<cell><![CDATA[<a href="'.get_permalink($item->ID).'">' . $item->ID . '</a>]]></cell>';
		$post_title = '<cell><![CDATA[<a href="' .get_permalink($item->ID). '">' . $item->post_title . '</a>]]></cell>';
		$init = get_post_meta($item->ID,'initiator', true);
		$in = explode(',', $init);
		foreach ($in as $k => $initiat) $in[$k] = get_the_title($initiat);
		$initiators = implode(', ', $in);
		$initiator = "<cell>$initiators</cell>";

		$re_ps = get_post_meta($item->ID,'responsible', true);
		$rp = explode(',', $re_ps);
		foreach ($rp as $k => $person)
			$rp[$k] = get_the_title($person);
		$responsible_persons = implode(', ', $rp);
		$responsible = "<cell>$responsible_persons</cell>";

		$re_ps = get_post_meta($item->ID,'participant', true);
		$rp = explode(',', $re_ps);
		foreach ($rp as $k => $partic) $rp[$k] = get_the_title($partic);
		$participant = implode(', ', $rp);
		$participant = "<cell>$participant</cell>";

		$date_deadline = '<cell>' . substr(get_field('date_deadline',$item->ID),0,11) . '</cell>';
		$date_end = '<cell>' . substr(get_field('date_end',$item->ID),0,11) . '</cell>';

		$state_terms = get_the_terms($item->ID,'state');
		if(is_array($state_terms)){
			unset($status);
			foreach ($state_terms as $k => $state)
				$status[$k] = $state->name;
			$states = implode(', ', $status);
		}
		else unset($states);
		$state = "<cell>$states</cell>";

		$obj = implode(',',get_post_meta($item->ID,'object'));
		$massiv = explode(',', $obj);
			for( $i=0; $i<count($massiv); $i++){
				if($i>0){$s.= ', ';}
				$objs= get_the_title($massiv[$i]);
			}
		$objects = "<cell>$objs</cell>";

		$category_terms = get_the_terms($item->ID,$tax_slug);
		if(is_array($category_terms)){
			unset($cats);
			foreach ($category_terms as $k => $category)
				$cats[$k] = $category->name;
			$categories = implode(', ', $cats);
		}else unset($categories);
		$functions = "<cell>$categories</cell>";

		$pri = get_post_meta($item->ID, 'prioritet'); if(is_array($pri)) $pri = implode(',', $pri);
		$prioritet = "<cell>$pri</cell>";

		$post_date = "<cell>$item->post_date</cell>";

    $s .= "<row id='$item->ID'>";
      foreach($fields as $field) $s .= $$field;
    $s .= '</row>';
  }
  $s .= '</rows>';

  header("Content-type: text/xml;charset=utf-8");
  echo $s;
  exit;
} add_action('wp_ajax_nopriv_wp_data_jqgrid','wp_data_jqgrid');
  add_action('wp_ajax_wp_data_jqgrid','wp_data_jqgrid');



function get_op($op, $what, $where){
  $what = mysql_real_escape_string(mb_strtolower($what));
  $where = mysql_real_escape_string(mb_strtolower($where));
  switch ($op){
    case 'eq': $result = ($what == $where); break;
    case 'ne': $result = ($what != $where); break;
    case 'lt': $result = ($what > $where); break;
    case 'le': $result = ($what >= $where); break;
    case 'gt': $result = ($what < $where); break;
    case 'ge': $result = ($what <= $where); break;
    case 'cn': $result = stristr($where, $what); break;
    case 'nc': $result = !stristr($where, $what); break;
    default: $result = '';
  }
  return $result;
}

function gf_count_cases($id_object,$posttype){
  global $wpdb;
  $query = "
    SELECT COUNT(*) FROM $wpdb->posts
    LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID=$wpdb->postmeta.post_id)
    WHERE post_status='publish' AND post_type='$posttype' AND meta_key='object' AND meta_value='$id_object'";
  return $wpdb->get_var($wpdb->prepare($query));
}
