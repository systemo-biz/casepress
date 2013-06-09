<?php

class Cases_Log_User_Actions {
	var $user_actions_table = 'cases_user_actions_log';

	function __construct() {
		global $wpdb;

		$this->user_actions_table = $wpdb->prefix . $this->user_actions_table;

		add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'delete_post' ) );
		add_action( 'added_post_meta', array( $this, 'added_post_meta' ), 10, 4 );
		add_action( 'updated_post_meta', array( $this, 'updated_post_meta' ), 10, 4 );
		add_action( 'deleted_post_meta', array( $this, 'deleted_post_meta' ), 10, 4 );
		add_action( 'wp_insert_comment', array( $this, 'insert_comment' ), 10, 2 );
		add_action( 'transition_comment_status', array( $this, 'transition_comment_status' ), 10, 3 );
		add_action( 'delete_comment', array( $this, 'delete_comment' ) );

		if ( is_admin() ) {
			//register_activation_hook( __FILE__, array( &$this, 'activate' ) );
			add_action('cp_activate',array( &$this, 'activate' ));
		}
	}

	function activate() {
		global $wpdb;

		if ( ! $wpdb->get_var( "SHOW TABLES LIKE '{$this->user_actions_table}'" ) ) {
			$charset_collate = '';

			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE $wpdb->collate";

			$wpdb->query(
				"CREATE TABLE {$this->user_actions_table} (
					action_id bigint(20) unsigned NOT NULL auto_increment,
					object_id bigint(20) unsigned NOT NULL,
					object_type varchar(20) NOT NULL,
					user_id bigint(20) unsigned NOT NULL,
					date datetime NOT NULL,
					action varchar(100) NOT NULL,
					old_status varchar(20) NOT NULL,
					new_status varchar(20) NOT NULL,
					meta_key varchar(255) NOT NULL,
					meta_value longtext NOT NULL,
					comment text NOT NULL,
					PRIMARY KEY  (action_id)
				) $charset_collate"
			);
		}
	}

	function save_data( $object_id, $object_type, $action, $data = array() ) {
		global $wpdb;

		$wpdb->insert( $this->user_actions_table, array(
			'object_id' => $object_id,
			'object_type' => $object_type,
			'user_id' => get_current_user_id(),
			'date' => current_time( 'mysql' ),
			'action' => $action,
			'old_status' => isset( $data['old_status'] ) ? $data['old_status'] : '',
			'new_status' => isset( $data['new_status'] ) ? $data['new_status'] : '',
			'meta_key' => isset( $data['meta_key'] ) ? $data['meta_key'] : '',
			'meta_value' => isset( $data['meta_value'] ) ? $data['meta_value'] : '',
			'comment' => isset( $data['comment'] ) ? $data['comment'] : '',
		) );
	}

	function transition_post_status( $new_status, $old_status, $post ) {
		$this->save_data( $post->ID, $post->post_type, __FUNCTION__, compact( 'old_status', 'new_status' ) );
	}

	function delete_post( $post_id ) {
		$post = get_post( $post_id );
		$this->save_data( $post->ID, $post->post_type, __FUNCTION__ );
	}

	function added_post_meta( $mid, $object_id, $meta_key, $meta_value ) {
		$this->save_data( $mid, 'postmeta', __FUNCTION__, compact( 'meta_key', 'meta_value' ) );
	}

	function updated_post_meta( $meta_id, $object_id, $meta_key, $meta_value ) {
		$this->save_data( $meta_id, 'postmeta', __FUNCTION__, compact( 'meta_key', 'meta_value' ) );
	}

	function deleted_post_meta( $meta_ids, $object_id, $meta_key, $meta_value ) {
		foreach( (array) $meta_ids as $meta_id )
			$this->save_data( $meta_id, 'postmeta', __FUNCTION__, compact( 'meta_key', 'meta_value' ) );
	}

	function insert_comment( $comment_id, $comment ) {
		$this->save_data( $comment_id, 'comment', __FUNCTION__ );
	}

	function transition_comment_status( $new_status, $old_status, $comment ) {
		$this->save_data( $comment->comment_ID, 'comment', __FUNCTION__, compact( 'old_status', 'new_status' ) );
	}

	function delete_comment( $comment_id ) {
		$this->save_data( $comment_id, 'comment', __FUNCTION__ );
	}

}

$cases_log_user_actions = new Cases_Log_User_Actions;
?>