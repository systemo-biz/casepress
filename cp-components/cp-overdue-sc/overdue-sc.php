<?php

	
	
	function overdue_func($attr){
		global $current_user;
		global $wpdb;
		get_currentuserinfo();
		$person_id = get_person_by_user($current_user->ID);
		$sql = 'SELECT p.ID as "ID", p.post_title as "title", initiator.post_title as "initiator", date_deadline.meta_value as "deadline", 
			case
			when DATEDIFF(date_deadline.meta_value, now()) is not null and DATEDIFF(date_deadline.meta_value, now()) < 0 then "1. Просроченные"
			when DATEDIFF(date_deadline.meta_value, now()) is not null and DATEDIFF(date_deadline.meta_value, now()) = 0 then "2. Сегодня"
			when DATEDIFF(date_deadline.meta_value, now()) is not null and DATEDIFF(date_deadline.meta_value, now()) = 1 then "3. Завтра"
			when DATEDIFF(date_deadline.meta_value, now()) is not null and DATEDIFF(date_deadline.meta_value, now()) = 2 then "4. Послезавтра"
			when DATEDIFF(date_deadline.meta_value, now()) is not null and DATEDIFF(date_deadline.meta_value, now()) > 2 and DATEDIFF(date_deadline.meta_value, now()) < 7 then "5. На этой неделе"
			when DATEDIFF(date_deadline.meta_value, now()) is not null and DATEDIFF(date_deadline.meta_value, now()) >= 7 then "6. Менее срочные"
			when DATEDIFF(date_deadline.meta_value, now()) is null and DATEDIFF(STR_TO_DATE(date_deadline.meta_value, "%m/%d/%Y, %H:%i:%s"), now()) < 0 then "1. Просроченные"
			when DATEDIFF(date_deadline.meta_value, now()) is null and DATEDIFF(STR_TO_DATE(date_deadline.meta_value, "%m/%d/%Y, %H:%i:%s"), now()) = 0 then "2. Сегодня"
			when DATEDIFF(date_deadline.meta_value, now()) is null and DATEDIFF(STR_TO_DATE(date_deadline.meta_value, "%m/%d/%Y, %H:%i:%s"), now()) = 1 then "3. Завтра"
			when DATEDIFF(date_deadline.meta_value, now()) is null and DATEDIFF(STR_TO_DATE(date_deadline.meta_value, "%m/%d/%Y, %H:%i:%s"), now()) = 2 then "4. Послезавтра"
			when DATEDIFF(date_deadline.meta_value, now()) is null and DATEDIFF(STR_TO_DATE(date_deadline.meta_value, "%m/%d/%Y, %H:%i:%s"), now()) > 2 and DATEDIFF(STR_TO_DATE(date_deadline.meta_value, "%m/%d/%Y, %H:%i:%s"), now()) < 7 then "5. На этой неделе"
			when DATEDIFF(date_deadline.meta_value, now()) is null and DATEDIFF(STR_TO_DATE(date_deadline.meta_value, "%m/%d/%Y, %H:%i:%s"), now()) >= 7 then "6. Менее срочные"
			else "Ошибка вычисления"
			end as "deadliner"
			FROM `'.$wpdb->prefix.'posts` as p
			LEFT JOIN '.$wpdb->prefix.'postmeta responsible_id on (p.id = responsible_id.post_id and responsible_id.meta_key="responsible")
			LEFT JOIN '.$wpdb->prefix.'posts responsible on (responsible_id.meta_value = responsible.id)
			LEFT JOIN '.$wpdb->prefix.'postmeta date_deadline on (p.id = date_deadline.post_id and date_deadline.meta_key="date_deadline")
			LEFT JOIN '.$wpdb->prefix.'postmeta date_end on (p.id = date_end.post_id and date_end.meta_key="date_end")
			LEFT JOIN '.$wpdb->prefix.'postmeta initiator_id on (p.id = initiator_id.post_id and initiator_id.meta_key="initiator")
			LEFT JOIN '.$wpdb->prefix.'posts initiator on (initiator_id.meta_value = initiator.id)			
			WHERE
			p.post_type = "cases" and 
			responsible.id = '.$person_id.' and 
			p.post_status = "publish" and
			date_deadline.meta_value > 0 and
			date_end.meta_value is null
			LIMIT 30';
		$params = array('fields'=>'ID:link,title:link,initiator,deadline,deadliner','titles'=>'title: задача,initiator: инициатор, deadline: срок, deadliner: срочность','group'=>'deadliner');
		datatable_generator($params,$sql);		
		return $return;
	} 
	 
	 
	 
	 
	add_shortcode('overdue', 'overdue_func'); 
?>
