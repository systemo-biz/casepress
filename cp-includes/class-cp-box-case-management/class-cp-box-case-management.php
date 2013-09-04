<?php
/*
 * Class for render form, get and save data
 */
class CP_Case_Management {

    function __construct(){
        //$nonce_save_members = wp_create_nonce('nonce_save_members');
        
        //add style $ scripts
        add_action( 'wp_enqueue_scripts', array($this, 'load_ss'));
        add_action( 'admin_enqueue_scripts', array($this, 'load_ss')); 

        //add forms
        add_action( 'edit_form_after_title', array($this, 'form_case_members_render') );
        add_action( 'post_submitbox_misc_actions', array($this, 'form_case_parameters_render') );
        add_action( 'post_submitbox_start', array($this, 'form_actions_case_parameters_render') );
        add_action( 'cp_entry_content_before', array($this, 'form_case_members_render_to_site'));
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
   
		add_action( 'added_post_meta', array($this, 'add_member_from_after_add_post_meta'), 10, 4 );
		add_action( 'deleted_post_meta', array($this, 'add_member_from_after_del_post_meta'), 10, 4);
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
		
	function add_member_from_after_add_post_meta($meta_id, $post_id, $meta_key, $meta_value) {
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
	}
	

    function form_case_members_render_to_site() {
        global $post;
        if (!($post->post_type == 'cases')) return;
        $this->form_case_members_render();

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

    function form_case_members_render(){
        global $post; 
        
        //check right post type
        if (is_admin() && !($post->post_type == 'cases')) return;

        $fields_render = new CP_Render_Fields;
        ?>	
                <div id="cp_case_managment_div" class="postbox">
                        <div id="cp_case_members_div">
								<?php $fields_render->field_add_person_button() ?>
                                <?php $fields_render->field_members_render() ?>
                                <?php $fields_render->field_member_responsible_render() ?>
                                <?php $fields_render->field_member_from_render() ?>
                        </div>
                </div>

        <?php
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
            }
            
            $date_deadline = get_post_meta($post_id, $key, true);
            $date_deadline = strtotime($date_deadline);
            $date_deadline = date('d.m.Y', $timestamp);
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
     * Load script and style
     */
	function load_ss(){
        
        //check right post type
		global $post;
        if (! isset($post) or $post->post_type != 'cases') return true;
        
        wp_enqueue_script( 'select2' );
        wp_enqueue_style( 'select2' );
        wp_enqueue_script( 'jquery-masonry' );
        //admin_enqueue_scripts ('jquery-masonry');
        wp_enqueue_style(
            'cp-box-case-management',
            trailingslashit( plugin_dir_url(__FILE__) ) .'cp-box-case-management.css');

        wp_enqueue_script(
            'cp-box-case-management',
            trailingslashit( plugin_dir_url(__FILE__) ) .'cp-box-case-management.js');

/* 		
        wp_register_script( 'cp-box-case-management-client-side', 'cp-box-case-management-client-side.js');  
        wp_enqueue_script( 'cp-box-case-management-client-side' );  */ 
		}
}
$The_CP_Members = new CP_Case_Management();

/*
 * Class for render fields
 * 
 * 
 */
class CP_Render_Fields {

    function __construct(){
        //
    }
    
    /**
    *  get field for action "Close"
    *  
    *  @return html
    */  
    function field_action_close(){

          ?>
            <div>
                <a href="#close">Закрыть</a>
            </div>
        <?php          
    }
    
    /*
     * get field for action "Accept"
     * 
     * @return html
     */
    function field_action_accept() {
          ?>
            <div>
                <a href="#accepts">Принять</a>
            </div>
        <?php   
    }

    /**
    *  get field for action "Delegate"
    *  
    *  @return html
    */  
    function field_action_delegate(){

        ?>
            <div>
                <a href="#delegate">Делегировать</a>
            </div>
        <?php
    }
        
    /**
    *  get field for add other field
    *  
    *  @return html
    */ 
	function field_action_add (){
		?>
		<div class="cp_add_case_data">

			<select id="cp_add_case_data_select" onchange="cp_add_field(this);">
				<option selected="selected">Добавить поле</option>
				<option value="cp_date_end_div">Дата завершения</option>
				<option value="cp_prioritet_div">Приоритет</option>
			</select>
			<script>
				function cp_add_field(v){
					var id = v.options[v.selectedIndex].value; //get id DOM for display
					jQuery("#" + id).show();
					jQuery('select#cp_add_case_data_select').prop('selectedIndex',0);
				};
			</script>
		</div>
		<?php
	}
	
    function field_case_category_render(){
        global $post;
        $post_id = $post->ID;
        $taxonomy = 'functions';
        $terms = get_the_terms( $post_id, $taxonomy );

        //get first term from array
        if (is_array($terms)) $term = array_shift($terms);
        
        ?>
        <div id="cp_case_category_div">
            
            <label class="cp_label" for="cp_case_category_select">Категория дела</label>
            <?php
            $case_category_id = '0';
            
            if (isset($term->term_id)){
                $case_category_id = $term->term_id;
            } elseif (isset($_REQUEST['case_category_id'])) {
                $case_category_id = $_REQUEST['case_category_id'];
            } else $case_category_id = '0';

            wp_dropdown_categories( array(
                'name' => 'cp_case_category',
                'class' => 'cp_full_width',
                'id' => 'cp_case_category_select',
                'echo' => 1,
                'hide_empty' => 0, 
                'show_option_none' => 'Выберите категорию дела',
                'option_none_value' => '0',
                'selected' => $case_category_id,
                'hierarchical' => 1,
                'taxonomy' => 'functions'
            )) ; ?>
            <div id="cp_field_case_category_edit" style="display: none">
                <a href="#ok" class="cp_button" id="cp_field_case_category_button_save">OK</a>
            </div>
            <script type="text/javascript">
                (function($) {
					$("#cp_case_category_select").change(function(){
						$("#cp_field_case_category_edit").show();
                    });
                    $("#cp_field_case_category_button_save").click(function(){
                        //alert("!!!");
                        
                        $.ajax({
                            data: ({
                                case_category: $("#cp_case_category_select").val(),
                                case_id: <?php echo $post->ID?>,
                                action: 'save_data_post'
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data) {
                                $("#cp_field_case_category_edit").hide();
                                <?php if (!(is_admin())) echo "location.reload();"; ?>
                            }                                
                         });
                    });

                })(jQuery);   
            </script>
        </div>
        <?php
    }
    
	function field_post_parent_render(){
		global $post;
		$case_parent_id = '0';
            
        if ($post->post_parent){
            $case_parent_id = $post->post_parent;
        } elseif (isset($_REQUEST['case_parent_id']) && is_numeric($_REQUEST['case_parent_id'])) {
           $case_parent_id = $_REQUEST['case_parent_id'];
        } else $case_parent_id = '0';
		
		wp_update_post(
				array(
					'ID' => $post->ID, 
					'post_parent' => $case_parent_id
				)
			);
		
		?>
		
		<div id="cp_case_post_parent_div">
			<label class="cp_label" id="cp_case_post_parent_input_label" for="cp_case_post_parent_input">Основание</label>
			<span id="cp_case_post_parent_view" class="cp_forms">
			<?php // echo $out; ?>
			</span>
			<div id="cp_case_post_parent_edit" style="display: none">
				<input type="hidden" id="cp_case_post_parent_input" name="cp_case_post_parent" class="cp_select2_single" />
				<a href="#ok" class="cp_button" id="cp_field_case_post_parent_button_save">OK</a>
				<a href="#cancel" class="cp_button" id="cp_field_case_post_parent_button_cancel">Отмена</a>
			</div>
		</div>
		<script type="text/javascript">

			(function($) {
			
							url = "<?php echo get_site_url() ?>";
							$("#cp_case_post_parent_input_label").click(function(){
                                $("#cp_case_post_parent_edit").show();
                                $("#cp_case_post_parent_view").hide();
                            });
                            
                            $("#cp_field_case_post_parent_button_cancel").click(function(){
                                $("#cp_case_post_parent_edit").hide();
                                $("#cp_case_post_parent_view").show();

                            });
							
                           $("#cp_field_case_post_parent_button_save").click(function(){
								cp_post_parent = $("#cp_case_post_parent_input").val();
								//console.log(cp_post_parent);
                                $.ajax({
                                    data: ({
                                        cp_post_parent: cp_post_parent,
                                        case_id: <?php echo $post->ID ?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data) {
										data = $.parseJSON(data);
										//console.log(data);
                                        $("#cp_case_post_parent_input").select2('data', data[0]);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: data
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_case_post_parent_view").html(links);
												$("#cp_case_post_parent_edit").hide();
												$("#cp_case_post_parent_view").show();
											}
										});
									}
								});
							});
                           

                })(jQuery);
								jQuery(document).ready(function($) {

									$("#cp_case_post_parent_input").select2({
                                        placeholder: "",
                                        width: '100%',
										allowClear: true,
                                        minimumInputLength: 1,
                                        ajax: {
                                                url: "<?php echo admin_url('admin-ajax.php') ?>",
                                                dataType: 'json',
                                                quietMillis: 100,
                                                data: function (term, page) { // page is the one-based page number tracked by Select2
                                                        return {
                                                                action: 'query_posts_cases',
                                                                page_limit: 10, // page size
                                                                page: page, // page number
                                                                //params: {contentType: "application/json;charset=utf-8"},
                                                                q: term //search term
                                                        };
                                                },
                                                results: function (data, page) {
                                                        //alert(data.total);
                                                        var more = (page * 10) < data.total; // whether or not there are more results available

                                                        // notice we return the value of more so Select2 knows if more results can be loaded
                                                        return {
                                                                results: data.elements,
                                                                more: more
                                                                };
                                                }
                                        },
                                        formatResult: elementFormatResult, // omitted for brevity, see the source of this page
                                        formatSelection: elementFormatSelection, // omitted for brevity, see the source of this page
                                        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                                        escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
									});
									$.ajax({
										data: ({
											action: 'get_post_parent',
											dataType: 'json',
											case_id: <?php echo $post->ID ?>
										}),
										url: "<?php echo admin_url('admin-ajax.php') ?>",
										success: function(data) {
											data = $.parseJSON(data);
											//console.log(data);
											$('#cp_case_post_parent_input').select2('data', data[0]);
											//data = [data];
											$.ajax({
												data: ({
													action: 'persons_links',
													data: data
												}),
												url: "<?php echo admin_url('admin-ajax.php') ?>",
												success: function(links){
													$("#cp_case_post_parent_view").html(links);
												}
											});
										}
									});
								});
                        </script>
        <?php
    }
	
    function field_date_end_render(){
        global $post;
		
        //convert date
        $date_end = "";
        $value = "";
        $timestamp = strtotime(get_post_meta($post->ID, "cp_date_end", true));
        if ($timestamp > 0) {
            $value = date('Y-m-d\TH:i', $timestamp); // format: 2013-12-31T23:55
            $date_end = date('d.m.Y H:i', $timestamp);
        }

        ?>
        <div id="cp_date_end_div" <?php //echo $hide; ?>>
            <label class="cp_label" for="cp_date_end_input" id="cp_field_date_end_label">Дата завершения:</label>
            <span id="cp_field_date_end_view" class="cp_forms"><?php echo $date_end ?></span>
            <div id="cp_field_date_end_edit" style="display: none">
                <input type="datetime-local" id="cp_date_end_input" name="cp_date_end" class="cp_full_width cp_input_datepicker" value="<?php echo $value ?>"/>
                <p>
                    <a href="#ok" id="cp_action_save_date_end" class="button">OK</a>
                    <a href="#cancel" id="cp_action_cancel_date_end">Отмена</a>
                </p>
            </div>
            <script type="text/javascript">
                (function($) {
                    $("#cp_field_date_end_label").click(function(){
                        $("#cp_field_date_end_edit").show();
                        $("#cp_field_date_end_view").hide();
                    });

                    $("#cp_action_cancel_date_end").click(function(){
                        $("#cp_field_date_end_edit").hide();
                        $("#cp_field_date_end_view").show();

                    });

                    $("#cp_action_save_date_end").click(function(){
                        date_end = $("#cp_date_end_input").val();
                        $.ajax({
                            data: ({
                                date_end: date_end,
                                case_id: <?php echo $post->ID?>,
                                action: 'save_data_post'
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data, str) {
                                $("#cp_field_date_end_view").text(data);
                                $("#cp_field_date_end_edit").hide();
                                $("#cp_field_date_end_view").show();                                     }
                         });

                    });



                })(jQuery);
            </script>
        </div>
        <?php
    }
    
    function field_prioritet_render(){
        global $post;
        $post_id = $post->ID;

        $key='cp_prioritet';

        //chek field for show
        $value = get_post_meta($post_id, $key, true);
        //$hide = (($value == "") ? "style='display: none;'" : ""); //temporarily off this

        ?>
        <div id="cp_prioritet_div"  <?php //echo $hide; ?>>
            <label class="cp_label" for="cp_prioritet_select">Приоритет</label><br/>
            <select id="cp_prioritet_select" name="cp_prioritet">
                <option <?php if($value=="") echo "selected='selected'" ?> >Без приоритета</option>
                <option <?php if($value=="1") echo "selected='selected'" ?> value="1">Критичный</option>
                <option <?php if($value=="2") echo "selected='selected'" ?> value="2">Высокий</option>
                <option <?php if($value=="3") echo "selected='selected'" ?> value="3">Нормальный</option>
                <option <?php if($value=="4") echo "selected='selected'" ?> value="4">Низкий</option>
                <option <?php if($value=="5") echo "selected='selected'" ?> value="5">Планируемый</option>
            </select>
        </div>
        <?php
    }
    
    function field_result_render() {
        global $post;
        $post_id = $post->ID;
        $taxonomy = 'results';
        $terms = get_the_terms( $post_id, $taxonomy );
        
        //get first term from array
        if (is_array($terms)) $term = array_shift($terms);
        if (isset($term->term_id)){
            $case_result_id = $term->term_id;
        } else $case_result_id = '0';
        ?>    
        <div id="cp_field_result_div">
            <label for="cp_field_result_select" class="cp_label">Результат</label>
                <?php
                wp_dropdown_categories( array(
                    'name' => 'cp_case_result',
                    'class' => 'cp_full_width',
                    'id' => 'cp_field_result_select',
                    'echo' => 1,
                    'hide_empty' => 0, 
                    'show_option_none' => 'Без результата',
                    'option_none_value' => '0',
                    'selected' => $case_result_id,
                    'hierarchical' => 1,
                    'taxonomy' => 'results'
                )) ;
                ?>
            <div id="cp_field_result_edit" style="display: none">
                <a href="#ok" class="cp_button" id="cp_field_result_button_save">OK</a>
            </div>
            <script type="text/javascript">
                (function($) {
                    $("#cp_field_result_select").change(function(){
                        $("#cp_field_result_edit").show();
                    });

                    $("#cp_field_result_button_save").click(function(){
                        //alert("!!!");
                        result = $("#cp_field_result_select").val();
                        $.ajax({
                            data: ({
                                result: result,
                                case_id: <?php echo $post->ID?>,
                                action: 'save_data_post'
                            }),
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            success: function(data) {
                                $("#cp_field_result_edit").hide();
                            }                                
                         });
                    });

                })(jQuery);   
            </script>
        </div>

        <?php
    }
	
    function field_add_person_button(){
		
        ?>
		
            <div class="cp_field" id="cp_action_add_person" style="visibility:hidden">
                            <p>
                                <a href="<?php echo admin_url( 'post-new.php?post_type=persons' ) ?>" id="cp_action_add_person" target="_blank">Добавить персону</a>
                            </p>
            </div>
        <?php

    }    
    function field_member_responsible_render(){
        global $post;
        ?>
            <div class="cp_field">
                            <p>
                                <label class="cp_label" id="cp_case_responsible_label" for="cp_case_responsible_input" onclick="">Ответственный</label>
								<span id="cp_case_responsible_view" class="cp_forms">
								<?php // echo $out; ?>
								</span>
								<div id="cp_case_responsible_edit" style="display: none">
									<div id="cp_case_responsible_edit_input">
										<input type="hidden" id="cp_case_responsible_input" name="cp_responsible" class="cp_select2_single" />
									</div>  
									<p>
										<a href="#ok" id="cp_action_save_responsible">OK</a>
										<a href="#cancel" id="cp_action_cancel_responsible">Отмена</a>
									</p>
								</div>
                            </p>
            </div>
            <script type="text/javascript">
			(function($) {
							url = "<?php echo get_site_url() ?>";
							$("#cp_case_responsible_label").click(function(){
                                $("#cp_case_responsible_edit").show();
                                $("#cp_case_responsible_view").hide();
                            });
                            
                            $("#cp_action_cancel_responsible").click(function(){
                                $("#cp_case_responsible_edit").hide();
                                $("#cp_case_responsible_view").show();

                            });
							
                            $("#cp_action_save_responsible").click(function(){
								cp_responsible = $("#cp_case_responsible_input").val();
                                $.ajax({
                                    data: ({
                                        cp_responsible: cp_responsible,
                                        case_id: <?php echo $post->ID ?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data) {
										data = $.parseJSON(data);
                                        $("#cp_case_responsible_input").select2('data', data[0]);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: data
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_case_responsible_view").html(links);
												$("#cp_case_responsible_edit").hide();
												$("#cp_case_responsible_view").show();
											}
										});
									}
								});
							});
                           

                })(jQuery);
								jQuery(document).ready(function($) {

									$("#cp_case_responsible_input").select2({
                                        placeholder: "",
                                        width: '100%',
										allowClear: true,
                                        minimumInputLength: 1,
                                        ajax: {
                                                url: "<?php echo admin_url('admin-ajax.php') ?>",
                                                dataType: 'json',
                                                quietMillis: 100,
                                                data: function (term, page) { // page is the one-based page number tracked by Select2
                                                        return {
                                                                action: 'query_persons',
                                                                page_limit: 10, // page size
                                                                page: page, // page number
                                                                //params: {contentType: "application/json;charset=utf-8"},
                                                                q: term //search term
                                                        };
                                                },
                                                results: function (data, page) {
                                                        //alert(data.total);
                                                        var more = (page * 10) < data.total; // whether or not there are more results available

                                                        // notice we return the value of more so Select2 knows if more results can be loaded
                                                        return {
                                                                results: data.elements,
                                                                more: more
                                                                };
                                                }
                                        },
                                        formatResult: elementFormatResult, // omitted for brevity, see the source of this page
                                        formatSelection: elementFormatSelection, // omitted for brevity, see the source of this page
                                        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                                        escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
									});
									$.ajax({
										data: ({
											action: 'get_responsible',
											dataType: 'json',
											case_id: <?php echo $post->ID ?>
										}),
										url: "<?php echo admin_url('admin-ajax.php') ?>",
										success: function(data) {
											data = $.parseJSON(data);
											$('#cp_case_responsible_input').select2('data', data);
											data = [data];
											$.ajax({
												data: ({
													action: 'persons_links',
													data: data
												}),
												url: "<?php echo admin_url('admin-ajax.php') ?>",
												success: function(links){
													$("#cp_case_responsible_view").html(links);
												}
											});
										}
									});
								});
                        </script>
        <?php

    }
    
