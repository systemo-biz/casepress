<?php

	// Main filler function
	function csposter_filler() {

		// Get plugin object
		global $csposter;

		// Check that is plugin request
		if ( isset( $_REQUEST['csposter'] ) ) {

			// Get fillers
			$fillers = $csposter->get_option( 'fillers' );

			// Fillers exists
			if ( count( $fillers ) > 0 ) {

				// Open script
				$result = '<script type="text/javascript">jQuery(document).ready(function($) {' . "\n";

				// Loop through fillers
				foreach ( $fillers as $filler ) {

					// Get value
					$value = ( isset( $_GET['csposter_' . $filler[0]] ) ) ? $_GET['csposter_' . $filler[0]] : false;

					// If value is set add script
					$result .= ( $value ) ? stripslashes( str_replace( '%value%', $value, $filler[1] ) ) . "\n" : '';
				}

				// Close script
				$result .= '});</script>';
			}

			// Print fillers
			echo $result;
		}
	}

	add_action( 'admin_head', 'csposter_filler' );
?>