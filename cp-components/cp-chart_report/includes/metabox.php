<?php
	function chart_report_add_meta_box() {
		add_meta_box( 'chart_report_metabox','chart_report', 'chart_report_meta_box', 'chart_report', 'normal', 'high' );
	}
	add_action( 'add_meta_boxes', 'chart_report_add_meta_box' );
	
	function chart_report_meta_box() {
		
		$url_charts_scripts=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
		wp_enqueue_script('mt_js', $url_charts_scripts.'/metabox-func.js', array('jquery'));
		wp_localize_script( 'mt_js', 'mt_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
		$current_chart_report_debug=get_post_meta($_GET['post'],'chart_report_debug',true);
		echo 'Debug';
		if ($current_chart_report_debug == '1'){
			echo '<input type="checkbox" id="chart_report_debug_chekbox" checked  />';
		} else {
			echo '<input type="checkbox" id="chart_report_debug_chekbox"  />';
		}
		echo '<input type="hidden" id="chart_report_debug" name="chart_report_debug" value="'.$current_chart_report_debug.'" /></br>';
		
		echo 'Источник данных:';
		$current_source=get_post_meta($_GET['post'],'chart_report_source',true);
		$acm='acm';
		$other='other';
		echo '<select name="chart_report_source" id="chart_report_source">';
		echo '<option value="acm"', $current_source == $acm ? ' selected="selected"' : '', '>ACM</option>';
		echo '<option value="other"', $current_source == $other ? ' selected="selected"' : '', '>other</option>';
		echo '</select>';
		$current_other_report_source=get_post_meta($_GET['post'],'other_report_source',true);
		if ($current_source == 'other' || $current_source =='' ){
			echo '<input type="text" id="other_report_source" name="other_report_source" value="'.$current_other_report_source.'" /></br>';
		} else {
			echo '<input type="text" style="display:none" id="other_report_source" name="other_report_source" value="'.$current_other_report_source.'" /></br>';
		}
		
		$current_types=get_post_meta($_GET['post'],'chart_report_types',true);
		echo 'Типы данных:';
		echo '<input type="text" name="chart_report_types" value="'.$current_types.'" /></br>';
		$current_chart_type=get_post_meta($_GET['post'],'chart_report_diagram_type',true);
		echo 'Типы Диаграммы:';
		$LineChart='LineChart';
		$ComboChart='ComboChart';
		$ColumnChart='ColumnChart';
		$AreaChart='AreaChart'; 
		$PieChart='PieChart';
		$BarChart='BarChart';
		$Gauge='Gauge';
		echo '<select name="chart_report_diagram_type" >';
		echo '<option value="LineChart"', $current_chart_type == $LineChart ? ' selected="selected"' : '', '>LineChart</option>';
		echo '<option value="ComboChart"', $current_chart_type == $ComboChart ? ' selected="selected"' : '', '>ComboChart</option>';
		echo '<option value="ColumnChart"', $current_chart_type == $ColumnChart ? ' selected="selected"' : '', '>ColumnChart</option>';
		echo '<option value="AreaChart"', $current_chart_type == $AreaChart ? ' selected="selected"' : '', '>AreaChart</option>';
		echo '<option value="PieChart"', $current_chart_type == $PieChart ? ' selected="selected"' : '', '>PieChart</option>';
		echo '<option value="BarChart"', $current_chart_type == $BarChart ? ' selected="selected"' : '', '>BarChart</option>';
		echo '<option value="Gauge"', $current_chart_type == $Gauge ? ' selected="selected"' : '', '>Gauge</option>';
		echo '</select></br>';
		
		
		$current_select_filter=get_post_meta($_GET['post'],'chart_report_select_filter',true);
		echo 'Возможность менять id фильтруемого ';
		if ($current_select_filter == '1'){
			echo '<input type="checkbox" id="chart_report_select_filter_chekbox" checked  />';
		} else {
			echo '<input type="checkbox" id="chart_report_select_filter_chekbox"  />';
		}
		echo '<input type="hidden" id="chart_report_select_filter" name="chart_report_select_filter" value="'.$current_select_filter.'" /></br>';
		
		$current_col_filter=get_post_meta($_GET['post'],'chart_report_col_filter',true);
		echo 'Возможность фильтрации по колонкам';
		echo '<input type="text" name="chart_report_col_filter" value="'.$current_col_filter.'" /><br/>';
		
		
		$current_reverse=get_post_meta($_GET['post'],'chart_report_reverse',true);
		echo 'Переворот ';
		if ($current_reverse == '1'){
			echo '<input type="checkbox" id="chart_report_reverse_chekbox" checked  />';
		} else {
			echo '<input type="checkbox" id="chart_report_reverse_chekbox"  />';
		}
		echo '<input type="hidden" id="chart_report_reverse" name="chart_report_reverse" value="'.$current_reverse.'" /></br>';
		
		
		$current_date_merge=get_post_meta($_GET['post'],'chart_report_date_merge',true);
		echo 'Слияние даты';
		echo '<input type="text" name="chart_report_date_merge" value="'.$current_date_merge.'" /><br/>';
		
		$current_filter=get_post_meta($_GET['post'],'chart_report_filter',true);
		echo 'Имя фильтруемого поля:';
		echo '<input type="text" name="chart_report_filter" value="'.$current_filter.'" />';
		
		$current_filter_zamena=get_post_meta($_GET['post'],'chart_report_filter_zamena',true);
		echo 'Заменить на';
		echo '<input type="text" name="chart_report_filter_zamena" value="'.$current_filter_zamena.'" /><br/>';
		
		/* pivot */
		$current_pivot=get_post_meta($_GET['post'],'chart_report_pivot',true);
		echo 'Включить пивот';
		
		if ($current_pivot == '1'){
			echo '<input type="checkbox" id="chart_report_pivot_chekbox" checked  />';
			echo '<div id="pivot_div" style="display:initial">';
		} else {
			echo '<input type="checkbox" id="chart_report_pivot_chekbox"  />';
			echo '<div id="pivot_div" style="display:none">';
		}
		echo '<input type="hidden" id="chart_report_pivot" name="chart_report_pivot" value="'.$current_pivot.'" /></br>';

		
			
			$current_pivot_row=get_post_meta($_GET['post'],'chart_report_pivot_row',true);
			echo 'Названия строк:';
			echo '<input type="text" name="chart_report_pivot_row" value="'.$current_pivot_row.'" /></br>';
			
			$current_pivot_col=get_post_meta($_GET['post'],'chart_report_pivot_col',true);
			echo 'Названия столбцов:';
			echo '<input type="text" name="chart_report_pivot_col" value="'.$current_pivot_col.'" /></br>';
			
			$current_pivot_value=get_post_meta($_GET['post'],'chart_report_pivot_value',true);
			echo 'Значения:';
			echo '<input type="text" name="chart_report_pivot_value" value="'.$current_pivot_value.'" /></br>';
			
			$current_pivot_count=get_post_meta($_GET['post'],'chart_report_pivot_count',true);
			echo 'Количество по полю';
			echo '<input type="text" name="chart_report_pivot_count" value="'.$current_pivot_count.'" /></br>';
			
			$current_pivot_null_replace=get_post_meta($_GET['post'],'chart_report_pivot_null_replace',true);
			echo 'NULL замена (по умолчанию 0)';
			echo '<input type="text" name="chart_report_pivot_null_replace" value="'.$current_pivot_null_replace.'" /></br>';
			
		echo '</div><br/>';
		
		/* /pivot */
		
		$current_convert_month=get_post_meta($_GET['post'],'chart_report_convert_month',true);
		echo 'Преобразовать названия месяцев';
		
		if ($current_convert_month == '1'){
			echo '<input type="checkbox" id="chart_report_convert_month_chekbox" checked  />';
		} else {
			echo '<input type="checkbox" id="chart_report_convert_month_chekbox"  />';
		}
		echo '<input type="hidden" id="chart_report_convert_month" name="chart_report_convert_month" value="'.$current_convert_month.'" /></br>';
		
		
		$current_single=get_post_meta($_GET['post'],'chart_report_single',true);
		echo 'Одиночное значение:';
		echo '<input type="text" name="chart_report_single" value="'.$current_single.'" /></br>';
		
		$current_additional_reports=get_post_meta($_GET['post'],'chart_report_additional_reports',true);
		echo 'Дополнительные отчеты';
		echo '<input type="text" name="chart_report_additional_reports" value="'.$current_additional_reports.'" /></br>';
		
		$current_reports_in_main=get_post_meta($_GET['post'],'chart_report_main_area',true);
		echo 'Отчеты в основной области';
		echo '<input type="text" name="chart_report_main_area" value="'.$current_reports_in_main.'" /></br>';
		
		
		
		echo '<hr/>';
		echo 'Свойства графика <br/>';
		$current_reports_graph_options=get_post_meta($_GET['post'],'reports_graph_options',true);
		$go = unserialize($current_reports_graph_options);
	echo 'Подпись по оси Х';
		echo '<input type="text" name="chart_report_hAxis-title" value="'.$go['hAxis']['title'].'" /></br>';
	echo 'Минимум по оси Х';
		echo '<input type="text" name="chart_report_hAxis-minValue" value="'.$go['hAxis']['minValue'].'" /></br>';
	echo 'Максимум по оси Х';
		echo '<input type="text" name="chart_report_hAxis-maxValue" value="'.$go['hAxis']['maxValue'].'" /></br>';
	echo 'Позиция текста по оси Х';
		echo '<input type="text" name="chart_report_hAxis-textPosition" value="'.$go['hAxis']['textPosition'].'" /></br>';	
	echo 'Направление';
		echo '<input type="text" name="chart_report_hAxis-direction" value="'.$go['hAxis']['direction'].'" /></br>';	
		
	echo 'Подпись по оси Y';
		echo '<input type="text" name="chart_report_vAxis-title" value="'.$go['vAxis']['title'].'" /></br>';
	echo 'Минимум по оси Y';
		echo '<input type="text" name="chart_report_vAxis-minValue" value="'.$go['vAxis']['minValue'].'" /></br>';
	echo 'Максимум по оси Y';
		echo '<input type="text" name="chart_report_vAxis-maxValue" value="'.$go['vAxis']['maxValue'].'" /></br>';
	echo 'Позиция текста по оси Y';
		echo '<input type="text" name="chart_report_vAxis-textPosition" value="'.$go['vAxis']['textPosition'].'" /></br>';	
	echo 'Направление';
		echo '<input type="text" name="chart_report_vAxis-direction" value="'.$go['vAxis']['direction'].'" /></br>';			
		
	echo 'reverseCategories';
		echo '<input type="text" name="chart_report_reverseCategories" value="'.$go['reverseCategories'].'" /></br>';	
	echo 'Позиция легенды';
		echo '<input type="text" name="chart_report_legend-position" value="'.$go['legend']['position'].'" /></br>';
		
	echo 'Ширина';
		echo '<input type="text" name="chart_report_width" value="'.$go['width'].'" /></br>';	
	echo 'Высота';
		echo '<input type="text" name="chart_report_height" value="'.$go['height'].'" /></br>';
		
	
	// Gauge OPTIONS
	echo 'Gauge_redFrom';
		echo '<input type="text" name="chart_report_Gauge_redFrom" value="'.$go['redFrom'].'" /></br>';
	echo 'Gauge_redTo';
		echo '<input type="text" name="chart_report_Gauge_redTo" value="'.$go['redTo'].'" /></br>';
	echo 'Gauge_yellowFrom';
		echo '<input type="text" name="chart_report_Gauge_yellowFrom" value="'.$go['yellowFrom'].'" /></br>';
	echo 'Gauge_yellowTo';
		echo '<input type="text" name="chart_report_Gauge_yellowTo" value="'.$go['yellowTo'].'" /></br>';
	echo 'Gauge_greenFrom';
		echo '<input type="text" name="chart_report_Gauge_greenFrom" value="'.$go['greenFrom'].'" /></br>';
	echo 'Gauge_greenTo';
		echo '<input type="text" name="chart_report_Gauge_greenTo" value="'.$go['greenTo'].'" /></br>';
	echo 'Gauge_minorTicks';
		echo '<input type="text" name="chart_report_Gauge_minorTicks" value="'.$go['minorTicks'].'" /></br>';
		
		
	
	echo 'Цвет элементов. off control';
		$current_reports_dashboard_series_color=get_post_meta($_GET['post'],'reports_dashboard_series_color',true);	
		echo '<input type="text" name="reports_dashboard_series_color" value="'.$current_reports_dashboard_series_color.'" /></br>';
		
	$current_report_series_last_line=get_post_meta($_GET['post'],'chart_report_series_last_line',true);
	echo 'Преобразовать последний столбец в Line (только если combochart and seriesType:bars)';
	
	if ($current_report_series_last_line == '1'){
		echo '<input type="checkbox" id="chart_report_series_last_line_chekbox" checked  />';
	} else {
		echo '<input type="checkbox" id="chart_report_series_last_line_chekbox"  />';
	}
	echo '<input type="hidden" id="chart_report_series_last_line" name="chart_report_series_last_line" value="'.$current_report_series_last_line.'" /></br>';
	
	
	echo 'combochart_seriestype';
		$current_reports_combochart_seriestype=get_post_meta($_GET['post'],'chart_report_combochart_seriestype',true);	
		echo '<input type="text" name="chart_report_combochart_seriestype" value="'.$current_reports_combochart_seriestype.'" /></br>';	
	

echo 'combochart_isStacked';
		$current_reports_combochart_isStacked=get_post_meta($_GET['post'],'chart_report_combochart_isStacked',true);	
		echo '<input type="text" name="chart_report_combochart_isStacked" value="'.$current_reports_combochart_isStacked.'" /></br>';	

	
	echo '<hr/>';
	echo 'Дополнительное управление <br/>';
	$current_reports_dashboard_options=get_post_meta($_GET['post'],'reports_dashboard_options',true);
	$dash_opt = unserialize($current_reports_dashboard_options);
	
	echo 'Включить дополнительное управление';
		$current_chart_report_dashboard_on=get_post_meta($_GET['post'],'chart_report_dashboard_on',true);
		
		if ($current_chart_report_dashboard_on == '1'){
			echo '<input type="checkbox" id="chart_report_dashboard_on_chekbox" checked  />';
			echo '<div id="dashboard_settings_div" style="display:initial">';
		} else {
			echo '<input type="checkbox" id="chart_report_dashboard_on_chekbox"  />';
			echo '<div id="dashboard_settings_div" style="display:none">';
		}
		echo '<input type="hidden" id="chart_report_dashboard_on" name="chart_report_dashboard_on" value="'.$current_chart_report_dashboard_on.'" /></br>';
		
		
	echo 'controlType';
		$current_chart_report_dashboard_controlType=get_post_meta($_GET['post'],'chart_report_dashboard_controlType',true);
		echo '<input type="text" name="chart_report_dashboard_controlType" value="'.$current_chart_report_dashboard_controlType.'" /></br>';
	
	echo 'filterColumnLabel';
		echo '<input type="text" name="chart_report_control_filterColumnLabel" value="'.$dash_opt['filterColumnLabel'].'" /></br>';
	echo 'ui.caption';
		echo '<input type="text" name="chart_report_control_ui-caption" value="'.$dash_opt['ui']['caption'].'" /></br>';
	echo 'ui.allowTyping';
		echo '<input type="text" name="chart_report_control_ui-allowTyping" value="'.$dash_opt['ui']['allowTyping'].'" /></br>';
	echo 'ui.allowMultiple';
		echo '<input type="text" name="chart_report_control_ui-allowMultiple" value="'.$dash_opt['ui']['allowMultiple'].'" /></br>';
	echo 'ui.selectedValuesLayout';
		echo '<input type="text" name="chart_report_control_ui-selectedValuesLayout" value="'.$dash_opt['ui']['selectedValuesLayout'].'" /></br>';
	echo 'ui.label';
		echo '<input type="text" name="chart_report_control_ui-label" value="'.$dash_opt['ui']['label'].'" /></br>';
	echo 'ui.labelStacking';
		echo '<input type="text" name="chart_report_control_ui-labelStacking" value="'.$dash_opt['ui']['labelStacking'].'" /></br>';
		
	echo '</div>';
	
	
		echo '<br/> <hr/>Включить предпросмотр';
		echo '<input type="checkbox" id="chart_report_preview" />';
		
		echo '<div id="chart_report_preview_div" style="display:none">';
			echo '<input type="button" id="get_preview" value="Refresh" />';
			echo '<div id="chart_report_preview_div_content">';
				//get_chart_report_content_new($_GET['post'],'preview');
			echo '</div>';
		echo '</div>';
	
	}
	
	add_action('wp_ajax_chart_report_get_preview', 'chart_report_get_preview');
	function chart_report_get_preview()
	{
	//	echo '232323';

		get_chart_report_content_new(30431,'preview');
		die;
	}
	
	function update_chart_report_settings( $post_id )
	{
		if (isset($_POST['chart_report_source']))
			update_post_meta($post_id, 'chart_report_source', $_POST['chart_report_source']);
		if (isset($_POST['other_report_source']))
			update_post_meta($post_id, 'other_report_source', $_POST['other_report_source']);
		if (isset($_POST['chart_report_debug'])) 
			update_post_meta($post_id, 'chart_report_debug', $_POST['chart_report_debug']);
			
		if (isset($_POST['chart_report_date_merge'])) 
			update_post_meta($post_id, 'chart_report_date_merge', $_POST['chart_report_date_merge']);	
			
			
		if (isset($_POST['chart_report_types']))
			update_post_meta($post_id, 'chart_report_types', $_POST['chart_report_types']);
		if (isset($_POST['chart_report_diagram_type']))
			update_post_meta($post_id, 'chart_report_diagram_type', $_POST['chart_report_diagram_type']);	
		if (isset($_POST['chart_report_filter']))
			update_post_meta($post_id, 'chart_report_filter', $_POST['chart_report_filter']);
		if (isset($_POST['chart_report_reverse']))
			update_post_meta($post_id, 'chart_report_reverse', $_POST['chart_report_reverse']);
		if (isset($_POST['chart_report_pivot']))
			update_post_meta($post_id, 'chart_report_pivot', $_POST['chart_report_pivot']);
			
		if (isset($_POST['chart_report_col_filter']))
			update_post_meta($post_id, 'chart_report_col_filter', $_POST['chart_report_col_filter']);
			
			
		if (isset($_POST['chart_report_select_filter']))
			update_post_meta($post_id, 'chart_report_select_filter', $_POST['chart_report_select_filter']);

		if (isset($_POST['chart_report_filter_zamena']))
			update_post_meta($post_id, 'chart_report_filter_zamena', $_POST['chart_report_filter_zamena']);	
			
		if (isset($_POST['chart_report_convert_month']))
			update_post_meta($post_id, 'chart_report_convert_month', $_POST['chart_report_convert_month']);	
		
		if (isset($_POST['chart_report_pivot_row']))
			update_post_meta($post_id, 'chart_report_pivot_row', $_POST['chart_report_pivot_row']);
		if (isset($_POST['chart_report_pivot_value']))
			update_post_meta($post_id, 'chart_report_pivot_value', $_POST['chart_report_pivot_value']);
		if (isset($_POST['chart_report_pivot_col']))
			update_post_meta($post_id, 'chart_report_pivot_col', $_POST['chart_report_pivot_col']);
		if (isset($_POST['chart_report_pivot_count']))
			update_post_meta($post_id, 'chart_report_pivot_count', $_POST['chart_report_pivot_count']);
		if (isset($_POST['chart_report_pivot_null_replace']))
			update_post_meta($post_id, 'chart_report_pivot_null_replace', $_POST['chart_report_pivot_null_replace']);
		
		
		
		if (isset($_POST['chart_report_single']))
			update_post_meta($post_id, 'chart_report_single', $_POST['chart_report_single']);
			
		if (isset($_POST['chart_report_additional_reports']))
			update_post_meta($post_id, 'chart_report_additional_reports', $_POST['chart_report_additional_reports']);
			
		if (isset($_POST['chart_report_main_area']))
			update_post_meta($post_id, 'chart_report_main_area', $_POST['chart_report_main_area']);
			
			
		/* chart options here */
		
		if (isset($_POST['chart_report_series_last_line']))
			update_post_meta($post_id, 'chart_report_series_last_line', $_POST['chart_report_series_last_line']);
		if (isset($_POST['chart_report_combochart_seriestype']))
			update_post_meta($post_id, 'chart_report_combochart_seriestype', $_POST['chart_report_combochart_seriestype']);
		if (isset($_POST['chart_report_combochart_isStacked']))
			update_post_meta($post_id, 'chart_report_combochart_isStacked', $_POST['chart_report_combochart_isStacked']);	
			
		
		$arr_out=array();
		$flag=0;
		if (isset($_POST['chart_report_hAxis-title'])) 
			{ $flag=1; $arr_out['hAxis']['title'] = $_POST['chart_report_hAxis-title']; };
		if (isset($_POST['chart_report_hAxis-minValue'])) 
			{ $flag=1; $arr_out['hAxis']['minValue'] = $_POST['chart_report_hAxis-minValue']; };
		if (isset($_POST['chart_report_hAxis-maxValue'])) 
			{ $flag=1; $arr_out['hAxis']['maxValue'] = $_POST['chart_report_hAxis-maxValue']; };
		if (isset($_POST['chart_report_hAxis-textPosition'])) 
			{ $flag=1; $arr_out['hAxis']['textPosition'] = $_POST['chart_report_hAxis-textPosition']; };
		if (isset($_POST['chart_report_hAxis-direction'])) 
			{ $flag=1; $arr_out['hAxis']['direction'] = $_POST['chart_report_hAxis-direction']; };
		
		if (isset($_POST['chart_report_vAxis-title'])) 
			{ $flag=1; $arr_out['vAxis']['title'] = $_POST['chart_report_vAxis-title']; };
		if (isset($_POST['chart_report_vAxis-minValue'])) 
			{ $flag=1; $arr_out['vAxis']['minValue'] = $_POST['chart_report_vAxis-minValue']; };
		if (isset($_POST['chart_report_vAxis-maxValue'])) 
			{ $flag=1; $arr_out['vAxis']['maxValue'] = $_POST['chart_report_vAxis-maxValue']; };
		if (isset($_POST['chart_report_vAxis-textPosition'])) 
			{ $flag=1; $arr_out['vAxis']['textPosition'] = $_POST['chart_report_vAxis-textPosition']; };
		if (isset($_POST['chart_report_vAxis-direction'])) 
			{ $flag=1; $arr_out['vAxis']['direction'] = $_POST['chart_report_vAxis-direction']; };
		
		if (isset($_POST['chart_report_reverseCategories']))
			{ $flag=1; $arr_out['reverseCategories'] = $_POST['chart_report_reverseCategories']; };
		if (isset($_POST['chart_report_legend-position'])) 
			{ $flag=1; $arr_out['legend']['position'] = $_POST['chart_report_legend-position']; };
			
		if (isset($_POST['chart_report_width']))
			{ $flag=1; $arr_out['width'] = $_POST['chart_report_width']; };
		if (isset($_POST['chart_report_height']))
			{ $flag=1; $arr_out['height'] = $_POST['chart_report_height']; };
			
			
			
		//GAUDE options
		if (isset($_POST['chart_report_Gauge_redFrom']))
			{ $flag=1; $arr_out['redFrom'] = $_POST['chart_report_Gauge_redFrom']; };
			
		if (isset($_POST['chart_report_Gauge_redTo']))
			{ $flag=1; $arr_out['redTo'] = $_POST['chart_report_Gauge_redTo']; };
			
		if (isset($_POST['chart_report_Gauge_yellowFrom']))
			{ $flag=1; $arr_out['yellowFrom'] = $_POST['chart_report_Gauge_yellowFrom']; };
			
		if (isset($_POST['chart_report_Gauge_yellowTo']))
			{ $flag=1; $arr_out['yellowTo'] = $_POST['chart_report_Gauge_yellowTo']; };
			
		if (isset($_POST['chart_report_Gauge_greenFrom']))
			{ $flag=1; $arr_out['greenFrom'] = $_POST['chart_report_Gauge_greenFrom']; };
			
		if (isset($_POST['chart_report_Gauge_greenTo']))
			{ $flag=1; $arr_out['greenTo'] = $_POST['chart_report_Gauge_greenTo']; };
			
		if (isset($_POST['chart_report_Gauge_minorTicks']))
			{ $flag=1; $arr_out['minorTicks'] = $_POST['chart_report_Gauge_minorTicks']; };
			
		
		if ($flag==1)
		{
			$arr_out['empty'] = false;
			if (
				($arr_out['hAxis']['title'] == '' ) && 
				($arr_out['hAxis']['minValue'] == '' ) &&
				($arr_out['hAxis']['maxValue'] == '' ) &&
				($arr_out['hAxis']['textPosition'] == '' ) &&
				($arr_out['hAxis']['direction'] == '' ) &&
				($arr_out['vAxis']['title'] == '' ) &&
				($arr_out['vAxis']['minValue'] == '' ) &&
				($arr_out['vAxis']['maxValue'] == '' ) &&
				($arr_out['vAxis']['textPosition'] == '' ) &&
				($arr_out['vAxis']['direction'] == '' ) &&
				($arr_out['reverseCategories'] == '' ) &&
				($arr_out['height'] == '' ) &&
				($arr_out['width'] == '' ) &&
				($arr_out['redFrom'] == '' ) &&
				($arr_out['redTo'] == '' ) &&
				($arr_out['yellowFrom'] == '' ) &&
				($arr_out['yellowTo'] == '' ) &&
				($arr_out['greenFrom'] == '' ) &&
				($arr_out['greenTo'] == '' ) &&
				($arr_out['minorTicks'] == '' ) &&
				($arr_out['legend']['position'] == '')
				) $arr_out['empty'] = true;
			$res_out = serialize ($arr_out);
			update_post_meta($post_id, 'reports_graph_options', $res_out);
		}
		
		$arr_out_control=array();
		$flag=0;
		
		if (isset($_POST['chart_report_control_filterColumnLabel'])) 
			{ $flag=1; $arr_out_control['filterColumnLabel'] = $_POST['chart_report_control_filterColumnLabel']; };
		if (isset($_POST['chart_report_control_ui-caption'])) 
			{ $flag=1; $arr_out_control['ui']['caption'] = $_POST['chart_report_control_ui-caption']; };
			
		if (isset($_POST['chart_report_control_ui-allowTyping'])) 
			{ $flag=1; $arr_out_control['ui']['allowTyping'] = $_POST['chart_report_control_ui-allowTyping']; };
			
		if (isset($_POST['chart_report_control_ui-allowMultiple'])) 
			{ $flag=1; $arr_out_control['ui']['allowMultiple'] = $_POST['chart_report_control_ui-allowMultiple']; };
			
		if (isset($_POST['chart_report_control_ui-selectedValuesLayout'])) 
			{ $flag=1; $arr_out_control['ui']['selectedValuesLayout'] = $_POST['chart_report_control_ui-selectedValuesLayout']; };
			
		if (isset($_POST['chart_report_control_ui-labelStacking'])) 
			{ $flag=1; $arr_out_control['ui']['labelStacking'] = $_POST['chart_report_control_ui-labelStacking']; };
		if (isset($_POST['chart_report_control_ui-label'])) 
			{ $flag=1; $arr_out_control['ui']['label'] = $_POST['chart_report_control_ui-label']; };

		if ($flag==1)
		{
			$arr_out_control['empty'] = false;
			if (
				($arr_out_control['ui']['caption'] == '' ) && 
				($arr_out_control['ui']['allowMultiple'] == '' ) && 
				($arr_out_control['ui']['selectedValuesLayout'] == '' ) && 
				($arr_out_control['ui']['label'] == '' ) && 
				($arr_out_control['ui']['labelStacking'] == '' ) && 
				($arr_out_control['filterColumnLabel'] == '' ) &&
				($arr_out_control['ui']['allowTyping'] == '')
				) $arr_out_control['empty'] = true;
			$res_out = serialize ($arr_out_control);
			update_post_meta($post_id, 'reports_dashboard_options', $res_out);
		}	
			
			
		if (isset($_POST['chart_report_dashboard_controlType']))
			update_post_meta($post_id, 'chart_report_dashboard_controlType', $_POST['chart_report_dashboard_controlType']);
		
		if (isset($_POST['chart_report_dashboard_on']))
			update_post_meta($post_id, 'chart_report_dashboard_on', $_POST['chart_report_dashboard_on']);
			
			
		if (isset($_POST['reports_dashboard_series_color']))
			update_post_meta($post_id, 'reports_dashboard_series_color', $_POST['reports_dashboard_series_color']);
			
	}
	add_action('post_updated', 'update_chart_report_settings');


?>