<div id="casepress-component-wrapper" class="wrap">
	<div id="icon-options-general" class="icon32 hide-if-no-js"><br /></div>
	<h2 id="casepress-component-nav-tabs" class="nav-tab-wrapper hide-if-no-js">
		<span class="nav-tab nav-tab-active"><?php _e( 'About', $this->textdomain ); ?></span>
		<span class="nav-tab"><?php _e( 'Fillers', $this->textdomain ); ?></span>
	</h2>
	<?php
		// Show notifications
		$this->notifications( array(
			'js' => __( 'For full functionality of this page it is reccomended to enable javascript.', $this->textdomain ),
			'reseted' => __( 'Settings reseted successfully', $this->textdomain ),
			'not-reseted' => __( 'There is already default settings', $this->textdomain ),
			'saved' => __( 'Settings saved successfully', $this->textdomain ),
			'not-saved' => __( 'Settings not saved, because there is no changes', $this->textdomain )
		) );

		// Show options
		$this->render_options();
	?>
</div>