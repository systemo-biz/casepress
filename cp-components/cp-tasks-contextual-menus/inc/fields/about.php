<div class="gndev-plugin-nav-pane">
	<div class="gndev-plugin-column">
		<h3><?php _e( 'Support', $this->textdomain ); ?></h3>
		<ol style="margin-bottom:30px">
			<li><a href="http://wordpress.org/tags/news-bar?forum_id=10" target="_blank"><?php _e( 'Support forum', $this->textdomain ); ?></a></li>
			<li><a href="http://twitter.com/gn_themes/" target="_blank"><?php _e( 'Author twitter', $this->textdomain ); ?></a></li>
		</ol>
	</div>
	<div class="gndev-plugin-column">
		<h3><?php _e( 'Do you love this plugin?', $this->textdomain ); ?></h3>
		<ol style="margin-bottom:30px">
			<li><a href="http://wordpress.org/extend/plugins/news-bar/" target="_blank"><?php _e( 'Rate this plugin at wordpress.org', $this->textdomain ); ?></a> (<?php _e( '5 stars', $this->textdomain ); ?>)</li>
			<li><?php _e( 'Review this plugin in your blog', $this->textdomain ); ?></li>
			<li><?php _e( 'Click buttons below', $this->textdomain ); ?></li>
		</ol>
	</div>
	<div class="gndev-plugin-clear"></div>
	<div class="gndev-plugin-column">
		<h3><?php _e( 'My other FREE plugins', $this->textdomain ); ?></h3>
		<ol>
			<li><a href="http://wordpress.org/extend/plugins/news-bar/" target="_blank"><?php _e( 'News Bar', $this->textdomain ); ?></a><span class="description"><?php _e( 'show recent tweets and news', $this->textdomain ); ?></span></li>
			<li><a href="http://wordpress.org/extend/plugins/shortcodes-ultimate/" target="_blank"><?php _e( 'Shortcodes Ultimate', $this->textdomain ); ?></a><span class="description"><?php _e( 'many useful shortcodes', $this->textdomain ); ?></span></li>
			<li><a href="http://wordpress.org/extend/plugins/power-slider/" target="_blank"><?php _e( 'Power slider', $this->textdomain ); ?></a><span class="description"><?php _e( 'customizable slider', $this->textdomain ); ?></span></li>
			<li><a href="http://wordpress.org/extend/plugins/wp-insert-post/" target="_blank"><?php _e( 'WP Insert Post', $this->textdomain ); ?></a><span class="description"><?php _e( 'frontend posting form', $this->textdomain ); ?></span></li>
		</ol>
	</div>
	<div class="gndev-plugin-column">

		<!-- Twitter -->
		<p><iframe src="http://platform.twitter.com/widgets/tweet_button.html?url=<?php echo urlencode( $this->plugin_url ); ?>&amp;via=gn_themes&amp;text=<?php echo str_replace( '+', '%20', urlencode( __( 'Awesome WordPress plugin ' . $this->name, $this->textdomain ) ) ); ?>&amp;lang=en" style="border:none;overflow:hidden;width:105px;height:21px;" scrolling="no"></iframe></p>

		<!-- Facebook -->
		<p><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode( $this->plugin_url ); ?>&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;height=21&amp;locale=en_US" style="border:none;overflow:hidden;width:80px;height:21px;" scrolling="no"></iframe></p>

		<!-- PlusOne -->
		<p><iframe src="https://plusone.google.com/_/+1/fastbutton?url=<?php echo urlencode( $this->plugin_url ); ?>&amp;size=medium&amp;count=true&amp;annotation=&amp;hl=en-US" style="border:none;overflow:hidden;width:80px;height:21px;" scrolling="no"></iframe></p>
	</div>
	<div class="gndev-plugin-clear"></div>
</div>