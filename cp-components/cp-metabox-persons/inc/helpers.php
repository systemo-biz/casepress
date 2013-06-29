<?php

	/**
	 * Return formatted hyperlink by specified data
	 *
	 * @param mixed $data Term slug or person ID. Example: (int) 1176, (string) persons_category:podrydchiki
	 */
	function cmp_get_link( $data ) {
		// Get plugin object
		global $cmp_plugin;
		// Post link
		if ( strpos( $data, ':' ) === false && is_numeric( $data ) ) {
			$link = '<a href="' . get_permalink( $data ) . '" data-type="person" data-id="' . $data . '" data-tax="">' . get_the_title( $data ) . '</a>';
		}
		// Term link
		elseif ( is_numeric( strpos( $data, ':' ) ) ) {
			$data = explode( ':', $data );
			$term = get_term_by( 'slug', $data[1], $data[0] );
			$link = '<a href="' . get_term_link( $term ) . '" data-type="term" data-id="' . $data[1] . '" data-tax="' . $data[0] . '">' . $term->name . '</a>';
		}
		// Not selected label
		else
			$link = '<strong>' . __( 'Not selected', $cmp_plugin->textdomain ) . '</strong>';
		// Return link
		return $link;
	}

	/**
	 * Simple field template helper
	 *
	 * @param string $field Field ID
	 * @param array $data Field data. array( (string) 'Field label', (bool) multiple )
	 * @param array $value Field value. array( id, type, title )
	 */
	function cmp_render_field( $field, $data, $value ) {
		global $cmp_plugin;
		?>
		<div class="cmp-field" data-role="<?php echo $field; ?>" data-multiple="<?php
		echo ( $data[1] ) ? 'true' : 'false';
		?>">
			<p>
				<strong>
					<em title="<?php _e( 'Click to edit', $cmp_plugin->textdomain ); ?>"><?php echo $data[0]; ?></em>
					<i class="cmp-hidden" title="<?php _e( 'Advanced search', $cmp_plugin->textdomain ); ?>"><?php _e( 'Advanced search', $cmp_plugin->textdomain ); ?></i>
				</strong>
				<span><?php echo implode( ', ', $value ); ?></span>
				<b class="cmp-hidden"><input type="hidden" name="<?php echo $field; ?>" value="" /></b>
			</p>
		</div>
		<?php
	}

	/**
	 * Simple invite badge template helper
	 *
	 * @param string $field Field ID
	 * @param array $data Field data. array( (string) 'Field label', (bool) multiple )
	 */
	function cmp_render_invite( $field, $data ) {
		echo '<span data-invite="' . $field . '">' . $data[0] . '</span>';
	}

	/**
	 * Retrieve list of persons
	 */
	function cmp_get_persons( $args = '' ) {
		$pts = get_post_types( array( 'public' => true ), 'objects' );
		$query = array(
			'post_type' => 'persons',
			'suppress_filters' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'post_status' => 'publish',
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
		);
		$args['pagenum'] = isset( $args['pagenum'] ) ? absint( $args['pagenum'] ) : 1;
		if ( isset( $args['s'] ) )
			$query['s'] = $args['s'];
		$query['offset'] = $args['pagenum'] > 1 ? $query['posts_per_page'] * ( $args['pagenum'] - 1 )
				: 0;
		// Do main query.
		$get_posts = new WP_Query;
		$posts = $get_posts->query( $query );
		// Check if any posts were found.
		if ( !$get_posts->post_count )
			return false;
		// Build results.
		$results = array( );
		foreach ( $posts as $post ) {
			if ( 'post' == $post->post_type )
				$info = mysql2date( __( 'Y/m/d' ), $post->post_date );
			else
				$info = $pts[$post->post_type]->labels->singular_name;
			if ( get_the_title( $post ) != '' )
				$results[] = array(
					'id' => $post->ID,
					'text' => trim( esc_html( strip_tags( get_the_title( $post ) ) ) )
				);
		}
		return $results;
	}
?>