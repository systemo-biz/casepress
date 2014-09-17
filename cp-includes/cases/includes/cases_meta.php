<?php

add_shortcode('case_meta', 'shortcode_meta');

function shortcode_meta(){
?>

<div id="case_category_wrapper">
	<label for="case_category">Категория дела</label>
	<?php wp_dropdown_categories( array(
		'taxonomy'			=> 'functions', 
		'id'				=> 'case_category', 
		'class'				=> "form-control", 
		'hide_if_empty' 	=> false,
		'hierarchical'		=> 1,
		'show_option_none'  => 'Без выбора',
		));
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		     $('#case_category').selectize();
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
	<label for="responsible_cp">Ответственный</label>
	<select id="responsible_cp" name="responsible_cp" class="form-control"></select>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#responsible_cp').selectize({
		    valueField: 'title',
		    labelField: 'title',
		    searchField: 'title',
		    options: [],
		    create: false,
		    render: {
		        option: function(item, escape) {
		            return '<div>' +
		                '<span class="title">' +
		                    '<span class="name">' + escape(item.title) + '</span>' +
		                '</span>' +
		            '</div>';
		        }
		    },
		    load: function(query, callback) {
		        if (!query.length) return callback();
		        $.ajax({
		            url: 'https://api.github.com/legacy/repos/search/' + encodeURIComponent(query),//'<?php echo admin_url('admin-ajax.php') ?>',
		            type: 'POST',
		            //dataType: 'jsonp',
		            data: {
		                //q: query,
		                //page_limit: 10,
		                action: 'get_person_cp'
		            },
		            error: function() {
		                callback();
		            },
		            success: function(res) {
		                callback(res.repositories.slice(0, 10));

		            }
		        });
		    }
		});
	});
	</script>
</div>
<?php
}