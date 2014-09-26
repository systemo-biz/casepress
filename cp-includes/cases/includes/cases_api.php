<?php

class CasesAPISingltone {
private static $_instance = null;
private function __construct() {

	add_action('wp_ajax_get_person_cp', array($this, 'get_person_cp'));


	//Автоматическое добавление и удаление даты закрытия дела
    add_action("add_term_relationship", array($this, "auto_set_date_end"), 10, 2);
    add_action("deleted_term_relationships", array($this, "auto_del_date_end"), 10, 2);

    //add forms
    add_action( 'post_submitbox_misc_actions', array($this, 'form_case_parameters_render') );
    add_action( 'post_submitbox_start', array($this, 'form_actions_case_parameters_render') );
    //add_action( 'the_content', array($this, 'form_case_members_render_to_site'));
    add_action( 'case-sidebar', array($this, 'form_case_parameters_render_to_site'));

    //load and save data
    add_action( 'wp_ajax_query_persons', array($this, 'query_persons_callback') );
	add_action( 'wp_ajax_query_posts_cases', array($this, 'query_posts_cases_callback') );
	add_action( 'wp_ajax_persons_links', array($this, 'persons_links_callback') );
	//add_action( 'wp_ajax_posts_links', array($this, 'posts_links_callback') );
    add_action( 'wp_ajax_get_members', array($this, 'get_members_callback') );
    add_action( 'save_post', array($this, 'save_data_post'), 9);
    add_action( 'wp_ajax_save_data_cp_members', array($this, 'save_data_cp_members') );
    add_action( 'wp_ajax_get_member_from', array($this, 'get_member_from_callback') );
	add_action( 'wp_ajax_get_responsible', array($this, 'get_responsible_callback') );
	add_action( 'wp_ajax_get_post_parent', array($this, 'get_post_parent_callback') );
    add_action( 'wp_ajax_save_data_post', array($this, 'save_data_ajax') );

	//add_action( 'added_post_meta', array($this, 'add_member_from_after_adeadline_cp_wrapperdd_post_meta'), 10, 4 );
	//add_action( 'deleted_post_meta', array($this, 'add_member_from_after_del_post_meta'), 10, 4);

    add_action( 'added_post_meta', array($this, 'add_responsible_to_members'), 11, 4 );
    add_action( 'updated_post_meta', array($this, 'add_responsible_to_members'), 11, 4 );

    add_action('wp', array($this, 'save_members'));
}

    function save_members(){
        if(! isset($_REQUEST['update_members'])) return;

        if(!is_singular('cases')){
            return;
        }

        global $post;

        if(isset($_REQUEST['case_members'])) {

            delete_post_meta($post->ID, 'members-cp-posts-sql');

            if(!empty($_REQUEST['case_members'])){

                foreach(explode(',', $_REQUEST['case_members']) as $member){
                    add_post_meta($post->ID, 'members-cp-posts-sql', trim($member));
                }
            }
        }
    }

    function persons_links_callback() {
        $out = '';
        if (isset($_REQUEST['data']) && is_array($_REQUEST['data'])) {
            if(!empty($_REQUEST['data']) && !empty($_REQUEST['data'][0])){
                $array = $_REQUEST['data'];
                if (!isset($array[0]['id']) || $array[0]['id'] == '' || $array[0]['id'] == 0) exit;
                foreach ($array as $subarray){
                    $out .= '<a href="'.get_permalink($subarray['id']).'">'.$subarray['title'].'</a>, ';
                }
            }
        }
        echo substr($out, 0, -2);
        exit;
    }

    function add_responsible_to_members($meta_id, $post_id, $meta_key, $meta_value){
        if ($meta_key == 'responsible-cp-posts-sql' && !empty($meta_value)){
            add_post_meta($post_id, 'members-cp-posts-sql', $meta_value);
        }
    }
		
