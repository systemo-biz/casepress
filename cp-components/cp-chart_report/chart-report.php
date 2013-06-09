<?php


require_once 'includes/Pivot.php';
require_once 'includes/functions.php';
require_once 'includes/metabox.php';


function add_chart_report_content($content){
  global $post;
  global $wpdb;
  if($post->post_type=='chart_report'){
	

	
	get_chart_report_content_new($post_id,$content);
	
	$additional_reports=get_post_meta($post->ID,'chart_report_additional_reports',true);
	if (strlen($additional_reports>0))
	{
		$additional_reports=explode(',',$additional_reports);
		foreach($additional_reports as $report)
		{
			echo '<hr/>';
			$p=get_post($report);
			echo '<h3>'.$p->post_title.'</h3>';
			get_chart_report_content_new($report,'');
		}
	}
	
	/*
	$param="http://".$_SERVER['HTTP_HOST']."/";
	echo '<br/><a href="'.$param.$post->ID.'" class="btn">Просмотреть отчет</a>';
	*/
	return $content;
  }
  return $content;
} 
add_filter('the_content', 'add_chart_report_content');





add_action('wp_ajax_update_report_values', 'update_report_values');
function update_report_values()
{
	global $wpdb;
	update_post_meta($_POST['post_id'],'chart_report_data','');
	get_chart_report_content_new($_POST['post_id'],'');

	die;

}


	add_action( 'wp_ajax_chart_report_get_persons_ajax', 'chart_report_get_persons_ajax' );
	function chart_report_get_persons_ajax()
	{
		$args = array(
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_type' => 'persons',
		);

		$posts = get_posts( $args );
		$results = array( );
		global $wpdb;
		$subs_table = 'cases_substitution';
		$subs_table = $wpdb->prefix . $subs_table;
		
		$user = get_current_user_id();
		$subs=$wpdb->get_results('SELECT replaceable FROM '.$subs_table.' where substitutional= '.$user.' and state= \'open\' and type = \'hierarchical\' ',ARRAY_A);
		
		foreach ( $posts as $post ) {
			
			$usr = get_user_by_person($post->ID);
			if( !current_user_can('cp_chart_report_filter') and !current_user_can('manage_options') )
			{
				if (in_array($usr, $subs))
				{
					$results[] = array(
						'id' => $post->ID,
						'text' => $post->post_title
					);
				}
			}
			else
			{
				$results[] = array(
						'id' => $post->ID,
						'text' => $post->post_title
					);
			}
		}
		die( json_encode( $results ) );
	}
	
	
	add_action( 'wp_ajax_chart_report_get_units_ajax', 'chart_report_get_units_ajax' );
	function chart_report_get_units_ajax()
	{
		$args = array(
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_type' => 'org_unit',
			'meta_key' => 'cp_post_org_unit_type',
			'meta_value' => 'unit'
		);
		

		$posts = get_posts( $args );
		$results = array( );
		foreach ( $posts as $post ) {
			
			
			$separate_org_unit = get_post_meta($post->ID,'separate_org_unit',true);
			$unit = get_post($separate_org_unit);
		/*	$cp_post_person_org_unit_head = get_post_meta($post->ID,'cp_post_person_org_unit_head',true);
			$results[] = array(
				'id' => $cp_post_person_org_unit_head,
				'text' => $post->post_title.'('.$unit->post_title.')'
			);*/
			
			$results[] = array(
				'id' => $post->ID,
				'text' => $post->post_title.'('.$unit->post_title.')'
			);
		}
		die( json_encode( $results ) );
	}




add_shortcode('get_chart_report','get_chart_report');
function get_chart_report($atts)
{
	return get_chart_report_content_new($atts['post_id'],$atts['content']);
}

