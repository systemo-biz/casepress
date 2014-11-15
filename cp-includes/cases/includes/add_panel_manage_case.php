<?php

/**
 * Добавляем панель контролья дела, если находимся на странице дела
 */
function add_panel_manage_case_cp(){

if(!is_singular('cases')) return;

?>
<div class="panel panel-default">
 <div class="panel-heading">
 <h3 class="panel-title">Контроль дела</h3>
 </div>
 <div class="panel-body">
 <?php echo do_shortcode('[case_meta]'); ?>
 </div>
</div>
<?php
} add_action('add_item_to_empty_sidebar_commone', 'add_panel_manage_case_cp', 0, 5); 