	/*function add_member_from_after_add_post_meta($meta_id, $post_id, $meta_key, $meta_value) {
		if ($meta_key == 'member_from-cp-posts-sql'){
			$meta_member_from = get_post_meta($post_id, $meta_key, true);
			$meta_members = get_post_meta($post_id, 'members-cp-posts-sql');
			if($meta_member_from != '' && $meta_member_from != 0 && !in_array($meta_member_from, $meta_members)) add_post_meta($post_id, 'members-cp-posts-sql', $meta_member_from);
		}
		if ($meta_key == 'responsible-cp-posts-sql'){
			$meta_member_from = get_post_meta($post_id, $meta_key, true);
			$meta_members = get_post_meta($post_id, 'members-cp-posts-sql');
			if($meta_member_from != '' && $meta_member_from != 0 && !in_array($meta_member_from, $meta_members)) add_post_meta($post_id, 'members-cp-posts-sql', $meta_member_from);
		}
	}
	
	function add_member_from_after_del_post_meta($meta_id, $post_id, $meta_key, $meta_value) {
		if ($meta_key == 'members-cp-posts-sql'){
			$meta_member_from = get_post_meta($post_id, 'member_from-cp-posts-sql', true);
			$meta_members = get_post_meta($post_id, $meta_key);
			if($meta_member_from != '' && $meta_member_from != 0 && !in_array($meta_member_from, $meta_members)) add_post_meta($post_id, $meta_key, $meta_member_from);
			
			$meta_member_from = get_post_meta($post_id, 'responsible-cp-posts-sql', true);
			$meta_members = get_post_meta($post_id, $meta_key);
			if($meta_member_from != '' && $meta_member_from != 0 && !in_array($meta_member_from, $meta_members)) add_post_meta($post_id, $meta_key, $meta_member_from);
		}
	}*/
	

    function form_case_members_render_to_site() {
      global $post;
      $content = $post->post_content;
      if($post->post_type=='cases'){
        ob_start();
        $this->form_case_members_render();
        $content .= ob_get_contents();
        ob_end_clean();
      }
      return $content;
    }
	

    function form_case_parameters_render_to_site() {
        $this->form_case_parameters_render();
    }
	
 	
    /**
    *  get form "Case Paramenters"
    *  
    *  @return html
    */     
    function form_case_parameters_render(){
            global $post; 

            //check post type
            if ((is_admin()) && !($post->post_type == 'cases')) return;

            $The_CP_Render_Fields = new CP_Render_Fields();

            echo "<div class=\"misc-pub-section\">";
            $The_CP_Render_Fields->field_case_category_render();
            $The_CP_Render_Fields->field_date_deadline();
            $The_CP_Render_Fields->field_result_render();
            $The_CP_Render_Fields->field_date_end_render();
			$The_CP_Render_Fields->field_post_parent_render();
            //$The_CP_Render_Fields->field_prioritet_render();
            do_action('add_field_for_case_aside_parameters', $post);
            echo "</div>";
    }

    /**
    *  get form "Case actions"
    *  
    *  @return html
    */ 
    function form_actions_case_parameters_render(){
        global $post; 
        if ((is_admin()) && !($post->post_type == 'cases')) return;

        $fields_render = new CP_Render_Fields;
        echo "<fieldset><legend>Действия:</legend><div id=\"form_actions_case_parameters\">";

        echo "</div></fieldset>";
    }

    
        
    function get_data_from_life_cycle(){
        //temp disable 
        $life_cycle = lfc_get_life_cycle( 12, 'post' );
    }
        

	
    function query_persons_callback(){
            $args = array(
                'fields' => 'ids',
                's' => $_GET['q'],
                'paged' => $_GET['page'],
                'posts_per_page' => $_GET['page_limit'],
                'post_type' => 'persons'
                );

            $query = new WP_Query( $args );

            $elements = array();
            foreach ($query->posts as $post_id){
                //try get organization
                $organization = "без организации";
                if ($organization_id = get_post_meta($post_id, 'organization-cp-posts-array', true)) {
                    $organization = get_the_title($organization_id[0]);
                }
                
                $elements[] = array(
                    'id' => $post_id,
                    'title' => get_the_title($post_id),
                    'organization' => $organization
                    );
            }
			
            $data[] = array(
                "total" => (int)$query->found_posts, 
                'elements' => $elements);
            //$data[] = $query;
            echo json_encode($data[0]);
            exit;
    }
	