function get_chart_report_content_new($post_id,$content)
{
	
	$post=get_post($id=$post_id);
	global $wpdb;	
	

	if($post->post_type=='chart_report')
	{
	
	
	
	$crd=get_post_meta($post->ID,'chart_report_source',true);
	//echo $crd;
	if ($crd=='acm')
	{
		$current_user = wp_get_current_user();
		$person = get_person_by_user($current_user->ID);
		$chart_report_filter = get_post_meta($post->ID,'chart_report_filter',true);
		

		
	}

	

	
	
	echo '<div id="charts_n">';

	
	$url_charts_scripts=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
	wp_enqueue_script('chart_js', $url_charts_scripts.'/js/chart.js', array('jquery'));
	wp_localize_script( 'chart_js', 'chart_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	
	
	
	$current_types=get_post_meta($post->ID,'chart_report_types',true);
	//if ($content == 'preview') 
	$current_debug=get_post_meta($post->ID,'chart_report_debug',true);
	
	$chart_report_source=get_post_meta($post->ID,'chart_report_source',true);
	$types_array=explode(',',$current_types); 
	
	$chart_report_data=chart_report_get_data($post);
	//echo $chart_report_data;
	//echo '1';
	$chart_report_data=unserialize($chart_report_data);
	$current_date=get_post_meta($post->ID,'chart_report_last_update',true);
	
//	else
//	{
		echo '<span style="cursor:default; font-size: 10px;">Данные акутальны на '.$current_date.'</span> &nbsp;';
		echo '<a style="cursor:pointer" id="'.$post->ID.'" class="update_chart_report_values">Обновить</a><br/>';
		
		
		
		
		
		
		
		
		
		
		
		$chart_report_select_filter=get_post_meta($post->ID,'chart_report_select_filter',true);
	if( current_user_can('cp_chart_report_filter') )
	if ($chart_report_select_filter=='1')
	{
		$cur_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		$pos = stripos($cur_url, '?');
		$url_not_get =substr_replace($cur_url, '', $pos, strlen($cur_url));
		$url_not_get.='?mod=true';
		
		
	/*	$get_filter_id=$_GET['filter_id'];
		$url_not_get=str_replace('&filter_id='.$get_filter_id,'',$cur_url);
		if (!isset($_GET['mod']))
		$url_not_get.='?mod=true';
		*/
	//	echo $url_not_get;
		//echo $url_not_get;
		//$usr = get_user_by_person(82);
	//	print_r ( get_substitution_by_user($usr));
		
		
		if ($chart_report_filter == 'отдел')
		{
			echo '<input type="hidden" name="chart_report_units" id="chart_report_units" value="' . $_GET['filter_id'] . '" style="width:300px" cur_url="'.$url_not_get.'" class="" >';
		
			?>
			<script>
				$.ajax({
					type: 'POST',
					url: '<?= admin_url( 'admin-ajax.php' )?>',
					data: {
						action: 'chart_report_get_units_ajax'

					},
					success: function(dt) {
						res = $.parseJSON(dt);
						$("#chart_report_units").select2({
							data: res
						});
						$("#chart_report_units").removeClass("select2-offscreen");
					},
					dataType: 'html'
				});
			
			</script>
			<?
		}
		else
		{
			echo '<input type="hidden" name="chart_report_persons" id="chart_report_persons" value="' . $_GET['filter_id'] . '" style="width:300px" cur_url="'.$url_not_get.'" class="" >';
			?>
			<script>
				$.ajax({
					type: 'POST',
					url: '<?= admin_url( 'admin-ajax.php' )?>',
					data: {
						action: 'chart_report_get_persons_ajax'

					},
					success: function(dt) {
						res = $.parseJSON(dt);
						$("#chart_report_persons").select2({
							data: res
						});
						$("#chart_report_persons").removeClass("select2-offscreen");
					},
					dataType: 'html'
				});
			
			</script>
			<?
		}
	}
		
		
		
		
		
		
		
		
		
		
		
		$keys=array();
		$chart_report_filter=get_post_meta($post->ID,'chart_report_filter',true);
		$chart_report_reverse=get_post_meta($post->ID,'chart_report_reverse',true);
	
		$chart_report_single=get_post_meta($post->ID,'chart_report_single',true);
		$single_value='';
	//	print_r ( $chart_report_data);
	//	echo '<br/><br/><br/><br/><br/><br/><br/>';
		

		if ($current_debug=='1')
		{
			echo 'После фильтрации';
			print_r ( $chart_report_data);
			echo '<br/>';
		}
		
		/* вычисление средних  */
		$series_last_line=get_post_meta($post->ID,'chart_report_series_last_line',true);
		if ($series_last_line=='1')
		{
			$all_data=chart_report_get_data($post);
			$all_data=unserialize($all_data);
			$chart_report_date_merge=get_post_meta($post->ID,'chart_report_date_merge',true);
			if (strpos($chart_report_date_merge,',')>0) 
			{
				$chart_report_date_merge=explode(',',$chart_report_date_merge); 
			
				foreach ($all_data as $k => &$ele) 
				{
					foreach ($ele as $key => $current)
					{
						if ($key == $chart_report_date_merge[0])
						{
							$add_elem=$current;
						}
						if ($key == $chart_report_date_merge[1])
						{
							if ($current==1) $month = 'Январь';
							if ($current==2) $month = 'Февраль';
							if ($current==3) $month = 'Март';
							if ($current==4) $month= 'Апрель';
							if ($current==5) $month = 'Май';
							if ($current==6) $month = 'Июнь';
							if ($current==7) $month = 'Июль';
							if ($current==8) $month = 'Август';
							if ($current==9) $month = 'Сентябрь';
							if ($current==10) $month = 'Октябрь';
							if ($current==11) $month = 'Ноябрь';
							if ($current==12) $month = 'Декабрь';
							$ele[$key]=$add_elem.'/ '.$month;
							$ele['pivot_id']='test';
						}
					}	
				}
			}
			
	
				$dt = Pivot::factory($all_data)
				->pivotOn(array('rieltor'))
				->addColumn(array('месяц'), array('количество'))
				->fullTotal()
				->fetch();
		foreach ($dt as &$r) {
				foreach ($r as &$i) {
					if ($i == '') $i=0;
				}
			}

	//	simpleHtmlTable($dt);	
			$sum_all=end($dt);
			$k=0;
			foreach ($sum_all as $key => $em)
			{
				if ($k==0 || $k==1)
					unset ($sum_all[$key]);
				$k++;
			}
		//	print_r ($sum_all);
			$count = count($dt)-2;
		//	echo '<h2>'.$count.'</h2>';
			
			$my_count_arr = Pivot::factory($chart_report_data)
				->pivotOn(array('rieltor'))
				->addColumn(array('месяц'), array('количество'))
				->fetch();
			foreach ($my_count_arr as &$rr) {
				foreach ($rr as &$i) {
					if ($i == '') $i=0;
				}
			}
		//	simpleHtmlTable($my_count_arr);
			$my_count = count($my_count_arr);
			//echo '<h2>'.$my_count.'</h2>';
			
			$all_avg=$sum_all;
			foreach ($all_avg as $key => $em)
			{
				$temp_param= round($em/$count*$my_count,2);
				$trans=array(","=>".");
				$temp_param=strtr($temp_param,$trans);
				$all_avg[$key] = $temp_param;
			}
			//print_r ($all_avg);
			
		}
		

		//слияние дат
		$chart_report_date_merge=get_post_meta($post->ID,'chart_report_date_merge',true);
		if (strpos($chart_report_date_merge,',')>0) 
		{
			$chart_report_date_merge=explode(',',$chart_report_date_merge); 
		
			foreach ($chart_report_data as $k => &$el) 
			{
				foreach ($el as $key => $current)
				{
					if ($key == $chart_report_date_merge[0])
					{
						$add_elem=$current;
					}
					if ($key == $chart_report_date_merge[1])
					{
						if ($current==1) $month = 'Январь';
						if ($current==2) $month = 'Февраль';
						if ($current==3) $month = 'Март';
						if ($current==4) $month= 'Апрель';
						if ($current==5) $month = 'Май';
						if ($current==6) $month = 'Июнь';
						if ($current==7) $month = 'Июль';
						if ($current==8) $month = 'Август';
						if ($current==9) $month = 'Сентябрь';
						if ($current==10) $month = 'Октябрь';
						if ($current==11) $month = 'Ноябрь';
						if ($current==12) $month = 'Декабрь';
						$el[$key]=$add_elem.'/ '.$month;
					}
					
				}	
			}
		}
		
		if ($current_debug=='1')
		{
			echo '<br/><br/><br/>';
			print_r ( $chart_report_data);
			echo '<br/>';
		}
		
		$chart_report_pivot=get_post_meta($post->ID,'chart_report_pivot',true);
		if ($chart_report_pivot==1)
		{
			$chart_report_pivot_row=get_post_meta($post->ID,'chart_report_pivot_row',true);
				if (strpos($chart_report_pivot_row,',')>0) {	
					$chart_report_pivot_row=explode(',',$chart_report_pivot_row); }
					else {
						$chart_report_pivot_row=array($chart_report_pivot_row);
					}
				
			$chart_report_pivot_col=get_post_meta($post->ID,'chart_report_pivot_col',true);
				if (strpos($chart_report_pivot_col,',')>0) {
					$chart_report_pivot_col=explode(',',$chart_report_pivot_col); }
					else {
						$chart_report_pivot_col=array($chart_report_pivot_col);
					}
				
			$chart_report_pivot_value=get_post_meta($post->ID,'chart_report_pivot_value',true);
				if (strpos($chart_report_pivot_value,',')>0) {
					$chart_report_pivot_value=explode(',',$chart_report_pivot_value); }
					else {
						$chart_report_pivot_value=array($chart_report_pivot_value);
					}
					
		/*		$chart_report_pivot_count=get_post_meta($post->ID,'chart_report_pivot_count',true);
				if (strpos($chart_report_pivot_count,',')>0) {
					$chart_report_pivot_value2=get_post_meta($post->ID,'chart_report_pivot_value',true);
					if ($chart_report_pivot_value2 == $chart_report_pivot_count)
					{
						$data = Pivot::factory($chart_report_data)
						->pivotMonth()
						->pivotOn($chart_report_pivot_row)
						->addColumn($chart_report_pivot_col, array(Pivot::count('rieltor')))
						->fetch();
					}
				} else
				{*/
//array(Pivot::count($chart_report_pivot_value2))
			$data = Pivot::factory($chart_report_data)
				->pivotMonth()
				->pivotOn($chart_report_pivot_row)
				->addColumn($chart_report_pivot_col, $chart_report_pivot_value)
				->fetch();
			//}
		
		
		
		$chart_report_pivot_null_replace=get_post_meta($post->ID,'chart_report_pivot_null_replace',true);
		if (strlen($chart_report_pivot_null_replace)<1) $chart_report_pivot_null_replace = 0;
		
			foreach ($data as &$row) {
				foreach ($row as &$item) {
					if ($item == '') $item=$chart_report_pivot_null_replace;
				}
			}
			
			$chart_report_data=$data;
		}
		

		
		if ($current_debug=='1')
		{
			echo 'После пивота';
			print_r ( $chart_report_data);
			echo '<br/>';
		}

		
				//удаление элемента если есть фильтрация, но выключен пивот, который сейчас удаляет элемент)
		if (($chart_report_pivot!=1)&&(strlen($chart_report_filter)>0))
		{
			foreach ($chart_report_data as $k => &$data) 
			{
				foreach ($data as $key => &$current)
				{
					if ($key == $chart_report_filter)
					{
						unset($data[$key]);
						break;
					}
				}
			}
		}
		
		if ($current_debug=='1')
		{
			echo 'После фильтрации, если выключен пивот';
			print_r ( $chart_report_data);
			echo '<br/>';
		}
		


		$current_convert_month=get_post_meta($post->ID,'chart_report_convert_month',true);
		
		if ($current_convert_month=='1')		
		foreach ($chart_report_data as &$ch_data) 
		{
			foreach ($ch_data as $key=>$current)
			{
				if ($key == 'месяц'||$key == 'Месяц')
				{
					if ($current==1) $ch_data[$key] = 'Январь';
					if ($current==2) $ch_data[$key] = 'Февраль';
					if ($current==3) $ch_data[$key] = 'Март';
					if ($current==4) $ch_data[$key] = 'Апрель';
					if ($current==5) $ch_data[$key] = 'Май';
					if ($current==6) $ch_data[$key] = 'Июнь';
					if ($current==7) $ch_data[$key] = 'Июль';
					if ($current==8) $ch_data[$key] = 'Август';
					if ($current==9) $ch_data[$key] = 'Сентябрь';
					if ($current==10) $ch_data[$key] = 'Октябрь';
					if ($current==11) $ch_data[$key] = 'Ноябрь';
					if ($current==12) $ch_data[$key] = 'Декабрь';
				}
			}
		}
	/*	echo '<br/>'; 	echo '<br/>'; 	echo '<br/>';
		print_r($chart_report_data);
		echo '<br/>'; 	echo '<br/>'; 	echo '<br/>';*/

		//$i=0;
		//echo $output;
	//	print_r($chart_report_data);
	//	echo '<br/><br/>';
	
	$current_filter_zamena=get_post_meta($post->ID,'chart_report_filter_zamena',true);
	
	
	if ($current_filter_zamena != '')
	{
		foreach ($chart_report_data as &$arr_elems) 
		{ 
			 
			foreach ($arr_elems as $k=>$e)
			{	
				if ($k==$chart_report_filter) $arr_elems[$k] = $current_filter_zamena;
			}
		}
		
		if ($current_debug=='1')
		{
			echo '<br/>После замены фильтруемого поля, если указан фильтр';
			print_r($chart_report_data);
		}
	}
	
		if ($series_last_line=='1')
		{
		//	$chart_report_data['avg']=$all_avg;
		}
		//print_r ($chart_report_data);
	
	
			$temp_data=$chart_report_data;
		


		foreach ($temp_data as $dt) 
		{
			foreach ($dt as $key=>$current)
			{
				if ($current_convert_month=='1')
				{
					if ($key==1) $key = 'Январь';
					if ($key==2) $key = 'Февраль';
					if ($key==3) $key = 'Март';
					if ($key==4) $key = 'Апрель';
					if ($key==5) $key = 'Май';
					if ($key==6) $key = 'Июнь';
					if ($key==7) $key = 'Июль';
					if ($key==8) $key = 'Август';
					if ($key==9) $key = 'Сентябрь';
					if ($key==10) $key = 'Октябрь';
					if ($key==11) $key = 'Ноябрь';
					if ($key==12) $key = 'Декабрь';

				}
				
				$keys[]=$key;
			}
			break;
			
		}
		//if ($series_last_line=='1') $keys[]='avg';
		$output="[[";
		foreach ($keys as $key)
		{
			$output.="'".$key."'";
		}
		$trans=array("''"=>"','");
		$output=strtr($output,$trans);
		$output.="],";
		
		//конец заполнения названий полей
	/*	echo '<br/><br/><br/><br/>';
		echo $output;
		echo '<br/><br/><br/><br/>';
		print_r($chart_report_data);*/
	
		foreach ($chart_report_data as $sql_result) 
		{ 
			$j=0;
			$i=0;
			$output.='[';
			 
			foreach ($sql_result as $key=>$current)
			{	
			//	echo $current.'<br/>';
				if ($types_array[$j]=='string')
				{
				$output.="'".$current."',";
				} else
				if ($types_array[$j]=='int')
				{
				$output.=$current.',';
				}

			//одиночное значение будет тут. указывается что результат - одиночное значение и указывается title для значения
				if (strlen($chart_report_single)>0)
				{
					$single_value=$current;
				}
				$j++;
			}
			
			$output.=']';
			$i++;
		}
		if ($series_last_line=='1')
		{
			$output.='[';
			$output.='avg,';
			foreach ($all_avg as $val)
			{
				$output.=$val.',';
			}
			$output.=']';
		}
		
		
		$output.=']';	
		$trans=array("]["=>"],[");
		$output=strtr($output,$trans);
		$trans=array(",]"=>"]");
		$output=strtr($output,$trans);
		
	/*	echo '<br/><br/><br/><br/>';
		echo $output;*/
	//}
	//echo $output;
	//флаг для одиночного значение будет тут
	if ($single_value!='')
	{
		echo '<span style="font-weight: bold;">'.$chart_report_single.'</span><span>&nbsp;'.$single_value.'</span>';
	}
	if ($single_value=='')
	{
		if ($chart_report_reverse=='1')
		{
			$output = chart_report_reverse($output);
		}
	
	}
	

	if ($current_debug=='1')
		if ($chart_report_col_filter ='true')
		{
			echo 'До фильтрации по колонкам';
			echo $output;
			echo '<br/>';
		}
	
	
		//ФИЛЬТРАЦИЯ ПО ЗАГОЛОВКАМ
	$chart_report_col_filter=get_post_meta($post->ID,'chart_report_col_filter',true);
	if ($chart_report_col_filter == 'true')
	{
	
	

	
		$filter_array =array();
		$filter_output = $output;
		$filter_output=substr($filter_output,2,strlen($filter_output)-3);
		
		$filter_array=explode('],[',$filter_output);
		foreach ($filter_array as $key => $elem)
		{
			$filter_array[$key] = explode(',',$elem);	
		}
		
		
	

		//СОЗДАНИЕ СЕЛЕКТА
		$titles_array_to_select = array();
		foreach($filter_array as $elem)
		{
			foreach($elem as $key => $cur_elem)
			{
				if ($key > 0)
					$titles_array_to_select[] = array(
						'id' => str_replace('\'', "", $cur_elem),
						'text' => str_replace('\'', "", $cur_elem)
					);

			}
			break;
		}
		
		$cur_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		$get_filter_id=$_GET['filter_id'];
		
		$pos = stripos($cur_url, '?');
		$url_not_cols =substr_replace($cur_url, '', $pos, strlen($cur_url));
		$url_not_cols.='?mod=true&filter_id='.$get_filter_id;
		//echo $url_not_cols;
		
		
		echo '<br/><br/>';
		echo '<h4>Фильтрация по заголовкам таблицы</h4>';
		echo '<input type="hidden" name="chart_report_col_filter" id="chart_report_col_filter" value="' . $_GET['cols'] . '" style="width:300px" cur_url="'.$url_not_cols.'" class="" tabindex="-1">';
		echo '<a class="btn" cur_url="'.$url_not_cols.'" id="chart_report_col_filter_btn">Применить</a>';
			?>
			<script>
			jQuery(document).ready(function(){
			var input_data = <?echo json_encode( $titles_array_to_select ); ?>;
				$("#chart_report_col_filter").select2({
					data: input_data,
					multiple: true
				});
			});	
			</script>
			<?
	
		
		
		
		//массив колок, которые отображать
		$filter_cols=explode(',',$_GET['cols']);
		foreach ($filter_cols as $key => $col)
		{
			$filter_cols[$key]='\''.$col.'\'';
		}
		
		
		//МАСС КЛЮЧЕЙ, КОТОРЫЕ НАДО УДАЛИТЬ
		$del_keys = array();
		foreach ($filter_array as $key => $elem)
		{
			foreach($elem as $single_key => $single)
			{
				if (in_array($single, $filter_cols))
				{
					$del_keys[]=$single_key;
				}
			}
			break;
		}
		
	//	print_r($del_keys);
		//удаляем лишнее из массива
		foreach ($filter_array as $key => $elem)
		{
			foreach($elem as $single_key => $single)
			{
				if ($single_key>0)
				if (!in_array($single_key, $del_keys))
					unset($filter_array[$key][$single_key]);
			}
		}
		//сбор обратно массива
		$res_array =array();
		foreach ($filter_array as $key => $elem)
		{
			$res_array[] = implode(',',$elem);	
		}
		$res_output = implode('],[',$res_array);	
		$res_output = '[['.$res_output.']]';

		//ВАЖНО!!
		if (isset($_GET['cols']))
			$output = $res_output;

	}
	
	if ($current_debug=='1')
	{
		echo '<br/>';
		echo 'Конечный результат';
		echo $output;
		echo '<br/>';
	}
	
	
	$dtype=get_post_meta($post->ID,'chart_report_diagram_type',true); //тип диаграммы
	
	
	$combochart_seriestype=get_post_meta($post->ID,'chart_report_combochart_seriestype',true);
	$combochart_isStacked=get_post_meta($post->ID,'chart_report_combochart_isStacked',true);
	$series_last_line=get_post_meta($post->ID,'chart_report_series_last_line',true);
	
	
	$dashboard_on=get_post_meta($post->ID,'chart_report_dashboard_on',true);
	
	
	$current_reports_graph_options=get_post_meta($post->ID,'reports_graph_options',true);
	
	$ser_color=get_post_meta($post->ID,'reports_dashboard_series_color',true);
	if ($ser_color!=''){
		if ($series_last_line!=''){
			$c=count($all_avg);
			$series = 'series: {1: {type: "line",color: "blue"}, 0:{color: "'.$ser_color.'"}},';
		}
		else
		{
			$series = 'series: [{color: "'.$ser_color.'"}],';
		}
	} else{
		if ($series_last_line!=''){
			$c=count($all_avg);
			$series = 'series: {1: {type: "line"}},';
		}
		else
		{
			$series='';
		}
		
	}
		
	
		$go = unserialize($current_reports_graph_options);
		if (!$go['empty'])
		{
		$chart_options = chart_report_generate_chart_options($go);
		}
	if ($dashboard_on!='1')
	if ($single_value=='')
	{
		
			if ($dtype=='Gauge') 
			{
				
				$trans=array("],["=>",");
				$output=strtr($output,$trans);
				$output=substr($output,1);
				$output = '[[\'Label\', \'Value\'],'.$output;
			//	echo $output;
			}
		
		?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load('visualization', '1', {'packages':['corechart']});
			<?
			if ($dtype=='Gauge') 
			{
			?>
				google.load('visualization', '1', {packages:['gauge']});
			<?
			}
			?>
			
			google.setOnLoadCallback(drawChart);

			
		function drawChart() 
		{	
			
		
			var div_id='chart_div_'+<? echo $post->ID;?>;
			var data = new google.visualization.arrayToDataTable(<? echo $output; ?>);
		//	var chart = new google.visualization.PieChart(document.getElementById(div_id));
			<? 
			if ($dtype=='LineChart') 
				echo 'var chart = new google.visualization.LineChart(document.getElementById(div_id));';
			if ($dtype=='BarChart') 
				echo 'var chart = new google.visualization.BarChart(document.getElementById(div_id));';
			if ($dtype=='ComboChart') 
				echo 'var chart = new google.visualization.ComboChart(document.getElementById(div_id));';
			if ($dtype=='ColumnChart') 
				echo 'var chart = new google.visualization.ColumnChart(document.getElementById(div_id));';
			if ($dtype=='AreaChart') 
				echo 'var chart = new google.visualization.AreaChart(document.getElementById(div_id));';
			if ($dtype=='PieChart') 
				echo 'var chart = new google.visualization.PieChart(document.getElementById(div_id));';
			if ($dtype=='Gauge') 
				echo 'var chart = new google.visualization.Gauge(document.getElementById(div_id));';
			
			
				
			if ($go['empty'])
			{
			echo 'chart.draw(data);';
			}
			else
			{
				echo 'chart.draw(data, {';
				echo $chart_options;
				echo $series;
				if ($dtype=='ComboChart')
				{
				
					if ($combochart_seriestype!='') echo 'seriesType: "'.$combochart_seriestype.'",';
					if ($combochart_isStacked!='') echo 'isStacked: "true",';
				}
				echo '});';
			}
			?>
			
		}

		</script>
		<?
		
	}
	
	if ($dashboard_on=='1')
	{
	
	$dashboard_controlType=get_post_meta($post->ID,'chart_report_dashboard_controlType',true);

	$current_reports_dashboard_options=get_post_meta($post->ID,'reports_dashboard_options',true);
		$dash_opt = unserialize($current_reports_dashboard_options);
		if (!$dash_opt['empty'])
		{
			$dashboard_options = chart_report_generate_chart_options($dash_opt);
		}
	
	?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load('visualization', '1.0', {'packages':['controls']});
		  google.setOnLoadCallback(drawDashboard);

		  function drawDashboard() {
			
			  var data = google.visualization.arrayToDataTable(<? echo $output; ?>);
			  var control_id='filter_div_'+<? echo $post->ID;?>;
			  var div_id='chart_div_'+<? echo $post->ID;?>;
			  var dashboard_div='dashboard_div_'+<? echo $post->ID;?>;
			  
			  var controlType='<? echo $dashboard_controlType;?>';
			  var chartType='<? echo $dtype;?>';

			  var categoryPicker = new google.visualization.ControlWrapper({
				'controlType': controlType,
				'containerId': control_id,
				'options': {<? echo $dashboard_options;?>}
			  });

			  // Define a Pie chart
			  var c_chart = new google.visualization.ChartWrapper({
				'chartType': chartType,
				'containerId': div_id,
				'options': {<? echo $chart_options;?>}
			  });


			  // Create a dashboard
			  new google.visualization.Dashboard(document.getElementById(dashboard_div)).
				  bind([categoryPicker], [c_chart]).
				  draw(data);
			
		  }
		</script>
	
	
	<?
	}
	echo '<div id="dashboard_div_'.$post->ID.'">';
		echo '<div id="filter_div_'.$post->ID.'"></div>';
		echo '<div id="chart_div_'.$post->ID.'"></div>';
	echo '</div>';
echo '	</div>';

	?>
	<script type="text/javascript">
		drawChart();
	</script>
	<?
	

	$chart_report_main_area=get_post_meta($post->ID,'chart_report_main_area',true);
	if (strlen($chart_report_main_area>0))
	{
		$chart_report_main_area=explode(',',$chart_report_main_area);
		foreach($chart_report_main_area as $report)
		{
			echo '<br/>';
			/*$p=get_post($report);
			echo '<h3>'.$p->post_title.'</h3>';*/
			get_chart_report_content_new($report,'');
		}
	}
	if ($content=='widget')
	{
		$param="http://".$_SERVER['HTTP_HOST']."/";
		echo '<br/><a style="position: relative; float: right;" href="'.$param.$post->ID.'" >Подробнее</a>';
	}
	
	
	?>
	<style type="text/css">
	.goog-menuitem {
		padding: 4px 4px!important;
	}
	.goog-link-button {
		position: relative!important;
		float: right!important;
	}
	
	</style>
	
	<?
	/*
	.google-visualization-controls-categoryfilter{
		width:300px;
	}
	.google-visualization-controls-categoryfilter-selected{
		max-width:100%!important;
	}
	*/
	
    return;
  }
}



	function simpleHtmlTable($data)
	{
	//	print_r($data);
		echo "<table border='1'>";
		echo "<thead>";
		foreach (array_keys($data[0]) as $item) {
			echo "<td><b>{$item}<b></td>";
		}
		echo "</thead>";
		$i=0;
		foreach ($data as $row) {
			$i++;
			echo '<tr alt="'.$i.'">';
			foreach ($row as $item) {
				echo "<td>{$item}</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	


?>
