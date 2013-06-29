<?php


	function get_person_id_by_email($email=null){
		if(!isset($email)) return 0;
		global $wpdb;
		$pid = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'email' AND meta_value = %s", $email));
		return (int)$pid;
	}
	
	function get_user_by_person($person_id){
		global $wpdb;
		$user_id=$wpdb->get_var("SELECT user_id FROM $wpdb->usermeta where meta_key='id_person' and meta_value='".$person_id."'");
		if (!isset($user_id)) $user_id=0;
		return $user_id;
	}
	
	function get_person_by_user($user_id){
		global $wpdb;
		$person_id=$wpdb->get_var("SELECT meta_value FROM $wpdb->usermeta where meta_key='id_person' and user_id='".$user_id."'");
		if (!isset($person_id)) $person_id=0;
		return $person_id;
	} 
	
	
	function get_object_taxs($object_id)
	{
		global $wpdb;
		$term_r = 'term_relationships';
		$term_r = $wpdb->prefix . $term_r;
		$term_tax=$wpdb->get_results('SELECT * FROM '.$term_r.' WHERE object_id="'.$object_id.'" ', ARRAY_A);
		
		$exist_terms = array();
		if (!empty($term_tax))
			foreach ($term_tax as $tt)
				$exist_terms[] = $tt['term_taxonomy_id'];
					
		return $exist_terms;
	}
	
	
	function get_tax_tree($parent, $lvl,$tax) 
	{ 
	
		$args = array(  
			'number'        => 0   
			,'hide_empty'   => false  
			,'hierarchical' => true  
			,'child_of'     => ''  
			,'parent'       => $parent  
		);  
  
		$mass = get_terms($tax, $args); 
		if (count($mass)>0)
		{
			if ($lvl==0){
				echo '<ul>'; 
			}
			else {
				echo "<ul>";
			}

			foreach ($mass as $term)
			{
				echo '<li term_id="'.$term->term_id.'">';
				echo '<a>'.$term->name.'</a>';
				$lvl++;
				get_tax_tree($term->term_id, $lvl,$tax); 
				$lvl--;
				
				echo "</li>";
			}
			echo "</ul>";
		}

	}
	
	
		/**
	 * Convert any date to readable format
	 */
	function cases_pretty_date( $date ) {

		if ( !empty( $date ) ) {
			$time = strtotime( $date );
			$result = date( 'j.m.Y', $time );
		}

		return $result;
	}

	/**
	 * Convert any date to readable format
	 */
	function cases_pretty_datetime( $datetime ) {

		if ( !empty( $datetime ) ) {
			$time = strtotime( $datetime );
			$result = date( 'j.m.Y, G:i', $time );
		}

		return $result;
	}



?>