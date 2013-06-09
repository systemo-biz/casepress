<?php
	
	function chart_report_get_data($post)
	{
		
		$chart_report_data=get_post_meta($post->ID,'chart_report_data',true);
		//echo $chart_report_data;
		if ((!isset($chart_report_data))||($chart_report_data==''))//если данных в массиве нет
		{	
			$chart_report_source=get_post_meta($post->ID,'chart_report_source',true);
			
			if ($chart_report_source=='acm')//если данные из ACM
			{	
				$sql = $post->post_excerpt;
				$query =$sql;
				$result_mass=array();
				$i=0;
				$sql_results = mysql_query($query) or die("Invalid query: " . mysql_error());
				
				while($sql_result = mysql_fetch_array($sql_results, MYSQL_ASSOC)) 
				{ 
					$result_mass[$i]=$sql_result;
					$i++;
				}
				$pst_ID = $post->ID;
				$result_mass = apply_filters('chart_report_data_before_save',$result_mass,$pst_ID);
	//$result_mass = change_values_for_reports_test($result_mass,$pst_ID);				
				$ser_array=serialize($result_mass);
				update_post_meta($post->ID,'chart_report_data',$ser_array);
				$now_time=date("d.m.Y");
				update_post_meta($post->ID,'chart_report_last_update',$now_time);
			}
			
			
			$chart_report_data = $ser_array;
		}
		
		
		$chart_report_source=get_post_meta($post->ID,'chart_report_source',true);
		if ($chart_report_source=='other')
		{
			$other_report_source=get_post_meta($post->ID,'other_report_source',true);
			$post_n=get_post($other_report_source);
			return chart_report_get_data($post_n);
		}
		else
		return $chart_report_data;
	}
	
	function chart_report_reverse($output)
	{
		$trans=array("[["=>"");
		$output_n=strtr($output,$trans);
		$trans=array("]]"=>"");
		$output_n=strtr($output_n,$trans);
		$trans=array("'"=>"");
		$output_n=strtr($output_n,$trans);
		$out_array=array();
		$rever=array();
		$out_array=explode('],[',$output_n);
		for($i=0;$i<count($out_array);$i++)
		{
			$out_array[$i]=explode(',',$out_array[$i]);
		}
		
		for($i=0;$i<count($out_array[0]);$i++)
		{
			for($j=0;$j<count($out_array);$j++)
			{
				if ($i==0)
				{
					$out_array[$j][$i]="'".$out_array[$j][$i]."'";
				}
				else 
				if ($j==0) 
				{
					$out_array[$j][$i]="'".$out_array[$j][$i]."'";
				}
				$rever[$i][$j]=$out_array[$j][$i];
			}
		}
		for($i=0;$i<count($rever);$i++)
		{
			$rever[$i]=implode(',',$rever[$i]);
		}
		$rever=implode('],[',$rever);
		$rever="[[".$rever."]]";

		return $rever;	
	}
	
	
	function chart_report_generate_chart_options($go)
	{
		$output = '';
		//unset($go['empty']);
			foreach ($go as $key => $elem)
			{
				if (is_array($elem))
				{
					$c=array_count_values($elem);
					if (count($elem)!=$c['']) //проверить на то, что все элементы не пустые
					{
						$output.= $key.': {';
						foreach ($elem as $k => $e)
						{
							if ($e!='')
							$output.= $k.': "'.$e.'", ';
						}
						$output.= '}, ';
					}
				} 
				else
				{	
					if ($elem!='')
					$output.= $key.': "'.$elem.'", ';
				}
			}
		$trans=array(", }"=>"}");
		$output=strtr($output,$trans);
		
		$trans=array(",}"=>"}");
		$output=strtr($output,$trans);
		
		return $output;
	}
	
	
	
	function change_values_for_reports_test($result_mass,$post_id)
		{
		//	update_post_meta(2696,'test','blabla');
			if ($post_id ==72066 )
			{
				$output = array();
				foreach( $result_mass as $key => $res)
				{
					$output[$key] = $res;
					//$output[$key]['test'] = 'test';
				}
				//implode in 1 row
				$i = 0;
				for ($i;$i<count($output);$i++)
				{
					if ($i < count($output)-1)
					{
						if ($output[$i+1]['ответственный'] == $output[$i]['ответственный'])
						{
							$output[$i]['nar']= $output[$i+1]['count'];
							$i++;
						}
					}
				}
				
				//delete not used row and elems
				$i = 0;
				$begin_count = count($output);
				for ($i;$i<$begin_count;$i++)
				{
					if ($output[$i]['dlit'] == 'Без нарушения срока')
					{
						unset($output[$i]['dlit']);
					}
					if ($output[$i]['dlit'] == 'Срок нарушен')
					{
						unset($output[$i]);
					}

				}
				
				//create % field. remove all other
				foreach($output as $key => $elem)
				{
					if (isset($elem['nar']))
					{
						$all = $elem['count']+$elem['nar'];
						$procent = $elem['nar']*100/$all;
					}
					else
					{
						$procent = 100;
					}
					$output[$key]['%'] = 100-round($procent, 0);
					if ($procent == 100) $output[$key]['%']=100;
					unset($output[$key]['nar']);
					unset($output[$key]['count']);
				}
				
				return $output;
			}
			else
			{
				return $result_mass;
			}
		}
		add_filter('chart_report_data_before_save', 'change_values_for_reports_test', 10, 2);

	
	
?>