	function query_posts_cases_callback(){
			$args = array(
                'fields' => 'ids',
                's' => $_GET['q'],
                'paged' => $_GET['page'],
                'posts_per_page' => $_GET['page_limit'],
                'post_type' => 'cases'
                );

            $query = new WP_Query( $args );

            $elements = array();
            foreach ($query->posts as $post_id){
                
                $elements[] = array(
                    'id' => $post_id,
                    'title' => get_the_title($post_id)
                    );
            }
			
            $data[] = array(
                "total" => (int)$query->found_posts, 
                'elements' => $elements);
            //$data[] = $query;
            echo json_encode($data[0]);
            exit;
    }
    /*
     * Get member "Responsible" for case
     * 
     * @return "json"
     */
    function get_responsible_callback(){
        $post_id = $_REQUEST['case_id'];
        $key = 'responsible-cp-posts-sql';

        //get Members IDs from Case metafield by key
        if (isset($post_id)) $ids = get_post_meta($post_id, $key);

        //Create array for save out data
        $out = array();

        //get member From by data metafield
		if ($ids != '' && $ids != 0){
			foreach ($ids as $member_id){
				$out[] = array(
					'id' => $member_id,
					'title' => get_the_title( $member_id )
				);			
			}
			if (isset($out[0])) {
                $out = $out[0];
            }
            
		}
        echo json_encode($out);
        exit;     
    }
	/*
     * Get member "Post Parent" for case
     * 
     * @return "json"
     */
   function get_post_parent_callback(){
        $post_id = $_REQUEST['case_id'];

        //get Members IDs from Case metafield by key
        if (isset($post_id)) $parent_id = get_post($post_id)->post_parent;

        //Create array for save out data
        $out = array();

        //get member From by data metafield

		$out[] = array(
			'id' => $parent_id,
			'title' => get_the_title( $parent_id )
		);			

        echo json_encode($out);
        exit;     
    }
    /*
     * Get member "From" for case
     * 
     * @return "json"
     */
function get_member_from_callback(){
        $post_id = $_REQUEST['case_id'];
        $user_id = $_REQUEST['user_id'];
        $key = 'member_from-cp-posts-sql';

        //get Members IDs from Case metafield by key
        if (isset($post_id)) $ids = get_post_meta($post_id, $key);

        //Create array for save out data
        $out = array();

        if (count($ids) > 0){
            //get member From by data metafield
            foreach ($ids as $member_id){
                $out[] = array(
                    'id' => $member_id,
                    'title' => get_the_title( $member_id )
                );			
            }
            $out = $out[0];
        } else if(count($ids) == 0)  {
            //get person by current user id as default
            $member_id = get_person_by_user($user_id);
			
			if ($member_id != 0) {
				$out[] = array(
					'id' => $member_id,
					'title' => get_the_title( $member_id )
				);    
				$out = $out[0];
			}
        }

		echo json_encode($out);
        exit;     
    }

