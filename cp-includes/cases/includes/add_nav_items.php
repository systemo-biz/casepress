<?php


function add_case_items_navigation(){
	$user_id = get_current_user_id();
	$person_id = get_person_by_user($user_id);

	?>
	<div id="navigation">
		<ul>
			<li><a href="/cases?open=yes&meta_responsible-cp-posts-sql=<?php echo $person_id; ?>">Я ответственный (мои дела)</a></li>
			<li><a href="/cases?open=yes&meta_members-cp-posts-sql=<?php echo $person_id; ?>">Я участник (мои дела)</a></li>
		</ul>
	</div>

	<?php

} add_action('add_navigation_item', 'add_case_items_navigation');