<?
	include_once 'save.php';

	$url = WP_PLUGIN_URL . "/" . dirname( plugin_basename( __FILE__ ) );
	wp_enqueue_script( 'life_cycle_js', $url . '/life_cycle.js', array( 'jquery' ) );
	wp_localize_script( 'life_cycle_js', 'life_cycle', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	function life_cycle_add_meta_box() {
		add_meta_box( 'life_cycle_metabox', 'life_cycle', 'life_cycle_meta_box', 'life_cycle', 'normal', 'high' );
	}

	add_action( 'add_meta_boxes', 'life_cycle_add_meta_box' );

	function life_cycle_meta_box() {
		global $post;
		$args = array(
			'hide_empty' => false
		);
		$state = get_terms( 'state', $args );
		$results = get_terms( 'results', $args );
		$functions = get_terms( 'functions', $args );

		$id = $_GET['post'];

		$state_cur = get_post_meta( $id, 'cp_posts_life_cycle_state_positions', true );
		//get_post_meta($id,'cp_posts_life_cycle_state');

		$functions_cur = get_post_meta( $id, 'cp_posts_life_cycle_functions' );

		$results_cur = get_post_meta( $id, 'cp_posts_life_cycle_results_positions', true );

		$default = get_post_meta( $id, 'cp_posts_life_cycle_default', true );
		echo '<input type="hidden" id="post_id" value="' . $_GET['post'] . '">';
		?>

		<h2>Категории действия жизненого цикла</h2>

		<?
		if ( $default == 'yes' ) {
			echo '<input name="cp_posts_life_cycle_default" type="checkbox" checked>';
		}
		else {
			echo '<input name="cp_posts_life_cycle_default" type="checkbox">';
		}
		echo '&nbsp; Жизненый цикл по умолчанию <br/><br/>';
		?>

		<table role="container" >
			<tr role="row" >
				<td role="td_name" width="250px">
					Выберите категори для которых будет применятся жизненый цикл
				</td>
				<td role="td_option" width="300px">
					<?
					$output = '';
					foreach ( $functions_cur as $cur ) {
						if ( $output == '' ) {
							$output = $cur;
						}
						else {
							$output .= ',' . $cur;
						}
					}
					echo '<input type="hidden" name="cp_posts_life_cycle_functions" id="functions_cur" value="' . $output . '" style="width:300px" class="" tabindex="-1">';
					?>
				</td>
			</tr>
		</table>
		<br/>


		<!-- СОСТОЯНИЯ -->


		<h2>Состояния</h2>
		<table role="container" >
			<tr role="row" >
				<td role="td_name" width="250px">
					Выберите состояния
				</td>
				<td role="td_option" width="300px">

					<?
					echo '<input type="hidden" name="cp_posts_life_cycle_state" id="state_cur" value="' . $state_cur . '" style="width:300px" class="" >';
					?>
				</td>
			</tr>
		</table><br/>



		<!-- РЕЗУЛЬТАТ -->
		<h2>Результаты</h2>
		<table role="container" >
			<tr role="row" >
				<td role="td_name" width="250px">
					Выберите результаты
				</td>
				<td role="td_option" width="300px">
					<?
					echo '<input type="hidden" name="cp_posts_life_cycle_results" id="results_cur" value="' . $results_cur . '" style="width:300px" class="" tabindex="-1">';
					?>
				</td>
			</tr>
		</table><br/>


		<?
		$args = array(
			'hide_empty' => false,
			'order' => 'DESC'
		);

		$avalible_posts = get_terms( 'state', $args );
		$results = array( );
		foreach ( $avalible_posts as $term ) {

			$results[] = array(
				'id' => $term->term_id,
				'text' => $term->name
			);
		}


		/* $str_elems = get_post_meta($id,'cp_posts_life_cycle_state_positions',true);
		  $selected=explode(',',$str_elems);
		  print_r($results);
		  echo '<br/>';
		  print_r($selected);
		  echo '<br/>';
		  sort_arrays_select2($results,$selected);
		 */


		//	get_life_cycle(320);
	}

	function get_life_cycle( $term_id ) {
		$args = array(
			'numberposts' => -1,
			'meta_key' => 'cp_posts_life_cycle_functions',
			'meta_value' => $term_id,
			'post_type' => 'life_cycle',
			'post_status' => 'publish'
		);

		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			echo $post->ID . '<br/>';
		}
		if ( count( $posts ) == 0 ) {
			$args = array(
				'numberposts' => -1,
				'meta_key' => 'cp_posts_life_cycle_default',
				'meta_value' => 'yes',
				'post_type' => 'life_cycle',
				'post_status' => 'publish'
			);

			$posts = get_posts( $args );
			foreach ( $posts as $post ) {
				echo $post->ID . '<br/>';
			}
		}
	}

	function sort_arrays_select2( $all, $selected ) {
		$output = array( );
		$del_keys = array( );
		foreach ( $selected as $select ) {
			foreach ( $all as $key => $elem ) {
				if ( $elem['id'] == $select ) {
					$output[] = $all[$key];
					$del_keys[] = $key;
				}
			}
		}

		//удаляем элементы которые уже в массиве
		foreach ( $del_keys as $key ) {
			unset( $all[$key] );
		}

		//добавляем оставшиеся элементы в массив
		foreach ( $all as $key => $elem ) {
			$output[] = $all[$key];
		}
		return $output;
	}

	add_action( 'wp_ajax_get_states_life_cycle_ajax', 'get_states_life_cycle_ajax' );

	function get_states_life_cycle_ajax() {

		//echo 'work';
		$args = array(
			'hide_empty' => false
		);

		$avalible_posts = get_terms( 'state', $args );
		$results = array( );
		foreach ( $avalible_posts as $term ) {

			$results[] = array(
				'id' => $term->term_id,
				'text' => $term->name
			);
		}
		$str_elems = get_post_meta( $_POST['post_id'], 'cp_posts_life_cycle_state_positions', true );
		$selected = explode( ',', $str_elems );
		$output = sort_arrays_select2( $results, $selected );


		die( json_encode( $output ) );
	}

	add_action( 'wp_ajax_get_functions_life_cycle_ajax', 'get_functions_life_cycle_ajax' );

	function get_functions_life_cycle_ajax() {
		$args = array(
			'hide_empty' => false
		);

		$avalible_posts = get_terms( 'functions', $args );
		$results = array( );
		foreach ( $avalible_posts as $term ) {

			$results[] = array(
				'id' => $term->term_id,
				'text' => $term->name
			);
		}

		die( json_encode( $results ) );
	}

	add_action( 'wp_ajax_get_results_life_cycle_ajax', 'get_results_life_cycle_ajax' );

	function get_results_life_cycle_ajax() {
		$args = array(
			'hide_empty' => false
		);

		$avalible_posts = get_terms( 'results', $args );
		$results = array( );
		foreach ( $avalible_posts as $term ) {

			$results[] = array(
				'id' => $term->term_id,
				'text' => $term->name
			);
		}

		$str_elems = get_post_meta( $_POST['post_id'], 'cp_posts_life_cycle_results_positions', true );
		$selected = explode( ',', $str_elems );
		$output = sort_arrays_select2( $results, $selected );

		die( json_encode( $output ) );
	}
?>