    function field_members_render(){
        global $post;
        ?>
            <div class="cp_field">
                <p>
                    <label for="cp_case_members_input" id="cp_case_members_label class="cp_label"">Участники</label>
					<span id="cp_case_members_view" class="cp_forms" <?php  if (is_admin()) echo 'style="display: none"' ?>>
						<?php // echo $out; ?>
					</span>
					<div id="cp_case_members_edit" <?php  if (!is_admin()) echo 'style="display: none"' ?>>
						<div id="cp_case_members_edit_input">
							<input type="hidden" id="cp_case_members_input" name="cp_case_members" class="cp_select2" />
						</div>  
						<p>
							<a href="#ok" id="cp_action_save_members">OK</a>
							<a href="#cancel" id="cp_action_cancel_members">Отмена</a>
						</p>
					</div>
                </p>
            </div>
	
            <script type="text/javascript">
                (function($) {
							$("#cp_case_managment_div").mouseenter(function(){
								$("#cp_action_add_person").css('visibility','visible');
							})
							$("#cp_case_managment_div").mouseleave(function(){
								$("#cp_action_add_person").css('visibility','hidden');
							});

							url = "<?php echo get_site_url() ?>";
							$("#cp_case_members_label").click(function(){
                                $("#cp_case_members_edit").show();
                                $("#cp_case_members_view").hide();
                            });
                            
                            $("#cp_action_cancel_members").click(function(){
                                $("#cp_case_members_edit").hide();
                                $("#cp_case_members_view").show();

                            });
							
                            $("#cp_action_save_members").click(function(){
								cp_case_members = $("#cp_case_members_input").val();
                                $.ajax({
                                    data: ({
                                        cp_case_members: cp_case_members,
                                        case_id: <?php echo $post->ID ?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data) {
										data = $.parseJSON(data);
										$("#cp_case_members_input").select2('data', data);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: data
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_case_members_view").html(links);
												$("#cp_case_members_edit").hide();
												$("#cp_case_members_view").show();
											}
										});                                    }
                                 });
								});
                        

                })(jQuery);
                jQuery(document).ready(function($) {
                    $("#cp_case_members_input").select2({
                        placeholder: "",
                        formatInputTooShort: function (input, min) { return "Пожалуйста, введите " + (min - input.length) + " или более символов"; },
                        minimumInputLength: 1,
                        formatSearching: function () { return "Поиск..."; },
                        formatNoMatches: function () { return "Ничего не найдено"; },
                        width: '100%',
                        multiple: true,
                        ajax: {
                            url: "<?php echo admin_url('admin-ajax.php') ?>",
                            dataType: 'json',
                            quietMillis: 100,
                            data: function (term, page) { // page is the one-based page number tracked by Select2
                                return {
                                    action: 'query_persons',
                                    page_limit: 10, // page size
                                    page: page, // page number
                                    //params: {contentType: "application/json;charset=utf-8"},
                                    q: term //search term
                                };
                            },
                            results: function (data, page) {
                                //alert(data.total);
                                var more = (page * 10) < data.total; // whether or not there are more results available

                                // notice we return the value of more so Select2 knows if more results can be loaded
                                return {
                                    results: data.elements,
                                    more: more
                                    };
                            }
                        },

                        formatResult: elementFormatResult, // omitted for brevity, see the source of this page
                        formatSelection: elementFormatSelection, // omitted for brevity, see the source of this page
                        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                        escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                    });
                    $.ajax({
                        data: ({
                            action: 'get_members',
                            dataType: 'json',
                            case_id: <?php echo $post->ID ?>,
                        }),
                        url: "<?php echo admin_url('admin-ajax.php') ?>",
                        success: function(data) {
                            members = $.parseJSON(data);
                            $('#cp_case_members_input').select2('data',  members);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: members
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_case_members_view").html(links);
											}
										});
                        }
                    });
                });
            </script>
        <?php
    }
    
    function field_member_from_render(){
        global $post;
        ?>
            <div class="cp_field">
                    <p>
                            <label for="cp_member_from_input" id="cp_member_from_label" class="cp_label" title="Указываем инициатора дела (задачи, сообщения, приказа ...)">От кого</label>
							<span id="cp_member_from_view" class="cp_forms" <?php  if (is_admin()) echo 'style="display: none"' ?>>
							<?php // echo $out; ?>
							</span>
							<div id="cp_member_from_edit" <?php  if (!is_admin()) echo 'style="display: none"' ?>>
								<div id="cp_member_from_edit_input">
										<input type="hidden" id="cp_member_from_input" name="cp_member_from" class="cp_select2_single" />
								</div>  
								<p>
									<a href="#ok" id="cp_action_save_member_from">OK</a>
									<a href="#cancel" id="cp_action_cancel_member_from">Отмена</a>
								</p>
							</div>
                    </p>
            </div>
            <script type="text/javascript">
				(function($) {
							url = "<?php echo get_site_url() ?>";
							$("#cp_member_from_label").click(function(){
                                $("#cp_member_from_edit").show();
                                $("#cp_member_from_view").hide();
                            });
                            
                            $("#cp_action_cancel_member_from").click(function(){
                                $("#cp_member_from_edit").hide();
                                $("#cp_member_from_view").show();

                            });
							
                            $("#cp_action_save_member_from").click(function(){
								cp_member_from = $("#cp_member_from_input").val();
                                $.ajax({
                                    data: ({
                                        cp_member_from: cp_member_from,
                                        case_id: <?php echo $post->ID ?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data) {
										data = $.parseJSON(data);
                                        $("#cp_member_from_input").select2('data', data[0]);
										$.ajax({
											data: ({
												action: 'persons_links',
												data: data
											}),
											url: "<?php echo admin_url('admin-ajax.php') ?>",
											success: function(links){
												$("#cp_member_from_view").html(links);
												$("#cp_member_from_edit").hide();
												$("#cp_member_from_view").show();
											}                                   
										});
									}
                                });

                            });
							
                })(jQuery);
                jQuery(document).ready(function($) {
                    var placeholder = "";

                    $("#cp_member_from_input").select2({
                            placeholder: placeholder,
                            width: '100%',
                            allowClear: true,
                            minimumInputLength: 1,
                            ajax: {
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    dataType: 'json',
                                    quietMillis: 100,
                                    data: function (term, page) { // page is the one-based page number tracked by Select2
                                            return {
                                                    action: 'query_persons',
                                                    page_limit: 10, // page size
                                                    page: page, // page number
                                                    //params: {contentType: "application/json;charset=utf-8"},
                                                    q: term //search term
                                            };
                                    },
                                    results: function (data, page) {
                                            //alert(data.total);
                                            var more = (page * 10) < data.total; // whether or not there are more results available

                                            // notice we return the value of more so Select2 knows if more results can be loaded
                                            return {
                                                    results: data.elements,
                                                    more: more
                                                    };
                                    }
                            },
                            formatResult: elementFormatResult, // omitted for brevity, see the source of this page
                            formatSelection: elementFormatSelection, // omitted for brevity, see the source of this page
                            dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
                            escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
                    });
                    $.ajax({
                        data: ({
                            action: 'get_member_from',
                            dataType: 'json',
                            user_id: <?php echo get_current_user_id() ?>,
                            case_id: <?php echo $post->ID ?>
                        }),
                        url: "<?php echo admin_url('admin-ajax.php') ?>",
                        success: function(data) {
                            data = $.parseJSON(data);
							$('#cp_member_from_input').select2('data', data);
							data = [data];
								$.ajax({
									data: ({
										action: 'persons_links',
										data: data
									}),
									url: "<?php echo admin_url('admin-ajax.php') ?>",
									success: function(links){
										$("#cp_member_from_view").html(links);
									}
								});					
							}
                    }); 
					
                });

            </script>
    <?php
    }
        

    
    function field_date_deadline(){
        global $post;

        //convert date
        $timestamp = strtotime(get_post_meta($post->ID, "cp_date_deadline", true));
        $value = "";
        $date_deadline = "";
        if ($timestamp > 0) {
            $value = date('Y-m-d', $timestamp);
            $date_deadline = date('d.m.Y', $timestamp);
        }
        

        ?>
        <div id="cp_field_date_deadline_div" >
                <label for="cp_field_date_deadline_input" class="cp_forms cp_label" id="cp_field_date_deadline_label">Срок:</label>
                <span id="cp_field_date_deadline_view" class="cp_forms"><?php echo $date_deadline?></span>
                <div id="cp_field_date_deadline_edit" style="display: none">
                    <div id="cp_field_date_deadline_edit_input">
                        <input type="date" id="cp_field_date_deadline_input" name="cp_date_deadline" class="cp_full_width cp_input_datepicker" value="<?php echo $value?>"/>
                    </div>  
                    <p>
                        <a href="#ok" id="cp_action_save_deadline" class="button">OK</a>
                        <a href="#cancel" id="cp_action_cancel_deadline">Отмена</a>
                    </p>
                    <script type="text/javascript">
                        (function($) {
                            $("#cp_field_date_deadline_label").click(function(){
                                $("#cp_field_date_deadline_edit").show();
                                $("#cp_field_date_deadline_view").hide();
                            });
                            
                            $("#cp_action_cancel_deadline").click(function(){
                                $("#cp_field_date_deadline_edit").hide();
                                $("#cp_field_date_deadline_view").show();

                            });
                            
                            $("#cp_action_save_deadline").click(function(){
                                deadline = $("#cp_field_date_deadline_input").val();
                                $.ajax({
                                    data: ({
                                        deadline: deadline,
                                        case_id: <?php echo $post->ID?>,
                                        action: 'save_data_post'
                                    }),
                                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                                    success: function(data, str) {
                                        $("#cp_field_date_deadline_view").text(data);
                                        $("#cp_field_date_deadline_edit").hide();
                                        $("#cp_field_date_deadline_view").show();
									}
                                });
                            });

                        })(jQuery);
                    </script>
               </div>
        </div>
        <?php
    }
}

class CP_Automaton_Case {

    function __construct() {
             //auto hooks
        add_action("add_term_relationship", array($this, "auto_set_date_end"), 10, 2);
        add_action("deleted_term_relationships", array($this, "auto_del_date_end"), 10, 2);
        
        
    }
	function auto_set_date_end($object_id, $tt_id){
        //post
        $post_id = $object_id;
        $key = 'cp_date_end';
        $value = current_time("mysql");//date("Y-m-d H:i:s");
			
        
        //receipt of the term and on-condition
        $field="id";
        $taxonomy = "results";
        $term = get_term_by( $field, $tt_id, $taxonomy);

        $current_date_end = get_post_meta($post_id, $key, true);
        
        if (is_object($term) && $term->taxonomy === "results" && !($current_date_end > 0)){
                update_post_meta( $post_id, $key, $value);
        }
    }
    
    function auto_del_date_end($object_id, $tt_id) {
	
		$post_id = $object_id;
		$key = 'cp_date_end';
		
        delete_post_meta($post_id, $key);
	}
}

$TheCP_Automaton_Case = new CP_Automaton_Case;
?>
