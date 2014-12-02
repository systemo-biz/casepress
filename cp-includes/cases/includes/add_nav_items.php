<?php


function add_case_items_navigation(){
	$user_id = get_current_user_id();
	$person_id = get_person_by_user($user_id);

	?>
    <li id="my_cases"><strong>Мои дела</strong>
        <ul>
			<li><a href="/cases?open=yes&meta_responsible-cp-posts-sql=<?php echo $person_id; ?>">Ответственный</a></li>
			<li><a href="/cases?open=yes&deadline=yes&meta_responsible-cp-posts-sql=<?php echo $person_id; ?>">Нарушен срок</a></li>
			<li><a href="/cases?open=yes&meta_members-cp-posts-sql=<?php echo $person_id; ?>">Участник</a></li>
        </ul>
    </li>
	<?php

} add_action('add_navigation_item', 'add_case_items_navigation');