    /*
     * Get member field "Members" for case
     * 
     * @return "json"
     */
function get_members_callback(){
        $ids = array();
        $elements = array();

        if (isset($_REQUEST['case_id']))
            $ids = get_post_meta( $_REQUEST['case_id'], 'members-cp-posts-sql');

        foreach ($ids as $member_id){
            $post_member = get_post($member_id);
            $elements[] = array(
                'id' => $member_id,
                'title' => $post_member->post_title
                );
        }

        echo json_encode($elements);
        exit; 
    }




	
function save_data_ajax() {
        //error_log ("go ajax");


         /** Save date end
         * field name: cp_case_result
         */
		if (isset($_REQUEST['date_end']) && isset($_REQUEST['case_id'])) {
            $key = 'cp_date_end';
            $timestamp = strtotime($_REQUEST['date_end']);
			$post_id = $_REQUEST['case_id'];

            if ($timestamp > 0) {
                $value = date('Y-m-d H:i:s', $timestamp);
    			update_post_meta( $post_id, $key, $value);

                $date_end = $value;
                $date_end = strtotime($date_end);
                $date_end = date('d.m.Y', $timestamp);

            } else { //if date end is null or delete
                delete_post_meta( $post_id, $key);
                $date_end ="";
            }
            
            echo $date_end;
            exit;               
        }	

        
        /*
         * Save case result
         * field name: cp_case_result
         */
        if (isset($_REQUEST['result']) && isset($_REQUEST['case_id'])) {

			$post_id = $_REQUEST['case_id'];
            $term = $_REQUEST['result'];
            $taxonomy = "results";
            $append = false;
            wp_set_post_terms( $post_id, $term, $taxonomy, $append );

            echo $term;
            exit;
        }

		/*
         * Save case post parent
         * field name: cp_post_parent
         */
        if (isset($_REQUEST['cp_post_parent']) && isset($_REQUEST['case_id'])) {

			$post_id = $_REQUEST['case_id'];
            $post_parent = $_REQUEST['cp_post_parent'];
			if ($post_parent > 0) {
				wp_update_post(
					array(
						'ID' => $post_id, 
						'post_parent' => $post_parent
					)
				);
			}
			
            $out = array();

			//get member From by data metafield

			$out[] = array(
				'id' => $post_parent,
				'title' => get_the_title( $post_parent )
			);			

			echo json_encode($out);
           // echo $post_parent;
            exit;
        }
		
        /*
         * Save case category
         * field name: case_category
         */
        if (isset($_REQUEST['case_category']) && isset($_REQUEST['case_id'])) {

			$post_id = $_REQUEST['case_id'];
            $term = $_REQUEST['case_category'];
            $taxonomy = "functions";
            $append = false;
            wp_set_post_terms( $post_id, $term, $taxonomy, $append );
            

            echo $term;
            exit;
        }
        
        
        /*
         * save deadline
         */
        if (isset($_REQUEST['deadline']) && isset($_REQUEST['case_id'])) {
			$key = 'cp_date_deadline';
            $timestamp = strtotime($_REQUEST['deadline']);
			$post_id = $_REQUEST['case_id'];
                    
            if ($timestamp > 0) {
                if (date('H:i:s', $timestamp) == "00:00:00") $timestamp = $timestamp + 86399;
                $value = date('Y-m-d H:i:s', $timestamp);
    			update_post_meta( $post_id, $key, $value);
            } else {
				delete_post_meta( $post_id, $key );
			}
            
            $date_deadline = get_post_meta($post_id, $key, true);
            $date_deadline = strtotime($date_deadline);
			if ($timestamp > 0) {
				$date_deadline = date('d.m.Y H:i', $date_deadline);}
			else {
				$date_deadline = '';}
            echo $date_deadline;
            exit;
        }
		
		/*
         * Field "Members"
		*/
		
		if (isset($_REQUEST['cp_case_members']) && isset($_REQUEST['case_id'])) {
			
            $key = 'members-cp-posts-sql';
            $data = trim($_REQUEST['cp_case_members']);
			$post_id = $_REQUEST['case_id'];
            
			delete_post_meta($post_id, $key);
			
			$meta_array = get_post_meta($post_id, $key);
			
			if ($_REQUEST['cp_case_members'] != '') {
				foreach (explode(',', $data) as $value ){
					if($value != '' && $value != 0 && !in_array($value, $meta_array)) add_post_meta($post_id, $key, $value);
				}
			}
			
			$out = array();
			$ids = get_post_meta($post_id, $key);
			
			if ($ids != '')
				foreach ($ids as $id) {
					$out[] = array('id' => $id, 'title' => get_the_title($id));
				}
				
			echo json_encode($out);
            exit;
		}

        /*
         * Field "From"
		*/
		
		if (isset($_REQUEST['cp_member_from']) && isset($_REQUEST['case_id'])) {
		
			$key = 'member_from-cp-posts-sql';
			$data = trim( $_REQUEST['cp_member_from'] );
			$post_id = $_REQUEST['case_id'];

			delete_post_meta($post_id, $key);

			if ($_REQUEST['cp_member_from'] != '') {
			
				foreach (explode(',', $data) as $value){
					add_post_meta($post_id, $key, $value, true);
				}
			}
			
			$out = array();
			$id = get_post_meta($post_id, $key, true);
			
			if ($id != '')
				$out[] = array('id' => $id, 'title' => get_the_title($id));
				
			echo json_encode($out);
            exit;
		}
	
        /*
         * Field "Responsible"
        */
		
		if (isset($_REQUEST['cp_responsible']) && isset($_REQUEST['case_id'])) {
			
			$key = 'responsible-cp-posts-sql';
			$data = trim( $_REQUEST['cp_responsible'] );
			$post_id = $_REQUEST['case_id'];
			
			delete_post_meta($post_id, $key);

			if ($_REQUEST['cp_responsible'] != '') {
				
				foreach (explode(',', $data) as $value){
					add_post_meta($post_id, $key, $value, true); 
				}	
			}
			
			$out = array();
			$id = get_post_meta($post_id, $key, true);
			
			if ($id != '')
				$out[] = array('id' => $id, 'title' => get_the_title($id));
				
			echo json_encode($out);
			exit;
		}
    }
	
		
function save_data_post(){
		global $post;
        //check right post type
        if (!(is_object($post))) return;
        if (!($post->post_type == 'cases')) return;
        
		$post_id = $post->ID;
        

        
        /*
         * Save case category
         * field name: cp_case_category
         */
        if (isset($_REQUEST['cp_case_category']) && $_REQUEST['cp_case_category'] != ''){
            $terms = $_REQUEST['cp_case_category'];
            $taxonomy = "functions";
            $append = false;
            wp_set_post_terms( $post_id, $terms, $taxonomy, $append );
        }
		
		 /** Save date end
         * field name: cp_date_end
         */
		if (isset($_REQUEST['cp_date_end'])) {
            $key = 'cp_date_end';
            $timestamp = strtotime($_REQUEST['cp_date_end']);

            if ($timestamp > 0) {
                $value = date('Y-m-d H:i:s', $timestamp);
    			update_post_meta( $post_id, $key, $value);
            }
                         
        }	
		
		/*
         * save result
         */
		if (isset($_REQUEST['cp_case_result'])) {
            $term = $_REQUEST['cp_case_result'];
            $taxonomy = "results";
            $append = false;
            wp_set_post_terms( $post_id, $term, $taxonomy, $append );
        }
		
        /*
         * save deadline
         */
        if (isset($_REQUEST['cp_date_deadline'])) {
			$key = 'cp_date_deadline';
            $timestamp = strtotime($_REQUEST['cp_date_deadline']);
                    
            if ($timestamp > 0) {
                if (date('H:i:s', $timestamp) == "00:00:00") $timestamp = $timestamp + 86399;
                $value = date('Y-m-d H:i:s', $timestamp);
    			update_post_meta( $post_id, $key, $value);
            }
        }
		
        /*
         * Field "Post Parent"
         */
		// infinity loop  fixed
		if (isset($_REQUEST['cp_case_post_parent'])) {

            $post_parent = trim( $_REQUEST['cp_case_post_parent'] );
			if ($post_parent > 0 && $post->post_parent != $post_parent){
			//unhook
				remove_action( 'save_post', array($this, 'save_data_post'), 9);
				wp_update_post(array(
					'ID' => $post_id, 
					'post_parent' => $post_parent
				));	
			//rehook
				add_action( 'save_post', array($this, 'save_data_post'), 9);
			}
        } 
		        
        /*
         * Field "Members"
         */
		if (isset($_REQUEST['cp_case_members']) && $_REQUEST['cp_case_members'] != '') {
			
            $key = 'members-cp-posts-sql';
            $data = trim( $_REQUEST['cp_case_members'] );
            
			delete_post_meta($post_id, $key);
			
			$meta_array = get_post_meta($post_id, $key);
			
            foreach (explode(',', $data) as $value ){
				if($value != '' && $value != 0 && !in_array($value, $meta_array)) add_post_meta($post_id, $key, $value);
            }
		}
 
        /*
         * Field "From"
         */
		if (isset($_REQUEST['cp_member_from']) && $_REQUEST['cp_member_from'] != '') {
		
			$key = 'member_from-cp-posts-sql';
			$data = trim( $_REQUEST['cp_member_from'] );

			delete_post_meta($post_id, $key);
	
            foreach (explode(',', $data) as $value){
                add_post_meta($post_id, $key, $value, true);
            }
        }
        /*
         * Field "Responsible"
         */
		if (isset($_REQUEST['cp_responsible']) && $_REQUEST['cp_responsible'] != '') {
		
			$key = 'responsible-cp-posts-sql';
			$data = trim( $_REQUEST['cp_responsible'] );
			
			delete_post_meta($post_id, $key);

			foreach (explode(',', $data) as $value){
				add_post_meta($post_id, $key, $value, true);
            }	
        }
            
		if (isset($_REQUEST['cp_prioritet'])) {
			$key = 'cp_prioritet';
			$value = $_REQUEST['cp_prioritet'];
			update_post_meta( $post_id, $key, $value);
		}
	}

/*

	Автоматическое добавление и удаление даты закрытия дела при указании результатов

*/
function auto_set_date_end($object_id, $tt_id){
    //post

    $post_id = $object_id;
    $key = 'cp_date_end';
    $value = current_time("mysql");//date("Y-m-d H:i:s");

    $field="id";
    $taxonomy = "results";
	
    $terms = get_terms('results', 'hide_empty=0');//get_term_by( $field, $tt_id, $taxonomy );
	foreach ($terms as $term){
		if ($term->term_taxonomy_id == $tt_id){
			$this_term = get_term_by( $field, $term->term_id, $taxonomy );
		}
	}

    $current_date_end = get_post_meta($post_id, $key, true);

    if (is_object($this_term) && $this_term->taxonomy === "results" && !($current_date_end > 0)){
            update_post_meta( $post_id, $key, $value);
    }
}

function auto_del_date_end($object_id, $tt_id) {
	
    //get list terms from tax 'results' and find term deleted by taxonomy id
    
    $terms = get_terms('results', 'hide_empty=0');
    foreach ($terms as $term){
		if ($term->term_taxonomy_id == $tt_id[0]) delete_post_meta($object_id, 'cp_date_end');
	}
    
}





/*
Механизм получения данных о персонах для поля ответственного в виджете контроля кейсов
*/

function get_person_cp(){

	if(isset($_REQUEST['s'])) $s = $_REQUEST['s'];


	$data = get_posts(array(
	    's' => $s,
	    //'paged' => $_REQUEST['page'],
	    //'posts_per_page' => $_REQUEST['page_limit'],
	    'post_type' => 'persons'
	    ));

	$elements = array();


	foreach ($data as $item) :

		$element = array(
	        'id' => $item->ID,
	        'name' => $item->post_title,
        );

		$elements[] = $element;
	endforeach;

	

	$data_echo = array(
	    'items' => $elements
	    );

	wp_send_json($data_echo);
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

} $CasesAPI = CasesAPISingltone::getInstance();





