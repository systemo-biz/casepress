<?php

	include_once 'includes/settings.php';
	include_once 'includes/metabox.php';
	/*
	  $url=WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );
	  wp_enqueue_script('select2', $url.'/includes/select2/select2.js', array('jquery'));
	  wp_register_style('select2', $url.'/includes/select2/select2.css');
	  wp_enqueue_style( 'select2');
	 */

	class CasePress_Members {

		var $object_id = 0;

		function __construct( $obj ) {


			$this->object_id = $obj;
		}

		function set_object( $obj ) {

			$this->object_id = $obj;
		}

		/*
		 *
		 * return true?false
		 * in: array (object, subject, subject_type, role)
		 * объект - пост, к которому добавляют участника
		 * субект- Id участника, которого добавляют
		 * тип субъекта либо person (какая либо персона), либо название таксаномии (если категория)
		 * роль - та роль, которая будет задана для субъекта
		 */

		//add_subject_to_object

		function add_subject( $args = null ) {
			$defaults = array(
				'subject_type' => 'person',
				'role' => 'to'
			);


			$params = wp_parse_args( $args, $defaults );
			$params['object'] = $this->object_id;

			// проверить,  можно ли добавить такую роль, для данного объекта
			$cur_terms = get_the_terms( $params['object'], 'functions' );
			$term = '';
			foreach ( $cur_terms as $cur_term )
				$term = ( int ) $cur_term->term_id;
			$common = get_common_roles( 'cases' );
			$tax = get_tax_roles( $term );
			if ( !$tax )
				$tax = array( );
			$avalible_roles = array_merge( $common, $tax );
			$avalible_roles = array_flip( $avalible_roles );

			$go = false;
			if ( in_array( $params['role'], $avalible_roles ) )
				$go = true;

			if ( $go ) {
				if ( $params['subject_type'] == 'person' ) {
					add_post_meta( $params['object'], 'cp_posts_persons_' . $params['role'], $params['subject'] );
					
					/* old data */
						if ($params['role'] == 'from')
							update_post_meta( $params['object'], 'initiator', $params['subject'] );
						if ($params['role'] == 'responsible')
							update_post_meta( $params['object'], 'responsible', $params['subject'] );
						if ($params['role'] == 'to')
						{
							$participant = get_post_meta($params['object'], 'participant',true);
							$part = explode(',',$participant);
							$part[] = $params['subject'];
							$outp = implode(',',$part);
							update_post_meta( $params['object'], 'participant', $outp );
						}
							
					/**/
					return true;
				}
				else {
					add_post_meta( $params['object'], 'cp_taxonomy_' . $params['subject_type'] . '_' . $params['role'], $params['subject'] );
					return true;
				}
			}

			return false;
		}

		function get_members( $subject_type, $role ) {
			$res = array( );
			$object = $this->object_id;
			if ( $subject_type == 'person' ) {
				$res = get_post_meta( $object, 'cp_posts_persons_' . $role );
			}
			else {
				$res = get_post_meta( $object, 'cp_taxonomy_' . $subject_type . '_' . $role );
			}
			//$res = unserialize($res);
			return $res;
		}

		/*
		 *
		 * return true?false
		 * in: array (object, subject, subject_type, role)
		 */

		function delete_subject( $args = null ) {
			$defaults = array(
				'subject_type' => 'person',
				'role' => 'all'
			);

			$params = wp_parse_args( $args, $defaults );
			$params['object'] = $this->object_id;


			if ( $params['subject_type'] == 'person' ) {
				delete_post_meta( $params['object'], 'cp_posts_persons_' . $params['role'], $params['subject'] );
				
				/* old data */
					if ($params['role'] == 'from')
						delete_post_meta( $params['object'], 'initiator', $params['subject'] );
					if ($params['role'] == 'responsible')
						delete_post_meta( $params['object'], 'responsible', $params['subject'] );
					if ($params['role'] == 'to')
					{
						$participant = get_post_meta($params['object'], 'participant',true);
						$part = explode(',',$participant);
						foreach ($part as $key => $elem)
						{
							if ($elem == $params['subject'])
								unset($part[$key]);
						}
						$outp = implode(',',$part);
						update_post_meta( $params['object'], 'participant', $outp );
					}
						
				/**/
				
				
				
				return true;
			}
			else {
				delete_post_meta( $params['object'], 'cp_taxonomy_' . $params['subject_type'] . '_' . $params['role'], $params['subject'] );
				return true;
			}

			return false;
		}

		/*
		 *
		 * return true?false
		 * in: array (object, subject, subject_type, role)
		 * subject - массив значений (даже если имеется всего 1 значение необходимо передавать массив)
		 */

		function update_subject( $args = null ) {


			$params = $args;
			$params['object'] = $this->object_id;


			// проверить,  можно ли добавить такую роль, для данного объекта
			$cur_terms = get_the_terms( $params['object'], 'functions' );
			$term = '';
			foreach ( $cur_terms as $cur_term )
				$term = ( int ) $cur_term->term_id;
			$common = get_common_roles( 'cases' );



			$tax = get_tax_roles( $term );
			if ( !$tax )
				$tax = array( );

			$avalible_roles = array_merge( $common, $tax );
			$avalible_roles = array_flip( $avalible_roles );

			//return $avalible_roles;

			$go = false;
			if ( in_array( $params['role'], $avalible_roles ) )
				$go = true;

			if ( $go ) {
				if ( $params['subject_type'] == 'person' ) {
					$old = get_post_meta( $params['object'], 'cp_posts_persons_' . $params['role'] );
				}
				else {
					$old = get_post_meta( $params['object'], 'cp_taxonomy_' . $params['subject_type'] . '_' . $params['role'] );
				}

				$new = $params['subject'];
				$delete = array_diff( $old, $new );
				foreach ( $delete as $elem ) {
					$args = array(
						'object' => $params['object'],
						'subject' => $elem,
						'subject_type' => $params['subject_type'],
						'role' => $params['role']
					);
					$this->delete_subject( $args );
				}

				$add = array_diff( $new, $old );
				foreach ( $add as $elem ) {
					$args = array(
						'object' => $params['object'],
						'subject' => $elem,
						'subject_type' => $params['subject_type'],
						'role' => $params['role']
					);
					$this->add_subject( $args );
				}
				return true;
			}
			else
				return $avalible_roles;
		}

	}

	/*
	 *
	 * return array of available roles for tax
	 * in: (term_id)
	 */

	function get_tax_roles( $term_id ) {
		global $wpdb;


		$args = array(
			'numberposts' => 1,
			'post_type' => 'tax_option',
			'post_parent' => $term_id
		);
		$tax_options = get_posts( $args );
		$option = '';
		$do = false;
		if ( count( $tax_options ) > 0 ) {
			foreach ( $tax_options as $tax_option ) {
				$option = $tax_option->ID;
			}
			$options = get_post_meta( $option, 'cp_posts_tax_option_roles', true );
			$options = unserialize( $options );
			if ( count( $options ) > 0 ) {
				return $options;
			}
			else
				$do = true;
		}
		else
			$do = true;

		if ( $do ) {
			$parent = $wpdb->get_var( $wpdb->prepare( "SELECT parent FROM $wpdb->term_taxonomy" ) );
			if ( $parent != 0 ) {
				get_tax_roles( $parent );
			} else
				$do = false;
		}
		else
			return $options = array( );
	}

	function remove_tax_role( $term_id, $role ) {
		$args = array(
			'numberposts' => 1,
			'post_type' => 'tax_option',
			'post_parent' => $term_id
		);
		$tax_options = get_posts( $args );
		$option = '';
		foreach ( $tax_options as $tax_option ) {
			$option = $tax_option;
		}
		$options = get_post_meta( $option, 'cp_posts_tax_option_roles', true );
		$options = unserialize( $options );

		unset( $options[$role] );
		$output = serialize( $options );

		update_post_meta( $option, 'cp_posts_tax_option_roles', $output );
		return $options;
	}

	function update_tax_role( $term_id, $role, $desc ) {

		$args = array(
			'numberposts' => 1,
			'post_type' => 'tax_option',
			'post_parent' => $term_id
		);
		$tax_options = get_posts( $args );
		$option = '';
		if ( count( $tax_options ) > 0 ) {
			foreach ( $tax_options as $tax_option ) {
				$option = $tax_option;
			}
			$options = get_post_meta( $option, 'cp_posts_tax_option_roles', true );
			$options = unserialize( $options );

			$options[$role] = $desc;
			$output = serialize( $options );

			update_post_meta( $option, 'cp_posts_tax_option_roles', $output );
		}
		else {
			$defaults = array(
				'post_status' => 'publish',
				'post_type' => 'tax_option',
				'post_author' => 211,
				'post_parent' => $term_id
			);
			$option = wp_insert_post( $defaults );

			$options = get_post_meta( $option, 'cp_posts_tax_option_roles', true );
			$options = unserialize( $options );

			$options[$role] = $desc;
			$output = serialize( $options );

			update_post_meta( $option, 'cp_posts_tax_option_roles', $output );
		}
		return $options;
	}

	function update_common_role( $object_type, $role, $desc ) {
		$roles = maybe_unserialize( get_option( 'cp_common_roles' ) );
		$roles[$object_type][$role] = $desc;
		return update_option( 'cp_common_roles', $roles );
	}

	function remove_common_role( $object_type, $role ) {
		$roles = get_option( 'cp_common_roles' );
		$roles = unserialize( $roles );
		foreach ( $roles as $key => &$opt ) {
			if ( $key == $object_type ) {
				foreach ( $opt as $k => $elem ) {
					if ( $k == $role ) {
						unset( $roles[$key][$k] );
						break;
					}
				}
				break;
			}
		}
		$output = serialize( $roles );
		update_option( 'cp_common_roles', $output );
		return true;
	}

	function get_common_roles( $object_type ) {
		$roles = get_option( 'cp_common_roles' );
		$roles_this = $roles[$object_type];
		return $roles_this;
	}

	/*
	 *
	 * return new array of objects, where $person have role
	 *
	 */

	function get_objects_by_subject( $subject, $subject_type, $role ) {

	}

	function test_persons_shortcode() {

		ShowTree2( 0, 0 );
		?>

		<script language="javascript">
			jQuery(document).ready(function(){
				jQuery("#navigation333").treeview({
					animated: "normal",
					collapsed: false,
					unique: false,
					persist: "cookie"
				});
			});
		</script>
		<?

	}

	add_shortcode( 'test_persons_shortcode', 'test_persons_shortcode' );

	//add_action( 'roots_entry_content_before', 'test_persons_shortcode', 5 );




	function ShowTree2( $parent, $lvl ) {

		$args = array(
			'number' => 0
			, 'hide_empty' => false
			, 'hierarchical' => true
			, 'child_of' => ''
			, 'parent' => $parent
		);

		$mass = get_terms( 'functions', $args );
		if ( count( $mass ) > 0 ) {

			if ( $lvl == 0 ) {
				echo '<UL id="navigation333">';
			}
			else {
				echo "<UL>";
			}



			foreach ( $mass as $term ) {
				echo "<LI>";
				echo $term->name;
				$lvl++;
				ShowTree2( $term->term_id, $lvl );
				$lvl--;
				echo "</LI>";
			}

			echo "</UL>";
		}
	}
?>