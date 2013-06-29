<?php



function cases_report_rewrite_flush(){
 // register_cases_report_posttype();
  flush_rewrite_rules();
}  
	add_action('cp-activate','cases_report_rewrite_flush');
//register_activation_hook(__FILE__, 'cases_report_rewrite_flush');


//add_action('wp_head','head_csv'); //этот хук вызывает ошибку: Notice: Undefined index: duration in /var/www/wp-content/plugins/wp-dtree-30/wp-dtree.php on line 107 Notice: Trying to get property of non-object in /var/www/wp-content/plugins/casepress/cp-components/cp-sql_reports/sql_reports.php on line 16
function head_csv(){
  global $post;
  if($post->post_type=='report'){
	$sql = $post->post_excerpt;
	$export = get_post_meta($post->ID,'cp_posts_report_csv_export',true);
		if  (strpos($_SERVER['REQUEST_URI'], 'csv=true') >0 )
		{ 
		/*	$new_sql = $sql;
			foreach ($_GET as $key => $get)
			{
				if ($key != 'csv')
				{
					$new_sql = str_replace('%'.$key.'%',$get,$new_sql);
				}
			}
		*/	
			//new
			$keys='';
			$values='';
			foreach ($_GET as $key => $get)
			{
				if ($key != 'csv')
				{
					if ($keys=='')
					{
						$keys = $key;
						$values = $get;
					}
					else
					{
						$keys .= ','.$key;
						$values .= ','.$get;
					}
				}
			}
			export_data_csv_new($keys,$values,$post->ID);
			
			
		//	echo $new_sql;
		//	echo '<br/><br/><br/>';
						//	export_data_csv($new_sql);
		//	print_r ($_GET);
			die();
		}
	}
}

	function export_data_csv_new($keys,$values,$post_id)
	{
		?>
		<script type="text/javascript">
			document.location.href = '/save_report.php?keys=<?=$keys?>&values=<?=$values?>&post_id=<?=$post_id?>';
		</script>
		<?
	}



	function export_data_csv($sql)
	{
		//echo $sql;
		global $wpdb;
		$res = $wpdb->get_results($sql, ARRAY_A);
		$data='';
		
		$keys = '';
		foreach ($res as $row_one)
		{
			foreach ($row_one as $key=> $val)
			{
				$key = str_replace( '"' , '""' , $key );
				$key = '"' . $key . '"' . ";";
				$keys .= $key;
			}
			break;
		}
		$data .= trim( $keys ) . "<br/>";
		
		foreach ($res as $row)
		{
			$line = '';
			foreach ($row as $value)
			{
				if ( ( !isset( $value ) ) || ( $value == "" ) )
				{
					$value = ";";
				}
				else
				{
					$value = str_replace( '"' , '""' , $value );
					$value = '"' . $value . '"' . ";";
				}
				$line .= $value;
			}
			$data .= trim( $line ) . "<br/>";
		}
		$data = str_replace( "\r" , "" , $data );
		if ( $data == "" )
		{
			$data = "\n(0) Records Found!\n";                        
		}
		

	/*	header_remove('X-Powered-By'); 
		header_remove('X-Pingback');
		header_remove('Expires');
		header_remove('Cache-Control');
		header_remove('Pragma');
		header_remove('Content-Type');*/
	
	/*	header_remove(); 
		print_r (headers_list());
		echo '<br/><br/>';
		*/
		//ob_clean();
	/*	header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=test.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
	*/	
	/*	$_POST['sql_report']= $data;
		session_start();
		$_SESSION['sql_report']= $data;
		setcookie ("sql_report", $data);
		//$_COOKIES['sql_report'] = $data;*/
	//print "$header\n$data";
	/*echo '<div>';
	echo urlencode($sql);
	echo '</div>';*/
		?>
		<script type="text/javascript">
			document.location.href = '/save_report.php?sql="<?=urlencode($sql)?>"';
		</script>
		<?
	//echo $data;
		//echo $sql;

	}


/*	
	add_action('roots_post_before', 'cp_reports_add_redirect_button');
	function cp_reports_add_redirect_button()
	{
		global $post;
		if($post->post_type=='report'){
			$init_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			if (count($_GET)>0)
			{
				$init_url .= '&csv=true';
			}
			else
			{
				$init_url .= '?csv=true';
			}
			echo '<a href="'.$init_url.'" class="btn btn-primary">Не жди пока загрузится отчет. Выгружай в CSV</a>';
		}
	}
  */

function add_cases_report_content($content){
  global $post;
  if($post->post_type=='report'){
    echo wpautop($content);
    $params = shortcode_parse_atts(get_post_meta($post->ID, 'datatable', true));
    $sql = $post->post_excerpt;
	/*	$export = get_post_meta($post->ID,'cp_posts_report_csv_export',true);
		if ($export == 'yes')
		{
		//	echo '<h3>Данный отчет предназначен для выгрузки. Отображаются не все данные</h3>';
		//	$sql .= ' limit 1';
		echo $_SERVER['DOCUMENT_ROOT'].'/assets/reports';
			if(function_exists('datatable_generator')) datatable_generator($params, $sql);
		}*/
    if(function_exists('datatable_generator')) datatable_generator($params, $sql);
    return;
  }
  return $content;
} add_filter('the_content', 'add_cases_report_content');


function report_csv_add_meta_box() 
	{
		add_meta_box( 'csv_convert_metabox','Выгрузка', 'report_csv_meta_box', 'report', 'normal', 'high' );
	}
	add_action( 'add_meta_boxes', 'report_csv_add_meta_box' );
		
	function report_csv_meta_box() {
	
		echo 'Выгружать данные отчета ';
		
		$post_id = $_GET['post'];
		$export = get_post_meta($post_id,'cp_posts_report_csv_export',true);
		if ($export == 'yes')
		{
			echo '<input type="checkbox" name="cp_posts_report_csv_export" checked>';
		}
		else
		{
			echo '<input type="checkbox" name="cp_posts_report_csv_export">';
		}
		
		
	}
	
	function update_report_csv_export( $post_id )
	{
		$post = get_post($post_id);
		if ($post->post_type == 'report')
		{
			if (isset($_POST['cp_posts_report_csv_export']))
				update_post_meta($post_id,'cp_posts_report_csv_export','yes');
		}		
	}
	add_action('post_updated', 'update_report_csv_export');


?>