<?php

add_shortcode('case_meta', 'shortcode_meta');

function shortcode_meta(){

ob_start();
?>
<form method="post" action="<?php echo add_query_arg( 'update_case_control', '1', get_permalink() ); ?>">
	<div id="case_category_wrapper">
		<label for="case_category">Категория дела</label>
		<?php wp_dropdown_categories( array(
			'name'				=> 'case_category',
			'taxonomy'			=> 'functions', 
			'id'				=> 'case_category', 
			'class'				=> "form-control-cp", 
			'hide_if_empty' 	=> false,
			'hierarchical'		=> 1,
			'show_option_none'  => 'Без выбора',
			));
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
			     //$('#case_category').select2();
			});
		</script>
	</div>

	<div id="deadline_cp_wrapper">
		<label for="deadline_cp">Срок</label>
		<input id="deadline_cp" name="deadline_cp" class="form-control">
		<script type="text/javascript">
			jQuery(document).ready(function($) {
			     rome(deadline_cp);
			});
		</script>
	</div>

	<div id="responsible_cp_wrapper">
		<label for="responsible_cp_">Ответственный</label>
		<select id="responsible_cp_" name="responsible_cp" class="form-control"></select>

		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#responsible_cp_').selectize({
				valueField: 'id',
				labelField: 'name',
				searchField: 'name',
				options: [],
				create: false,
				render: {
					option: function(item, escape) {
						return '<div>' + escape(item.name) + '</div>';
					}
				},
				load: function(query, callback) {
					if (!query.length) return callback(); 
					$.ajax({
						url: 'http://cp.balt-plus.ru/wp-admin/admin-ajax.php?action=get_person_cp&s=' + encodeURIComponent(query),//'https://api.github.com/legacy/repos/search/' + encodeURIComponent(query),
						type: 'GET',
						success: function(data) {
							callback(data.items);
						}
					});
				}
			});
		});
		</script>
	</div>
	<input type="submit">
</form>
<?php

$html = ob_get_contents();

ob_get_clean();

return $html;
}